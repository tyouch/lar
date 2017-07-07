<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/28
 * Time: 15:40
 */
?>
@extends('layouts.app')
@section('title', '资产监控')

@section('content')
<div class="container">
    <div class="row assets">
        <div class="col-md-3">
            @includeIf('hosts.area', [])
            @includeIf('hosts.services', [])
            @includeIf('hosts.os', [])
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">资产列表</div>
                <div class="panel-body">

                    @foreach($hosts as $host)
                    <div class="well">
                        <div class="row">
                            <div class="col-md-12 col-2xx">
                                <table class="table tbl" style="width: 49%; float: left;">
                                    <tr>
                                        <th>主机IP：</th>
                                        <td><?= $host['ip'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>位置：</th>
                                        <td><?= $host['province'].(empty($host['province'])?'':'/').$host['city'].(empty($host['city'])?'':'/').$host['area'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>操作系统类型：</th>
                                        <td><?= $host['os'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>计算机名：</th>
                                        <td><?= $host['hostname'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>所属信息系统：</th>
                                        <td><?= $host['dept'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>资产评分：</th>
                                        <td style="color: red;"><?= $host['asset'] ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a href="javascript:;" class="glyphicon glyphicon-th-list edit" data-id="<?= $host['id'] ?>" data-toggle="modal" data-target=".bs-modal-lg-assets-edit" title="资产详情（编辑）"></a>&nbsp;&nbsp;
                                            <a href="javascript:;" class="glyphicon glyphicon glyphicon-modal-window vuln-edit" data-id="<?= $host['id'] ?>" data-toggle="modal" data-target=".bs-modal-lg-vuln-edit" title="漏洞详情"></a>&nbsp;&nbsp;
                                            <a href="javascript:;" class="glyphicon glyphicon-log-in" title="攻击详情"></a>&nbsp;&nbsp;
                                            <a href="javascript:;" class="glyphicon glyphicon-search"></a>
                                        </td>
                                    </tr>
                                </table>

                                <table class="table tbr" style="width: 49%; float: right">
                                    <tr>
                                        <th>服务名称</th>
                                        <th>端口</th>
                                        <th>描述信息</th>
                                    </tr>
                                    @foreach ($host['d3'] as $d3)
                                    <tr>
                                        <td><?= $d3['service'];?></td>
                                        <td><?= $d3['port'];?></td>
                                        <td><?= $d3['version'];?></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @includeIf('public.pages')

                </div>
            </div>
        </div>
    </div>
</div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection

@push('scripts')
    @includeIf('hosts.areajs')
    @includeIf('hosts.statjs')
@endpush