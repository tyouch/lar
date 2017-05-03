<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 11:10
 */

namespace App\Libraries\Encryption;

use App\Libraries\Encryption\Crypt3Des;
use App\Libraries\Encryption\RSA;
use App\Libraries\Encryption\HttpRequest;

class HXBankConfig
{
    protected $message;
    protected $Des3Key;
    protected $url;
    protected $TRANSCODE;
    protected $MERCHANTID;
    protected $APPID;

    public function __construct()
    {
        $this->Des3Key  = 'A1B2C3D4E5F6G7H8I9J0K1L2';
        $this->url      = 'http://183.63.131.106:40011/extService/ghbExtService.do';
        $this->message  = '';
        $this->TRANSCODE  = 'OGW00042';
        $this->MERCHANTID  = '7654321';
        $this->APPID  = 'PC';
    }


    /**
     * 组织接口数据的 XML
     * @param array $data
     * @return string
     */
    public function bXml($data = [])
    {
        $xml =
            '<TRANSCODE>'.$this->TRANSCODE.'</TRANSCODE>'.
            '<MERCHANTID>'.$this->MERCHANTID.'</MERCHANTID>'.
            '<APPID>'.$this->APPID.'</APPID>';
        /*$xml =
            '<TRANSCODE>'.config('test.TRANSCODE').'</TRANSCODE>'.
            '<MERCHANTID>'.$this->MERCHANTID.'</MERCHANTID>'.
            '<APPID>'.$this->APPID.'</APPID>';*/

        foreach ($data as $k=>$v)
        {
            $xml .= '<'.$k.'>'.$v.'</'.$k.'>';
        }

        return $xml;
    }

    /**
     * 组织最终报文数据
     * @param $sign
     * @param $cipher
     */
    public function toXml($data)
    {
        // -1- 组织<XML>
        $xml = $this->bXml($data);

        // -2- 对称加密获得密文
        $ciphertext = (new Crypt3Des($this->Des3Key))->encrypt($xml);

        // -3- 单向加密获得签名
        $sign = (new RSA())->RSAEncode($xml);

        // -4- 组织最终报文
        $this->message =
            '001X11          00000256'.$sign.
            '<?xml version="1.0" encoding="utf-8"?>'.
            '<Document>'.
                '<header>'.
                    '<channelCode>P2P001</channelCode>'.
                    '<channelFlow>OG012016045333cg1AlM</channelFlow>'.
                    '<channelDate>'.date('Ymd').'</channelDate>'.
                    '<channelTime>'.date('His').'</channelTime>'.
                    '<encryptData></encryptData>'.
                '</header>'.
                '<body>'.
                    '<TRANSCODE>OGW00019</TRANSCODE>'.
                    '<XMLPARA>'.$ciphertext.
                    '</XMLPARA>'.
                '</body>'.
            '</Document>';
    }

    /**
     * 接收用户请求
     * @param array $data
     */
    public function iRequest($data = [])
    {
        // -4- 组织最终报文
        $this->toXml($data);
        //var_dump($this->url, $this->message);exit;

        // -5- http request
        $res = (new HttpRequest())->ihttp_request($this->url, $this->message);
        //var_dump($res);exit;

        // -6- 验签
        var_dump($this->getResStatus($res['content']));exit;
        $resSign = $this->getResSign($res['content']);
        $md5 = (new RSA())->RSADecode($resSign);
        var_dump($md5);exit;

        // -7- 解密
        //$plaintext =

        // test
        /*echo '明文：'.htmlspecialchars($xml);
        var_dump(
            '密文：'.$cipher,
            '签名：'.$sign,
            '报文：'.$this->message
        );exit;*/
    }

    /**
     * 获取表单数据
     * @param array $data
     */
    public function getFormData($data = [])
    {
        $this->toXml($data);

        $reData = [
            'action'        => $this->url,
            'RequestData'   => $this->message,
            'transCode'     => $this->TRANSCODE
        ];

        return $reData;
    }

    /**
     * 获取回调请求数据
     */
    public function getBackReqData($data)
    {
        // ...
        //$sign = (new MyRSA())->RSADecode($data);
        //$cipher = (new Crypt3Des($this->Des3Key))->decrypt($data);

    }

    public function getResStatus($data)
    {
        $xml =  substr($data, strpos($data, '<'));
        return json_decode(json_encode(simplexml_load_string($xml)), true);
    }

    /**
     * 获取银行返回时的私钥
     * @param $data
     * @return string
     */
    public function getResSign($data)
    {
        $headSize = 24; // 报文头和私钥之间如何区分界限
        //$data = ltrim($data, '001X11          00000256');
        $sign = substr($data, $headSize, strpos($data, '<')-$headSize);
        var_dump($sign);exit;
        return $sign;
    }

}