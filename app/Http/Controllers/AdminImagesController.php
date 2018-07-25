<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
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
