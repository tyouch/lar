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
    private $appIDS;
    private $appSecretS;
    private $redirectUri;
    private $mchID;
    private $apiKey;
    private $unifiedorderUrl;
    private $notifyUrl;
    private $weid;

    public function __construct(Request $request)
    {
        //$this->middleware('wechatAuth:pay/jsapi');
        $this->weid = $request->input('weid');

        $this->appIDS = config('wechat.appIDS');
        $this->appSecretS = config('wechat.appSecretS');
        // wx3aed9fe20f883ac8 / d2faf1607c212876a1f123af200d501b
        $this->mchID = config('wechat.mchID');
        $this->apiKey = config('wechat.apiKey');

        $this->redirectUri = config('wechat.redirectUri');//
        $this->unifiedorderUrl = config('wechat.unifiedorderUrl');
        $this->notifyUrl = config('wechat.notifyUrl');

    }


    /**
     * 小程序 支付
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxsPay(Request $request)
    {
        // 验证并组织数据
        $this->validate($request, [
            'total_fee' => 'required|numeric|between:0.01,100000',
        ]);

        $package    = [
            'appid'             => $this->appIDS,
            'mch_id'            => $this->mchID,
            'nonce_str'         => random(32),
            'body'              => $request->input('body'),
            'out_trade_no'      => $request->input('out_trade_no'),
            'total_fee'         => $request->input('total_fee') * 100,
            'spbill_create_ip'  => getip(),
            'notify_url'        => $this->notifyUrl,
            'trade_type'        => 'JSAPI',
            'openid'            => $request->input('openid')
        ];
        $package['sign'] = Pay::sign($package); //dd($package);

        // 统一下单
        $unifiedorderRes = Pay::unifiedOrder(arrayToXml($package)); //$this->unifiedOrder($package);
        $unifiedorderRes['result_code'] == 'FAIL' && die($unifiedorderRes['err_code_des']);
        //return response()->json($unifiedorderRes);


        // 组织支付临时展示数据
        $wOpt = [
            'appId'         => $unifiedorderRes['appid'],
            'timeStamp'     => strval(time()),
            'nonceStr'      => random(32),
            'package'       => 'prepay_id='.$unifiedorderRes['prepay_id'],
            'signType'      => 'MD5',
        ];
        $wOpt['paySign']    = Pay::sign($wOpt);
        return response()->json($wOpt);
    }


    /**
     * 公众号 JSSAPI 支付
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jsApi(Request $request)
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

        // 统一下单
        $unifiedorderRes = Pay::unifiedOrder(arrayToXml($package)); //$this->unifiedOrder($package);
        $unifiedorderRes['result_code'] == 'FAIL' && die($unifiedorderRes['err_code_des']);
        //dd($unifiedorderRes);

        // 组织支付临时展示数据
        $wOpt = [
            'appId'         => $payment['wechat']['appid'],
            'timeStamp'     => time(),
            'nonceStr'      => random(32),
            'package'       => 'prepay_id='.$unifiedorderRes['prepay_id'],
            'signType'      => 'MD5',
        ];
        $wOpt['paySign']    = Pay::sign($wOpt); //$this->sign($wOpt);
        //dump($wOpt);


        //获取签名包
        $signPackage = $this->getSignPackage(
            $this->redirectUri.'pay/jsapi',
            $payment['wechat']['appid'],
            $payment['wechat']['secret']
            );
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
    public function getSignPackage($url, $appId, $appSecret)
    {
        return (new Jssdk([
            'appid'     => $appId,
            'appsecret' => $appSecret,
        ]))->getSignPackage($url);
    }

}
