<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Image;
use App\ImageUser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AdminUsersController extends Controller
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

    //Dohvaćanje svih korisnika i prikaz u tablici
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.users.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //Spremanje novog korisnika kreiranog od strane admina
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

    //Prikaz korisnika nakon klika na njega u tablici
    public function show($id)
    {
        $user = User::findOrFail($id);
        $images = $user->images;
        return view('admin.users.show-user', compact('user', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

    //Brisanje korisnika
    public function destroy($id)
    {
        //Provjera da li admin briše drugog administratora ili sebe
        if(User::findOrFail($id)->isAdmin())
        {
            if(Auth::user()->id == $id)
            {
                return redirect()->back()->withErrors('You cannot delete yourself!.');
            }

            return redirect()->back()->withErrors('Administrator cannot be deleted!');
        }

        //Brisanje slika iz baze od korisnika koje nisu poslane
        $images = Image::whereUser_id($id)->get();
        if($images)
        {
            //Provjeri za svaku sliku da li je poslana, ako nije obriši ju, u suprotnom dodjeli joj user_id=0
            foreach($images as $image)
            {
                if($image->times_sent == 0)
                {
                    $image->delete();
                }
                else
                {
                    $image->user_id = 0;
                    $image->save();
                }


            }
        }

        //Brisanje primljenih slika iz baze
        $received_images = ImageUser::whereTo_user($id)->get();
        if($received_images)
        {
            foreach($received_images as $received_image)
            {
                $image = Image::findOrFail($received_image->image_id);
                if($image->times_sent == 1 && $image->user_id == 0)
                {
                    $image->delete();
                }
                else
                {
                    $image->times_sent = $image->times_sent - 1;
                }

                $received_image->delete();
            }
        }

        User::findOrFail($id)->delete();
        Storage::deleteDirectory('public/images/'.$id);

        Session::flash('success', 'User deleted!');
        return redirect('/admin/users');

    }
}
