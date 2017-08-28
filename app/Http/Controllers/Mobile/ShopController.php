<?php

namespace App\Http\Controllers\Mobile;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\Jssdk;
use App\Lib\Wechat\Pay;
use App\Lib\Wechat\HttpRequest;

use App\Models\Wechats;
use App\Models\Fans;
use App\Models\ShoppingAdv;
use App\Models\ShoppingGoods;
use App\Models\ShoppingAddress;


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

    private $weid;


    public function __construct(Request $request)
    {
        $this->weid = $request->input('weid');
        $this->middleware('wechatAuth:mobile/shop/index?weid='.$this->weid);

        $this->appId = config('wechat.appID');
        $this->appSecret = config('wechat.appSecret');

        $this->redirectUri = config('wechat.redirectUri');//
        $this->unifiedorderUrl = config('wechat.unifiedorderUrl');
        $this->notifyUrl = config('wechat.notifyUrl');
        $this->shorturl = config('wechat.shorturl');

        $this->mchID = config('wechat.mchID');
        $this->apiKey = config('wechat.apiKey');
    }


    /**
     * 主页备份
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index2()
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

        QrCode::format('png')->size(120)->merge('/public/imgs/headimg.jpg', .15)->margin(0)->generate($res['short_url'], public_path('imgs/wx_pay_qrcode1.png'));
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

        QrCode::format('png')->size(120)->merge('/public/imgs/headimg.jpg', .15)->margin(0)->generate($url2, public_path('imgs/wx_pay_qrcode2.png'));


        $signPackage = $this->getSignPackage($this->redirectUri.'mobile/shop/index?weid='.$this->weid);
        return view('mobile.shop.index2', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'package'           => $package,
            'navActive'         => 'index'
        ]);
    }


    /**
     * 分类
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category()
    {

        $url = $this->buildUrl('category', ['weid'=>$this->weid]);
        $signPackage = $this->getSignPackage($url['link']);
        return view('mobile.shop.category', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'navActive'         => 'category',
            'url'               => $url
        ]);
    }


    /**
     * 购物车
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cart()
    {

        $url = $this->buildUrl('cart', ['weid'=>$this->weid]);
        $signPackage = $this->getSignPackage($url['link']);
        return view('mobile.shop.cart', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'navActive'         => 'cart',
            'url'               => $url
        ]);
    }


    /**
     * 我的
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(Request $request)
    {
        $this->openid = session('openid'); //dd($this->openid);
        $fans = Fans::where(['from_user'=>$this->openid])->first(); //dd($fans);

        $status = $request->input('status');


        $url = $this->buildUrl('home', ['weid'=>$this->weid, 'status'=>$status]);
        $signPackage = $this->getSignPackage($url['link']);
        return view('mobile.shop.home', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'fans'              => $fans,
            'navActive'         => 'home',
            'url'               => $url,
            'status'            => $status
        ]);
    }


    /**
     * 主页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $advs = ShoppingAdv::where(['weid'=>$this->weid])->get(); //dd($advs);
        $goods = ShoppingGoods::where(['weid'=>$this->weid, 'isrecommand'=>1])->get(); //dd($goods);


        $url = $this->buildUrl('index', ['weid'=>$this->weid]);
        $signPackage = $this->getSignPackage($url['link']);
        return view('mobile.shop.index', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'advs'              => $advs,
            'goods'             => $goods,
            'navActive'         => 'index',
            'url'               => $url
        ]);
    }


    /**
     * 详情页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $good = ShoppingGoods::where(['weid'=>$this->weid, 'id'=>$id])->first();
        $good['advs'] = iunserializer($good['thumb_url']);


        //$advs = ShoppingAdv::where(['weid'=>$this->weid])->get();
        //dd($good, $advs);

        $url = $this->buildUrl('detail', ['weid'=>$this->weid, 'id'=>$id]);
        $signPackage = $this->getSignPackage($url['link']);
        return view('mobile.shop.detail', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'navActive'         => 'home',
            'url'               => $url,
            'good'              => $good,
            //'advs'              => $advs,
        ]);
    }


    /**
     * 填写订单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirm(Request $request)
    {
        $id = $request->input('id');
        $good = ShoppingGoods::where(['weid'=>$this->weid, 'id'=>$id])->first();
        $wechat = Wechats::where(['weid'=>$this->weid])->first();
        $payment = iunserializer($wechat['payment']);

        //dd($payment, $good, $wechat);

        $package = [
            'appid'         => $payment['wechat']['appid'], // $this->appId, // test
            'mch_id'        => $payment['wechat']['mchid'], //$this->mchID, // test
            'nonce_str'     => random(32),
            'body'          => $good['title'],
            'out_trade_no'  => 'oid'.time(),
            'total_fee'     => floatval($good['productprice']),
            'spbill_create_ip'  => getip(),
            'notify_url'    => $this->notifyUrl,
            'trade_type'    => 'JSAPI',

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];
        $package['sign']    = Pay::sign($package); //dd($package);



        $url = $this->buildUrl('confirm', ['weid'=>$this->weid, 'id'=>$id]); //dd($url);
        $signPackage = $this->getSignPackage($url['link']);
        return view('mobile.shop.confirm', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'navActive'         => 'home',
            'url'               => $url,
            'good'              => $good,
            'package'           => $package,
        ]);
    }


    /**
     * 处理订单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orders(Request $request)
    {
        $id = $request->input('id');
        $good = ShoppingGoods::where(['weid'=>$this->weid, 'id'=>$id])->first();
        $address = ShoppingAddress::where(['weid'=>$this->weid, 'openid'=>session('openid')])->first();
        $wechat = Wechats::where(['weid'=>$this->weid])->first();
        $payment = iunserializer($wechat['payment']);

        //dd($payment, $good, $wechat);

        $package = [
            'appid'         => $payment['wechat']['appid'], // $this->appId, // test
            'mch_id'        => $payment['wechat']['mchid'], //$this->mchID, // test
            'nonce_str'     => random(32),
            'body'          => $good['title'],
            'out_trade_no'  => 'oid'.time(),
            'total_fee'     => floatval($good['productprice']),
            'spbill_create_ip'  => getip(),
            'notify_url'    => $this->notifyUrl,
            'trade_type'    => 'JSAPI',

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];
        $package['sign']    = Pay::sign($package); //dd($package);



        $url = $this->buildUrl('orders', ['weid'=>$this->weid, 'id'=>$id]); //dd($url);
        $signPackage = $this->getSignPackage($url['link']);
        return view('mobile.shop.confirm', [
            'weid'              => $this->weid,
            'signPackage'       => $signPackage,
            'navActive'         => 'home',
            'url'               => $url,
            'good'              => $good,
            'package'           => $package,
        ]);
    }


    /**
     * 构建 link
     * @param $path
     * @param null $qs
     * @param string $img
     * @return array
     */
    public function buildUrl($path, $qs = null, $img = 'imgs/headimg.jpg')
    {

        $path = 'mobile/shop/'.$path;

        if(!empty($qs) && is_array($qs)) {
            $string = '?';
            foreach ($qs as $k=>$v) {
                !empty($v) && $string .= $k.'='.$v.'&';
            }
            $string = rtrim($string, '&');
        }

        $url = [
            'host'  => $this->redirectUri,
            'link'  => $this->redirectUri.$path.$string,
            'img'   => $this->redirectUri.$img
        ];

        return $url;
    }


    /**
     * 获取签名包
     * @param $url
     * @return array
     */
    public function getSignPackage($url, $qs = null)
    {
        $params = [
            'appid'     => $this->appId,
            'appsecret' => $this->appSecret,
        ];

        if(!empty($qs) && is_array($qs)) {
            $string = '?';
            foreach ($qs as $k=>$v) {
                $string .= $k.'='.$v.'&';
            }
            $string = rtrim($string, '&');
            $url .= $string;
        }

        $jssdk = new Jssdk($params);
        return $jssdk->getSignPackage($url);
    }
}
