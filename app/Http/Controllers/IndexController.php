<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 14:53
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function index(){

        $acid = DB::connection('alienvault_siem')->table('acid_event')
            ->where(['plugin_id'=>'1001'])
            ->offset(0)
            ->limit(50)
            ->get();
        //var_dump($acid);exit;

        $geoCoordMap = ['郑州' => array(113.4668, 34.6234)]; $c = 0;
        foreach ($acid as $ac) {
            //var_dump($ac); exit;
            $res = geoip_record_by_name(@inet_ntop($ac->ip_src));
            //var_dump($res);exit;
            if(isset($res['country_code3'])  && $res['country_code3'] != "CHN"){
                //var_dump($res);exit;
                //$res->city = $res->region=='QC'?'Quebec':$res->city; // 特殊处理 魁北克 Qu�bec
                //$res->city = $res->region=='34'?'Bogota':$res->city; // 特殊处理 圣菲波哥大 Bogot�
                $city = empty($res['city']) ? $res['country_name'] : $res['city'];//'Unknown';//$res->country_name
                $geoCoordMap[$city] = [$res['longitude'], $res['latitude']];
                $zzData[] = [
                    ['name'=>'郑州'],
                    ['name'=>$city, 'value'=>80, 'ip_src'=>@inet_ntop($ac->ip_src), 'ip_dst'=>@inet_ntop($ac->ip_dst), 'classtype'=>'zy'], //$ac->classtype
                ];
                //$tbData[] = $ac;
                if(($c++) >= 9) {
                    //var_dump($c);exit;
                    break;
                }

            }

        }

        //var_dump($geoCoordMap, $zzData); //, json_encode($tbData, true)
        //var_dump(json_encode($geoCoordMap), json_encode($zzData)); //, json_encode($tbData, true)
        //exit;

        return view('index', [
            'title'         => 'ACID',
            'geoCoordMap'   => json_encode($geoCoordMap),
            'zzData'        => json_encode($zzData),
        ]);
    }


}
