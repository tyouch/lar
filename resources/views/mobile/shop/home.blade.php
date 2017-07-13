<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '我的')


@section('content')
    <style>
        body{padding: 10px 0 70px 0;}
        .profile{float:left; height: 85px; margin-right: 20px;}
        .headimg{width: 85px;}
        .nickname{width: 50%;}
    </style>
    <div class="container">
        <div class="row assets">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">我的</div>
                    <div class="panel-body">
                        <div class="well" style="height: 120px;">
                            <div class="profile headimg">
                                <img src="{{ $fans->avatar }}" class="img-thumbnail" alt="{{ $fans->nickname }}">
                            </div>
                            <div class="profile nickname">
                                <p>{{ $fans->nickname }}</p>
                                <p>{{ $fans->nationality }} | {{ $fans->resideprovince }} | {{ $fans->residecity }}</p>
                                <p id="loc"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
    wx.ready(function () {

        wx.getLocation({
            type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                //alert("纬度:"+latitude+" 经度:"+longitude);


                console.log('微信位置',longitude, latitude);
                $("#loc").html('['+longitude+','+latitude+']')

            },
            cancel: function (res) {
                alert('用户拒绝授权获取地理位置');
            }
        });

    });
</script>
@endpush