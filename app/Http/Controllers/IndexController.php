<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 14:53
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function index(Request $request){

        $acids = DB::connection('alienvault_siem')->table('acid_event')
            ->where(['plugin_id'=>'1001'])
            ->offset(0)
            ->limit(50)
            ->orderBy('timestamp', 'desc')
            ->get();
        //var_dump($acids);exit;


        $data = []; $i = 0; $c = 0;
        $geoCoordMap = ['郑州' => array(113.4668, 34.6234)];
        foreach ($acids as $acid) {
            //var_dump($ac); exit;
            $res = geoip_record_by_name(@inet_ntop($acid->ip_src));
            //var_dump($res);exit;
            if(isset($res['country_code3'])  && $res['country_code3'] != "CHN"){
                //var_dump($res);exit;
                //$res->city = $res->region=='QC'?'Quebec':$res->city; // 特殊处理 魁北克 Qu�bec
                //$res->city = $res->region=='34'?'Bogota':$res->city; // 特殊处理 圣菲波哥大 Bogot�
                $city = empty($res['city']) ? $res['country_name'] : $res['city'];//'Unknown';//$res->country_name
                $geoCoordMap[$city] = [$res['longitude'], $res['latitude']];
                $zzData[] = [
                    ['name'=>'郑州'],
                    ['name'=>$city, 'value'=>80, 'ip_src'=>@inet_ntop($acid->ip_src), 'ip_dst'=>@inet_ntop($acid->ip_dst), 'classtype'=>'zy'], //$acid->classtype
                ];

                $data[$i]['timestamp'] = $acid->timestamp;
                $data[$i]['ip_src'] = @inet_ntop($acid->ip_src);
                $data[$i]['ip_dst'] = @inet_ntop($acid->ip_dst);
                $data[$i]['layer4_sport'] = $acid->layer4_sport;
                $data[$i]['layer4_dport'] = $acid->layer4_dport;
                $data[$i]['plugin_sid'] = $acid->plugin_sid;
                $data[$i++]['classtype'] = 'unknown';

                if(($c++) >= 9) {
                    //var_dump($c);exit;
                    break;
                }

            }

        }
        //var_dump($data);exit;
        //var_dump($geoCoordMap, $zzData); //, json_encode($tbData, true)
        //var_dump(json_encode($geoCoordMap), json_encode($zzData)); //, json_encode($tbData, true)
        //exit;

        if($request->input('test')){
            return response()->json([
                'msg'       => 'ok',
                'status'    => 0,
                'data'      => $data
            ]);
        }

        return view('index', [
            'title'         => 'ACID',
            'geoCoordMap'   => json_encode($geoCoordMap),
            'zzData'        => json_encode($zzData),
        ]);
    }

    public function test()
    {
        var_dump('index-test');
    }

}
