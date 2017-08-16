<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/8/10
 * Time: 10:00
 */
?>

@extends('layouts.app')
@section('title', '订单管理')


@section('content')
    <style>
        .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover{
            border-radius: 5px 5px 0 0;
            background-color: #404a59;
        }
    </style>

    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('web.nav', [])
                @includeIf('web.nav2', [])
            </div>

            <div class="col-md-9">
                <div class="panel panel-default">

                    <form action="{{ route('shop.orders', ['weid'=>$weid]) }}" method="get">
                        <div class="panel-heading">
                            <span>管理订单</span>
                            <span class="pull-right">
                            <input type="button" class="btn btn-success btn-xs add" data-id="0" data-toggle="modal" data-target=".example-modal" value="添加订单">
                        </span>
                        </div>

                        <div class="panel-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Nav tabs -->
                            <ul class="nav nav-pills" role="tablist">
                                <li @if($status == 1)class="active"@endif>
                                    <a href="{{ route('shop.orders', ['weid'=>$weid, 'status'=>1]) }}">待付款</a>
                                </li>
                                <li @if($status == 2)class="active"@endif>
                                    <a href="{{ route('shop.orders', ['weid'=>$weid, 'status'=>2]) }}">待发货</a>
                                </li>
                                <li @if($status == 3)class="active"@endif>
                                    <a href="{{ route('shop.orders', ['weid'=>$weid, 'status'=>3]) }}">待收货</a>
                                </li>
                                <li @if($status == 4)class="active"@endif>
                                    <a href="{{ route('shop.orders', ['weid'=>$weid, 'status'=>4]) }}">已完成</a>
                                </li>
                                <li @if($status == 5)class="active"@endif>
                                    <a href="{{ route('shop.orders', ['weid'=>$weid, 'status'=>5]) }}">已关闭</a>
                                </li>
                                <li @if(!$status)class="active"@endif>
                                    <a href="{{ route('shop.orders', ['weid'=>$weid]) }}">全部订单</a>
                                </li>
                            </ul>


                            <div class="well">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group has-feedback">
                                            <div class="input-group">
                                                <span class="input-group-addon">订单ID</span>
                                                <input type="text" name="ordersn" class="form-control" value="{{ @$pagePram['ordersn'] }}" maxlength="20">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                <input type="text"  id="reservation" name="createtime" class="form-control" style="width: 160%" value="{{ @$pagePram['createtime'] }}">
                                                <input type="hidden" value="">
                                                <input type="hidden" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="hidden" name="weid" value="{{ $weid }}">
                                        <button type="submit" class="btn btn-primary" style="width: 100%">
                                            <span class="glyphicon glyphicon-search"></span>
                                            <span>搜索</span>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="well">
                                <table class="table">
                                    <tr>
                                        <th style="width: 15%">订单号</th>
                                        <th style="width: 10%;">姓名</th>
                                        <th style="width: 10%;">电话</th>
                                        <th style="width: 15%;">总价</th>
                                        <th style="width: 25%;">下单时间</th>
                                        <th style="width: 10%;">状态</th>
                                        <th style="width: 26%">操作</th>
                                    </tr>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->ordersn }}</td>
                                            <td>{{ $order->address['realname'] }}</td>
                                            <td>{{ $order->address['mobile'] }}</td>
                                            <td>￥{{ $order->price }}</td>
                                            <td>{{ date('Y-m-d H:i:s', $order->createtime) }}</td>
                                            <td>
                                                @if($order->status == 1)<span class="label label-info">待付款</span>
                                                @elseif($order->status == 2)<span class="label label-primary">待发货</span>
                                                @elseif($order->status == 3)<span class="label label-warning">待收货</span>
                                                @elseif($order->status == 4)<span class="label label-danger">已完成</span>
                                                @elseif($order->status == 5)<span class="label label-default">已关闭</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-xs detail" data-id="{{ $order['id'] }}">详情</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                                <div style="text-align: center;">
                                    {!! $orders->appends($pagePram)->links() !!}
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{--模态框--}}
        <div class="modal fade example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('shop.orders', ['weid'=>$weid]) }}" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">订单详情</h4>
                        </div>
                        <div class="modal-body">
                            <h4>订单信息</h4>
                            <table class="table">
                                <tr>
                                    <th style="width: 20%">订单号</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>交易码</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>订单状态</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>价格</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>下单日期</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>发票</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>备注</th>
                                    <td><textarea rows="3" name="remark" class="form-control"></textarea></td>
                                </tr>
                            </table>

                            <h4>快递信息</h4>
                            <table class="table tb2">
                                <tr>
                                    <th style="width: 20%">快递公司</th>
                                    <td>
                                        <input type="hidden" name="expresscom" value="">
                                        <select id='express' class="form-control sr-only">
                                            @includeIf('web.shop.express', [])
                                        </select>
                                        <span></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>快递单号</th>
                                    <td>
                                        <input type="text" name="expresssn" class="form-control sr-only" maxlength="16">
                                        <span></span>
                                    </td>
                                </tr>
                            </table>

                            <h4>用户信息</h4>
                            <table class="table">
                                <tr>
                                    <th style="width: 20%">姓名</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>手机</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>地址</th>
                                    <td></td>
                                </tr>
                            </table>

                            <h4>商品信息</h4>
                            <table class="table tb4">
                                <tr>
                                    <th>商品标题</th>
                                    <th>商品属性</th>
                                    <th>价格</th>
                                    <th>数量</th>
                                </tr>
                            </table>

                        </div>
                        <div class="modal-footer">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="">
                            <input type="submit" name="submit" class="btn btn-primary sr-only" value="确认发货">
                            <input type="submit" name="submit" class="btn btn-default" value="关闭订单">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')
