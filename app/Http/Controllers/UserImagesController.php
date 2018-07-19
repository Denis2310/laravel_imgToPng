<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Image;
use App\ImageUser;
use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;


class UserImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user');
    }

    //Pogled korisnikove slike
    public function index()
    {
        $user = Auth::user();
        $images = $user->images;

        //Ako postoje primljene slike spoji ih i sortiraj za korisnikovim slikama
        if($received_images = ImageUser::whereTo_user($user->id)->get()) {

            $all_images = $images->merge($received_images); 
            
            $images = $all_images->sortBy(function($image){

                return $image->created_at;
            });
        }

        return view('user.images', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Prebacivanje na pogled za upload slike
    public function create()
    {
        return view('user.upload');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //Validacija, konverzija u png, spremanje slike
    public function store(UploadRequest $request)
    {
        //Provjera duljine imena slike (max 20 znakova)
        if(!is_valid_name($request->file('file'))) {

            return redirect()->back()->withErrors(['File name can\'t be longer than 20 characters.']);
        }

        $user = Auth::user();
        $time = time();
        $image = $request->file('file');

        //Dodaj trenutno vrijeme prije imena slike kako bi se slika mogla identificirati
        $image_name = $time . $image->getClientOriginalName();

        //Ako je slika png spremi ju bez konverzije
        if($image->getClientOriginalExtension() == "png")
        {
            Storage::put('public/images/'.$user->id.'/png/'.$image_name, file_get_contents($image));
            return save_image_to_database($image, $user, $time);
        }
        else
        {

            if(check_extension_and_convert($image, $user, $time))
            {
                //Spremanje originalne slike
                Storage::put('public/images/'.$user->id.'/'.$image_name, file_get_contents($image));
                return save_image_to_database($image, $user, $time);
            }
            else
            {

                return redirect()->back()->withErrors(['Something went wrong, file was not uploaded!.']);
            }
                
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Show image pogled, nakon klika na sliku
    public function show($id)
    {
        $image = Image::findOrFail($id);
        if(Auth::user()->id != $image->user_id)
        {
            return abort(404);
        }

        return view('user.show_image', compact('image'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function edit($id)
    {
        //
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, $id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Korisnik briše svoju učitanu sliku
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        //Pronađi sliku u folderu i obriši ju
        Storage::delete('/public/images/'.$image->user_id.'/png/'.$image->path);

        //Ako nije original nije png onda pronađi u folderu original i obriši i njega
        if($image->extension != "png")
        {
            $image_name = substr($image->path, 0, -3);
            Storage::delete('/public/images/'.$image->user_id.'/'.$image_name.$image->extension);
        }

        Session::flash('success', 'Your image was successfully deleted!');

        //Ako je slika poslana, obriši samo ID od vlasnika
        if($image->times_sent > 0)
        {
            $image->user_id = 0;
            $image->save();
            return redirect('/images');
        }
        else
        {
            //Slika nije poslana obriši ju iz baze podataka
            $image->delete();
            return redirect('/images');            
        }
                   
    }

} 


function is_valid_name($file){

    if(strlen($file->getClientOriginalName()) > 24) {

        return false;
    }
    else {

        return true;
    }
}


function check_extension_and_convert($image, $user, $time){

    //Ukloni ekstenziju
    $image_name_without_extension = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
    $image_name_png = $time . $image_name_without_extension .'.png';

    $extension = $image->getClientOriginalExtension();

    switch($extension){

        case 'bmp': $original_image = imagecreatefrombmp($image->path()); break;
        case 'jpg': $original_image = imagecreatefromjpeg($image->path()); break;
        case 'gif': $original_image = imagecreatefromgif($image->path()); break;
        default: return false;
    }

    //Provjeri da li postoji direktorij, ako ne postoji kreiraj ga
    if (!file_exists(storage_path().'/app/public/images/'.$user->id.'/png/')) {

        mkdir(storage_path().'/app/public/images/'.$user->id.'/png/', 0777, true);
    }

    $is_converted = imagepng($original_image, storage_path().'/app/public/images/'.$user->id.'/png/'.$image_name_png);

    return $is_converted;
}


function save_image_to_database($image, $user, $time){

    $db_image = new Image();

    if($image->extension() == 'png')
    {
        $db_image->path = $time . $image->getClientOriginalName();
        $db_image->png_size = $image->getClientOriginalSize();
    }
    else
    {
        $path = $time . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.png';
        $png_size = Storage::size('/public/images/'.$user->id.'/png/'.$path);

        $db_image->path = $path;
        $db_image->png_size = $png_size;
    }

    $db_image->user_id = $user->id;
    $db_image->extension = $image->getClientOriginalExtension();
    $db_image->size = $image->getClientSize();   
    $db_image->save();

    return redirect('/images');
}





