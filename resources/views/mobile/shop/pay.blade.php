<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '打赏店主')


@section('content')
    <style>
        body{padding: 10px 0 70px 0;}
        .breakLine{width: 100%; word-break: break-all;}
    </style>

    <div class="container">
        <div class="row assets">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Package</div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <th>openid</th>
                                <td class="breakLine">{{ $package['openid'] }}</td>
                            </tr>
                            <tr>
                                <th>prepay_id</th>
                                <td class="breakLine">{{ $unifiedorderRes['prepay_id'] }}</td>
                            </tr>
                            <tr>
                                <th>raw</th>
                                <td class="breakLine">{{ $signPackage['rawString'] }}</td>
                            </tr>
                            <tr>
                                <th>notify_url</th>
                                <td class="breakLine">{{ $package['notify_url'] }}</td>
                            </tr>
                            <tr>
                                <th>location</th>
                                <td id="loc"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    wx.ready(function () {

        wx.chooseWXPay({
            timestamp: '{{ $wOpt['timeStamp'] }}', // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
            nonceStr: '{{ $wOpt['nonceStr'] }}', // 支付签名随机串，不长于 32 位
            package: '{{ $wOpt['package'] }}', // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
            signType: '{{ $wOpt['signType'] }}', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
            paySign: '{{ $wOpt['paySign'] }}', // 支付签名
            success: function (res) {
                // 支付成功后的回调函数
                //alert(res);
            }
        });

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