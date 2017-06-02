<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 11:10
 */

namespace App\Lib\Sumapay;

use App\Lib\Sumapay\Crypt3Des;
use App\Lib\Sumapay\RSA;
use App\Lib\Sumapay\HttpRequest;

class HXBankApi
{
    protected $message;
    protected $plaintext;
    protected $des3Key;
    protected $url;
    protected $merchantID;
    protected $channelCode;
    protected $channelFlow;
    protected $transCode;

    public function __construct()
    {
        $this->message      = '';
        $this->des3Key      = config('hxbank.DES3KEY'); // 'AZJ174D4G9849H6GEMJ0K1I3';
        $this->url          = config('hxbank.HTTP_REQUEST_URL_TEST'); // HTTP_REQUEST_URL 'http://183.63.131.106:40011/extService/ghbExtService.do';
        $this->merchantID   = config('hxbank.MERCHANTID'); // '商户号:SFD';
        $this->channelCode  = config('hxbank.CHANNELCODE'); // '接口报文头字段“接入渠道”:P2P174';
        $this->channelFlow  = config('hxbank.CHANNELCODE').date("Ymd"); // '接口报文头字段“接入渠道”:P2P174';
    }


    /**
     * 组织接口数据的 XML
     * @param array $data
     * @return string
     */
    public function buildXmlPara($data = [])
    {
        //$xml = '<MERCHANTID>'.$this->merchantID.'</MERCHANTID>';
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
                    '<channelCode>'.$this->channelCode.'</channelCode>'.
                    '<channelFlow>'.$this->channelFlow.'</channelFlow>'.
                    '<channelDate>'.date('Ymd').'</channelDate>'.
                    '<channelTime>'.date('His').'</channelTime>'.
                    '<encryptData></encryptData>'.
                '</header>'.
                '<body>'.
                    '<TRANSCODE>'.$this->transCode.'</TRANSCODE>'.
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
        $this->transCode = $data['TRANSCODE'];
        $this->channelFlow .= substr($data['TRANSCODE'], -3, 3).$this->generateMerchantFlow(11);
        //dd($this->channelFlow);
        unset($data['TRANSCODE']);

        // -1- 组织数据 <XMLPARA>
        $data['MERCHANTID'] = $this->merchantID;
        $xmlpara = $this->buildXmlPara($data);
        //dd($xmlpara);

        // -2- 对称加密获得密文
        $ciphertext = (new Crypt3Des($this->des3Key))->encrypt($xmlpara);

        // 组织 <XML>
        $xml = $this->buildXml($ciphertext);
        $this->plaintext = $this->buildXml2($xmlpara);

        // -3- 单向加密获得签名
        $sign = (new RSA())->RSAEncode($xml);

        // -4- 组织最终报文
        $this->message = '001X11          00000256' . $sign . $xml;
        //var_dump($this->message);exit;
    }

    /**
     * 生成 11位商户流水
     * @param int $length
     * @return string
     */
    public function generateMerchantFlow($length = 11)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomStr = '';
        for ($i=0; $i<$length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $randomStr .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $randomStr .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $randomStr;
    }

    /**
     * 接收用户请求
     * @param array $data
     */
    public function doRequest($data = [])
    {
        // -4- 组织最终报文
        $this->buildMsg($data);
        //dd($this->url, $this->message);

        // -5- http request
        //$res = (new HttpRequest())->ihttp_request($this->url, $this->message);
        //$res = HttpRequest::toFormat($this->url, $this->message);
        //dd($res);

        // -6- 验签 -7- 解密
        //var_dump($this->getResStatus($res['content']));exit;
        $res['msg'] = '001X11          000002560F66959CA2D46D55FBCC80DAAB7CD976D93420576223DAF9C0641273537F4073F9B82136AD1B92734B7784C60348B8B53E5E7D3F7BBC83714E4F2D4AFA4268CEBC881D38970E95E9F04319E2D060219DD2E7F9EEAFA5E50A19F941141016FFF40B54729A451EC946AA2D1AE53A93BC60833B65F13A1AE20403BBAB9D5775D499<?xml version="1.0" encoding="UTF-8" ?><Document><header><encryptData>N</encryptData><serverFlow>OGW012017060244JVxn</serverFlow><channelCode>GHB</channelCode><status>testMode</status><serverTime>172048</serverTime><errorCode>0</errorCode><errorMsg></errorMsg><channelFlow>P2P17420170602043xocyRj2wsMd</channelFlow><serverDate>20170602</serverDate></header><body><TRANSCODE>OGW00043</TRANSCODE><BANKID>GHB</BANKID><XMLPARA>3cY1iThbmEmCbiSxiHCJdG4YZAAjrxP2IG5adTlxDsgnoLepo/Vo6GbgwT0cSYcvCeGx7QZmyjkwZGvso3MDRgbfJax7GB4o9Sen0tAfEhsxN5qV4GOfmBV8OiHuZh+VdBEqyQLqvSYgblp1OXEOyMPPk1IGSpE1ZuDBPRxJhy/W22VT3aoj5A==</XMLPARA><MERCHANTID>SFD</MERCHANTID></body></Document>';
        $this->checkAndDecrypt($res['msg']); //$this->message $res['content'] temp test $this->message
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
        //dd($msg, $md51, $md52);

        if($md51 == $md52){ //验签成功

            // 进行解密
            $rule = '#<XMLPARA>(.*?)</XMLPARA>#i';
            if(preg_match_all($rule, $msg['xml'], $matches))
            {
                //var_dump($matches);exit;
                $ciphertext = $matches[1][0];
                $plaintext  = (new Crypt3Des($this->des3Key))->decrypt($ciphertext);
                $xml = preg_replace($rule, '<XMLPARA>'.$plaintext.'</XMLPARA>', $msg['xml']);
                $xmlArr = json_decode(json_encode(simplexml_load_string($xml)), true);
                dd(
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
    public function doGetFormData($data = [])
    {
        $this->buildMsg($data);

        $reData = [
            'action'        => $this->url,
            'RequestData'   => $this->message,
            'plaintext'     => $this->plaintext,
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
     * 查看明文 测试用
     * @param $plaintext
     * @return string
     */
    public function buildXml2($plaintext)
    {
        $xml =
            '<?xml version="1.0" encoding="utf-8"?>'.
            '<Document>'.
            '<header>'.
            '<channelCode>'.$this->channelCode.'</channelCode>'.
            '<channelFlow>'.$this->channelFlow.'</channelFlow>'.
            '<channelDate>'.date('Ymd').'</channelDate>'.
            '<channelTime>'.date('His').'</channelTime>'.
            '<encryptData></encryptData>'.
            '</header>'.
            '<body>'.
            '<TRANSCODE>'.$this->transCode.'</TRANSCODE>'.
            '<XMLPARA>'.$plaintext.
            '</XMLPARA>'.
            '</body>'.
            '</Document>';
        return $xml;
    }

    /**
     * test
     */
    public function getResStatus($data)
    {
        $xml =  substr($data, strpos($data, '<'));
        return json_decode(json_encode(simplexml_load_string($xml)), true);
    }

    /**
     * @param $name
     * @param $arguments
     * @return array|void
     */
    public static function __callStatic($name, $arguments) {

        //var_dump($name, $arguments, get_called_class());exit;

        switch ($name) {
            case 'request':
                $data= $arguments[0];
                return (new HXBankApi())->doRequest($data);

            case 'getFormData':
                $data= $arguments[0];
                return (new HXBankApi())->doGetFormData($data);

            default:
                throw new Exception('Invalid method : '.$name);
        }
    }
}