<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\Jssdk;
use App\Lib\Wechat\HttpRequest;

class PayController extends Controller
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
        //$this->openid = session('openid');

        $this->appId = config('wechat.appID');
        $this->appSecret = config('wechat.appSecret');

        $this->redirectUri = config('wechat.redirectUri');
        $this->unifiedorderUrl = config('wechat.unifiedorderUrl');
        $this->notifyUrl = config('wechat.notifyUrl');

        $this->mchID = config('wechat.mchID');
        $this->apiKey = config('wechat.apiKey');
    }

    public function index(Request $request)
    {
        $this->openid = session('openid');
        //dd($this->openid, session('openid'));
        $package = [
            'appid'         => $this->appId, // test
            'mch_id'        => $this->mchID, // test
            'nonce_str'     => random(32),
            'body'          => 'JSAPI支付测试',
            'out_trade_no'  => 'oid'.time(),
            'total_fee'     => 0.01 * 100,
            'spbill_create_ip'  => getip(),
            'notify_url'    => $this->notifyUrl,
            'trade_type'    => 'JSAPI',
            'openid'        => $this->openid//'odk8d0vQ3Oqr7UAOOPFaGxCuOG0E'

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];

        ksort($package, SORT_STRING);
        $string = $string1 = '';
        foreach($package as $key => $v) {
            $string1 .= "{$key}={$v}&";
        }
        $string1 .= 'key='.config('wechat.apiKey');
        $package['sign'] = strtoupper(md5($string1));
        //dump($package);
        $data = array2xml($package);
        $unifiedorderRes = HttpRequest::xmlToArray($this->unifiedorderUrl, $data);
        //dump($unifiedorderRes);

        $wOpt = [
            'appId'         => $this->appId,
            'timeStamp'     => time(),
            'nonceStr'      => random(32),
            'package'       => 'prepay_id='.$unifiedorderRes['prepay_id'],
            'signType'      => 'MD5',
        ];
        ksort($wOpt, SORT_STRING);
        foreach($wOpt as $key => $v) {
            $string .= "{$key}={$v}&";
        }
        $string .= 'key='.$this->apiKey;
        $wOpt['paySign'] = strtoupper(md5($string));

        $signPackage = $this->getSignPackage();
        //dump($signPackage);

        return view('wechat.wetest', [
            'wOpt'              => $wOpt,
            'signPackage'       => $signPackage,
            'package'           => $package,
            'unifiedorderRes'   => $unifiedorderRes
        ]);
    }

    public function getSignPackage()
    {
        $params = [
            'appid'     => $this->appId,
            'appsecret' => $this->appSecret,
        ];
        $jsskd = new Jssdk($params);
        return $jsskd->getSignPackage($this->redirectUri);
    }

    public function notify()
    {
        $file = public_path('js/cb.json');
        $raw_post_data = file_get_contents('php://input', 'r');

        if(empty($raw_post_data)) {
            $msg = file_get_contents($file);
            //unlink($file);//file_put_contents($file, null, LOCK_EX);
            dd($msg);
        } else {
            $obj = simplexml_load_string($raw_post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
            file_put_contents($file, json_encode($obj, JSON_UNESCAPED_UNICODE), LOCK_EX);
        }
    }
}
