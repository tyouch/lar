<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Libraries\Encryption\Crypt3Des;
use App\Libraries\Encryption\RSA;
use App\Libraries\Encryption\HXBankConfig;
//use App\Libraries\Encryption\MyRequest;

class TestController extends Controller
{
    public function index()
    {
        //var_dump(1);exit;


        $a = new HXBankConfig();
        //echo htmlspecialchars($a->test());

        $data = [
            'TRSTYPE'   => '123',
            'ACNO'      => '13662222344',

        ];
        echo htmlspecialchars($a->iRequest($data));
        //echo htmlspecialchars($a->getSMSVerificationCode('OGW00041','1', '001', 0, 123));
    }

    public function index2()
    {
        //var_dump(2);exit;
        $data = [
            'TRSTYPE'   => '0',
            'ACNO'      => '13662222344',

        ];
        //var_dump((new HXBankConfig())->bXml($data));
        $xml ='<?xml version="1.0" encoding="utf-8"?><Document><header><channelCode>P2P001</channelCode><channelFlow>OG012016045333cg1AlM</channelFlow><channelDate>20170503</channelDate><channelTime>161325</channelTime><encryptData></encryptData></header><body><TRANSCODE>OGW00019</TRANSCODE><XMLPARA>a6U4P6ZdcJRp66jZJliS5Ve2CEK2qpUeHYnSlt2kIXxQcCEZqFHpqO8QhXuL+sPAT8FdKwRRT8LroUQlbw9Ju+Bub/6/Ln3KNWBwdu9+LOKIg70kpgPLzBuTiFYaAFiA2fmE1RXZKdh+jjHN976pemX8k7RXyNhPhm0SIPb8oOE=</XMLPARA></body></Document>';
        $arr = json_decode(json_encode(simplexml_load_string($xml)), true);
        var_dump($arr);
    }
}
