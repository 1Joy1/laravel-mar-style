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

Route::get('/', function () {

    $messages = App\Message::all();

    $groups = App\Group::all();


    foreach ($groups as $group) {
        $portfol[$group->name] = ['img'=>$group->photo_src, 'name'=>$group->display_name];
    }


    return view('main', ['menu' => [
                                ['id'=>'menu-main', 'href'=>'#main', 'name'=>'Главная'],
                                ['id'=>'menu-portfol', 'href'=>'#portfolio', 'name'=>'Портфолио'],
                                ['id'=>'menu-service', 'href'=>'#services', 'name'=>'Услуги'],
                                ['id'=>'menu-feedback', 'href'=>'#feadback', 'name'=>'Отзывы'],
                                ['id'=>'menu-link', 'href'=>'#link', 'name'=>'Ссылки'],
                            ],
                         'portfol' => $portfol,
                         'messages'=> $messages,
                        ]);
});



Route::resource('photo', 'PhotoController', ['only' => ['index', 'show']]);

Route::get('group/{name}/photo', 'GroupController@indexGroupPhoto');

Route::resource('group', 'GroupController', ['only' => ['index', 'show']]);

Route::resource('message', 'MessageController', ['only' => ['index', 'create', 'store']]);

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'admin', 'namespace'=>'Admin', 'middleware'=>['auth']], function(){
    Route::get('/', 'DashboardController@index')->name('admin.index');
    Route::put('photo/{id}/attach/group','PhotoController@attachGroup');
    Route::put('photo/{id}/detach/group','PhotoController@detachGroup');
    Route::resource('photo', 'PhotoController', ['as'=>'admin', 'except'=>['show', 'create', 'edit']]);
    Route::resource('group', 'GroupController', ['as'=>'admin', 'except'=>['destroy', 'show', 'store', 'create', 'edit']]);
    Route::resource('message', 'MessageController', ['as'=>'admin', 'except'=>['show', 'store', 'create', 'edit']]);

});
