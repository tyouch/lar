<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
    //return response()->json(['abc'=>123]);
});*/

Route::get('foo', function () { //middleware('auth:api')->
    return response()->json(['abc' => 'Hello World']);
});//->middleware('auth:api');

Route::group(['middleware' => ['web']], function () {
    Route::get('index2', 'TestController@index2')->name('api.index2');
    Route::get('index3', 'TestController@index3')->name('api.index3');
});


Route::any('/', 'ApiController@index')->name('api');
Route::get('detail', 'Api\ShopController@getGoodsDetail')->name('api.shop.detail');//->middleware('auth:api');
Route::get('cart', 'Api\ShopController@getCart')->name('api.shop.cart1');//->middleware('auth:api');



Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'shop'], function () {
        Route::group(['prefix' => 'index'], function () {
            Route::get('adds', 'Api\ShopController@getIndexAdds')->name('api.shop.index.adds');//->middleware('auth:api');
            Route::get('goods', 'Api\ShopController@getIndexGoods')->name('api.shop.index.goods');//->middleware('auth:api');
        });
        Route::group(['prefix' => 'home'], function () {
        });
        Route::group(['prefix' => 'category'], function () {
        });
        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', 'Api\ShopController@getCart')->name('api.shop.cart');
        });
        Route::get('detail', 'Api\ShopController@getGoodsDetail')->name('api.shop.detail');//->middleware('auth:api');
        Route::get('address', 'Api\ShopController@getAddress')->name('api.shop.address');//->middleware('auth:api');
        Route::get('invoice', 'Api\ShopController@getInvoice')->name('api.shop.invoice');//->middleware('auth:api');

        Route::any('orders', 'Api\ShopController@doOrders')->name('api.shop.orders');
        Route::any('pay', 'Api\ShopController@pay')->name('api.shop.pay');
        Route::any('login', 'Api\ShopController@wxLogin')->name('api.shop.login');
    });

});



