<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Image;
use App\SendImage as ReceivedImages;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;


class UserImagesController extends Controller
{

    public function __construct(){

        $this->middleware('auth');
        $this->middleware('user');
    }

    //Pogled korisnikove slike
    public function index(){

        $user = Auth::user();
        $images = $user->images;

        if($received_images = $user->received_images)
        {
            $all_images = collect();

            foreach($images as $image)
            {
                $all_images->push($image);
            }

            foreach($received_images as $image)
            {
                $all_images->push($image);
            }
            
            $images = $all_images->sortBy(function($image){

                return $image->created_at;
            });
        }

       
        return view('user.images', compact('images'));
    }



    //Prebacivanje na pogled za upload slike
    public function create(){

        return view('user.upload');
    }



    //Validacija, konverzija u png, spremanje slike
    public function store(UploadRequest $request){

        //Provjera duljine imena slike (max 20 znakova)
        if(!is_valid_name($request->file('file'))) 
        {
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
            Storage::put('public/images/'.$user->id.'/uploaded/'.$image_name, file_get_contents($image));
            return save_image_to_database($image, $user, $time);
        }
        else
        {
            $result = check_extension_and_convert($image, $user, $time);

            //mAKNIO JEDNU OZNAKU =
            if($result == true)
            {
                //Spremanje originalne slike
                return save_image_to_database($image, $user, $time);
            }
            elseif($result == false)
            {
                return redirect()->back()->withErrors(['Something went wrong, file was not uploaded!.']);
            }
            else
            {
                return redirect()->back()->withErrors([$result]);    
            }
        }
    }



    //Show image pogled, nakon klika na sliku
    public function show($id){

        $image = Image::findOrFail($id);
        
        if(Auth::user()->id != $image->user_id)
        {
            return abort(404);
        }

        return view('user.show_image', compact('image'));
    }


/*
    public function edit($id) {

        //
    }


    public function update(Request $request, $id)
    {
        //
    }
    */

    public function destroy($id)
    {
        
        $image = Image::findOrFail($id);

        //Pronađi sliku u folderu i obriši ju
        Storage::delete('/public/images/'.$image->user_id.'/uploaded/'.$image->path);
        Session::flash('success', 'Your image was successfully deleted!');

        //Ako je slika poslana, obriši samo ID od vlasnika
        if($image->times_sent > 0)
        {
            $image->user_id = 0;
            $image->save();
        }
        else
        {
            //Slika nije poslana obriši ju iz baze podataka
            $image->delete();
        
        }
 
        return redirect('/images');                      
    }
} // Kraj kontrolera


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

        //Bmp baca gresku za monokromatski bitmap
        case 'bmp': 
            try {
                $original_image = imagecreatefrombmp($image->path()); break; //image->path vraca tmp path
                } 
            catch (\Exception $e){

                return 'Invalid type, 1-bit Bitmap is not allowed.'; break;
            }
        case 'jpg': $original_image = imagecreatefromjpeg($image->path()); break;
        case 'gif': $original_image = imagecreatefromgif($image->path()); break;
        
        default: return 'Invalid type of file.';
    }

    //Provjeri da li postoji direktorij, ako ne postoji kreiraj ga
    if (!file_exists(storage_path().'/app/public/images/'.$user->id.'/uploaded/')) {

        mkdir(storage_path().'/app/public/images/'.$user->id.'/uploaded/', 0777, true);
    }

    $is_converted_and_saved = imagepng($original_image, storage_path().'/app/public/images/'.$user->id.'/uploaded/'.$image_name_png);

    return $is_converted_and_saved;
}


function save_image_to_database($image, $user, $time){

    $db_image = new Image();


    if($image->extension() == 'png')
    {
        $db_image->path = $time . $image->getClientOriginalName();

        try {
            $db_image->png_size = $image->getClientOriginalSize();
            } 
        catch (\Exception $e){

                $db_image->png_size = $image->getSize();
            }
    }
    else
    {
        $path = $time . pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME).'.png';
        $png_size = Storage::size('/public/images/'.$user->id.'/uploaded/'.$path);

        $db_image->path = $path;
        $db_image->png_size = $png_size;
    }

    $db_image->user_id = $user->id;
    $db_image->extension = $image->getClientOriginalExtension();
    $db_image->size = $image->getClientSize();   
    $db_image->save();

    Session::flash('success', 'Image uploaded.');
    return redirect('/images');
}