<script src="{{ asset('/js/moment.js') }}"></script>
<script src="{{ asset('/js/daterangepicker.js') }}"></script>
<script>
    // 日期插件 必须放在前面
    $('#reservation').daterangepicker({
        "autoApply": true,
        "timePicker": true,
        "timePicker24Hour": true,
        "timePickerSeconds": true,
        "opens": "left",
        "locale": {
            "format": "YYYY-MM-DD HH:mm",
        }
    }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });

    function dateParse($timeRange) {
        $timeRange = $timeRange.split(" - ");
        console.log(1, $timeRange[0], $timeRange[1]);
        console.log(1, Date.parse($timeRange[0])/1000, Date.parse($timeRange[1])/1000);
        $("input[name=createtime]").val(Date.parse($timeRange[0])/1000+'_'+Date.parse($timeRange[1])/1000);
    }
</script>

<script>
    $(".detail").on('click', function (e) {
        $('.example-modal').modal('show');
        id = $(this).attr("data-id");
        $("input[name=id]").val(id);


        $.ajax({
            url: '{{ route('shop.orders', ['weid'=>$weid]) }}',
            Type: 'POST',
            dataType: 'JSON',
            data: {
                _token: '{{ csrf_token() }}',
                op: 'detail',
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (d, s) {
                console.log(d, s);
                $(":contains('订单号')").next("td").html(d.ordersn);
                $(":contains('交易码')").next("td").html(d.transid);
                $(":contains('价格')").next("td").html('￥' + d.price);
                $(":contains('下单日期')").next("td").html(d.createtime2);
                d.address ? $(":contains('姓名')").next("td").html(d.address.realname) : $(":contains('姓名')").next("td").html("");
                d.address ? $(":contains('手机')").next("td").html(d.address.mobile) : $(":contains('手机')").next("td").html("");
                d.address ? $(":contains('地址')").next("td").html(d.address.province+' '+d.address.city+' '+d.address.area+' '+d.address.address) : $(":contains('地址')").next("td").html("");
                d.invoice ? $(":contains('发票')").next("td").html(d.invoice.title) : $(":contains('发票')").next("td").html("");
                $(":contains('备注')").next("td").children(" textarea").html(d.remark);

                $("#express").next("span").html(d.expresscom);
                $("input[name=expresssn]").next("span").html(d.expresssn);

                switch (d.status) {
                    case 1: $status='<span class="label label-info">待付款</span>';
                        $("h4:contains('快递信息')").hide();
                        $(".tb2").hide();
                        break;
                    case 2: $status='<span class="label label-primary">待发货</span>';
                        $("h4:contains('快递信息')").show();
                        $(".tb2").show();
                        $("#express").removeClass("sr-only").next("span").hide();
                        $("input[name=expresssn]").removeClass("sr-only").next("span").hide();
                        $("input[value=确认发货]").removeClass("sr-only");
                        break;
                    case 3: $status='<span class="label label-warning">待收获</span>';
                        break;
                    case 4: $status='<span class="label label-danger">已完成</span>';
                        $("input[value=关闭订单]").hide();
                        break;
                    case 5: $status='<span class="label label-default">已关闭</span>';
                        $("input[value=关闭订单]").hide();
                        break;
                }
                $(":contains('订单状态')").next("td").html($status);


                tb4 =
                    '<tr>' +
                        '<th>商品标题</th>'+
                        '<th>价格</th>'+
                        '<th>数量</th>'+
                    '</tr>';
                $.each(d.order_goods, function (i, o) {
                    tb4 +=
                        '<tr>' +
                            '<td>' + o.title + '</td>'+
                            '<td>' + o.price + '</td>'+
                            '<td>' + o.total + '</td>'+
                        '</tr>';
                });
                $(".tb4").html(tb4);
            }

        });

    });

    function express() {
        var obj = $("#express");
        var sel = obj.find("option:selected").attr("data-name");
        //alert(sel);
        $("input[name=expresscom]").val(sel);
    }
    $("#express").on('change', express);

    $(function () { express(); });

</script>
@endpush
