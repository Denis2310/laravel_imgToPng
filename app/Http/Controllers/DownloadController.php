<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Image;
use App\SendImage as ReceivedImage;
use Illuminate\Support\Facades\Storage;


class DownloadController extends Controller
{

	public function __construct(){

	    $this->middleware('auth');
    }


    public function download_user_image($image_id, $original=false){

            if(!$image_id)
            {
              return abort(404);
            }

            $image = Image::findOrFail($image_id);
            $user = Auth::user();

            //Autentifikacija korisnika
            if(($user->id != $image->user_id) && ($user->role->name != "Administrator"))
            {
                  return abort(404);
            }

            $image_real_name = substr($image->path, 10);

            if($original == true)
            {
                if(convert_image($image, $image->extension))
                {
                    $image_real_name = substr($image_real_name,0,-3) . $image->extension;

                    return response()->download('storage/images/'. $image->user_id .'/uploaded/'. $image_real_name, $image_real_name)->deleteFileAfterSend(true);
                }
                else {

                    return redirect()->back()->withErrors(['File can\'t be downloaded.']);
                }
            }

            elseif($original == false)
            {
                return response()->download('storage/images/'. $image->user_id .'/uploaded/'. $image->path, $image_real_name);
            }

            else
            {
                return redirect()->back()->withErrors(['File can\'t be downloaded.']);
            }
    }


    public function download_recv_image($image_id, $original=false){

            if(!$image_id)
            {
                return abort(404);
            }

            $image = ReceivedImage::findOrFail($image_id);
            $user = Auth::user();

            //Autentifikacija korisnika
            if(($user->id != $image->to_user) && ($user->role->name != "Administrator"))
            {
                return abort(404);
            }

            $image_real_name = substr($image->path, 10);
            $extension = $image->imageData->extension;
            
            if($original == true)
            {

                if(convert_image($image, $extension, true))
                {
                    $image_real_name = substr($image_real_name,0,-3) . $extension;

                    return response()->download('storage/images/'. $image->to_user .'/received/'. $image_real_name, $image_real_name)->deleteFileAfterSend(true);
                }
            }

            elseif($original == false)
            {
                return response()->download('storage/images/'. $image->to_user .'/received/'. $image->path, $image_real_name);
            }

            else
            {
                return redirect()->back()->withErrors(['File can\'t be downloaded.']);
            }
    }
}


function convert_image($image, $extension, $received=false){

    $image_name_without_extension = substr($image->path,0,-3);
    $image_realname = substr($image_name_without_extension, 10);

    if($received)
    {
      $image_full_path = storage_path().'/app/public/images/'.$image->to_user.'/received/'.$image->path;
      $store_path = storage_path().'/app/public/images/'.$image->to_user.'/received/';
    } 

    else
    {
      $image_full_path = storage_path().'/app/public/images/'.$image->user_id.'/uploaded/'.$image->path;
      $store_path = storage_path().'/app/public/images/'.$image->user_id.'/uploaded/';
    }

    $png_image = imagecreatefrompng($image_full_path);

    switch($extension){

        case 'bmp': $is_converted = imagebmp($png_image, $store_path.$image_realname.'bmp'); break;
        case 'jpg': $is_converted = imagejpeg($png_image, $store_path.$image_realname.'jpg'); break;
        case 'gif': $is_converted = imagegif($png_image, $store_path.$image_realname.'gif'); break;
    
        default: return false;
    }

    return $is_converted;
}