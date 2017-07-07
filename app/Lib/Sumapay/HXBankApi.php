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
    protected $plainText;
    protected $des3Key;
    protected $url;
    protected $merchantID;
    protected $transCode;

    protected $channelCode;
    protected $channelFlow;
    protected $channelDate;
    protected $channelTime;

    protected $serverFlow;
    protected $serverDate;
    protected $serverTime;

    public function __construct()
    {
        $this->message      = '';
        $this->des3Key      = config('hxbank.DES3KEY'); // 'AZJ174D4G9849H6GEMJ0K1I3';
        $this->url          = config('hxbank.HTTP_REQUEST_URL'); // 'http://183.63.131.106:40011/extService/ghbExtService.do';
        $this->merchantID   = config('hxbank.MERCHANTID'); // '商户号:SFD';
        $this->merchantName = config('hxbank.MERCHANTNAME'); // '商户号:SFD';
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
    public function buildXml($cipherText, $type = 'req')
    {
        $xml =
            '<?xml version="1.0" encoding="utf-8"?>'.
            '<Document>'.
                '<header>'.
                    '<channelCode>'.$this->channelCode.'</channelCode>'.
                    '<channelFlow>'.$this->channelFlow.'</channelFlow>'.
                    '<channelDate>'.$this->channelDate.'</channelDate>'.
                    '<channelTime>'.$this->channelTime.'</channelTime>'.
                    '<encryptData></encryptData>'
        ;

        if($type == 'res') {
            $xml .=
                '<transCode>'.$this->transCode.'</transCode>'.
                '<serverFlow>'.$this->serverFlow.'</serverFlow>'.
                '<serverDate>'.$this->serverDate.'</serverDate>'.
                '<serverTime>'.$this->serverTime.'</serverTime>'.
                '<status>0</status>'.
                '<errorCode>0</errorCode>'.
                '<errorMsg>success</errorMsg>'
            ;
        }

        $xml .=
                '</header>'.
                '<body>'.
                    '<TRANSCODE>'.$this->transCode.'</TRANSCODE>'.
                    '<XMLPARA>'.$cipherText.
                    '</XMLPARA>'.
                '</body>'.
            '</Document>'
        ;

        return $xml;
    }

    /**
     * 组织最终报文数据
     * @param $sign
     * @param $cipher
     */
    public function buildMsg($data, $type = 'req')
    {
        $this->transCode = $data['TRANSCODE'];
        unset($data['TRANSCODE']);

        if($type == 'req') {
            isset($data['MERCHANTID']) && $data['MERCHANTID'] = $this->merchantID;
            isset($data['MERCHANTNAME']) && $data['MERCHANTNAME'] = $this->merchantName;
            $this->channelFlow .= substr($this->transCode, -3, 3).$this->generateMerchantFlow(11);
            $this->channelDate  = date('Ymd');
            $this->channelTime  = date('His');
            //dd($this->channelFlow, $data);
        } else {
            //$this->channelCode  = $data['channelCode'];
            $this->channelFlow  = $data['channelFlow'];
            $this->channelDate  = $data['channelDate'];
            $this->channelTime  = $data['channelTime'];
            unset($data['channelCode']);
            unset($data['channelFlow']);
            unset($data['channelDate']);
            unset($data['channelTime']);
            $this->serverFlow   = 'SFD'.date('YmdHis').$this->generateMerchantFlow(3);
            $this->serverDate   = date('Ymd');
            $this->serverTime   = date('His');
            //dump($this->serverFlow, $this->serverDate, $this->serverTime);
        }


        // -1- 组织数据 <XMLPARA>
        $xmlpara = $this->buildXmlPara($data);
        //dd($xmlpara);

        // -2- 对称加密获得密文
        $cipherText = (new Crypt3Des($this->des3Key))->encrypt($xmlpara);

        // 组织 <XML>
        if ($type == 'req') {
            $xml = $this->buildXml($cipherText);
            $this->plainText = $this->buildXml2($xmlpara); // test
        } else {
            $xml = $this->buildXml($cipherText, 'res');
            $this->plainText = $this->buildXml2($xmlpara, 'res');
        }

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
        //$res = HttpRequest::to($this->url, $this->message);
        $res = HttpRequest::toFormat($this->url, $this->message);
        //dd($res);

        // -6- 验签 -7- 解密
        //var_dump($this->getResStatus($res['content']));exit;
        return $this->checkAndDecrypt($res['msg']); //$this->message $res['content'] temp test $this->message
        //exit;

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

        if($md51 == $md52) { //验签成功

            // 进行解密
            $rule = '#<XMLPARA>(.*?)</XMLPARA>#i';
            if(preg_match_all($rule, $msg['xml'], $matches)) {
                //var_dump($matches);exit;
                $cipherText = $matches[1][0];
                $plainText  = (new Crypt3Des($this->des3Key))->decrypt($cipherText);
                $msg['xml'] = preg_replace($rule, '<XMLPARA>'.$plainText.'</XMLPARA>', $msg['xml']);
            }
            $array = json_decode(json_encode(simplexml_load_string($msg['xml'])), true);
            $json = json_encode(simplexml_load_string($msg['xml']), JSON_UNESCAPED_UNICODE);
            /*dd(
                '全文：'.$data,
                '签名：'.$msg['sign'],
                'XML：'.$msg['xml'],
                '摘要：'.$md51.'<=>'.$md52,
                '密文：'.$cipherText,
                '明文：'.$plainText,
                '全文：'.$xml, $array
            );*/
            return [
                'xml'   => $msg['xml'],
                'json'  => $json,
                'array' => $array
            ];

        } else {
            response()->json([
                'errorCode' => 'xxxx',
                'errorMsg'  => '验签失败'
            ]);
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
            'plainText'     => $this->plainText,
            'transCode'     => $data['TRANSCODE']
        ];

        return $reData;
    }

    /**
     * 第三方公司应返回
     */
    public function responseBank($data = [])
    {
        // -4- 组织最终报文
        $this->buildMsg($data, 'res');
        //dump($this->url, $this->message, $this->plainText);

        // -5- http request
        return HttpRequest::to($this->url, $this->message);
    }


    /**
     * 查看明文 测试用
     * @param $plainText
     * @return string
     */
    public function buildXml2($plainText, $type = 'req')
    {
        $xml =
            '<?xml version="1.0" encoding="utf-8"?>'.
            '<Document>'.
            '<header>'.
            '<channelCode>'.$this->channelCode.'</channelCode>'.
            '<channelFlow>'.$this->channelFlow.'</channelFlow>'.
            '<channelDate>'.$this->channelDate.'</channelDate>'.
            '<channelTime>'.$this->channelTime.'</channelTime>'.
            '<encryptData></encryptData>'
        ;

        if($type == 'res') {
            $xml .=
                '<transCode>'.$this->transCode.'</transCode>'.
                '<serverFlow>'.$this->serverFlow.'</serverFlow>'.
                '<serverDate>'.$this->serverDate.'</serverDate>'.
                '<serverTime>'.$this->serverTime.'</serverTime>'.
                '<status>0</status>'.
                '<errorCode>0</errorCode>'.
                '<errorMsg>success</errorMsg>'
            ;
        }

        $xml .=
            '</header>'.
            '<body>'.
            '<TRANSCODE>'.$this->transCode.'</TRANSCODE>'.
            '<XMLPARA>'.$plainText.
            '</XMLPARA>'.
            '</body>'.
            '</Document>'
        ;

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

            case 'getBackReqData':
                $data= $arguments[0];
                return (new HXBankApi())->checkAndDecrypt($data);

            case 'resBank':
                $data= $arguments[0];
                return (new HXBankApi())->responseBank($data);

            default:
                throw new Exception('Invalid method : '.$name);
        }
    }
}