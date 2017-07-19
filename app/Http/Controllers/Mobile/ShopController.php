<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\Jssdk;
use App\Lib\Wechat\Pay;
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
    private $shorturl;


    public function __construct()
    {
        $this->middleware('wechatAuth');

        $this->appId = config('wechat.appID');
        $this->appSecret = config('wechat.appSecret');

        $this->redirectUri = config('wechat.redirectUri');//
        $this->unifiedorderUrl = config('wechat.unifiedorderUrl');
        $this->notifyUrl = config('wechat.notifyUrl');
        $this->shorturl = config('wechat.shorturl');

        $this->mchID = config('wechat.mchID');
        $this->apiKey = config('wechat.apiKey');
    }


    public function index()
    {
        //模式一
        $pkg1 = [
            'appid'     => $this->appId,
            'mch_id'    => $this->mchID,
            'nonce_str' => random(32),
            'product_id'=> $this->appId.'001',//
            'time_stamp'=> time()
        ];
        $qs1 = createUrlStr($pkg1); // 'appid='.$this->appId.'&mch_id='.$this->mchID.'&nonce_str='.random(32).'&product_id='.$this->appId.time().'&time_stamp='.time();
        $sign1 = Pay::sign($pkg1); //$url1 = 'weixin://wxpay/bizpayurl?'.$qs1.'&sign='.strtoupper(md5($qs1.'&key='.$this->apiKey));
        $url1 = 'weixin://wxpay/bizpayurl?'.$qs1.'sign='.$sign1;
        Log::info('模式一 长URL:'.PHP_EOL.$url1.PHP_EOL);

        $short = [
            'appid'     => $this->appId,
            'mch_id'    => $this->mchID,
            'nonce_str' => random(32),
            'long_url'  => $url1
        ];
        $short['sign']  = Pay::sign($short);
        $res = HttpRequest::xmlToArray($this->shorturl, array2xml($short));
        Log::info('模式一 短URL:'.PHP_EOL.$res['short_url'].PHP_EOL);

        QrCode::format('png')->size(120)->merge('/public/imgs/headimg.jpg', .15)->margin(0.5)->generate($res['short_url'], public_path('imgs/wx_pay_qrcode1.png'));
        //dd($url, $qs1, $qs1.'key='.$this->apiKey);



        //模式二
        $package = [
            'appid'         => $this->appId, // test
            'mch_id'        => $this->mchID, // test
            'nonce_str'     => random(32),
            'body'          => '打赏店主',
            'out_trade_no'  => 'oid'.time(),
            'total_fee'     => 0.01 * 100,
            'spbill_create_ip'  => getip(),
            'notify_url'    => $this->notifyUrl,
            'trade_type'    => 'NATIVE',//'JSAPI',

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];
        $package['sign']    = Pay::sign($package); //dd($package);
        $unifiedorderRes    = Pay::unifiedOrder(array2xml($package)); //dd($unifiedorderRes);
        $url2 = $unifiedorderRes['code_url'];

        QrCode::format('png')->size(120)->merge('/public/imgs/headimg.jpg', .15)->margin(0.5)->generate($url2, public_path('imgs/wx_pay_qrcode2.png'));


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
