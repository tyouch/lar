<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\Jssdk;
use App\Lib\Wechat\Pay;
use App\Lib\Wechat\HttpRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Wechats;

class PayController extends Controller
{
    private $appId;
    private $appSecret;
    private $redirectUri;
    private $mchID;
    private $apiKey;
    private $unifiedorderUrl;
    private $notifyUrl;
    private $weid;

    public function __construct(Request $request)
    {
        $this->middleware('wechatAuth:pay/jsapi');
        //$this->openid = session('openid');
        $this->weid = $request->input('weid');


        $this->appId = config('wechat.appID');
        $this->appSecret = config('wechat.appSecret');

        $this->redirectUri = config('wechat.redirectUri');//
        $this->unifiedorderUrl = config('wechat.unifiedorderUrl');
        $this->notifyUrl = config('wechat.notifyUrl');

        $this->mchID = config('wechat.mchID');
        $this->apiKey = config('wechat.apiKey');
    }


    /**
     * JSSAPI 支付
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jsapi(Request $request)
    {
        // 验证并组织数据
        $this->validate($request, [
            'total_fee' => 'required|numeric|between:0.01,100000',
        ]);

        $wechat     = Wechats::where(['weid'=>$this->weid])->first();
        $payment    = iunserializer($wechat['payment']); //dd($wechat,$payment);
        $package    = [
            'appid'             => $payment['wechat']['appid'], // $this->appId, // test
            'mch_id'            => $payment['wechat']['mchid'], //$this->mchID, // test
            'nonce_str'         => random(32),
            'body'              => $request->input('body'),
            'out_trade_no'      => $request->input('out_trade_no'),
            'total_fee'         => $request->input('total_fee') * 100,
            'spbill_create_ip'  => getip(),
            'notify_url'        => $this->notifyUrl,
            'trade_type'        => 'JSAPI',
            'openid'            => session('openid')//'odk8d0vQ3Oqr7UAOOPFaGxCuOG0E'

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];
        $package['sign']    = Pay::sign($package); //dd($package);

        /*$package = [
            'appid'             => $request->input('appid'), // test
            'mch_id'            => $request->input('mch_id'), // test
            'nonce_str'         => $request->input('nonce_str'),
            'body'              => $request->input('body'),
            'out_trade_no'      => $request->input('out_trade_no'),//'oid'.time(),
            'total_fee'         => $request->input('total_fee') * 100,
            'spbill_create_ip'  => $request->input('spbill_create_ip'),
            'notify_url'        => $request->input('notify_url'),
            'trade_type'        => $request->input('trade_type'),
            'openid'            => session('openid')//'odk8d0vQ3Oqr7UAOOPFaGxCuOG0E'

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];
        $package['sign']    = Pay::sign($package); //dd($package);*/


        // 统一下单
        $unifiedorderRes = Pay::unifiedOrder(array2xml($package)); //$this->unifiedOrder($package);
        $unifiedorderRes['result_code'] == 'FAIL' && die($unifiedorderRes['err_code_des']);
        //dd($unifiedorderRes);

        // 组织支付临时展示数据
        $wOpt = [
            'appId'         => $this->appId,
            'timeStamp'     => time(),
            'nonceStr'      => random(32),
            'package'       => 'prepay_id='.$unifiedorderRes['prepay_id'],
            'signType'      => 'MD5',
        ];
        $wOpt['paySign']    = Pay::sign($wOpt); //$this->sign($wOpt);
        //dump($wOpt);

        //获取签名包
        $signPackage = $this->getSignPackage($this->redirectUri.'pay/jsapi');
        //dump($signPackage);

        return view('mobile.shop.pay', [
            'wOpt'              => $wOpt,
            'signPackage'       => $signPackage,
            'package'           => $package,
            'unifiedorderRes'   => $unifiedorderRes,
            'navActive'         => 'index'
        ]);
    }


    public function native()
    {
        $package = [

            //'time_start'    => date('YmdHis', time()+0),
            //'time_expire'   => date('YmdHis', time() + 600),
        ];
    }


    /**
     * 获取签名包
     * @return array
     */
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
