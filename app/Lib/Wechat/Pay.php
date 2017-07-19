<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/14
 * Time: 21:25
 */
namespace App\Lib\Wechat;

use Illuminate\Support\Facades\Log;

class pay {

    private $appId;
    private $appSecret;
    private $redirectUri;
    private $mchID;
    private $apiKey;
    private $unifiedorderUrl;
    private $notifyUrl;

    public function __construct() {
        $this->appId = config('wechat.appID');
        $this->appSecret = config('wechat.appSecret');

        $this->redirectUri = config('wechat.redirectUri');//
        $this->unifiedorderUrl = config('wechat.unifiedorderUrl');
        $this->notifyUrl = config('wechat.notifyUrl');

        $this->mchID = config('wechat.mchID');
        $this->apiKey = config('wechat.apiKey');
    }


    /**
     * 统一下单
     * @param $package
     * @return mixed
     */
    public function doUnifiedOrder($xml)
    {

        //$unifiedorderRes = HttpRequest::content($this->unifiedorderUrl, $xml);
        $unifiedorderRes = HttpRequest::xmlToArray($this->unifiedorderUrl, $xml);
        $unifiedorderRes['return_code'] == 'FAIL' && exit($unifiedorderRes['return_msg']);

        return $unifiedorderRes;
    }


    /**
     * 签名算法
     * @param $wOpt
     * @return string
     */
    public function doSign($wOpt ,$check = null)
    {
        $string = createUrlStr($wOpt ,$check);
        $string .= 'key='.$this->apiKey;
        //Log::info($string);
        return strtoupper(md5($string));
    }


    /**
     * 验签
     * @param $file
     * @return mixed
     */
    public function doCheck($xml)
    {
        $get = xmlToArray($xml);
        $sign = $this->doSign($get, true);
        return $sign == $get['sign'];
    }


    /**
     * @param $name
     * @param $arguments
     * @return array|void
     */
    public static function __callStatic($name, $arguments) {

        //var_dump($name, $arguments, get_called_class());exit;
        $data = !empty($arguments[0]) ? $arguments[0] : '';
        //dd($name, $arguments);

        switch ($name) {
            case 'unifiedOrder':
                return (new Pay())->doUnifiedOrder($data);

            case 'sign':
                return (new Pay())->doSign($data);

            case 'check':
                return (new Pay())->doCheck($data);

            default:
                die('error');
        }
    }

}

