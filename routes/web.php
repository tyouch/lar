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

/*Route::get('/', function () {
    //return view('welcome');
    return view('index', [
        'title'=>'index'
    ]);
});*/

/*Route::get('test/{age?}', function ($age = 0) {
    return view('test', [
        'age'   => $age
    ]);
})->middleware('mycheck');*/


Route::get('test/index2', 'TestController@index2');
Route::resource('test', 'TestController');


Route::get('/', 'IndexController@index');
Route::get('hosts/{p}', 'HostsController@index');


Route::get('user', 'UserController@index');
Route::resource('photo', 'PhotoController');
/*, [
    'only' => [
        'index', 'show', 'create', 'update'
    ],
    'names' => [
        'create' => 'photo.build'
    ]
]*/