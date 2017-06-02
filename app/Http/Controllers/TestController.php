<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Lib\Sumapay\Crypt3Des;
use App\Lib\Sumapay\RSA;
use App\Lib\Sumapay\HXBankApi;
use App\Lib\Sumapay\HttpRequest;
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


        //$a = new HXBankApi();
        //echo htmlspecialchars($a->test());

        $data = [
            'TRSTYPE'   => '123',
            'ACNO'      => '13662222344',

        ];

        $OGW00041 = array (
            'TRANSCODE' => 'OGW00041',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'TRSTYPE' => '0',
            'ACNO' => '',
            'MOBILE_NO' => '13666666666',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );

        $OGW00042 = array (
            'TRANSCODE' => 'OGW00042',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'TTRANS' => '6',
            'MERCHANTNAME' => 'P2P三分贷',
            'ACNAME' => '施秀艾',
            'IDTYPE' => '1010',
            'IDNO' => '231181197606154944',
            'MOBILE' => '13661111444',
            'EMAIL' => '',
            'RETURNURL' => '123',
            'CUSTMNGRNO' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        //dd(HXBankApi::getFormData($OGW00042));

        $OGW00043 = array (
            'TRANSCODE' => 'OGW00043',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170602042eVjC2uS0OeQ',//P2P17420170602042zK4n4Y0XQ8T
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        dd(HXBankApi::request($OGW00043));
        //echo htmlspecialchars(HXBankApi::request($OGW00051));
        //echo htmlspecialchars($a->getSMSVerificationCode('OGW00041','1', '001', 0, 123));
    }

    public function index2(Request $request)
    {
        $login_url = 'http://m.istarshine.com/LoginOK!login.do';
        $post = array(
            'userid' => '清华大学',
            'passwd' => 'qhdx'
        );
        //$res = (new HttpRequest())->ihttp_request($login_url,$post);
        $res = (new HttpRequest())->ihttp_request('http://www.baidu.com/');
        dd($res);


        exit;
        return response('1234', 200)->header('Content-Type', 'text/html;charset=utf-8')
            ->withCookie('zhaoyao','great.org', 1, '/');
        //var_dump(2);exit;
        $data = [
            'TRSTYPE'   => '0',
            'ACNO'      => '13662222344',

        ];
        //var_dump((new HXBankApi())->bXml($data));
        $xml ='<?xml version="1.0" encoding="utf-8"?><Document><header><channelCode>P2P001</channelCode><channelFlow>OG012016045333cg1AlM</channelFlow><channelDate>20170503</channelDate><channelTime>161325</channelTime><encryptData></encryptData></header><body><TRANSCODE>OGW00019</TRANSCODE><XMLPARA>a6U4P6ZdcJRp66jZJliS5Ve2CEK2qpUeHYnSlt2kIXxQcCEZqFHpqO8QhXuL+sPAT8FdKwRRT8LroUQlbw9Ju+Bub/6/Ln3KNWBwdu9+LOKIg70kpgPLzBuTiFYaAFiA2fmE1RXZKdh+jjHN976pemX8k7RXyNhPhm0SIPb8oOE=</XMLPARA></body></Document>';
        $arr = json_decode(json_encode(simplexml_load_string($xml)), true);
        var_dump($arr);
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



    public function show(Request $request)
    {
        //dd($request->method());
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
}
