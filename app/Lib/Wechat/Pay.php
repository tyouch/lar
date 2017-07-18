<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/14
 * Time: 21:25
 */
namespace App\Lib\Wechat;

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
    public function doUnifiedOrder($package)
    {
        $xml = array2xml($package);

        //$file = 'js/notify_qrcode.json';
        //file_put_contents($file, $xml.PHP_EOL.PHP_EOL, FILE_APPEND | LOCK_EX);

        //$unifiedorderRes = HttpRequest::to($this->unifiedorderUrl, $xml);
        $unifiedorderRes = HttpRequest::xmlToArray($this->unifiedorderUrl, $xml);

        $unifiedorderRes['return_code'] == 'FAIL' && exit($unifiedorderRes['return_msg']);

        return $unifiedorderRes;
    }


    /**
     * 构建升序查询串
     * @param $wOpt
     * @param null $check
     * @return string
     */
    public function doQueryString($wOpt ,$check = null)
    {
        $string = '';
        ksort($wOpt, SORT_STRING);
        foreach($wOpt as $k => $v) {
            if(empty($check)){
                $string .= "{$k}={$v}&";
            } else {
                if ($v != '' && $k != 'sign') {
                    $string .= "{$k}={$v}&";
                }
            }
        }
        return $string;
    }


    /**
     * 签名算法
     * @param $wOpt
     * @return string
     */
    public function doSign($wOpt ,$check = null)
    {
        $string = $this->doQueryString($wOpt ,$check);
        $string .= 'key='.$this->apiKey;

        return strtoupper(md5($string));
    }


    /**
     * 验签
     * @param $file
     * @return mixed
     */
    public function doCheck($xml)
    {
        $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($obj, JSON_UNESCAPED_UNICODE);
        $get = json_decode($json, true);

        $get['sign1'] = $this->doSign($get, true);
        return $get;
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

            case 'string1':
                return (new Pay())->doQueryString($data);

            case 'sign':
                return (new Pay())->doSign($data);

            case 'check':
                return (new Pay())->doCheck($data);

            default:
                die('error');
        }
    }

}

