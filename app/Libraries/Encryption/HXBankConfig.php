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

    public function __construct()
    {
        $this->Des3Key      = config('hxbank.DES3KEY'); // 'A1B2C3D4E5F6G7H8I9J0K1L2';
        $this->url          = config('hxbank.HTTP_REQUEST_URL'); //'http://183.63.131.106:40011/extService/ghbExtService.do';
        $this->message      = '';
        $this->MERCHANTID   = config('hxbank.MERCHANTID'); // '7654321';
    }


    /**
     * 组织接口数据的 XML
     * @param array $data
     * @return string
     */
    public function buildXmlPara($data = [])
    {
        //$xml = '<MERCHANTID>'.$this->MERCHANTID.'</MERCHANTID>';
        //var_dump($data);exit;

        $xml = '';

        foreach ($data as $k=>$v)
        {
            if(is_array($v)) {
                $xml .= '<'.$k.'>'.$this->buildXmlPara($v).'</'.$k.'>';
            } else {
                $xml .= '<'.$k.'>'.$v.'</'.$k.'>';
            }
        }

        return $xml;
    }

    //
    public function buildXml($ciphertext)
    {
        $xml =
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
                    '<TRANSCODE>'.$this->TRANSCODE.'</TRANSCODE>'.
                    '<XMLPARA>'.$ciphertext.
                    '</XMLPARA>'.
                '</body>'.
            '</Document>';
        return $xml;
    }

    /**
     * 组织最终报文数据
     * @param $sign
     * @param $cipher
     */
    public function buildMsg($data)
    {
        // -1- 组织数据 <XMLPARA>
        $xmlpara = $this->buildXmlPara($data); //var_dump($xml);

        // -2- 对称加密获得密文
        $ciphertext = (new Crypt3Des($this->Des3Key))->encrypt($xmlpara);

        // 组织 <XML>
        $xml = $this->buildXml($ciphertext);

        // -3- 单向加密获得签名
        $sign = (new RSA())->RSAEncode($xml);

        // -4- 组织最终报文
        $this->message = '001X11          00000256'.$sign.$xml;

        //var_dump($this->message);exit;
    }

    /**
     * 接收用户请求
     * @param array $data
     */
    public function request($data = [])
    {
        // -4- 组织最终报文
        //$data['MERCHANTID'] = $this->MERCHANTID;
        $this->TRANSCODE = $data['TRANSCODE'];
        unset($data['TRANSCODE']);

        $this->buildMsg($data);
        //var_dump($this->url, $this->message);exit;

        // -5- http request
        //$res = (new HttpRequest())->ihttp_request($this->url, $this->message);
        //var_dump($res);exit;

        // -6- 验签 -7- 解密
        //var_dump($this->getResStatus($res['content']));exit;
        $this->checkAndDecrypt($this->message); // temp test $this->message
        exit;

    }

    /**
     * 验签和解密
     * @param array $data
     */
    public function checkAndDecrypt($data = [])
    {
        // test data
        //var_dump($data);exit;

        $msg = $this->splitResMsg($data); //var_dump($msg);exit;
        $md51 = strtoupper(md5($msg['xml']));
        $md52 = (new RSA())->RSADecode($msg['sign']);

        if($md51 == $md52){ //验签成功

            // 进行解密
            $rule = '#<XMLPARA>(.*?)</XMLPARA>#i';
            if(preg_match_all($rule, $msg['xml'], $matches))
            {
                //var_dump($matches);exit;
                $ciphertext = $matches[1][0];
                $plaintext  = (new Crypt3Des($this->Des3Key))->decrypt($ciphertext);
                $xml = preg_replace($rule, '<XMLPARA>'.$plaintext.'</XMLPARA>', $msg['xml']);
                $xmlArr = json_decode(json_encode(simplexml_load_string($xml)), true);
                var_dump(
                    '全文：'.$data,
                    '签名：'.$msg['sign'],
                    'XML：'.$msg['xml'],
                    '摘要：'.$md51.'<=>'.$md52,
                    '密文：'.$ciphertext,
                    '明文：'.$plaintext,
                    '全文：'.$xml, $xmlArr
                );
                /*return $xmlArr;*/
            }
        } else {
            die('验签失败');
        }

    }

    /**
     * 获取银行返回时的私钥和 xml部分
     * @param $data
     * @return string
     */
    public function splitResMsg($data)
    {
        $headSize = 24; // 报文头和私钥之间如何区分界限
        //$data = ltrim($data, '001X11          00000256');
        $msg    = [
            'sign'  => substr($data, $headSize, strpos($data, '<')-$headSize),
            'xml'   => substr($data, strpos($data, '<'))
        ];
        //var_dump($msg);exit;
        return $msg;
    }

    /**
     * 获取表单数据
     * @param array $data
     */
    public function getFormData($data = [])
    {
        $this->buildMsg($data);

        $reData = [
            'action'        => $this->url,
            'RequestData'   => $this->message,
            'transCode'     => $data['TRANSCODE']
        ];

        return $reData;
    }

    /**
     * 获取回调请求数据
     */
    public function getBackReqData($data)
    {
        return $this->checkAndDecrypt($data);
    }

    /**
     * test
     */
    public function getResStatus($data)
    {
        $xml =  substr($data, strpos($data, '<'));
        return json_decode(json_encode(simplexml_load_string($xml)), true);
    }

}