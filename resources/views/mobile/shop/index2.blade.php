<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '首页')


@section('content')
    <div class="container">
        <div class="row assets">
            <div class="col-md-12" style="text-align: center;">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('pay.jsapi') }}" method="post">
                    <input type="hidden" name="appid" value="{{ $package['appid'] }}">
                    <input type="hidden" name="mch_id" value="{{ $package['mch_id'] }}">
                    <input type="hidden" name="nonce_str" value="{{ $package['nonce_str'] }}">
                    <input type="hidden" name="body" value="{{ $package['body'] }}">
                    <input type="hidden" name="spbill_create_ip" value="{{ $package['spbill_create_ip'] }}">
                    <input type="hidden" name="notify_url" value="{{ $package['notify_url'] }}">
                    <input type="hidden" name="trade_type" value="JSAPI">
                    {{ csrf_field() }}
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon" id="sizing-addon1">打赏金额</span>
                        <input type="text" class="form-control" name="total_fee" value="0.01">
                    </div>
                    <input type="submit" class="btn btn-success btn-lg" name="submit" style="width: 100%; margin: 20px 0;" value="微信支付">
                </form>
                <img src="{{ url('imgs/wx_pay_qrcode1.png') }}">
                <img src="{{ url('imgs/wx_pay_qrcode2.png') }}">
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
    $("input[name=submit]").touchend(function (e) {
        $val = $("input[name=total_fee]").val();
        if(!isNaN($val) && $val>0 && $val<=37) {
            alert("请输入正确的金额！");
        }
    })
</script>
@endpush