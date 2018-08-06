<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use App\User;
use App\SendImage as SentImages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class AdminImagesController extends Controller
{


    //Sve rute moraju proći middleware admin
    public function __construct(){
        return $this->middleware('admin');
    }


    //Dohvaćanje svih slika i prikaz na stranici admina
    public function index()
    {
        $images = Image::all()->where('user_id', '>', 0);
        
        if($sent_images = SentImages::all())
        {
            $all_images = $images->merge($sent_images);
            $images = $all_images->sortBy(function($image){

                return $image->created_at;
            });
        }
        return view('admin.images.index', compact('images'));
    }


    public function create()
    {
        //
    }



    public function store(Request $request)
    {
        //
    }



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
        $to_user = User::findOrFail($image->to_user)->name;
        $image_data = Image::findOrFail($image->image_id);
        return view('admin.images.show-sent-image', compact('image', 'image_data', 'to_user'));
    }



    public function edit($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        //
    }



    //Brisanje slike od strane admina
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        if($image->times_sent == 0)
        {
            $image->delete();  
        }
        else
        {
            $image->user_id = 0;
            $image->save();
        }
        
        Storage::delete('public/images/'.$image->user_id.'/uploaded/'.$image->path);    
        Session::flash('success', 'User image successfully deleted!');
        return redirect('/admin/images');
    }
}
