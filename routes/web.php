<?php
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Redirektanje logiranog korisnika sa welcome pogleda
Route::get('/', function(){
	
	if(Auth::check())
	{
		if(Auth::user()->role->name === "Administrator")
		{
			return redirect('admin/users');
		}

		return redirect('/home');
	}

	return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/admin/users', 'AdminUsersController');

Route::resource('/admin/images', 'AdminImagesController');

//ruta za formu za uploadanje slike

Route::get('/images', 'ImageController@index');

Route::get('/images/{image_id}', 'ImageController@show');

Route::get('/upload', 'ImageController@upload');

Route::post('/upload', 'ImageController@store');

Route::get('/received', 'ImageController@received');

