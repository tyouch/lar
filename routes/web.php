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

//Route::group(['middleware' => 'auth'], function () {

    Route::get('admin', 'HomeController@index')->name('home');
    //Route::get('/', 'HomeController@index');
    Route::get('/', 'Mobile\ShopController@index');

    Route::group(['prefix'=>'hosts'], function (){
        Route::get('{p}', 'HostsController@index')->name('hosts');
        Route::get('{p}/{filter}', 'HostsController@index');
    });

    Route::group(['prefix'=>'detection'], function (){
        Route::get('map', 'DetectionController@map')->name('detection.map');
    });

    Route::group(['prefix'=>'account'], function (){
        Route::get('/', 'AccountController@index')->name('account.index');
        Route::post('upload', 'AccountController@uploadFile')->name('account.upload');
        Route::get('operate', 'AccountController@operate')->name('account.operate');
        Route::post('store', 'AccountController@store')->name('account.store');
    });


    //---------------------------------------------
    Route::group(['prefix'=>'mobile'], function (){
        Route::group(['prefix'=>'shop'], function (){
            Route::get('index', 'Mobile\ShopController@index')->name('mobile.shop.index');
            Route::get('category', 'Mobile\ShopController@category')->name('mobile.shop.category');
            Route::get('cart', 'Mobile\ShopController@cart')->name('mobile.shop.cart');
            Route::get('home', 'Mobile\ShopController@home')->name('mobile.shop.home');
        });
    });

    Route::group(['prefix'=>'pay'], function (){
        //Route::get('/', 'Payment\PayController@index')->name('pay.index');
        Route::get('index', 'Payment\PayController@index')->name('pay.index');
        //Route::get('notify', 'Payment\PayController@notify')->name('pay.notify');
    });

    Route::get('notify.php', 'Payment\NotifyController@index')->name('notify.index');
    Route::group(['prefix'=>'notify'], function (){
        Route::get('test', 'Payment\NotifyController@test')->name('notify.test');
    });
    //---------------------------------------------


    Route::group(['prefix'=>'test'], function (){
        Route::get('/', 'TestController@index')->name('test.index');
        Route::get('index2', 'TestController@index2');
        Route::get('vue', 'TestController@vue')->name('test.vue');
        Route::get('ajax', 'TestController@ajax');
        Route::get('excel', 'TestController@excelToDoc');
        Route::get('putip', 'TestController@putIp');
        Route::get('aliyun', 'TestController@aliyun');
        Route::get('form', 'TestController@getFormData');
        Route::get('request', 'TestController@request');
    });

    Route::group(['prefix'=>'hxcalling'], function (){
        Route::get('post', 'HxCallingController@post')->name('hxCalling.post');
        Route::any('tx', 'HxCallingController@tx')->name('hxCalling.tx');
        Route::get('ogw000{id}', 'HxCallingController@ogw000')->where('id', '[4-7]|[1-9]\d')->name('hxcalling.ogw');
    });



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
//});

Route::get('post', 'HxCallingController@post');
Route::get('cors', 'TestController@cors');

