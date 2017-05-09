<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 11:07
 */
/**
 * Class MyRSA
 * 私钥签名和公钥验证签名
 */
namespace App\Libraries\Encryption;

class RSA {

    protected $private_key;
    protected $public_key;

    public function __construct()
    {
        /*$private_str = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCnyzixo/FKPY1e0V2ycFB+9MMkvSP/R2+x4ZsvIJ7Ygmvu2BpH
EHQtzMWrWj40rZsf7cWWukzhytHFD+DIyRjr7kFbGg93v4t0Wfcx/7Fq3SIvU3p7
U/nqwkV8Xu0FScvSZJE3MLI8ivHwX98nsYwUXd5s5GYMBaEgZ7v2iZJU+QIDAQAB
AoGASUvIlDCdMmT44DZsy4msYAjjRaUpmFXIQNfgRhHl0YYHR+o2cFyLo4YTwupE
yhYb8TKYYWM4OlmykHaDJrJRo+i39E/DEISFnb3mp67Svkrb/1EhjYWSeFvGIBAh
+O2OnD63QIVcQKFNN94BXxfc0Ck0URvX8z2JgbNzMZ8ijhECQQDalCwkkKjjXXZd
fTzT3nR9l6tl4qN6gTiGantr16UrJSKpjlRIeXulzapBsiebc4HNYYF06I9Mh9PM
rysMaHTfAkEAxIVBfWx94eI2eFdtILbELLp2XIqVuFlqrOZO5/oS+EZhNI83Naex
EjrnxAbA1dr6UBA7cyERXc8oE6fYPNlZJwJAT80fHK5v6qq5x0ItDhE+qIfSPN10
4AoDkBeaPfI6TDk/oXXkUZ2AxjUDPv8DNN8D+e7qa7tobgF9E1K0vc/5MwJBAK7T
VSV9JKeUlJyOOhjGPtMDtlQxPWxYr5vM7xlT0RhplAQr/BORcOck3BX5ZAdb3R7o
sdqD6m0n4yFJSgcn3DUCQQCNXpuC+dEAUV9lt7adfrP7VTP4/fiA4kjiUg0l3wmK
vB/rqBHfciWMLmS29p5TuB+/a71JI8JR1XGD3R7f109Y
-----END RSA PRIVATE KEY-----
EOD;

        $public_str = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnyzixo/FKPY1e0V2ycFB+9MMkv
SP/R2+x4ZsvIJ7Ygmvu2BpHEHQtzMWrWj40rZsf7cWWukzhytHFD+DIyRjr7kFbGg
93v4t0Wfcx/7Fq3SIvU3p7U/nqwkV8Xu0FScvSZJE3MLI8ivHwX98nsYwUXd5s5GY
MBaEgZ7v2iZJU+QIDAQAB
-----END PUBLIC KEY-----
EOD;*/

        $this->private_key  = openssl_pkey_get_private(config('hxbank.PRIVATE_STR'));
        $this->public_key   = openssl_pkey_get_public(config('hxbank.PUBLIC_STR'));
    }

    /**
     * @param $data
     * @return string
     * 私钥签名
     */
    public function RSAEncode($data)
    {
        $MD5 = strtoupper(md5($data));
        $encrypted="";
        openssl_private_encrypt($MD5, $encrypted, $this->private_key);//私钥加密
        return strtoupper(bin2hex($encrypted));//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
    }

    /**
     * @param $encrypted
     * @return bool
     * 公钥验签
     */
    public function RSADecode($encrypted)
    {
        $decrypted = "";
        openssl_public_decrypt(hex2bin(strtolower($encrypted)), $decrypted, $this->public_key);//私钥加密的内容通过公钥可用解密出来
        return $decrypted;
    }

}

/*$data = "<?xml version=\"1.0\" encoding=\"utf-8\"?><Document><header><channelCode>P2P001</channelCode><channelFlow>20160316175420006</channelFlow><channelDate>20160316</channelDate><channelTime>175420</channelTime><encryptData></encryptData><header><body><XMLPARA>7wPjJiSOm4ucZcU7lq0eqc37HWkJuz1bqjKpo6dgH11wqXi7ffFBzs2xmLOvYIhmAW6AVmky2uBvmIfhc0BTGDCQEbLUsjxZlzTrkHnodoBvOhLVjY/nWb+snb8izM6XuM9rtf2VYuAGkT8idBq+vMTh/sag+ccb7uiGHWmHUQno9bUCtcoA2TaGePIt9MkMIC6+QxRlda6mWzSoUZOj4w==</XMLPARA></body></Document>";
$a = new MyRSA();
$data2 = $a->RSAEncode($data);
$data3 = $a->RSADecode($data2);
var_dump($data2, $data3);*/