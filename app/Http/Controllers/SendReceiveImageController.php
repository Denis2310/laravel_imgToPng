<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
Use App\Image;
Use App\ImageUser;
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

    public function index()
    {
        $user = Auth::user();
        $received_images = ImageUser::where('to_user', $user->id)->get();
        return view('user.received', compact('received_images'));
    }

    public function show($id)
    {
        $image = ImageUser::findOrFail($id);
        $image_data = Image::findOrFail($image->image_id);
        if(Auth::user()->id != $image->to_user)
        {
            return redirect()->back();
        }
        return view('user.show_received_image', compact('image', 'image_data'));
    }

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

        //Dodaje se vrijeme ispred slike koja se šalje jer ako se šalju dvije iste slike a ne stavi se vrijeme biti će istog naziva
        $time = time();
        $receiver_email = $request->email;
        $receiver = User::where('email', $receiver_email)->firstOrFail();

        //Uklanjanje prethodnog vremena iz imena slike koja se šalje i dodavanje novog
        $sent_image_path = $time.substr($image->path, 10);

        //Putanja slike koja se šalje, putanja gdje se sprema slika
        Storage::copy('public/images/'.$image->user_id.'/png/'.$image->path, 'public/images/'.$receiver->id.'/received/'.$sent_image_path);

        //Spremanje slike u tablicu imageusers
        $sendImage = new ImageUser;
        $sendImage->image_id = $image->id;
        $sendImage->path = $sent_image_path;
        $sendImage->to_user = $receiver->id;
        $sendImage->from_user = $image->user->name;
        $sendImage->save();
        $image->times_sent = $image->times_sent + 1;
        $image->save();

        Session::flash('success', 'Image was sent!');
        return redirect('/images');

    }

    //Brisanje primljene slike
    public function destroy($id)
    {
        $image = ImageUser::findOrFail($id);
        $image_data = Image::findOrFail($image->image_id); //Podaci o slici u Images tablici

        //Brisanje slike iz Images tablice ako nema vlasnika i nikome više nije poslana
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
        $image->delete();
        
        Session::flash('success', 'Your image was successfully deleted!');
        return redirect('/received');

    }

}
