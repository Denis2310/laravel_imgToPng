<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Image;
use App\SendImage as SentReceivedImages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Validator;

class SendReceiveImageController extends Controller
{

    public function __construct(){

        $this->middleware('auth');
        $this->middleware('user');
    }

    //Prikaz svih slika u sekciji Received
    public function index(){

        $user = Auth::user();
        $received_images = SentReceivedImages::where('to_user', $user->id)->get();
        return view('user.received', compact('received_images'));
    }

    //Prikaz slike nakon klika na sliku
    public function show($id){

        $image = SentReceivedImages::findOrFail($id);
        $image_data = Image::findOrFail($image->image_id);
        
        if(Auth::user()->id != $image->to_user)
        {
            return redirect()->back();
        }

        return view('user.show_received_image', compact('image', 'image_data'));
    }

    //Slanje slike drugom korisniku
    public function send(Request $request, $image_id){

        //Validator za provjeru da li postoji unešeni email (nije od admina, niti usera koji šalje sliku)
        $validator = Validator::make($request->all(), [
        'email' => [
            'required',
            Rule::exists('users')->where(function ($query) {
                
                $query->where([
                    ['id', '!=', Auth::user()->id],
                    ['role_id', 2]]);
                }),
            ],
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator);
        }

        //Dohvaćanje slike koja se šalje
        $image = Image::findOrFail($image_id);
        $receiver_email = $request->email;
        $receiver = User::where('email', $receiver_email)->firstOrFail();

        if($user= SentReceivedImages::where(['image_id'=>$image->id, 'to_user'=>$receiver->id])->exists())
        {
            return redirect()->back()->withErrors(['This image was already sent to this user.']);
        }

        //Dodaje se vrijeme ispred slike koja se šalje jer ako se šalju dvije iste slike a ne stavi se vrijeme biti će istog naziva
        //$time = time();
        $receiver_email = $request->email;
        $receiver = User::where('email', $receiver_email)->firstOrFail();

        //Uklanjanje prethodnog vremena iz imena slike koja se šalje i dodavanje novog
        //$sent_image_path = $time.substr($image->path, 10);

        //Putanja slike koja se šalje, putanja gdje se sprema slika
        if(Storage::copy('public/images/'.$image->user_id.'/uploaded/'.$image->path, 'public/images/'.$receiver->id.'/received/'.$image->path))
        {
            //Spremanje slike u tablicu SendImage
            $sendImage = new SentReceivedImages();
            $sendImage->image_id = $image->id;
            $sendImage->path = $image->path;
            $sendImage->to_user = $receiver->id;
            $sendImage->from_user = $image->user->name;
            $sendImage->save();
        
            $image->times_sent = $image->times_sent + 1;
            $image->save();

            Session::flash('success', 'Image was sent!');
            return redirect('/images');
        }

        return redirect()->back()->withErrors(['Image was not sent!']);
    }

    //Brisanje primljene slike
    public function destroy($id){

        $image = SentReceivedImages::findOrFail($id);
        //Podaci o slici u Images tablici
        $image_data = Image::findOrFail($image->image_id); 

        //Brisanje slike iz Images tablice ako nema vlasnika i nije poslana
        if($image_data->user_id == 0 && $image_data->times_sent == 1)
        {
            $image_data->delete();
        }
        else
        {
            $image_data->times_sent = $image_data->times_sent - 1;
            $image_data->save();
        }
        
        //Obriši primljenu sliku
        Storage::delete('public/images/'.$image->to_user.'/received/'.$image->path);
        //Obriši iz tablice primljenih slika
        $image->delete();
        
        Session::flash('success', 'Your image was successfully deleted!');
        return redirect('/images');
    }

}
