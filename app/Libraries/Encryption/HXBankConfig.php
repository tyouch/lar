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
    public function buildXmlPara($data = [])
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
                    '<TRANSCODE>OGW00019</TRANSCODE>'.
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
    public function iRequest($data = [])
    {
        // -4- 组织最终报文
        $this->buildMsg($data);
        //var_dump($this->url, $this->message);exit;

        // -5- http request
        $res = (new HttpRequest())->ihttp_request($this->url, $this->message);
        //var_dump($res);exit;

        // -6- 验签
        //var_dump($this->getResStatus($res['content']));exit;
        $this->checkAndDecrypt();
        exit;
        //$resSign = $this->getResSign($res['content']);
        //$md5 = (new RSA())->RSADecode($resSign);
        //var_dump($md5);exit;

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
     * 验签和解密
     * @param array $data
     */
    public function checkAndDecrypt($data = [])
    {
        // test data
        $data = '001X11          000002567E8BA252167EDDA8541A2DED4A6500DBD79E44736FA60892C3E916DD507BA6A6E47CE37BC70B7242D8F3DEA1218270B22A85D1DD1AB26F00215E390AA23B261BF55203EBB63CE0B1096D92FACD60967CF587A96F25417453FDC23AF7A4AB6515E9DA83AC27B8FEC4F2B6F96C908A5305889B25F327173B6B8C5950DC774DD69D<?xml version="1.0" encoding="utf-8"?><Document><header><channelCode>P2P001</channelCode><channelFlow>OG012016045333cg1AlM</channelFlow><channelDate>20170504</channelDate><channelTime>115509</channelTime><encryptData></encryptData></header><body><TRANSCODE>OGW00019</TRANSCODE><XMLPARA>a6U4P6ZdcJRp66jZJliS5Ve2CEK2qpUeHYnSlt2kIXxQcCEZqFHpqO8QhXuL+sPAT8FdKwRRT8LroUQlbw9Ju+Bub/6/Ln3KNWBwdu9+LOKIg70kpgPLzBuTiFYaAFiA2fmE1RXZKdh+jjHN976pemX8k7RXyNhPhm0SIPb8oOE=</XMLPARA></body></Document>';

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
    }

    public function getResStatus($data)
    {
        $xml =  substr($data, strpos($data, '<'));
        return json_decode(json_encode(simplexml_load_string($xml)), true);
    }

}