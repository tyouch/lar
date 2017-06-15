<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\Sumapay\HXBankApi;
use App\Lib\Sumapay\HttpRequest;

class HxCallingController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * 同步请求
     * @param $data
     */
    public function sysRequest($data)
    {
        dd(HXBankApi::request($data));
    }

    /**
     * 异步请求
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function makeForm($data)
    {
        return view('test.form', [
            'data'  => HXBankApi::getFormData($data)
        ]);
    }

    public function ogw000($id)
    {
        $syn = true; //dd($id);
        switch($id) {
            case '41': // 6.1	获取短信验证码(OGW00041)
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
                $syn = true; break;

            case '42': // 6.2	账户开立(OGW00042) （必选，跳转我行页面处理）
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
                $syn = false; break;

            case '43':
                $OGW00043 = array (
                    'TRANSCODE' => 'OGW00043',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P174201706090426Zy2jY8e9W3',//'P2P17420170609044x1UCsr03uVE',//'P2P17420170609042yib7PfTmhVv',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );
                $syn = true; break;

            case '44': // 6.4	绑卡(OGW00044)（可选，跳转我行页面处理）
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
                $syn = false; break;

            case '45': // 6.5	单笔专属账户充值(OGW00045) （跳转我行页面处理）
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
                $syn = false; break;

            case '46': // 6.6	单笔充值结果查询 (OGW00046)
                $OGW00046 = array (
                    'TRANSCODE' => 'OGW00046',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P174201706120458BQQtRZOn8x',//'P2P17420170609045rOyB2UX29UW',//'P2P17420170609045MxwKRXaYs8r',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );
                $syn = true; break;

            case '47': // 6.7	单笔提现(OGW00047) （跳转我行页面处理）
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
                $syn = false; break;

            case '48': // 6.8 单笔提现结果查询(OGW00048)
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
                $syn = true; break;

            case '49': // 6.9	账户余额查询(OGW00049)
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
                $syn = true; break;

            case '50': // 6.10	账户余额校检(OGW00050)
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
                $syn = true; break;

            case '51': // 6.11	单笔发标信息通知(OGW00051)
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
                $syn = true; break;

            case '52': // 6.12	单笔投标 (OGW00052)（跳转我行页面处理）
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
                $syn = false; break;

            case '53': // 6.13 单笔投标结果查询(OGW00053)
                $OGW00053 = array (
                    'TRANSCODE' => 'OGW00053',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P17420170613052fWXyBsNDSn8',//'P2P17420170613052emNf7m1XIim',//'P2P17420170612052f3feBOsHrs5',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );
                $syn = true; break;

            case '54': // 6.14	投标优惠返回（可选）(OGW00054)
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
                $syn = true; break;

            case '55': //6.15	投标优惠返回结果查询（可选）(OGW00055)
                $OGW00055 = array (
                    'TRANSCODE' => 'OGW00055',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P17420170613054SFaiyNrWtef',
                    'SUBSEQNO' => '052f3feBOsHrs5',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );
                $syn = true; break;

            case '56': // 6.16	自动投标授权(OGW00056) （可选，跳转我行页面处理）
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
                $syn = false; break;

            case '57': // 6.17	自动投标授权结果查询（可选）(OGW00057)
                $OGW00057 = array (
                    'TRANSCODE' => 'OGW00057',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P17420170613056NkwTg6dJ7Ru',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );
                $syn = true; break;

            case '58': // 6.18 自动投标授权撤销（可选）(OGW00058)
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
                $syn = true; break;

            case '59': // 6.19	自动单笔投标（可选）(OGW00059)
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
                $syn = true; break;

            case '60': // 6.20 单笔撤标(OGW00060)
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
                $syn = true; break;

            case '61': //6.22	债券转让申请(OGW00061) （可选，跳转我行页面处理）
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
                $syn = false; break;

            case '62': // 6.23	债券转让结果查询(OGW00062)
                $OGW00062 = array (
                    'TRANSCODE' => 'OGW00062',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P17420170613061dMaWBQC7XmQ',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );
                $syn = true; break;

            case '63': // 6.24	流标(OGW00063)
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
                $syn = true; break;

            case '64': // 6.26	流标结果查询 (OGW00064)
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
                $syn = true; break;

            case '65': // 6.27	放款(OGW00065)
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
                $syn = true; break;

            case '66': // 6.28	放款结果查询 (OGW00066)
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
                $syn = true; break;

            case '67': // 6.29 借款人单标还款 (OGW00067) （必须，跳转我行页面处理）
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
                $syn = false; break;

            case '68': // 6.30 借款人单标还款结果查询(OGW00068)
                $OGW00068 = array (
                    'TRANSCODE' => 'OGW00068',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P17420170613067kXeRqnHzy6O',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );
                $syn = true; break;

            case '69': //6.31	自动还款授权 (OGW00069) （可选，跳转我行页面处理）
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
                $syn = false; break;

            case '70': // 6.32	自动还款授权结果查询（可选）(OGW00070)
                $OGW00070 = array (
                    'TRANSCODE' => 'OGW00070',
                    'MERCHANTID' => '',
                    'APPID' => 'PC',
                    'OLDREQSEQNO' => 'P2P17420170614069EyGN84twDxY',
                    'EXT_FILED1' => '',
                    'EXT_FILED2' => '',
                    'EXT_FILED3' => '',
                );// P2P17420170614070UMlhgXtGWrv
                $syn = true; break;

            case '71': // 6.33 自动还款授权撤销（可选）(OGW00071)
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
                $syn = true; break;

            case '72': // 6.34	自动单笔还款（可选）(OGW00072)
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
                $syn = true; break;

            case '73': //6.35	单标公司垫付还款(OGW00073)
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
                $syn = true; break;

            case '74': // 6.36 还款收益明细提交(OGW00074)
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
                $syn = true; break;

            case '75': // 6.37 还款收益结果查询 (OGW00075)
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
                $syn = true; break;

            case '76': // 6.38 单笔奖励或分红（可选）(OGW00076)
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
                $syn = true; break;

            case '77': // 6.39 日终对账请求(OGW00077)
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
                $syn = true; break;

            default:
        }
        //$data = 'OGW000'.$id; //dd($$data);
        return !empty($syn) ? $this->sysRequest(${'OGW000'.$id}) : $this->makeForm(${'OGW000'.$id});
    }

    /**
     * 回调
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * 测试提交
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
}
