<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class AdminImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Sve rute moraju proći middleware admin
    public function __construct(){
        return $this->middleware('admin');
    }

    //Dohvaćanje svih slika i prikaz na stranici admina
    public function index()
    {
        $images = Image::all();
        return view('admin.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Prikaz jedne slike na koju se klikne
    public function show($id)
    {
        $image = Image::findOrFail($id);
        return view('admin.images.show-image', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Brisanje slike od strane admina
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
        Session::flash('success', 'User image successfully deleted!');
        return redirect('/admin/users');
    }
}
