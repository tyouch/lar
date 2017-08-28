<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '填写订单')


@section('content')
    <style>
        .cart{position: fixed; bottom: 0; z-index: 100; width: 100%;}
        .cart button{border-radius: 0;}
    </style>
    <div class="container container-mobile">
        <div class="row assets">
            <div class="col-xs-12">

                <div>{{ $good['title'] }}</div>
                <div>{{ $good['productprice'] }}</div>

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
                        <button type="submit" class="btn btn-danger btn-lg pull-right" name="submit">提交订单</button>
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