<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/6/1
 * Time: 16:52
 */
namespace App\Lib\Wechat;

class Jssdk {
    private $appId;
    private $appSecret;

    public function __construct($params) {
        $this->appId = $params['appid'];
        $this->appSecret = $params['appsecret'];
    }

    public function getSignPackage($url) {
        $jsapiTicket    = $this->getJsApiTicket();
        $protocol       = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url            = $url ? $url : "{$protocol}{$_SERVER[HTTP_HOST]}{$_SERVER[REQUEST_URI]}";
        $timestamp      = time();
        $nonceStr       = $this->createNonceStr(32);

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $file = '../public/js/jsapi_ticket.json';
        file_exists($file) && $data = json_decode(file_get_contents($file), true); //dd($data);

        if (!file_exists($file) || $data['expires_in'] < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = HttpRequest::toArray($url); //dd($res);
            $ticket = $res['ticket'];
            if (!empty($ticket)) {
                $data['errcode']    = $res['errcode'];
                $data['errmsg']     = $res['errmsg'];
                $data['ticket']     = $ticket;
                $data['expires_in'] = time() + 7000;
                file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));
            }
        } else {
            $ticket = $data['ticket'];
        }

        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $file = '../public/js/access_token.json';
        file_exists($file) &&  $data = json_decode(file_get_contents($file), true);

        if (!file_exists($file) || $data['expires_in'] < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = HttpRequest::toArray($url); //dd($res);
            $access_token = $res['access_token'];
            if (!empty($access_token)) {
                $data['access_token'] = $access_token;
                $data['expires_in'] = time() + 7000;
                file_put_contents($file, json_encode($data));
            }
        } else {
            $access_token = $data['access_token'];
        }

        return $access_token;
    }

}

