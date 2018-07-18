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

        //Ako postoje primljene slike spoji ih i sortiaj za korisnikovim slikama
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
        if(strlen($request->file('file')->getClientOriginalName()) > 24)
        {
            return redirect()->back()->withErrors(['File name can\'t be longer than 20 characters.']);
        }

        $user = Auth::user();
        $time = time();
        $db_image = new Image();
        $image = $request->file('file');

        //Dodaj trenutno vrijeme prije imena slike kako bi se slika mogla identificirati
        $image_name = $time . $image->getClientOriginalName();

        //Ako je slika png spremi ju bez konverzije
        if($image->getClientOriginalExtension() == "png")
        {
            Storage::put('public/images/'.$user->id.'/png/'.$image_name, file_get_contents($image));
            $db_image->path = $image_name;
            $db_image->user_id = $user->id;
            $db_image->extension = $image->getClientOriginalExtension();
            $db_image->png_size = $image->getClientSize();
            $db_image->save();
            Session::flash('success', 'Your image was successfully uploaded!');
            return redirect('/images');
        }
        else
        {
            //Ukloni ekstenziju
            $image_name_without_extension = pathinfo($image_name, PATHINFO_FILENAME);

            //Dodaj png na ime slike
            $image_name_png = $image_name_without_extension .'.png';
        
            //Pretvaranje originalne slike u png
            $png_image = (string) Img::make($image)->encode('png');

            //Spremanje originalne slike i png slike
            Storage::put('public/images/'.$user->id.'/'.$image_name, file_get_contents($image));
            Storage::put('public/images/'.$user->id.'/png/'.$image_name_png, $png_image);

            //Spremanje podataka o slici u bazu
            //$db_image = new Image; uklonio jer mislim da ne treba ima gore
            $db_image->path = $image_name_png;
            $db_image->user_id = $user->id;

            //Dohvaćanje originalne ekstenzije uploadane slike
            $db_image->extension = $image->getClientOriginalExtension();
            $db_image->size = $image->getClientSize();

            //Duljina stringa png slike
            $db_image->png_size = strlen($png_image);
            $db_image->save();
            Session::flash('success', 'Your image was successfully uploaded!');
            return redirect('/images');                    
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
        Storage::delete('public/images/'.$image->user_id.'/png/'.$image->path);

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

/* Mislim da to ne treba jer je download controller gotov


    //Download slike -popraviti jer ništa se ne događa
    public function download($image_id, $png){
        $image = Image::findOrFail($image_id);
        if($png == 0)
        {
            $image_name = substr($image->path, 0, -3);
            return response()->download(storage_path('app/public/'.$image->user_id.'/'.$image_name.$image->extension));
        }

        return 'text';
    }
*/

}
