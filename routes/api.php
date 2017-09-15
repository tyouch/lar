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
})->middleware('auth:api');


Route::any('/', 'ApiController@index')->name('api');
Route::get('detail', 'Api\ShopController@getGoodsDetail')->name('api.shop.detail');//->middleware('auth:api');



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
        });
        Route::get('detail', 'Api\ShopController@getGoodsDetail')->name('api.shop.detail');//->middleware('auth:api');
        Route::get('address', 'Api\ShopController@getAddress')->name('api.shop.address');//->middleware('auth:api');
        Route::get('invoice', 'Api\ShopController@getInvoice')->name('api.shop.invoice');//->middleware('auth:api');
        Route::post('orders', 'Api\ShopController@orders')->name('api.shop.orders');//->middleware('auth:api');
        Route::any('pay', 'Api\ShopController@pay')->name('api.shop.pay');
        Route::any('login', 'Api\ShopController@wxLogin')->name('api.shop.login');
    });

});



