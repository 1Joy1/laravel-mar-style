<?php

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
//use App\Group;
Route::get('/test', function () {
    echo "<pre>";
    //echo App::getLocale();
    //print_r(App\Message::where("id", 3)->select('text')->get()) ;
    //return view('welcome');
});

Route::get('/', 'MainPageController@index');


Route::resource('photo', 'PhotoController', ['only' => ['index']]);

Route::get('group/{name}/photo', 'GroupController@indexGroupPhoto');

Route::resource('group', 'GroupController', ['only' => ['index']]);

Route::resource('message', 'MessageController', ['only' => ['index', 'create', 'store']]);

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['auth']], function(){

    Route::get('/', 'DashboardController@index')->name('admin.index');

    Route::put('photo/{id}/attach/group','PhotoController@attachGroup');

    Route::put('photo/{id}/detach/group','PhotoController@detachGroup');

    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');

    Route::delete('photo', 'PhotoController@deleteInactivePhotos');

    Route::resource('photo', 'PhotoController', ['as'=>'admin', 'except'=>['show', 'create', 'edit']]);

    Route::resource('group', 'GroupController', ['as'=>'admin', 'except'=>['destroy', 'show', 'store', 'create', 'edit']]);

    Route::resource('message', 'MessageController', ['as'=>'admin', 'except'=>['show', 'store', 'create', 'edit']]);

});
