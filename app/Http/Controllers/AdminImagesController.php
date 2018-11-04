<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use App\User;
use App\SendImage as SentImages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

class AdminImagesController extends Controller
{


    //Sve rute moraju proći middleware admin
    public function __construct(){
        
        $this->middleware('auth');
        $this->middleware('admin');
    }


    //Dohvaćanje svih slika i prikaz na stranici admina
    public function index()
    {
        $images = Image::all()->where('user_id', '>', 0);

        if($sent_images = SentImages::all())
        {
            $all_images = collect();

            foreach($images as $image)
            {
                $all_images->push($image);
            }

            foreach($sent_images as $image)
            {
                $all_images->push($image);
            }


            $images = $all_images->sortBy(function($image){

                return $image->created_at;
            });
        }

        return view('admin.images.index', compact('images'));
    }


 /*   public function create()
    {
        //
    }



    public function store(Request $request)
    {
        //
    }
*/


    //Prikaz jedne slike na koju se klikne
    public function show($id)
    {
        $image = Image::findOrFail($id);
        return view('admin.images.show-image', compact('image'));
    }

    //Prikaz poslane slike na koju se klikne
    public function show_sent($id)
    {
        $image = SentImages::findOrFail($id);
        return view('admin.images.show-sent-image', compact('image'));
    }


/*
    public function edit($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        //
    }

*/

    //Brisanje slike od strane admina
    public function destroy($id)
    {

        $image = Image::findOrFail($id);
        Storage::delete('public/images/'.$image->user_id.'/uploaded/'.$image->path);
     
        if($image->times_sent == 0)
        {
            $image->delete();  
        }
        else
        {
            $image->user_id = 0;
            $image->save();
        }
      
        Session::flash('success', 'Image successfully deleted!');
        return redirect('/admin/images');
    }

    //Brisanje slike iz tablice poslanih/primljenih slika
    public function destroy_sent($id)
    {

        $image = SentImages::findOrFail($id);
        $image_data = Image::findOrFail($image->image_id);

        //Brisanje slike iz Images tablice ako nema vlasnika i nije poslana
        //if($image_data->user_id == 0 && $image_data->times_sent == 1)
        if($image->imageData->user_id == 0 && $image->imageData->times_sent == 1)
        {
            //$image_data->delete();
            $image->imageData->delete();
        }
        else
        {
            //$image_data->times_sent = $image_data->times_sent - 1;
            //$image_data->save();
            $image->imageData->times_sent--;
            $image->imageData->save();
        }
        
        //Obriši primljenu sliku
        Storage::delete('public/images/'.$image->to_user.'/received/'.$image->path);
        
        //Obriši iz tablice primljenih slika
        $image->delete();
        
        Session::flash('success', 'Image successfully deleted!');
        return redirect('/admin/images');
    }
}
