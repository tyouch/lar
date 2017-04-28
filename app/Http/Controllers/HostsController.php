<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostsController extends Controller
{
    public function index()
    {
        $hosts = DB::connection('alienvault')
            /*->table('host')
            ->leftJoin('host_detail', 'hex(id)', '=', 'host_id')
            ->offset(0)
            ->limit(2)
            ->get();*/
            ->select('
                select hex(h.id) id, d.*  
                from host h left join host_detail d on hex(h.id)=d.host_id limit :b, :e', [
                'b'=>0,
                'e'=>2
            ]);

        /*$service = DB::connection('alienvault')
            ->select('
                select hex(h.id) id, s.port, s.protocol, s.service, s.version 
                from host h left join host_services s on h.id=s.host_id
            ');


        $data = $hosts;  $i = 0;
        //var_dump($data); exit;
        foreach ($hosts as $host) {
            //var_dump($host);
            $d3 = array(); $j = 0;
            foreach ($service as $srv) {

                if($host->id == $srv->id) { //id

                    //var_dump($j);exit;
                    $d3[$j]['service'] = $srv->service;
                    $d3[$j]['port'] = $srv->port;
                    $d3[$j++]['version'] = $srv->version;
                }
            }

            $data[$i++]->d3 = $d3;
            var_dump($data[$i]); exit;
        }
        exit;
        var_dump($data);*/

        return view('hosts.index', [
            'hosts' => $hosts
        ]);
    }
}
