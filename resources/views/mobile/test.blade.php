<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '微信支付')

<style>
    .breakLine{width: 100%; word-break: break-all;}
</style>

@section('content')
    <div class="container">
        <div class="row assets">
            <div class="col-md-12">
                <img src="../imgs/wx_pay_qrcode.png">
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush

</body>
</html>
