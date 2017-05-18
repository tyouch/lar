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
use App\Models\Acid_event;
//use App\Models\Plugin_sid;

class IndexController extends Controller
{

    public function index(Request $request){

        $begin = microtime(true);
        /*"
        //$this->alienvault_siem->where('inet6_ntoa(ip_src) !=', '0.0.0.0');
        //$this->alienvault_siem->where('layer4_dport !=', 0);
        //$this->alienvault_siem->order_by('timestamp', 'desc');
        //$this->alienvault_siem->limit(20);

        SELECT hex(e.id), inet6_ntoa(ip_src) ip_src, inet6_ntoa(ip_dst) ip_dst, layer4_sport, layer4_dport, convert_tz(e.timestamp,'+00:00','+08:00') timestamp, e.plugin_id, e.plugin_sid, p.name classtype, ossim_risk_a, ossim_risk_c
        FROM acid_event e LEFT JOIN alienvault.plugin_sid p ON e.plugin_sid=p.sid
        WHERE e.plugin_id=1001
        ORDER BY timestamp desc
        "*/

        /*$acids = DB::connection('alienvault_siem')->table('acid_event')
            //->leftJoin(DB::raw('plugin_sid'), DB::raw('acid_event.plugin_sid'), '=', DB::raw('plugin_sid.sid'))
            ->select(DB::raw('ip_src, ip_dst, layer4_sport, layer4_dport, plugin_sid, timestamp'))
            ->where(['plugin_id'=>'1001'])
            ->offset(0)
            ->limit(50)
            ->orderBy('timestamp', 'desc')
            ->get();
        //var_dump($acids);exit;*/


        $acids = Acid_event::with('pluginSid')
            ->select(DB::raw('ip_src, ip_dst, layer4_sport, layer4_dport, plugin_sid, timestamp'))
            ->where(['plugin_id'=>'1001'])
            ->offset(0)
            ->limit(50)
            ->orderBy('timestamp', 'desc')
            ->get();

        //var_dump($acids[0]->pluginSid->name);
        //var_dump($acids); exit;

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
                //$classtype = substr($acid->pluginSid->name, strpos($acid->pluginSid->name, '"')+1, strlen($acid->pluginSid->name)-19);
                $classType = trim(ltrim($acid->pluginSid->name, 'AlienVault NIDS: '), '"');
                $city = empty($res['city']) ? $res['country_name'] : $res['city'];//'Unknown';//$res->country_name
                $geoCoordMap[$city] = [$res['longitude'], $res['latitude']];
                $zzData[] = [
                    ['name'=>'郑州'],
                    ['name'=>$city, 'value'=>80, 'ip_src'=>@inet_ntop($acid->ip_src), 'ip_dst'=>@inet_ntop($acid->ip_dst), 'classtype'=>$classType], //$acid->classtype
                ];

                $data[$i]['timestamp'] = $acid->timestamp;
                $data[$i]['ip_src'] = @inet_ntop($acid->ip_src);
                $data[$i]['ip_dst'] = @inet_ntop($acid->ip_dst);
                $data[$i]['layer4_sport'] = $acid->layer4_sport;
                $data[$i]['layer4_dport'] = $acid->layer4_dport;
                $data[$i]['plugin_sid'] = $acid->plugin_sid;
                $data[$i++]['classtype'] = $classType;

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

        $end    = microtime(true);
        return view('index', [
            'title'         => 'ACID',
            'geoCoordMap'   => json_encode($geoCoordMap),
            'zzData'        => json_encode($zzData),
            'pass'          => $end - $begin
        ]);
    }

    public function test()
    {
        var_dump('index-test');
    }

}
