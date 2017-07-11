<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Wechat\HttpRequest;

class PayController extends Controller
{
    private $payUrl;
    public function __construct()
    {
        $this->payUrl = config('wechat.wPayUrl');
    }

    public function index()
    {
        dd(random(32));
        dd('pay');
        $package = [
            'appid'         => config('wechat.AppID'),
            'mch_id'        => config('wechat.mchID'),
            'device_info'   => 'WEB',
            'nonce_str'     => random(32),
            'sign'          => '',
            'attach'        => '支付测试',
            'body'          => 'JSAPI支付测试',
            'time_start'    => date('YmdHis', time()+0),
            'time_expire'   => date('YmdHis', time() + 600),
        ];


        include $this->template('pay');
    }

    public function notify()
    {
        $file = '../cb.txt';
        $raw_post_data = file_get_contents('php://input', 'r');

        if(empty($raw_post_data)) {
            $msg = file_get_contents($file);
            unlink($file);//file_put_contents($file, null, LOCK_EX);

            dd($msg);
        } else {
            file_put_contents($file, $raw_post_data, LOCK_EX);
        }
    }
}
