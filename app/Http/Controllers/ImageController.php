<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Auth;
Use App\Image;
use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

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
    	if(Auth::user()->id != $image->user_id)
    	{
    		return redirect()->back();
    	}
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
		if(strlen($request->file('file')->getClientOriginalName()) > 24)
		{
			return redirect()->back()->withErrors(['File name can\'t be longer than 20 characters.']);
		}
		$user = Auth::user();
		$time = time();
		$db_image = new Image();
		$image = $request->file('file');
		$image_name = $time . $image->getClientOriginalName();

		if($image->getClientOriginalExtension() == "png")
		{
			Storage::put('public/images/'.$user->id.'/png/'.$image_name, file_get_contents($image));
			$db_image->path = $image_name;
			$db_image->user_id = $user->id;
			$db_image->extension = $image->getClientOriginalExtension();
			$db_image->png_size = $image->getClientSize();
			$db_image->save();
			return redirect('/images');
		}

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
    	/*Session::flash('success', 'Your image was successfully uploaded!');*/
		return redirect('/images');
	}

	public function destroy($id)
	{
		$image = Image::findOrFail($id);
		Storage::delete('public/images/'.$image->user_id.'/png/'.$image->path);
		if($image->extension != "png")
		{
			$image_name = substr($image->path, 0, -3);
			Storage::delete('public/images/'.$image->user_id.'/'.$image_name.$image->extension);
		}
		$image->delete();
		return redirect('/images');
	}
}

//Storage::size('public/images/'.$user->id.'/png/'.$image_name_png);