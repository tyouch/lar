<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\Jssdk;
use App\Lib\Wechat\HttpRequest;
use App\Models\Fans;

class ShopController extends Controller
{
    private $appId;
    private $appSecret;
    private $redirectUri;
    private $mchID;
    private $apiKey;
    private $unifiedorderUrl;
    private $notifyUrl;
    private $openid;


    public function __construct()
    {
        $this->middleware('wechatAuth');

        $this->appId = config('wechat.appID');
        $this->appSecret = config('wechat.appSecret');

        $this->redirectUri = config('wechat.redirectUri');//
        $this->unifiedorderUrl = config('wechat.unifiedorderUrl');
        $this->notifyUrl = config('wechat.notifyUrl');

        $this->mchID = config('wechat.mchID');
        $this->apiKey = config('wechat.apiKey');
    }


    public function index()
    {

        $qs1 = 'appid='.$this->appId.'&mch_id='.$this->mchID.'&nonce_str='.random(32).'&product_id='.$this->appId.time().'&time_stamp='.time();
        $url = 'weixin://wxpay/bizpayurl?'.$qs1.'&sign='.strtoupper(md5($qs1.'&key='.$this->apiKey));
        //$url = 'http://www.tyoupub.com/';
        //dd($url, $qs1, $qs1.'key='.$this->apiKey);
        QrCode::format('png')->size(200)->merge('/public/imgs/headimg.jpg', .2)->margin(1)->generate($url, public_path('imgs/wx_pay_qrcode.png'));


        $package = [
            'appid'         => $this->appId, // test
            'mch_id'        => $this->mchID, // test
            'nonce_str'     => random(32),
            'body'          => '打赏店主',
            'out_trade_no'  => 'oid'.time(),
            'total_fee'     => 0.01 * 100,
            'spbill_create_ip'  => getip(),
            'notify_url'    => $this->notifyUrl,
            'trade_type'    => 'JSAPI',
            'openid'        => session('openid')//'odk8d0vQ3Oqr7UAOOPFaGxCuOG0E'

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];
        //dd($package);

        $signPackage = $this->getSignPackage($this->redirectUri.'mobile/shop/index');
        return view('mobile.shop.index', [
            'signPackage'       => $signPackage,
            'package'           => $package,
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
