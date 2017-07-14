<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\HttpRequest;
use App\Models\Paylog;

class NotifyController extends Controller
{
    private $apiKey;
    private $unifiedorderUrl;

    public function __construct()
    {
        $this->apiKey           = config('wechat.apiKey');
        $this->unifiedorderUrl  = config('wechat.unifiedorderUrl');
    }


    /**
     * jssapi 支付的回调通知
     */
    public function jsapi()
    {
        $file = 'js/notify.json';
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

            $res = HttpRequest::to($this->unifiedorderUrl, $xml);
            file_put_contents($file, $res['content'], FILE_APPEND | LOCK_EX);
        } else {
            file_put_contents($file, '验签失败'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
        }

    }


    /**
     * 扫码支付 模式一 的回调通知
     */
    public function qrcode()
    {
        $file = 'js/notify_qrcode.json';
        $get = $this->check($file);
        if ($get['sign'] == $get['sign1']) {
            file_put_contents($file, 'notify_qrcode.php'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
        }
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


            ksort($get, SORT_STRING);
            $string1 = '';
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
    public function test()
    {

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
