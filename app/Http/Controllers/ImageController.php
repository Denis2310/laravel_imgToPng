<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Auth;
Use App\Image;
use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    
	public function __construct(){

		return $this->middleware('auth');
	}
    
    public function upload(){

    	return view('user.upload');
    }

    public function received(){

    	return view('user.received');
    }

    // Prvo se provjerava UploadRequest i ako validacija prođe ulazi se u funkciju store
	public function store(UploadRequest $request){
		$user = Auth::user();
		$time = time();
		
		//Definiranje foldera za slike
		$target_directory = "images/".$user->id."/";
		$target_directory_png = "images/".$user->id."/png/";

		//Ako ne postoji, kreiraj png folder
		if(!File::exists($target_directory_png)) {
    		File::makeDirectory($target_directory_png, 775, true);
		}

		//Dohvaćanje podataka o slici
		$file = $request->file('image');
		$file_name = $time . $file->getClientOriginalName();
		$file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
		$file_name_png = $file_name_without_extension .'.png';
		
		//Spremanje originalne i png slike u foldere
		Img::make($file)->save($target_directory_png . $file_name_png);
		$file->move($target_directory, $file_name);

		//Spremanje podataka o slici u bazu
		$image = new Image;
    	$image->path = $file_name_png;
    	$image->extension = $file->getClientOriginalExtension();
    	$image->user_id = $user->id;
    	$image->save();
		return redirect('/home');
	}
}
