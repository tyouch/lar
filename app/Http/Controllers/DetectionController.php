<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ip;

class DetectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function map(Request $request)
    {

        $begin = microtime(true);
        $ips = Ip::orderBy('time1', 'desc')->get();
        $ip_dst = '47.93.62.221';
        //dd($ips);

        $data = []; $i = 0; $c = 0;
        $geoCoordMap = ['Beijing' => array(116.4551, 40.2539)];
        foreach ($ips as $ip) {
            //var_dump($ac); exit;
            $res = geoip_record_by_name(@inet_ntop($ip->ip_src));
            //var_dump($res);
            if(1){//isset($res['country_code3'])  && $res['country_code3'] == "CHN"
                //var_dump($res);exit;
                //$res->city = $res->region=='QC'?'Quebec':$res->city; // 特殊处理 魁北克 Qu�bec
                //$res->city = $res->region=='34'?'Bogota':$res->city; // 特殊处理 圣菲波哥大 Bogot�
                //$classtype = substr($ip->pluginSid->name, strpos($ip->pluginSid->name, '"')+1, strlen($ip->pluginSid->name)-19);
                //$classType = trim(ltrim($ip->pluginSid->name, 'AlienVault NIDS: '), '"');
                $city = empty($res['city']) ? $res['country_name'] : $res['city'];//'Unknown';//$res->country_name
                $geoCoordMap[$city] = [$res['longitude'], $res['latitude']];
                $zzData[] = [
                    ['name'=>'Beijing'],
                    ['name'=>$city, 'value'=>$ip->att_times, 'ip_src'=>@inet_ntop($ip->ip_src), 'ip_dst'=>$ip_dst, 'att_times'=>$ip->att_times],
                ];

                $data[$i]['time1'] = date('Y-m-d H:i:s', $ip->time1);
                $data[$i]['time2'] = date('Y-m-d H:i:s', $ip->time2);
                $data[$i]['ip_src'] = @inet_ntop($ip->ip_src);
                $data[$i]['ip_dst'] = $ip_dst;
                $data[$i++]['att_times'] = $ip->att_times;

                if(($c++) >= 20) {
                    //var_dump($c);exit;
                    break;
                }
            }
        }

        //dd(json_encode($geoCoordMap, JSON_UNESCAPED_UNICODE), json_encode($zzData, JSON_UNESCAPED_UNICODE)); //, json_encode($tbData, true)
        //exit;

        if($request->input('test')){
            return response()->json([
                'msg'       => 'ok',
                'status'    => 0,
                'data'      => $data
            ]);
        }

        $end    = microtime(true);
        return view('detection.map', [
            'title'         => 'ACID',
            'geoCoordMap'   => json_encode($geoCoordMap, JSON_UNESCAPED_UNICODE),
            'zzData'        => json_encode($zzData, JSON_UNESCAPED_UNICODE),
            'pass'          => $end - $begin
        ]);
    }
}
