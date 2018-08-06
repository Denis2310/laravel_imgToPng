<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminNewUserRequest;
use App\User;
use App\Image;
use App\SendImage as ReceivedImages;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AdminUsersController extends Controller
{

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


    public function create()
    {
        return view('admin.users.new');
    }


    //Spremanje novog korisnika kreiranog od strane admina
    public function store(AdminNewUserRequest $request)
    {
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        Session::flash('Success', 'New user registered!');
        return redirect('admin/users');
    }


    //Prikaz korisnika nakon klika na njega u tablici
    public function show($id)
    {
        $user = User::findOrFail($id);
        $images = $user->images;
        
        //Ako postoje primljene slike spoji ih i sortiraj za korisnikovim slikama
        if($received_images = ReceivedImages::whereTo_user($user->id)->get()){

            $all_images = $images->merge($received_images); 
            
            $images = $all_images->sortBy(function($image){

                return $image->created_at;
            });
        }
        return view('admin.users.show-user', compact('user', 'images'));
    }



    public function edit($id)
    {

    }



    public function update(Request $request, $id)
    {
        //
    }



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

        //Brisanje primljenih slika iz baze
        $received_images = ReceivedImages::whereTo_user($id)->get();
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
                    $image->save();
                }
                
                $received_image->delete();
            }
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

        User::findOrFail($id)->delete();
        Storage::deleteDirectory('public/images/'.$id);

        Session::flash('success', 'User deleted!');
        return redirect('/admin/users');

    }
}
