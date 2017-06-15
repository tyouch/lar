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
Route::get('detection/map', 'DetectionController@map');

Route::get('test/index2', 'TestController@index2');
Route::get('test/ajax', 'TestController@ajax');
Route::get('excel', 'TestController@excelToDoc');
Route::get('test/putip', 'TestController@putIp');
Route::get('test/aliyun', 'TestController@aliyun');

Route::get('form', 'TestController@getFormData');
Route::get('request', 'TestController@request');
Route::get('post', 'TestController@post');
Route::any('hxcalling/tx', 'TestController@tx');

Route::get('ogw000{id}', 'HxCallingController@ogw000');

/*Route::get('ogw00041', 'HxCallingController@ogw00041');
Route::get('ogw00042', 'HxCallingController@ogw00042');
Route::get('ogw00043', 'HxCallingController@ogw00043');
Route::get('ogw00044', 'HxCallingController@ogw00044');
Route::get('ogw00045', 'HxCallingController@ogw00045');
Route::get('ogw00046', 'HxCallingController@ogw00046');
Route::get('ogw00047', 'HxCallingController@ogw00047');
Route::get('ogw00048', 'HxCallingController@ogw00048');
Route::get('ogw00049', 'HxCallingController@ogw00049');
Route::get('ogw00050', 'HxCallingController@ogw00050');
Route::get('ogw00051', 'HxCallingController@ogw00051');
Route::get('ogw00052', 'HxCallingController@ogw00052');
Route::get('ogw00053', 'HxCallingController@ogw00053');
Route::get('ogw00054', 'HxCallingController@ogw00054');
Route::get('ogw00055', 'HxCallingController@ogw00055');
Route::get('ogw00056', 'HxCallingController@ogw00056');
Route::get('ogw00057', 'HxCallingController@ogw00057');
Route::get('ogw00058', 'HxCallingController@ogw00058');
Route::get('ogw00059', 'HxCallingController@ogw00059');
Route::get('ogw00060', 'HxCallingController@ogw00060');
Route::get('ogw00061', 'HxCallingController@ogw00061');
Route::get('ogw00062', 'HxCallingController@ogw00062');
Route::get('ogw00063', 'HxCallingController@ogw00063');
Route::get('ogw00064', 'HxCallingController@ogw00064');
Route::get('ogw00065', 'HxCallingController@ogw00065');
Route::get('ogw00066', 'HxCallingController@ogw00066');
Route::get('ogw00067', 'HxCallingController@ogw00067');
Route::get('ogw00068', 'HxCallingController@ogw00068');
Route::get('ogw00069', 'HxCallingController@ogw00069');
Route::get('ogw00070', 'HxCallingController@ogw00070');
Route::get('ogw00071', 'HxCallingController@ogw00071');
Route::get('ogw00072', 'HxCallingController@ogw00072');
Route::get('ogw00073', 'HxCallingController@ogw00073');
Route::get('ogw00074', 'HxCallingController@ogw00074');
Route::get('ogw00075', 'HxCallingController@ogw00075');
Route::get('ogw00076', 'HxCallingController@ogw00076');
Route::get('ogw00077', 'HxCallingController@ogw00077');*/


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


