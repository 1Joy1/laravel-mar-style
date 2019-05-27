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

Route::get('/test', function () {
    //echo App::getLocale();
    //print_r(App\Message::where("id", 3)->select('text')->get());

});

Route::get('/phpinfo', function() {
    dump(Route::getMiddleware());
    phpinfo();
});


// Main Page Routes...
Route::get('/', 'MainPageController@index');
Route::get('photo', 'PhotoController@index');
//TODO: Kill this route & dependent controller
//Route::get('group', 'GroupController@index');
Route::get('group/{name}/photo', 'PhotoController@indexGroupPhoto');
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



// Admin Panel Routes...
Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['auth']], function() {

    Route::get('/', function () {
        return redirect('admin/photo');
    })->name('admin.index');

    // Register New Administrators Routes...
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');


    // Manage Photo, GroupPhoto and MessagesClients Routes...
    Route::put('photo/{id}/attach/group','PhotoController@attachGroup');
    Route::put('photo/{id}/detach/group','PhotoController@detachGroup');
    Route::delete('photo', 'PhotoController@deleteInactivePhotos');
    Route::resource('photo', 'PhotoController', ['as'=>'admin', 'except'=>['show', 'create', 'edit']]);
    Route::resource('group', 'GroupController', ['as'=>'admin', 'except'=>['destroy', 'show', 'store', 'create', 'edit']]);
    Route::resource('message', 'MessageController', ['as'=>'admin', 'except'=>['show', 'store', 'create', 'edit']]);

});
