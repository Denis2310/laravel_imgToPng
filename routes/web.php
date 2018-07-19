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

Route::resource('/admin/users', 'AdminUsersController')->names([
	'index' => 'admin.users.index',
	'store' => 'admin.users.store',
	'create' => 'admin.users.create',
	'destroy' => 'admin.users.destroy',
	'update' => 'admin.users.update',
	'show' => 'admin.users.show',
	'edit' => 'admin.users.edit'
]);

Route::resource('/admin/images', 'AdminImagesController')->names([
	'index' => 'admin.images.index',
	'store' => 'admin.images.store',
	'create' => 'admin.images.create',
	'destroy' => 'admin.images.destroy',
	'update' => 'admin.images.update',
	'show' => 'admin.images.show',
	'edit' => 'admin.images.edit'
]);

//User routes
/*Route::get('/images', 'ImageController@index')->name('home');

Route::get('/images/{image_id}', 'ImageController@show');

Route::delete('/images/{image_id}', 'ImageController@destroy')->name('images.destroy');

Route::get('/upload', 'ImageController@upload');

Route::post('/upload', 'ImageController@store');*/


Route::resource('/images', 'UserImagesController');


/****SendReceiveImageController routes***/

Route::get('/received', 'SendReceiveImageController@index');

Route::get('/received/{image_id}', 'SendReceiveImageController@show');

Route::post('/images/{image_id}', 'SendReceiveImageController@send')->name('images.send');

Route::delete('/received/{image_id}', 'SendReceiveImageController@destroy')->name('received.destroy');


/***DownloadController routes***/

Route::get('/images/download/{image_id}', 'DownloadController@download_original');

Route::get('/images/download/png/{image_id}/{isreceived?}', 'DownloadController@download_png');

Route::get('/received/download/{image_id}', 'DownloadController@download_original_recv');

