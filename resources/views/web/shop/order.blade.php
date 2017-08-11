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
        .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover{border-radius: 5px 5px 0 0}
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
                                                <input type="text"  id="reservation" name="createtime" class="form-control" style="width: 160%" value="">
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
                                            <td>{{ $order->fans->realname }}</td>
                                            <td>{{ $order->fans->mobile }}</td>
                                            <td>￥{{ $order->price }}</td>
                                            <td>{{ date('Y-m-d H:i:s', $order->createtime) }}</td>
                                            <td>
                                                @if($order->status == 1)<span class="label label-info">待付款</span>
                                                @elseif($order->status == 2)<span class="label label-primary">待发货</span>
                                                @elseif($order->status == 3)<span class="label label-warning">待收货</span>
                                                @elseif($order->status == 4)<span class="label label-success">已完成</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="button" class="btn btn-danger btn-xs del" data-id="{{ $order['id'] }}" value="删除">
                                                <input type="button" class="btn btn-success btn-xs edit" data-id="{{ $order['id'] }}" value="编辑">
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
                            <h4 class="modal-title" id="myModalLabel">添加分类</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <th style="width: 16%">名称</th>
                                    <td>
                                        <input type="text" name="name" class="form-control" maxlength="20">
                                        <input type="hidden" name="id" value="" class="form-control">
                                        <input type="hidden" name="parentid" value="" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th>图片</th>
                                    <td>
                                        <div style="position: relative; margin-bottom: 15px;">
                                            <div class="input-group" style="position: absolute;" type="text">
                                                <input id="txt-preview" class="form-control" placeholder="大图片建议尺寸：300像素 * 300像素 上传选择文件" disabled>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-open"></span>
                                                </span>
                                            </div>
                                            <input style="position: absolute; opacity: 0" id="thumb" name="thumb" type="file" class="form-control">
                                        </div>&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sr-only">图片预览</td>
                                    <td><img id="thumb-preview" src="" class="img-thumbnail"></td>
                                </tr>
                                <tr>
                                    <th>描述</th>
                                    <td><textarea rows="3" name="description" class="form-control"></textarea></td>
                                </tr>
                                <tr>
                                    <th style="width: 16%">排序</th>
                                    <td>
                                        <input type="text" name="displayorder" class="form-control" maxlength="5" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th>首页推荐</th>
                                    <td>
                                        <label class="radio-inline"><input type="radio" name="isrecommand" value="1" checked="checked">是</label>
                                        <label class="radio-inline"><input type="radio" name="isrecommand" value="0">否</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>是否显示</th>
                                    <td>
                                        <label class="radio-inline"><input type="radio" name="enabled" value="1">是</label>
                                        <label class="radio-inline"><input type="radio" name="enabled" value="0" checked="checked">否</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="保存分类">
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

    $("#reservation").on('change', function (e) {
        $timeRange = $(this).val().split(" - ");
        console.log(1, $timeRange[0], $timeRange[1]);
    });
</script>

<script>

    /*$(".add").on('click', function (e) {
        id = $(this).attr("data-id");

        $('.example-modal').css("z-index", 99999);
    });

    $(".del").on('click', function (e) {
        id = $(this).attr("data-id");
        if (confirm("确认要删除这条规则吗？【rid:" + id + "】")) {
            alert('正在删除...');
        } else {
            alert('放弃删除...');
        }
    });

    $(".edit").on('click', function (e) {
        id = $(this).attr("data-id");


    });*/
</script>
@endpush
