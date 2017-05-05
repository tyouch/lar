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
            <div class="panel panel-default">
                <div class="panel-heading">区域选择</div>
                <div class="panel-body">
                    <div id="treeview5" class="treeview"></div>
                </div>
            </div>
            <div class="panel panel-default">
                <!--<div class="panel-heading">资产统计</div>-->
                <div id="box1" class="panel-body" style="height: 250px;"></div>
            </div>

            <div class="panel panel-default">
                <!--<div class="panel-heading">系统占比</div>-->
                <div id="box2" class="panel-body" style="height: 250px;"></div>
            </div>
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

                    <nav class="pager-top">
                        <ul class="pager pull-right">
                            <li class="previous"><a href="<?= $p>1 ? $p-1 : $p ?>">上一页</a></li>
                            <li class=""><a href="javascript:;"><?= $p . '/' . $pm ?></a></li>
                            <li class="next"><a href="<?= $p<$pm ? $p+1 : $pm ?>">下一页</a></li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="<?= url('js/bootstrap-treeview.min.js') ?>"></script>
<script src="<?= url('js/echarts.min.js') ?>"></script>
<script type="text/javascript">

    function convertData(arrays) {
        //console.log(arrays);
        var treeData = [];
        $.each(arrays, function (i, ele1) {
            treeData[i] = {
                text    : ele1.name,
                href    : 'javascript:;',//'' + ele1.name,
                tags    : [ele1.city.length.toString()],
                nodes   : []
            };
            var nodes_city = [];
            $.each(ele1.city, function(j, ele2) {
                nodes_city[j] = {
                    text    : ele2.name,
                    href    : 'javascript:;',//'' + ele2.name,
                    tags    : [ele2.area.length.toString()],
                    nodes   : []
                };
                var nodes_area = [];
                $.each(ele2.area, function(k, ele3){
                    nodes_area[k] = {
                        text    : ele3,
                        href    : 'javascript:;',//'' + ele3,
                        tags    : ['0'],
                    }
                });
                nodes_city[j].nodes = nodes_area;
            });
            treeData[i].nodes = nodes_city;
        });

        return treeData;
    }


    $(function() {

        $.getJSON("<?= url('js/ChineseCities.json') ?>", function (ChineseCities) {

            /*var treeData = [
             {
             text: '河南省',
             href: '#',
             tags: ['4'],
             nodes: [
             {
             text: '郑州市',
             href: '#',
             tags: ['2'],
             nodes: [
             {
             text: '金水区',
             href: '#grandchild1',
             tags: ['0']
             },
             {
             text: '二七区',
             href: '#grandchild2',
             tags: ['0']
             }
             ]
             },
             {
             text: '新政市',
             href: '#',
             tags: ['0']
             }
             ]
             },
             {
             text: '陕西省',
             href: '#parent2',
             tags: ['0']
             },
             {
             text: '山西省',
             href: '#parent3',
             tags: ['0']
             }
             ];*/

            console.log(ChineseCities);
            var treeData = convertData(ChineseCities);
            console.log(treeData);

            $('#treeview5').treeview({
                levels: 1,
                color           : "#15ceff",
                backColor       : "#404a59",
                expandIcon      : 'glyphicon glyphicon-chevron-right',
                collapseIcon    : 'glyphicon glyphicon-chevron-down',
                //nodeIcon      : 'glyphicon glyphicon-bookmark',
                enableLinks     : true,
                data            : treeData
            });

        });


        $(window).on('resize',function(){
            // 调用相关echarts的resize方法. ** 放在echart声明之后
            myChart1.resize();
            myChart2.resize();
        });

    });


    // 基于准备好的dom，初始化echarts实例
    var myChart1 = echarts.init(document.getElementById('box1'));
    var myChart2 = echarts.init(document.getElementById('box2'));

    option1 = {
        title : {
            text: '服务统计',
            //subtext: '纯属虚构',
            x:'center',
            textStyle: {
                color: '#fff'
            }
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            //orient: 'vertical',
            //left: 'left',
            //data: ['正常','异常','新增']
        },
        color:['#c0242a','#b6c434','#fccf0f','#ec7c24','#28727b','#ff8463'],
        backgroundColor: '#323c48',//'#404a59',
        series : [
            {
                name: '服务统计',
                type: 'pie',
                selectedMode: 'single',
                radius : '55%',
                center: ['50%', '60%'],
                data: <?= $ServiceRatio ?>,
                /*data:[
                 {value:535, name:'正常'},
                 {value:310, name:'异常'},
                 {value:234, name:'新增'}
                 ],*/
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    option2 = {
        title : {
            text: '系统占比',
            //subtext: '纯属虚构',
            x:'center',
            textStyle: {
                color: '#fff'
            }
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            //orient: 'vertical',
            //left: 'left',
            //data: ['Linux/Unix系统','Windows系统','其他']
        },
        color:['#9bcb62','#f3a43b','#60c0de','#974da9','#b6c434','#ff8463'],
        backgroundColor: '#323c48',//'#404a59',
        series : [
            {
                name: '系统占比',
                type: 'pie',
                selectedMode: 'single',
                radius : '55%',
                center: ['50%', '60%'],
                data: <?= $OSRatio ?>,
                /*data:[
                 {value:535, name:'Linux/Unix系统'},
                 {value:310, name:'Windows系统'},
                 {value:234, name:'其他'}
                 ],*/
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    myChart1.setOption(option1);
    myChart2.setOption(option2);

</script>
@endpush