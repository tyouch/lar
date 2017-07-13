<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
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

    public function index()
    {
        $file = 'js/notify.json';
        $raw_post_data = file_get_contents('php://input', 'r');
        //dump($raw_post_data, empty($raw_post_data));

        if(file_exists($file) && empty($raw_post_data)) {
            $msg = file_get_contents($file);
            //unlink($file);//file_put_contents($file, null, LOCK_EX);
            var_dump($msg);
        } else {
            $obj    = simplexml_load_string($raw_post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
            $json   = json_encode($obj, JSON_UNESCAPED_UNICODE);
            $get    = json_decode($json, true);
            file_put_contents($file, $raw_post_data.PHP_EOL.PHP_EOL, LOCK_EX);
            file_put_contents($file, $json.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents($file, $this->apiKey.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            //file_put_contents($file, $get.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);


            ksort($get, SORT_STRING);
            $string1 = '';
            foreach($get as $k => $v) {
                if($v != '' && $k != 'sign') {
                    $string1 .= "{$k}={$v}&";
                }
            }
            $sign = strtoupper(md5($string1 . 'key='.$this->apiKey));
            file_put_contents($file, $string1.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents($file, $sign.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            if ($sign == $get['sign']) {

                // write log
                $paylog = Paylog::where(['ordersn'=>$get['out_trade_no']])->first();
                if(empty($paylog)){
                    file_put_contents($file, 'Write log begin'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
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
                    file_put_contents($file, 'Write log end - plid:'.$paylog->plid.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
                }

                $ret = [
                    'return_code'   => 'SUCCESS',
                    'return_msg'    => 'OK'
                ];
                file_put_contents($file, json_encode($ret, JSON_UNESCAPED_UNICODE).PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
                $xml = array2xml($ret);
                file_put_contents($file, $xml.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

                $res = HttpRequest::to($this->unifiedorderUrl, $xml);
                file_put_contents($file, $res['content'], FILE_APPEND | LOCK_EX);
            }
        }
    }

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
