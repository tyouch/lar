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



Auth::routes();
//Route::get('/home', 'HomeController@index');

Route::get('/', 'HomeController@index');
Route::get('hosts/{p}', 'HostsController@index');
Route::get('hosts/{p}/{filter}', 'HostsController@index');

Route::get('test/index2', 'TestController@index2');
Route::get('test/ajax', 'TestController@ajax');
Route::get('test/excel', 'TestController@excelToDoc');
Route::resource('test', 'TestController');

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


