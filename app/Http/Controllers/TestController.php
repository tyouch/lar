<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Lib\Sumapay\Crypt3Des;
use App\Lib\Sumapay\RSA;
use App\Lib\Sumapay\HXBankApi;
//use App\Lib\aliyunOpenapiPhpSdkMaster;
use App\Lib\Sumapay\HttpRequest;
use App\Models\Ip;
use Maatwebsite\Excel\Facades\Excel;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $begin = microtime(true);

        $title_str = file_get_contents('../doc/title.txt');
        $titles = explode(PHP_EOL, $title_str);
        //dd($titles);
        $data = [];
        foreach ($titles as $title) {
            $rule = '#\(OGW(.*?)\)#i';
            if(preg_match_all($rule, $title, $matches)) {
                //var_dump($matches[1][0]);
                $data['OGW'.$matches[1][0]] = $title;
            }else{
                dd('error');
            }
        }
        /*for($i=41; $i<78; $i++) {
            $data[$i-41] = 'ogw000'.$i;
        }*/
        //dd($data);
        $end    = microtime(true);
        return view('test.index', [
            'data'  => $data,
            'pass'  => $end - $begin
        ]);
        //echo htmlspecialchars(HXBankApi::request($OGW00051));
        //echo htmlspecialchars($a->getSMSVerificationCode('OGW00041','1', '001', 0, 123));
    }

    public function vue()
    {
        $begin = microtime(true);

        $title_str = file_get_contents('../doc/title.txt');
        $titles = explode(PHP_EOL, $title_str);
        //dd($titles);
        $data = [];
        foreach ($titles as $title) {
            $rule = '#\(OGW(.*?)\)#i';
            if(preg_match_all($rule, $title, $matches)) {
                //var_dump($matches[1][0]);
                $data['OGW'.$matches[1][0]] = $title;
            }else{
                dd('error');
            }
        }

        $end    = microtime(true);
        return view('test.vue', [
            'data'  => $data,
            'pass'  => $end - $begin
        ]);
    }

    public function getFormData()
    {
        // 6.2	账户开立(OGW00042) （必选，跳转我行页面处理）
        $OGW00042 = array (
            'TRANSCODE' => 'OGW00042',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'TTRANS' => '6',
            'MERCHANTNAME' => 'P2P三分贷',
            'ACNAME' => '郝洪刚',//'黄耿嘉', //'刘通', //'蒋静',
            'IDTYPE' => '1010',
            'IDNO' => '232101198704080831 ',//'445202199002108316', //'210802198711174026', //'622224197808234020',
            'MOBILE' => '13661111999',
            'EMAIL' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'CUSTMNGRNO' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.4	绑卡(OGW00044)（可选，跳转我行页面处理）
        $OGW00044 = array (
            'TRANSCODE' => 'OGW00044',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'TTRANS' => '1',
            'ACNO' => '8970660100000014428',//'8970660100000014386',//'8970660100000014253',//'8970660100000013602',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.5	单笔专属账户充值(OGW00045) （跳转我行页面处理）
        $OGW00045 = array (
            'TRANSCODE' => 'OGW00045',
            'MERCHANTID' => '',
            'MERCHANTNAME' => 'P2P三分贷',
            'APPID' => 'PC',
            'TTRANS' => '7',
            'ACNO' => '8970660100000014428',//''8970660100000014386',//'8970660100000014253',//'8970660100000013602',
            'ACNAME' => '郝洪刚',//'黄耿嘉', //'刘通', //'蒋静',
            'AMOUNT' => '1000.00',
            'REMARK' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.7	单笔提现(OGW00047) （跳转我行页面处理）
        $OGW00047 = array (
            'TRANSCODE' => 'OGW00047',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'MERCHANTNAME' => '',
            'TTRANS' => '8',
            'ACNO' => '8970660100000014428',//'8970660100000014386',
            'ACNAME' => '郝洪刚',//'黄耿嘉',
            'AMOUNT' => '2.00',
            'REMARK' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.12	单笔投标 (OGW00052)（跳转我行页面处理）
        $OGW00052 = array (
            'TRANSCODE' => 'OGW00052',
            'MERCHANTID' => '',
            'MERCHANTNAME' => '',
            'APPID' => 'PC',
            'TTRANS' => '4',
            'LOANNO' => 'B20170614',
            'ACNO' => '8970660100000014428',
            'ACNAME' => '郝洪刚',
            'AMOUNT' => '1000.00',
            'REMARK' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P174201706140522EICjkuAkaI P2P17420170613052t35TBZTqjtB P2P17420170613052XnTv641JCIJ P2P17420170613052hyO3uTOuLLN | P2P17420170613052lBGaMN1ubBi P2P17420170613052fWXyBsNDSn8  P2P17420170613052emNf7m1XIim
        // 6.14	投标优惠返回（可选）(OGW00054)
        $OGW00054 = array (
            'TRANSCODE' => 'OGW00054',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B70612',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'AMOUNT' => '3.00',
            'TOTALNUM' => '1',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'FEEDBACKLIST' =>
                array (
                    'SUBSEQNO' => '052f3feBOsHrs5',
                    'OLDREQSEQNO' => 'P2P17420170612052f3feBOsHrs5',
                    'ACNO' => '8970660100000014428',
                    'ACNAME' => '郝洪刚',
                    'AMOUNT' => '3.00',
                    'REMARK' => '',
                    'EXT_FILED3' => '',
                ),
        );//P2P17420170613054SFaiyNrWtef
        // 6.16	自动投标授权(OGW00056) （可选，跳转我行页面处理）
        $OGW00056 = array (
            'TRANSCODE' => 'OGW00056',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'MERCHANTNAME' => '',
            'TTRANS' => '9',
            'ACNO' => '8970660100000014428',
            'ACNAME' => '郝洪刚',
            'REMARK' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );//P2P17420170613056dHRGlELT94D   P2P17420170613056DCLGlmSBjKy
        //6.22	债券转让申请(OGW00061) （可选，跳转我行页面处理）
        $OGW00061 = array (
            'TRANSCODE' => 'OGW00061',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'TTRANS' => '2',
            'OLDREQSEQNO' => 'P2P17420170613052fWXyBsNDSn8',//'P2P17420170613052emNf7m1XIim',
            'OLDREQNUMBER' => 'B201706131',
            'OLDREQNAME' => '三分房抵1601号',
            'ACCNO' => '8970660100000014428',
            'CUSTNAME' => '郝洪刚',
            'AMOUNT' => '500.00',
            'PREINCOME' => '3.00',
            'REMARK' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        ); //P2P17420170613061dMaWBQC7XmQ P2P17420170613061CN1NjNFFuK7 P2P17420170613061bOdQkeMQ7MO
        // 6.29 借款人单标还款 (OGW00067) （必须，跳转我行页面处理）
        $OGW00067 = array (
            'TRANSCODE' => 'OGW00067',
            'MERCHANTID' => '',
            'MERCHANTNAME' => '',
            'APPID' => 'PC',
            'TTRANS' => '5',
            'DFFLAG ' => '1',//'备注：1=正常还款 2=垫付后，借款人还款',
            'OLDREQSEQNO' => '',//'字段名称：原垫付请求流水号 | 类型：C（28） | 可空：是 | 备注：垫付还款时必需',
            'LOANNO' => 'B20170614',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'AMOUNT' => '100.00',
            'REMARK' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'FEEAMT' => '3.00',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P17420170614067QLqSQkvkrK0 P2P17420170613067kXeRqnHzy6O P2P17420170613067UK3WSa7Jcj7
        //6.31	自动还款授权 (OGW00069) （可选，跳转我行页面处理）
        $OGW00069 = array (
            'TRANSCODE' => 'OGW00069',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'MERCHANTNAME' => '',
            'TTRANS' => '10',
            'LOANNO' => 'B201706136',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'REMARK' => '',
            'RETURNURL' => 'http://121.43.37.74:8080/hxcalling/tx',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P17420170614069XqcBeOGzUVX P2P17420170614069EyGN84twDxY

        //dd(HXBankApi::getFormData($OGW00045));
        return view('test.form', [
            'data'  => HXBankApi::getFormData($OGW00067)
        ]);
    }


    public function request()
    {
        // 6.1 获取短信验证码(OGW00041)
        $OGW00041 = array (
            'TRANSCODE' => 'OGW00041',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'TRSTYPE' => '2',//'1：自动投标撤销 2：自动还款授权撤销 0：默认',
            'ACNO' => '8970660100000014386',//'8970660100000014428',//'备注：E账户账号，即关联账号. P2p商户必填',
            'MOBILE_NO' => '13661111888',//'13661111999',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.3	账户开立结果查询(OGW00043)
        $OGW00043 = array (
            'TRANSCODE' => 'OGW00043',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P174201706090426Zy2jY8e9W3',//'P2P17420170609044x1UCsr03uVE',//'P2P17420170609042yib7PfTmhVv',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.6	单笔充值结果查询 (OGW00046)
        $OGW00046 = array (
            'TRANSCODE' => 'OGW00046',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P174201706120458BQQtRZOn8x',//'P2P17420170609045rOyB2UX29UW',//'P2P17420170609045MxwKRXaYs8r',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.8 单笔提现结果查询(OGW00048)
        $OGW00048 = array (
            'TRANSCODE' => 'OGW00048',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'TRANSDT' => '',
            'OLDREQSEQNO' => 'P2P17420170612047CbsrWCjAGX9',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.9	账户余额查询(OGW00049)
        $OGW00049 = array (
            'TRANSCODE' => 'OGW00049',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'BUSTYPE' => '',
            'ACNO' => '8970660100000014428',
            'ACNAME' => '郝洪刚',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.10	账户余额校检(OGW00050)
        $OGW00050 = array (
            'TRANSCODE' => 'OGW00050',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'ACNO' => '8970660100000014428',
            'AMOUNT' => '2.00',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.11	单笔发标信息通知(OGW00051)
        $OGW00051 = array (
            'TRANSCODE' => 'OGW00051',
            'MERCHANTID' => '',
            'MERCHANTNAME' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B20170614',
            'INVESTID' => 'B20170614',
            'INVESTOBJNAME' => '三分房抵1401号',
            'INVESTOBJINFO' => '我公司是一家以销售建筑材料为主营业务的商贸公司，主要销售的建材是钢材和地板等。本人多年一直从事建筑行业，拥有丰富的行业经验，积累了广泛的人脉资源和渠道资源，与众多工程施工单位及企业等均有良好关系，合作稳定，销量有保障，收入稳定，还款没有太大压力。已与平台有过良好合作，信誉有保障，此次借款是经营进货资金周转，公司经营稳定，发展前景良好，同时提供了足值的房产作为抵押，不会逾期，希望大家继续支持。',
            'MININVESTAMT' => '100.00',
            'MAXINVESTAMT' => '1000.00',
            'INVESTOBJAMT' => '1000.00',
            'INVESTBEGINDATE' => date('Ymd'),
            'INVESTENDDATE' => '20170620',
            'REPAYDATE' => '20170920',
            'YEARRATE' => '10.00',
            'INVESTRANGE' => '30',
            'RATESTYPE' => '按月计息',
            'REPAYSTYPE' => '按月付息到期还本',
            'INVESTOBJSTATE' => '0',
            'BWTOTALNUM' => '1',
            'REMARK' => '',
            'ZRFLAG' => '0',
            'REFLOANNO' => '',
            'OLDREQSEQ' => '',
            'EXT_FILED1' => '',
            'BWLIST' =>
                array (
                    'BWACNAME' => '黄耿嘉',
                    'BWIDTYPE' => '1010',
                    'BWIDNO' => '445202199002108316',
                    'BWACNO' => '8970660100000014386',
                    'BWACBANKID' => '6212263602006485288',
                    'BWACBANKNAME' => '工商银行',
                    'BWAMT' => '1000.00',
                    'MORTGAGEID' => ''.date('Ymd'),
                    'MORTGAGEINFO' => '借款人抵押品简单描述',
                    'CHECKDATE' => '20170614',
                    'REMARK' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                ),
        ); // P2P17420170614051tObhNu6iQJT P2P17420170613051RMYD5EHQgvR P2P17420170613051TK0lG7P8Vla P2P17420170613051SUVR8auvtQQ
        // 6.13 单笔投标结果查询(OGW00053)
        $OGW00053 = array (
            'TRANSCODE' => 'OGW00053',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170613052fWXyBsNDSn8',//'P2P17420170613052emNf7m1XIim',//'P2P17420170612052f3feBOsHrs5',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );

        // 6.17	自动投标授权结果查询（可选）(OGW00057)
        $OGW00057 = array (
            'TRANSCODE' => 'OGW00057',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170613056NkwTg6dJ7Ru',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.18 自动投标授权撤销（可选）(OGW00058)
        $OGW00058 = array (
            'TRANSCODE' => 'OGW00058',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OTPSEQNO' => 'PAD2017061310565300072962',//
            'OTPNO' => '639124',//
            'ACNO' => '8970660100000014428',
            'ACNAME' => '郝洪刚',
            'REMARK' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.19	自动单笔投标（可选）(OGW00059)
        $OGW00059 = array (
            'TRANSCODE' => 'OGW00059',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B20170612',
            'ACNO' => '8970660100000014428',
            'ACNAME' => '郝洪刚',
            'AMOUNT' => '3.00',
            'REMARK' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.20 单笔撤标(OGW00060)
        $OGW00060 = array (
            'TRANSCODE' => 'OGW00060',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B20170612',
            'OLDREQSEQNO' => 'P2P17420170612052f3feBOsHrs5',
            'ACNO' => '8970660100000014428',
            'ACNAME' => '郝洪刚',
            'CANCELREASON' => '玩玩',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.27	放款(OGW00065)
        $OGW00065 = array (
            'TRANSCODE' => 'OGW00065',
            'MERCHANTID' => '',
            'MERCHANTNAME' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B20170614',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'ACMNGAMT' => '3.00',
            'GUARANTAMT' => '3.00',
            'REMARK' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        ); // P2P17420170614065ardIqu1RV7e P2P17420170613065bBFJYfpozUd P2P17420170613065KGed2Q4ZIQA
        // 6.28	放款结果查询 (OGW00066)
        $OGW00066 = array (
            'TRANSCODE' => 'OGW00066',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170613065bBFJYfpozUd',//'P2P17420170613065KGed2Q4ZIQA',//'P2P17420170613065Nwi0qFPytiQ',
            'LOANNO' => 'B201706136',
            'OLDTTJNL' => 'P2P17420170613051RMYD5EHQgvR',//'P2P17420170613051TK0lG7P8Vla',//'P2P17420170613052fWXyBsNDSn8',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.23	债券转让结果查询(OGW00062)
        $OGW00062 = array (
            'TRANSCODE' => 'OGW00062',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170613061dMaWBQC7XmQ',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.24	流标(OGW00063)
        $OGW00063 = array (
            'TRANSCODE' => 'OGW00063',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B201706132',
            'CANCELREASON' => '流标原因,玩玩',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        ); // P2P174201706130634lApaQio1XD P2P17420170613063sZMpYUe62Di
        // 6.26	流标结果查询 (OGW00064)
        $OGW00064 = array (
            'TRANSCODE' => 'OGW00064',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P174201706130634lApaQio1XD',//'P2P17420170613063sZMpYUe62Di',
            'OLDTTJNL' => 'P2P17420170613051SUVR8auvtQQ',//'P2P17420170613052emNf7m1XIim',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P174201706130666ql4vNTWSBe
        // 6.30 借款人单标还款结果查询(OGW00068)
        $OGW00068 = array (
            'TRANSCODE' => 'OGW00068',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170613067kXeRqnHzy6O',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.32	自动还款授权结果查询（可选）(OGW00070)
        $OGW00070 = array (
            'TRANSCODE' => 'OGW00070',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170614069EyGN84twDxY',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P17420170614070UMlhgXtGWrv
        // 6.33 自动还款授权撤销（可选）(OGW00071)
        $OGW00071 = array (
            'TRANSCODE' => 'OGW00071',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OTPSEQNO' => 'PAD2017061409142900073789',
            'OTPNO' => '970981',
            'LOANNO' => 'B201706136',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'REMARK' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P17420170614071UVmwusdxJfY
        // 6.34	自动单笔还款（可选）(OGW00072)
        $OGW00072 = array (
            'TRANSCODE' => 'OGW00072',
            'MERCHANTID' => '',
            'MERCHANTNAME' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B201706136',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'FEEAMT' => '3.00',
            'AMOUNT' => '10.00',
            'REMARK' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P17420170614072bARk0Hz0wsj
        //6.35	单标公司垫付还款(OGW00073)
        $OGW00073 = array (
            'TRANSCODE' => 'OGW00073',
            'MERCHANTID' => '',
            'MERCHANTNAME' => '',
            'APPID' => 'PC',
            'LOANNO' => 'B201706136',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'AMOUNT' => '10.00',
            'REMARK' => '',
            'FEEAMT' => '3.00',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );
        // 6.36 还款收益明细提交(OGW00074)
        $OGW00074 = array (
            'TRANSCODE' => 'OGW00074',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170614067QLqSQkvkrK0', //字段名称：原还款交易流水号 | 类型：C(28） | 可空：否 | 备注：
            'DFFLAG' => '1', //备注：1=正常还款 2=垫付后，借款人还款
            'LOANNO' => 'B20170614',
            'BWACNAME' => '黄耿嘉',
            'BWACNO' => '8970660100000014386',
            'TOTALNUM' => '1', //字段名称：总笔数 | 类型：C(10) | 可空：否 | 备注：整型
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'REPAYLIST' =>
                array (
                    'SUBSEQNO' => 'SFDQLqSQkvkrK0',
                    'ACNO' => '8970660100000014428',
                    'ACNAME' => '郝洪刚',
                    'INCOMEDATE' => '20170920',
                    'AMOUNT' => '103.00',
                    'PRINCIPALAMT' => '100.00',
                    'INCOMEAMT' => '2.00',
                    'FEEAMT' => '3.00',
                    'EXT_FILED3' => '',
                ),
        );//P2P17420170614074RzLd5PZQfDx P2P17420170614074lIwDWqkPHaH P2P17420170614074aujbgjOXTrZ
        // 6.37 还款收益结果查询 (OGW00075)
        $OGW00075 = array (
            'TRANSCODE' => 'OGW00075',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OLDREQSEQNO' => 'P2P17420170614074RzLd5PZQfDx',
            'LOANNO' => 'B20170614',
            'SUBSEQNO' => 'SFDQLqSQkvkrK0',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        ); // P2P17420170614075z3mVP1uB6Gn
        // 6.38 单笔奖励或分红（可选）(OGW00076)
        $OGW00076 = array (
            'TRANSCODE' => 'OGW00076',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'ACNO' => '8970660100000014428',
            'ACNAME' => '郝洪刚',
            'AMOUNT' => '10.00',
            'REMARK' => '',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );// P2P17420170614076mjio3hSJ1aa
        // 6.39 日终对账请求(OGW00077)
        $OGW00077 = array (
            'TRANSCODE' => 'OGW00077',
            'MERCHANTID' => '',
            'APPID' => 'PC',
            'OPERFLAG' => '0',
            'CHECKDATE' => '20170614',
            'EXT_FILED1' => '',
            'EXT_FILED2' => '',
            'EXT_FILED3' => '',
        );

        dd(HXBankApi::request($OGW00077));
    }

    public function tx(Request $request)
    {
        return response()->json($request->all());

        print_r($request->all());
        //dd($request);
        //echo $request->method();
        exit;

        /*$post = [
            'RequestData' => $request->input('RequestData'),
            'transCode' => $request->input('transCode')
        ];
        echo json_encode($post);
        //return response()->json($post);
        exit;*/

        HXBankApi::getBackReqData();
    }

    public function post()
    {
        $url = url('hxcalling/tx');
        $post = [
            'RequestData'   => '001X11          0000025620F051CE06E1CB555EE119DF98DA756DC34FDA709FDB723168830DCBF3721B41D700926DDA9EF0D25992EAA8848B3510545FB3DA28B68DABD08A22055E8D7D2F9766B34E2F0ECA0957334B388D51D1DA8D6E4F21FAEC1B8596246FC69885F1CA9AC87A079AB7832900C94C25A4B19C9778275B4AA6BFAD61C20D12CE786688AC<?xml version="1.0" encoding="utf-8"?><Document><header><channelCode>P2P174</channelCode><channelFlow>P2P17420170612045Ui6BpyMfIoI</channelFlow><channelDate>20170612</channelDate><channelTime>033242</channelTime><encryptData></encryptData></header><body><TRANSCODE>OGW00045</TRANSCODE><XMLPARA>+X2br6lee+grGi6GWUfT+UbDSfsoB2yQDEw+geQXLwbGmRvSusD9zEcIsSGuHmg9rXi+dJuNuK4jkob5tK5IRurERamuYDH4lTsp+qz0MbZPEKpNek0mFMJHek7JsJ4NfTWDwYsi9zPUMc88MMsP3q92OU3N5/s7wMSHBmzh2umhpHdW7pISxfuI0/w+w//lLHDhY61GOfW+BD8i1r8VD/ravmH6qZ6MmkVX3VP4UJb0Jr/lxdIBQQSnRKF/k0ObJR3A9V+1PdaUL3xBokWSUnSLwAxs7pIAjCFqXIFJBSNc3gaz/xA296A5C8MInfOvgsEV6LUm5yZVsI5h0lcrevfCirlk1zbQY/5FAz00u7+L/6pECOj+PvM83b2q5HPI3cY1iThbmEmCbiSxiHCJdG4YZAAjrxP2IG5adTlxDsgnoLepo/Vo6GbgwT0cSYcvjV+Anjx7ww4=</XMLPARA></body></Document>',
            'transCode'     => 'OGW00047',
        ];
        //dd($url, $post);
        dd(HttpRequest::to($url, $post));
        //dd(HttpRequest::toFormat($url, $post));

        return view('test.request',[

        ]);
    }




    /**
     * ----------------------------------------------------------
     * @param Request $request
     * @return mixed
     */
    public function index2(Request $request)
    {
        $login_url = 'http://m.istarshine.com/LoginOK!login.do';
        $post = array(
            'userid' => '清华大学',
            'passwd' => '1234'
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
        //$filePath = 'storage/exports/123.xlsx';
        $filePath = 'doc/Api_array.xlsx';
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
                            $remarks[$i] = ' //'.
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
                //$file   = '../storage/exports/array.php';
                $file       = '../doc/Api_array.php';
                $tit_file   = '../doc/title.txt';//----
                $usage  = 'HXBandApi::request('.$arrayName.'))';
                $usage2 = 'HXBankApi::getFormData('.$arrayName.'))';
                file_put_contents($file, $this->remarks($title), FILE_APPEND);
                file_put_contents($tit_file, $title.PHP_EOL, FILE_APPEND);//----
                file_put_contents($file, $this->remarks($desc), FILE_APPEND);
                file_put_contents($file, $arrayName.' = '.var_export($rData, true).';'.PHP_EOL, FILE_APPEND);
                !$async && file_put_contents($file, $this->remarks($usage, 'Usage: '), FILE_APPEND);
                $async && file_put_contents($file, $this->remarks($usage2, 'Usage: '), FILE_APPEND);
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

    public function putIp() {

        $data = [
            [
                'ip_src'    => '193.107.172.15',
                'att_times' => 24,
                'time1'     => '2017-06-13 09:24:19',
                'time2'     => '2017-06-13 09:36:23'
            ],
            [
                'ip_src'    => '221.234.167.61',
                'att_times' => 2,
                'time1'     => '2017-06-09 18:19:52',
                'time2'     => '2017-06-09 18:19:52'
            ],
            [
                'ip_src'    => '58.48.232.114',
                'att_times' => 1,
                'time1'     => '2017-05-24 01:19:01',
                'time2'     => '2017-05-24 01:19:01'
            ],
            [
                'ip_src'    => '118.184.35.238',
                'att_times' => 311,
                'time1'     => '2017-05-16 01:27:57',
                'time2'     => '2017-05-16 01:35:26'
            ],
            [
                'ip_src'    => '103.249.162.211',
                'att_times' => 14,
                'time1'     => '2017-05-03 22:50:23',
                'time2'     => '2017-05-03 22:50:36'
            ],
            [
                'ip_src'    => '103.249.162.21',
                'att_times' => 12,
                'time1'     => '2017-05-03 02:59:48',
                'time2'     => '2017-05-03 02:59:51'
            ],
            [
                'ip_src'    => '103.249.162.21',
                'att_times' => 6,
                'time1'     => '2017-05-03 02:59:48',
                'time2'     => '2017-05-03 02:59:51'
            ],
            [
                'ip_src'    => '118.252.30.62',
                'att_times' => 29,
                'time1'     => '2017-05-02 13:22:43',
                'time2'     => '2017-05-02 13:26:39'
            ],
            [
                'ip_src'    => '113.223.241.199',
                'att_times' => 22,
                'time1'     => '2017-05-02 13:22:31',
                'time2'     => '2017-05-02 13:26:36'
            ],
            [
                'ip_src'    => '118.252.227.166',
                'att_times' => 31,
                'time1'     => '2017-05-02 13:22:41',
                'time2'     => '2017-05-02 13:26:30'
            ],
            [
                'ip_src'    => '118.248.5.219',
                'att_times' => 45,
                'time1'     => '2017-05-02 13:22:19',
                'time2'     => '2017-05-02 13:26:09'
            ],
            [
                'ip_src'    => '118.252.49.85',
                'att_times' => 15,
                'time1'     => '2017-05-02 13:22:34',
                'time2'     => '2017-05-02 13:26:05'
            ],
            [
            'ip_src'    => '58.221.44.73',
                'att_times' => 89,
                'time1'     => '2017-05-01 17:00:50',
                'time2'     => '2017-05-01 17:01:24'
            ],
            [
                'ip_src'    => '103.249.162.21',
                'att_times' => 14,
                'time1'     => '2017-05-01 04:28:59',
                'time2'     => '2017-05-01 04:29:13'
            ],
            [
                'ip_src'    => '119.98.120.29',
                'att_times' => 2,
                'time1'     => '2017-04-26 00:24:44',
                'time2'     => '2017-04-26 00:24:44'
            ],
            [
                'ip_src'    => '62.28.167.246',
                'att_times' => 1,
                'time1'     => '2017-04-22 09:08:00',
                'time2'     => '2017-04-22 09:08:00'
            ],
            [
                'ip_src'    => '63.141.246.75',
                'att_times' => 85,
                'time1'     => '2017-04-21 12:09:51',
                'time2'     => '2017-04-21 12:11:00'
            ],
        ];


        foreach ($data as $rec) {
            $ip = new Ip();
            $ip->ip_src     = @inet_pton($rec['ip_src']);
            $ip->att_times  = $rec['att_times'];
            $ip->time1      = strtotime($rec['time1']);
            $ip->time2      = strtotime($rec['time2']);
            //dd($ip);
            //$ip->save();
            /*IP::create([
                'ip_src'    => @inet_pton($rec['ip_src']),
                'att_times' => $rec['att_times'],
                'time1'     => strtotime($rec['time1']),
                'time2'     => strtotime($rec['time2'])
            ]);*/
        }
        dd('success!');

    }

    public function aliyun()
    {
        dd(1);
    }

    public function cors(Request $request)
    {
        $ips = Ip::get();
        $data = []; $i = 0;
        //dd($ips);
        foreach ($ips as $item) {
            $data[$i]['id'] = $item->id;
            $data[$i]['ip_src'] = @inet_ntop($item->ip_src);
            $data[$i]['att_times'] = $item->att_times;
            $data[$i]['time1'] = date('Y-m-d H:i:s', $item->time1);
            $data[$i++]['time2'] = date('Y-m-d H:i:s', $item->time2);
        }
        //dd($data);

        header('Content-type: application/json;charset=UTF-8');
        header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        return response()->json($data);
        //echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    public function onLogin(Request $request)
    {
        $code = $request->input('code');
        if(!empty($code)){
            //wx3aed9fe20f883ac8 / d2faf1607c212876a1f123af200d501b
            $appid  = 'wx3aed9fe20f883ac8';
            $secret = 'd2faf1607c212876a1f123af200d501b';
            $url    = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';

            $http   = new Client();
            $response = $http->get($url);
            $body = json_decode((string) $response->getBody(), true);
            //dump($accessToken);
            return response()->json(['body'=>$body]);
        }else{
            return response()->json(['abc'=>1]);
        }
    }

    /*onLaunch: function () {
        //调用API从本地缓存中获取数据
        var logs = wx.getStorageSync('logs') || []
        logs.unshift(Date.now())
        wx.setStorageSync('logs', logs)

        //var that = this
        //this.store = new(jsonApi.JsonApiDataStore)
        //this.jsonModel = jsonApi.JsonApiDataStoreModel
        //this.globalData.code = wx.getStorageSync('code')
        wx.request({
            url     : 'https://yuan.tyoupub.com/oauth/token',
            method  : 'POST',
            data    : {
                'grant_type'    : 'password',
                'client_id'     : '2',
                'client_secret' : 'kBHofTIZ7pJJ0UJDJvQzNozge6LErViI51B4QAUX',
                'username'      : 'admin',//'you.ch@hotmail.com',
                'password'      : 'admin123',
                'scope'         : '*',
            },
            success: function(res) {
                console.log(res.data)
                wx.setStorageSync('oauth', res.data)
                console.log('Bearer '+ wx.getStorageSync('oauth').access_token)


                wx.login({
                    success: function(res) {
                        if (res.code) {
                            //发起网络请求
                            wx.request({
                                url     : 'https://yuan.tyoupub.com/api/wxs/login',
                                header  : {
                                    'Accept'        : 'application/json',
                                    'Authorization' : 'Bearer '+ wx.getStorageSync('oauth').access_token
                                },
                                data    : {
                                    code: res.code
                                },
                                success : function(res) {
                                    console.log(res.data)
                                    wx.setStorageSync('logInfo', res.data.body)
                                }
                            })
                        } else {
                            console.log('获取用户登录态失败！' + res.errMsg)
                        }
                    }
                });



            }
        })

    },*/
}
