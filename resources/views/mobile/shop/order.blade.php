<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '我的订单')


@section('content')
    <style>
        .confirm-list{color: #333; padding: 10px; margin: 0 -5px 5px; background: white;}

        .address{background: white url("{{ url('images/location-border.png') }}") repeat-x bottom;}
        .invoice{}

        .good-list{position: relative; height: 120px; color: #333; background: white}
        .good-list .thumb{width: 30%; margin: 0 10px 0 0;}
        .good-list img{width: 100px; height: 100px;}
        .good-list .content{width: 42%; font-size: 16px; padding: 5px 0 0 0;}
        .good-list .price{width: 24%; text-align: right; padding: 15px 0 0 0;}

        .cart{position: fixed; bottom: 0; z-index: 100; width: 100%;}
        .cart button{border-radius: 0; color: white; width: 50%; height: 60px;}
    </style>
    <div class="container container-mobile">
        <div class="row assets">
            <div class="col-xs-12">


                <form action="{{ route('pay.jsapi') }}" method="post">
                    <input type="hidden" name="appid" value="{{ $package['appid'] }}">
                    <input type="hidden" name="mch_id" value="{{ $package['mch_id'] }}">
                    <input type="hidden" name="nonce_str" value="{{ $package['nonce_str'] }}">
                    <input type="hidden" name="body" value="{{ $package['body'] }}">
                    <input type="hidden" name="spbill_create_ip" value="{{ $package['spbill_create_ip'] }}">
                    <input type="hidden" name="notify_url" value="{{ $package['notify_url'] }}">
                    <input type="hidden" name="trade_type" value="{{ $package['trade_type'] }}">
                    <input type="hidden" name="total_fee" value="{{ $package['total_fee'] }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="weid" value="{{ $weid }}">
                    <div class="cart">
                        <button type="submit" class="btn btn-success btn-lg pull-right" name="submit">微信支付</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".footer-mobile").hide();
    </script>
@endpush