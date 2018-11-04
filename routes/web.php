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


/*
|-------------------------------------------------
| Administrator routes
|-------------------------------------------------
*/

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

Route::delete('sent/images/{image_id}', 'AdminImagesController@destroy_sent')->name('admin.sent-images.destroy');

Route::get('admin/images/sent/{image_id}', 'AdminImagesController@show_sent');


/*
|-------------------------------------------------
|UserImages Controller routes
|-------------------------------------------------
*/

Route::resource('/images', 'UserImagesController');



/*
|-------------------------------------------------
| SendReceiveImageController user routes
|-------------------------------------------------
*/

Route::get('/received', 'SendReceiveImageController@index');

Route::get('/received/{image_id}', 'SendReceiveImageController@show');

Route::post('/images/{image_id}', 'SendReceiveImageController@send')->name('images.send');

Route::delete('/received/{image_id}', 'SendReceiveImageController@destroy')->name('received.destroy');



/*
|-------------------------------------------------
| DownloadController routes
|-------------------------------------------------
*/

Route::get('download/{image_id}/{isoriginal?}', 'DownloadController@download_user_image');

Route::get('received/download/{image_id}/{isoriginal?}', 'DownloadController@download_recv_image');


