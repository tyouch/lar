<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\HttpRequest;
use App\Lib\Wechat\Pay;
use App\Models\Paylog;

class NotifyController extends Controller
{
    private $appId;
    private $mchID;
    private $apiKey;

    private $unifiedorderUrl;
    private $notifyUrl;
    private $notifyUrlQrcode;


    public function __construct()
    {
        $this->appId = config('wechat.appID');
        $this->mchID = config('wechat.mchID');

        $this->apiKey           = config('wechat.apiKey');
        $this->unifiedorderUrl  = config('wechat.unifiedorderUrl');
        $this->notifyUrl        = config('wechat.notifyUrl');
        $this->notifyUrlQrcode  = config('wechat.notifyUrlQrcode');
    }


    /**
     * jssapi 支付的回调通知
     */
    public function index()
    {
        //echo htmlspecialchars('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
        //exit;
        $xml = file_get_contents('php://input', 'r');
        empty($xml) && die('无回调数据！');
        Log::info('接收回调原始数据 [普通]：'.PHP_EOL.$xml.PHP_EOL);

        if(Pay::check($xml)) {
            $get = xmlToArray($xml);

            $paylog = Paylog::where(['ordersn'=>$get['out_trade_no']])->first();
            if(empty($paylog)){
                $paylog = new Paylog();
                $paylog->type       = 'wechat';
                $paylog->weid       = 11;
                $paylog->openid     = $get['openid'];
                $paylog->tid        = 0;
                $paylog->ordersn    = $get['out_trade_no'];
                $paylog->fee        = $get['total_fee'] * 0.01;
                $paylog->status     = 1;
                $paylog->module     = 'shop';
                $paylog->tag        = serialize(['transaction_id'=>$get['transaction_id']]);
                $paylog->save();
                Log::info('paylog 表插入成功：plid='.$paylog->plid.PHP_EOL);
            }
            $ret = [
                'return_code'   => 'SUCCESS',
                'return_msg'    => 'OK'
            ];

        } else {
            $ret = [
                'return_code'   => 'FAIL',
                'return_msg'    => '签名错误'
            ];
        }

        $xml = array2xml($ret);
        Log::info('告知微信通知处理结果：'.PHP_EOL.xmlFormatting($xml).PHP_EOL);
        echo $xml;
    }


    /**
     * 扫码支付 模式一 的回调通知
     */
    public function native()
    {
        $xml = file_get_contents('php://input', 'r');

        if(empty($xml)) {
            Log::info('没有接到回调数据'.PHP_EOL.$xml.PHP_EOL); die('无回调数据！');
        } else {
            Log::info('接收回调原始数据 [扫码支付模式一]：'.PHP_EOL.$xml.PHP_EOL);
        }

        if(Pay::check($xml)) {
            $get = xmlToArray($xml);
            // 响应请求，生成商户订单()... ...
            // 统一下单
            $get['body']                = '打赏店主';
            $get['out_trade_no']        = 'oid'.time();
            $get['total_fee']           = 0.01 * 100;
            $get['spbill_create_ip']    = getip();
            $get['notify_url']          = $this->notifyUrl;
            $get['trade_type']          = 'NATIVE';
            unset($get['is_subscribe']);
            unset($get['sign']);
            $get['sign']                = Pay::sign($get);


            $xml = array2xml($get);//
            Log::info('组织统一下单的数据：'.PHP_EOL.xmlFormatting($xml).PHP_EOL);
            $unifiedorderRes = Pay::unifiedOrder($xml);
            Log::info('接收统一下单结果：'.PHP_EOL.xmlFormatting(array2xml($unifiedorderRes)).PHP_EOL);

            /*$unifiedorderRes['err_code_des'] = 'OK';
            unset($unifiedorderRes['code_url']);
            unset($unifiedorderRes['trade_type']);
            unset($unifiedorderRes['sign']);
            $unifiedorderRes['sign'] = Pay::sign($unifiedorderRes);*/

            // 组织响应数据
            $data = [
                'appid'         => $unifiedorderRes['appid'],
                'err_code_des'  => 'OK',
                'mch_id'        => $unifiedorderRes['mch_id'],
                'nonce_str'     => $unifiedorderRes['nonce_str'],
                'prepay_id'     => $unifiedorderRes['prepay_id'],
                'result_code'   => 'SUCCESS',
                'return_code'   => 'SUCCESS',
                'return_msg'    => 'OK',
            ];
            $data['sign']       = Pay::sign($data);

        } else {
            $data = [
                'return_code'   => 'FAIL',
                'return_msg'    => '签名错误'
            ];
            Log::info('验签失败');
        }

        $xml = array2xml($data);
        Log::info('结果输出：'.PHP_EOL.xmlFormatting($xml).PHP_EOL);
        echo $xml;

    }


