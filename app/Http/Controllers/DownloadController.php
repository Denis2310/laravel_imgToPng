<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Use App\Image;
use App\ImageUser;

use Intervention\Image\Facades\Image as Img;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
	public function __construct(){

	     $this->middleware('auth');
           $this->middleware('user');
    	}

	public function download_png($id, $received=null){

	   	//Provjera da li je to primljena slika
	   	if($received == true)
       	{
                  //Provjera da li je slika od logiranog korisnika
       		$image = ImageUser::findOrFail($id);
       		if(Auth::user()->id != $image->to_user)
       		{
       			return abort(404);
       		} 

       		$image_realname = substr($image->path, 10); 

                  //Dohvaćanje slike iz korisnikove mape primljenih slika
       		return response()->download('storage/images/'.$image->to_user.'/received/'.$image->path, $image_realname);
       	}

       	//Ako nije primljena onda se skida korisnikova učitana slika
       	$image = Image::findOrFail($id);
       	if(Auth::user()->id != $image->user_id)
       	{
       		return abort(404);
       	}

       	$image_realname = substr($image->path, 10); 

            //Dohvaćanje korisnikove slike iz mape
       	return response()->download('storage/images/'.$image->user_id.'/png/'.$image->path, $image_realname);
	   }


      //Skidanje originalne slike koju je korisnik sam učitao
      public function download_original($id){

       	$image = Image::findOrFail($id);
       	if(Auth::user()->id != $image->user_id)
       	{
       		return abort(404);
       	}
       	$image_path = substr($image->path,0,-3);
       	$image_realname = substr($image_path, 10).$image->extension;
       	return response()->download('storage/images/'.$image->user_id.'/'.$image_path.$image->extension, $image_realname);
       }
      
      //Funkcija za konverziju i skidanje primljene png slike
      public function download_original_recv($id){
       	$png_image = ImageUser::findOrFail($id);

       	if(Auth::user()->id != $png_image->to_user)
       	{
       		return abort(404);
       	}

            //Dohvaćanje ekstenzije originalne slike kako bi se png mogao pretvoriti u original
       	$extension = Image::findOrFail($png_image->image_id)->extension;

            //Uklanjanje ekstenzije
       	$image_path = substr($png_image->path,0,-3);

            //Uklanjanje brojeva dodanih prilikom spremanja u bazu podataka
       	$image_realname = substr($image_path, 10);

            //Ako je slika već bila skidana
      	if($png_image->is_converted == true)
      	{
      		return response()->download('storage/images/'.$png_image->to_user.'/received/original/'.$image_path.$extension, $image_realname.$extension);
      	} 
            else
            {
            //Dohvaćanje primljene png slike
            $png_image_full_path = Storage::get('public/images/'.$png_image->to_user.'/received/'.$png_image->path);

            //Pretvorba slike u original
            $originalImage = (string) Img::make($png_image_full_path)->encode($extension);

            //Spremanje pretvorene slike
            Storage::put('public/images/'.$png_image->to_user.'/received/original/'.$image_path.$extension, $originalImage);

            //Slanje podatka u bazu da je slika pretvorena u original
            $png_image->is_converted = true;
            $png_image->save();

            return response()->download('storage/images/'.$png_image->to_user.'/received/original/'.$image_path.$extension, $image_realname.$extension);                  
            }

       }
}
