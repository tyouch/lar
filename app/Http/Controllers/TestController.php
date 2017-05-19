<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Encryption\Crypt3Des;
use App\Libraries\Encryption\RSA;
use App\Libraries\Encryption\HXBankConfig;
//use App\Libraries\Encryption\MyRequest;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //var_dump(1);exit;


        $a = new HXBankConfig();
        //echo htmlspecialchars($a->test());

        $data = [
            'TRSTYPE'   => '123',
            'ACNO'      => '13662222344',

        ];
        $OGW00051 = array (
            'TRANSCODE' => '字段名称：交易码 | 类型：C(8) | 可空：否 | 备注：OGW00051',
            'MERCHANTID' => '字段名称：商户唯一编号 | 类型：C (20) | 可空：否 | 备注：由华兴银行统一分配',
            'MERCHANTNAME' => '字段名称：商户名称 | 类型：C(128) | 可空：否 | 备注：',
            'APPID' => '字段名称：应用标识 | 类型：C(3) | 可空：否 | 备注：个人电脑:PC（不送则默认PC） 手机：APP 微信：WX',
            'LOANNO' => '字段名称：借款编号 | 类型：C (64) | 可空：否 | 备注：目前两者为一致',
            'INVESTID' => '字段名称：标的编号 | 类型：C (128) | 可空：否 | 备注：目前两者为一致',
            'INVESTOBJNAME' => '字段名称：标的名称 | 类型：C (512) | 可空：否 | 备注：',
            'INVESTOBJINFO' => '字段名称：标的简介 | 类型：C (1028) | 可空：是 | 备注：',
            'MININVESTAMT' => '字段名称：最低投标金额 | 类型：M | 可空：是 | 备注：',
            'MAXINVESTAMT' => '字段名称：最高投标金额 | 类型：M | 可空：是 | 备注：',
            'INVESTOBJAMT' => '字段名称：总标的金额 | 类型：M | 可空：否 | 备注：各个借款人列表中的BWAMT总和',
            'INVESTBEGINDATE' => '字段名称：招标开始日期 | 类型：D | 可空：否 | 备注：YYYYMMDD',
            'INVESTENDDATE' => '字段名称：招标到期日期 | 类型：D | 可空：否 | 备注：YYYYMMDD',
            'REPAYDATE' => '字段名称：还款日期 | 类型：D | 可空：是 | 备注：YYYYMMDD',
            'YEARRATE' => '字段名称：年利率 | 类型：I2 | 可空：否 | 备注：最大值为：999.999999',
            'INVESTRANGE' => '字段名称：期限 | 类型：N(10) | 可空：否 | 备注：整型，天数，单位为天',
            'RATESTYPE' => '字段名称：计息方式 | 类型：C(128) | 可空：是 | 备注：',
            'REPAYSTYPE' => '字段名称：还款方式 | 类型：C(128) | 可空：是 | 备注：',
            'INVESTOBJSTATE' => '字段名称：标的状态 | 类型：C(3) | 可空：否 | 备注：0 正常 1 撤销',
            'BWTOTALNUM' => '字段名称：借款人总数 | 类型：N(10) | 可空：否 | 备注：整型',
            'REMARK' => '字段名称：备注 | 类型：C(512) | 可空：是 | 备注：',
            'ZRFLAG' => '字段名称：是否为债券转让标的 | 类型：C(1) | 可空：是 | 备注：0 否，1 是',
            'REFLOANNO' => '字段名称：债券转让原标的 | 类型：C(64) | 可空：是 | 备注：当ZRFLAG=1时必填',
            'OLDREQSEQ' => '字段名称：原投标第三方交易流水号 | 类型：C(28) | 可空：是 | 备注：当ZRFLAG=1时必填',
            'EXT_FILED1' => '字段名称：备用字段1 | 类型：C(200) | 可空：是 | 备注：备用字段1',
            'BWLIST' =>
                array (
                    'BWACNAME' => '字段名称：借款人姓名 | 类型：C(128) | 可空：否 | 备注：',
                    'BWIDTYPE' => '字段名称：借款人证件类型 | 类型：C(4) | 可空：是 | 备注：身份证：1010',
                    'BWIDNO' => '字段名称：借款人证件号码 | 类型：C(32) | 可空：是 | 备注：18位身份证',
                    'BWACNO' => '字段名称：借款人账号 | 类型：N(32) | 可空：否 | 备注：',
                    'BWACBANKID' => '字段名称：借款人账号所属行号 | 类型：N(64) | 可空：是 | 备注：12位联行号，12位数字',
                    'BWACBANKNAME' => '字段名称：借款人账号所属行名 | 类型：C(256) | 可空：是 | 备注：',
                    'BWAMT' => '字段名称：借款人金额 | 类型：M | 可空：否 | 备注：',
                    'MORTGAGEID' => '字段名称：借款人抵押品编号 | 类型：C(128) | 可空：是 | 备注：',
                    'MORTGAGEINFO' => '字段名称：借款人抵押品简单描述 | 类型：C(1024) | 可空：是 | 备注：',
                    'CHECKDATE' => '字段名称：借款人审批通过日期 | 类型：C(8) | 可空：是 | 备注：',
                    'REMARK' => '字段名称：备注（其它未尽事宜） | 类型：C(1028) | 可空：是 | 备注：',
                    'EXT_FILED2' => '字段名称：备用字段2 | 类型：C(200) | 可空：是 | 备注：备用字段2',
                    'EXT_FILED3' => '字段名称：备用字段3 | 类型：C(300) | 可空：是 | 备注：备用字段3',
                ),
        );
        echo htmlspecialchars($a->request($OGW00051));
        //echo htmlspecialchars($a->getSMSVerificationCode('OGW00041','1', '001', 0, 123));
    }

    public function ajax(Request $request)
    {
        //var_dump($request->input('test'));exit;
        $acids = DB::connection('alienvault_siem')->table('acid_event')
            ->where(['plugin_id'=>'1001'])
            ->offset(0)
            ->limit(10)
            ->orderBy('timestamp', 'desc')
            ->get();
        //var_dump($acids);exit;

        $data = []; $i = 0;
        foreach ($acids as $acid) {
            $data[$i]['timestamp'] = $acid->timestamp;
            $data[$i]['ip_src'] = @inet_ntop($acid->ip_src);
            $data[$i]['ip_dst'] = @inet_ntop($acid->ip_dst);
            $data[$i]['layer4_sport'] = $acid->layer4_sport;
            $data[$i]['layer4_dport'] = $acid->layer4_dport;
            $data[$i]['plugin_sid'] = $acid->plugin_sid;
            $data[$i++]['classtype'] = 'unknown';
        }
        //var_dump($data);exit;

        /*$data = [
            [
                'timestamp'     => '12345678',
                'ip_src'        => '192.168.0.1',
                'ip_dst'        => '192.168.0.3',
                'ip_dst'        => '192.168.0.3',
                'layer4_sport'  => '223',
                'layer4_dport'  => '345',
                'classtype'     => 'xxxxooooXXXX',
                'plugin_sid'    => '1001',
            ],
            [
                'timestamp'     => '1231234',
                'ip_src'        => '192.168.0.2',
                'ip_dst'        => '192.168.0.4',
                'layer4_sport'  => '2233',
                'layer4_dport'  => '3453',
                'classtype'     => 'ooooxxxx0000',
                'plugin_sid'    => '1001',
            ]
        ];*/

        if($request->input('test')){
            return response()->json([
                'msg'       => 'ok',
                'status'    => 0,
                'data'      => $data
            ]);
        }else {
            //return Redirect::back()->withInput()->withErrors('失败！');
            return response()->json([
                'msg' => 'fail!',
                'status' => 2
            ]);
        }
    }

    public function excelToDoc() {
        $filePath = 'storage/exports/123.xlsx';
        //var_dump($filePath);exit;
        Excel::load($filePath, function ($reader) {
            $data = $reader->all();
            //var_dump($data);exit;


            for($i=0; $i<count($data); $i++) {

                $rData = $remarks = [];
                $async = false; $layer = 1; $key2 = null;

                for($j=0; $j<count($data[$i]); $j++) {
                    switch ($j) {
                        case 0:
                            $title = $data[$i][$j]['field_id'];
                            if(preg_match_all('#\((.*?)\)#i', $title, $matches)){
                                $arrayName = '$'.$matches[1][0];
                            }
                            break;
                        case 1: $desc  = $data[$i][$j]['field_id']; break;
                        case 3: break;
                        case 2:
                        case 4:
                        default:
                            $key = $data[$i][$j]['field_id'];
                            $key == 'RETURNURL' && $async = true;
                            $remarks[$i] =
                                '字段名称：'.$data[$i][$j]['field_name'].' | '.
                                '类型：'.$data[$i][$j]['type'].' | '.
                                '可空：'.$data[$i][$j]['is_null'].' | '.
                                '备注：'.$data[$i][$j]['remarks'];
                            if(substr($data[$i][$j]['field_id'], 0, 1) == '<'){
                                $key2 = substr($data[$i][$j]['field_id'], 1, strlen($data[$i][$j]['field_id'])-2);
                                $layer = 2;
                                continue;
                            }elseif($layer == 2){
                                $rData[$key2][$key] = $remarks[$i];
                            }elseif(substr($data[$i][$j]['field_id'], 0, 2) == '</'){
                                $layer = 1;
                            }else{
                                $rData[$key] = $remarks[$i];
                            }
                    }
                }
                var_dump($title, $desc, $rData, '--------------------------------------------------------------------------------------');/**/
                $file   = '../storage/exports/array.php';
                $usage  = '(new(HXBandConfig())->request('.$arrayName.'))';
                $usage2 = '(new(HXBandConfig())->getFormData('.$arrayName.'))';
                file_put_contents($file, $this->remarks($title), FILE_APPEND);
                file_put_contents($file, $this->remarks($desc), FILE_APPEND);
                file_put_contents($file, $arrayName.' = '.var_export($rData, true).';'.PHP_EOL, FILE_APPEND);
                file_put_contents($file, $this->remarks($usage, 'Usage: '), FILE_APPEND);
                $async && file_put_contents($file, $this->remarks($usage2, '       '), FILE_APPEND);
                file_put_contents($file, PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL, FILE_APPEND);

            }
        });
        var_dump('success!');
        exit;
    }
    public function remarks($connect, $etc = null)
    {
        return '/** '.$etc.$connect.' */'.PHP_EOL;
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
