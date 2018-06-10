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

		return redirect('/images');
	}

	return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/admin/users', 'AdminUsersController');

Route::resource('/admin/images', 'AdminImagesController');

//User routes
Route::get('/images', 'ImageController@index')->name('home');

Route::get('/images/{image_id}', 'ImageController@show');

Route::delete('/images/{image_id}', 'ImageController@destroy')->name('images.destroy');

Route::get('/upload', 'ImageController@upload');

Route::post('/upload', 'ImageController@store');

Route::get('/received', 'ImageController@received');