    public function qrcode()
    {
        /*$file = 'js/notify_native.json';
        //$raw_post_data = file_get_contents('php://input', 'r');
        //file_put_contents($file, $raw_post_data, LOCK_EX);
        //exit;

        $get = $this->check($file);
        if ($get['sign'] == $get['sign1']) {
            file_put_contents($file, date('Y-m-d H:i:s', time()).' | '.$file.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            // 响应请求，生成商户订单()
            // 统一下单
            $package = [
                'appid'         => $get['appid'],
                'mch_id'        => $get['mch_id'],
                'nonce_str'     => $get['nonce_str'],//random(32),
                'body'          => '打赏店主',
                'out_trade_no'  => $get['product_id'],
                'total_fee'     => 0.01 * 100,
                'spbill_create_ip'  => getip(),
                'notify_url'    => $this->notifyUrl,
                'trade_type'    => 'NATIVE',//'JSAPI',
                'openid'        => $get['openid'],
                'product_id'    => $get['product_id']

                //'time_start'    => date('YmdHis', time()+0),
                //'time_expire'   => date('YmdHis', time() + 600),
            ];
            file_put_contents($file, Pay::string1($package).PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            $package['sign']    = Pay::sign($package, null); //dd($package);
            file_put_contents($file, array2xml($package).PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

            //file_put_contents($file, json_encode($get, JSON_UNESCAPED_UNICODE).PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            //file_put_contents($file, json_encode($package, JSON_UNESCAPED_UNICODE).PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

            file_put_contents($file, 'unifiedOrder begin'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            $unifiedorderRes = Pay::unifiedOrder($package);
            file_put_contents($file, json_encode($unifiedorderRes, JSON_UNESCAPED_UNICODE).PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

            unset($unifiedorderRes['code_url']);
            $unifiedorderRes['sign']       = Pay::sign($unifiedorderRes);

            $xml = array2xml($unifiedorderRes);
            file_put_contents($file, $xml.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

            echo $xml;
        }*/

        /*$file = 'js/notify.json';
        $get = $this->check($file);
        if ($get['sign'] == $get['sign1']) {

            // write log
            file_put_contents($file, 'Write log begin'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            $paylog = Paylog::where(['ordersn'=>$get['out_trade_no']])->first();
            if(empty($paylog)){
                $paylog = new Paylog();
                $paylog->type       = 'wechat';
                $paylog->weid       = 11;
                $paylog->openid     = $get['openid'];
                $paylog->tid        = 0;
                $paylog->ordersn    = $get['out_trade_no'];
                $paylog->fee        = $get['total_fee'] * 0.01;
                $paylog->status     = 1;
                $paylog->module     = 'shop';
                $paylog->tag        = serialize(['transaction_id'=>$get['transaction_id']]);
                $paylog->save();
            }
            file_put_contents($file, 'Write log end - plid:'.$paylog->plid.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

            $ret = [
                'return_code'   => 'SUCCESS',
                'return_msg'    => 'OK'
            ];
            file_put_contents($file, json_encode($ret, JSON_UNESCAPED_UNICODE).PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            $xml = array2xml($ret);
            file_put_contents($file, $xml.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            echo $xml;
            exit;

        } else {
            file_put_contents($file, '验签失败'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            exit('fail');
        }*/
    }


    /**
     * 验签
     * @param $file
     * @return mixed
     */
    public function check($file)
    {
        $raw_post_data = file_get_contents('php://input', 'r');
        //dump($raw_post_data, empty($raw_post_data));

        if(file_exists($file) && empty($raw_post_data)) {
            $msg = file_get_contents($file);
            //unlink($file);//file_put_contents($file, null, LOCK_EX);
            var_dump($msg);
        } else {
            $obj = simplexml_load_string($raw_post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
            $json = json_encode($obj, JSON_UNESCAPED_UNICODE);
            $get = json_decode($json, true);
            file_put_contents($file, $raw_post_data . PHP_EOL . PHP_EOL, LOCK_EX);
            file_put_contents($file, $json . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents($file, $this->apiKey . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
            //file_put_contents($file, $get.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);


            $string1 = '';
            ksort($get, SORT_STRING);
            foreach ($get as $k => $v) {
                if ($v != '' && $k != 'sign') {
                    $string1 .= "{$k}={$v}&";
                }
            }
            $sign = strtoupper(md5($string1 . 'key=' . $this->apiKey));
            file_put_contents($file, $string1 . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents($file, $sign.' <=> '.$get['sign'] . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        $get['sign1'] = $sign;
        return $get;
    }


    /**
     * test
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function test($ex)
    {
        if($ex == '.html'){
            $str = htmlspecialchars('<xml><return_code><![CDATA[SUCCESS]]></return_code>
<return_msg><![CDATA[OK]]></return_msg>
</xml>');
            echo $str;
        }

        //return response($str);//, 200->header('Content-Type', 'text/plain');
        exit;
        /*$paylog = new Paylog();
        $paylog->type       = 'wechat';
        $paylog->weid       = 11;
        $paylog->openid     = 'xoxoxo';
        $paylog->tid        = 0;
        $paylog->ordersn    = 'oid'.time();
        $paylog->fee        = 0;
        $paylog->status     = 1;
        $paylog->module     = 'shop';
        $paylog->tag        = serialize('oid'.time());
        $paylog->save();
        dd($paylog->plid);*/
        //QrCode::format('png')->generate('http://www.tyoupub.com/', public_path('imgs/wx_pay_qrcode.png'));
        QrCode::format('png')->size(200)->merge('/public/imgs/headimg.jpg',.2)->margin(1)->generate('http://www.tyoupub.com/',public_path('imgs/wx_pay_qrcode.png'));

        return view('mobile.test', [
            'signPackage'   => [
                'appId'     => '',
                'timestamp' => '',
                'nonceStr'  => '',
                'signature' => '',
            ],
            'navActive'    => 'index'
        ]);
    }
}
