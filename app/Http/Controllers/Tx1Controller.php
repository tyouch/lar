<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\Sumapay\HXBankApi;

class Tx1Controller extends Controller
{
    /**
     * 回调
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tx(Request $request)
    {

        $file = '../tx.txt';
        $raw_post_data = file_get_contents('php://input', 'r');
        if(empty($raw_post_data)) {
            $msg = file_get_contents($file);
            //file_put_contents($file, null, LOCK_EX);
            unlink($file);
            $ret = HXBankApi::getBackReqData($msg);
            echo $ret['json'];
            $data = [
                'channelCode'   => $ret['array']['header']['channelCode'],
                'channelFlow'   => $ret['array']['header']['channelFlow'],
                'channelDate'   => $ret['array']['header']['channelDate'],
                'channelTime'   => $ret['array']['header']['channelTime'],
                //'BANKID'    => $ret['array']['body']['BANKID'],
                'TRANSCODE' => $ret['array']['body']['TRANSCODE'],
                'RETURNCODE'    => '000000',
                'RETURNMSG' => '交易成功',
                'OLDREQSEQNO'   => $ret['array']['body']['XMLPARA']['OLDREQSEQNO'],
            ];
            //dump($ret, $data);
            $res = HXBankApi::resBank($data);
            //dd($res);
        } else {
            file_put_contents($file, $raw_post_data, LOCK_EX);
        }

    }
}
