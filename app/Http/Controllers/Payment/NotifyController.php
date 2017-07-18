<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
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
            echo $xml;
            exit;

        } else {
            file_put_contents($file, '验签失败'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            exit('fail');
        }

    }


    /**
     * 扫码支付 模式一 的回调通知
     */
    public function qrcode()
    {
        $file = 'js/notify_qrcode.json';
        //$raw_post_data = file_get_contents('php://input', 'r');
        //file_put_contents($file, $raw_post_data, LOCK_EX);
        //exit;

        $get = $this->check($file);
        if ($get['sign'] == $get['sign1']) {
            file_put_contents($file, date('Y-m-d H:i:s', time()).' | notify_qrcode.php'.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);
            // 响应请求，生成商户订单()
            // 统一下单
            $package = [
                'appid'         => $this->appId, // test
                'mch_id'        => $this->mchID, // test
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
