<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\Wechat\Jssdk;
use App\Lib\Wechat\HttpRequest;

class WetestController extends Controller
{
    private $appId;
    private $appSecret;
    private $redirectUri;

    public function __construct()
    {
        //$this->middleware('wechatAuth');
        $this->appId = config('wechat.AppID');
        $this->appSecret = config('wechat.AppSecret');
        $this->redirectUri = config('wechat.redirectUri');
        $this->apiKey = config('wechat.apiKey');
    }

    public function index()
    {
        //var_dump(session());
        dd(public_path());
        $package = [
            'appid'         => config('wechat.AppID'), // test
            'mch_id'        => config('wechat.mchID'), // test
            'nonce_str'     => random(32),
            'body'          => 'JSAPI支付测试',
            'out_trade_no'  => 'oid'.time(),
            'total_fee'     => 0.01 * 100,
            'spbill_create_ip'  => getip(),
            'notify_url'    => 'https://yuan.tyoupub.com/pay/notify',
            'trade_type'    => 'JSAPI',
            'openid'        => 'odk8d0vQ3Oqr7UAOOPFaGxCuOG0E'

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
        dump($package);
        $data = array2xml($package);
        $res = HttpRequest::xmlToArray(config('wechat.wPayUrl'), $data);
        dump($res);

        $wOpt = [
            'appId'         => $this->appId,
            'timeStamp'     => time(),
            'nonceStr'      => random(32),
            'package'       => 'prepay_id='.$res['prepay_id'],
            'signType'      => 'MD5',
        ];
        ksort($wOpt, SORT_STRING);
        foreach($wOpt as $key => $v) {
            $string .= "{$key}={$v}&";
        }
        $string .= 'key='.$this->apiKey;
        $wOpt['paySign'] = strtoupper(md5($string));

        $signPackage = $this->getSignPackage();
        dump($signPackage);

        return view('web.wetest', [
            'wOpt'          => $wOpt,
            'signPackage'   => $signPackage,
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
}
