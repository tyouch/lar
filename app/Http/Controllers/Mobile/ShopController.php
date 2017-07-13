<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\Jssdk;
use App\Lib\Wechat\HttpRequest;
use App\Models\Fans;

class ShopController extends Controller
{
    private $appId;
    private $appSecret;
    private $redirectUri;
    private $openid;


    public function __construct()
    {
        $this->middleware('wechatAuth');

        $this->appId = config('wechat.appID');
        $this->appSecret = config('wechat.appSecret');
        $this->redirectUri = config('wechat.redirectUri');
    }


    public function index()
    {
        $signPackage = $this->getSignPackage($this->redirectUri.'mobile/shop/index');
        return view('mobile.shop.index', [
            'signPackage'       => $signPackage,
            'navActive'         => 'index'
        ]);
    }

    public function category()
    {
        $signPackage = $this->getSignPackage($this->redirectUri.'mobile/shop/category');
        return view('mobile.shop.category', [
            'signPackage'       => $signPackage,
            'navActive'         => 'category'
        ]);
    }

    public function cart()
    {
        $signPackage = $this->getSignPackage($this->redirectUri.'mobile/shop/cart');
        return view('mobile.shop.cart', [
            'signPackage'       => $signPackage,
            'navActive'         => 'cart'
        ]);
    }

    public function home()
    {
        $this->openid = session('openid');
        //dd($this->openid);
        $fans = Fans::where(['from_user'=>$this->openid])->first();
        //dd($fans);

        $signPackage = $this->getSignPackage($this->redirectUri.'mobile/shop/home');
        return view('mobile.shop.home', [
            'signPackage'       => $signPackage,
            'fans'              => $fans,
            'navActive'         => 'home'
        ]);
    }

    public function getSignPackage($url)
    {
        $params = [
            'appid'     => $this->appId,
            'appsecret' => $this->appSecret,
        ];
        $jssdk = new Jssdk($params);
        return $jssdk->getSignPackage($url);
    }
}
