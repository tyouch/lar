<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class HostsController extends Controller
{
    public function index(Request $request)
    {
        $begin = microtime(true);

        /*$pw = 'admin123';
        $ph = password_hash($pw, PASSWORD_BCRYPT);
        $salt = password_verify($pw, $ph);

        var_dump($pw, $ph, $salt);exit;*/


        $qs = explode('/', $request->path());
        $p = $qs[1];
        $ps = 2;
        $pb = ($p-1)*$ps;

        if(!empty($qs[2])) {
            $filter = "host_detail.city like '%".urldecode($qs[2])."%'";
        }else{
            $filter = 1;
        }

        //var_dump($qs);exit;
        $hosts = DB::connection('alienvault')->table('host')
            ->select(DB::raw('hex(host.id) as id, host_detail.*'))
            ->leftJoin('host_detail', DB::raw('hex(host.id)'), '=', 'host_detail.host_id')
            //->where(DB::raw($filter))
            ->offset($pb)
            ->limit($ps)
            ->orderBy('host_detail.createtime')
            ->get();

        //$i = 0;
        //foreach ($hosts as $host) {
            //$hosts[$i++]->ip = @inet_ntop($host->ip);
        //}
            /*->table('host_detail')
            //->paginate($ps);
            ->get()->toArray();
            ->select('
                select hex(h.id) id, d.*  
                from host h left join host_detail d on hex(h.id)=d.host_id 
                limit :b, :e', [
                    'b'         => $pb,
                    'e'         => $ps
            ]);*/
        $hosts = json_decode(json_encode($hosts), true);
        //var_dump($hosts); exit;


        $service = DB::connection('alienvault')
            ->select('
                select hex(h.id) id, s.port, s.protocol, s.service, s.version 
                from host h left join host_services s on h.id=s.host_id
            ');

        $i = 0;
        //var_dump($service); exit;
        foreach ($hosts as $host) {
            //var_dump($host);
            $d3 = []; $j = 0;
            foreach ($service as $srv) {
                if($host['id'] == $srv->id) { //id
                    //var_dump($j);exit;
                    $d3[$j]['service'] = $srv->service;
                    $d3[$j]['port'] = $srv->port;
                    $d3[$j++]['version'] = $srv->version;
                }
            }
            $hosts[$i++]['d3'] = $d3;
        }
        //var_dump($hosts); exit;


        // 服务统计
        $ServiceRatio = DB::connection('alienvault')
            ->select('select count(*) as value,service as name from host_services group by name order by value desc');
        $ServiceRatio = json_encode($ServiceRatio);

        // 系统统计
        $OSRatio = DB::connection('alienvault')
            ->select('select count(*) as value, os as name from host_detail group by os order by value desc');
        $i = 0;
        foreach ($OSRatio as $item) {
            $OSRatio[$i++]->name = empty($item->name)?'Other':$item->name;
        }
        $OSRatio = json_encode($OSRatio);
        //var_dump($ServiceRatio, $OSRatio);exit;

        $end    = microtime(true);
        return view('hosts.index', [
            'hosts'         => $hosts,
            'ServiceRatio'  => $ServiceRatio,
            'OSRatio'       => $OSRatio,
            'p'             => $p,
            'pm'            => ceil(DB::connection('alienvault')->table('host_detail')->count()/$ps),
            'pass'          => $end - $begin
        ]);
    }
}
