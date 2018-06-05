<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Auth;
Use App\Image;
use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
		
		$image = $request->file('image');
		$image_name = $time . $image->getClientOriginalName();
		$image_name_without_extension = pathinfo($image_name, PATHINFO_FILENAME);
		$image_name_png = $image_name_without_extension .'.png';
		//Pretvaranje originalne slike u png
		$png_image = (string) Img::make($image)->encode('png');

		//Spremanje originalne slike i png slike
		Storage::put('public/images/'.$user->id.'/'.$image_name, file_get_contents($image));
		Storage::put('public/images/'.$user->id.'/png/'.$image_name_png, $png_image);


		//Spremanje podataka o slici u bazu
		$db_image = new Image;
    	$db_image->path = $image_name_png;
    	$db_image->user_id = $user->id;
    	$db_image->extension = $image->getClientOriginalExtension();
    	$db_image->size = $image->getClientSize();
    	$db_image->png_size = strlen($png_image);
    	$db_image->save();
		return redirect('/images');
	}
}

//Storage::size('public/images/'.$user->id.'/png/'.$image_name_png);