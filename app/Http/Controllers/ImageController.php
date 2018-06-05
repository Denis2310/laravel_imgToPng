<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Auth;
Use App\Image;
use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\File;
use Validator;

class ImageController extends Controller
{
    
	public function __construct(){

		return $this->middleware('auth');
	}
    
    public function index(){

    	$user = Auth::user();
        $images = $user->images;
        return view('user.images', compact('images'));
    }

    public function show($id){
    	$image = Image::findOrFail($id);
    	return view('user.show_image', compact('image'));
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
		$target_directory = "user_images/".$user->id."/";
		$target_directory_png = "user_images/".$user->id."/png/";

		//Ako ne postoji, kreiraj png folder
		if(!File::exists($target_directory_png)) {
    		File::makeDirectory($target_directory_png, 775, true);
		}

		//Dohvaćanje podataka o slici
		$file = $request->file('image');
		$file_name = $time . $file->getClientOriginalName();
		$file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);
		$file_name_png = $file_name_without_extension .'.png';
		
		//Spremanje png i originalne slike na server
		$png_image =Img::make($file);
		$png_image->save($target_directory_png . $file_name_png);
		$file->move($target_directory, $file_name);

		//Spremanje podataka o slici u bazu
		$image = new Image;
    	$image->path = $file_name_png;
    	$image->user_id = $user->id;
    	$image->extension = $file->getClientOriginalExtension();
    	$image->size = $file->getClientSize();
    	$image->png_size = $png_image->fileSize();
    	$image->save();
		return redirect('/images');
	}
}
