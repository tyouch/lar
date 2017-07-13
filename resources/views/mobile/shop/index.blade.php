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
            <div class="col-md-12">
                <a href="{{ route('pay.index') }}" class="btn btn-warning btn-lg" style="width: 100%">打赏店主1分钱</a>
            </div>
        </div>
    </div>
@endsection


@push('scripts')

@endpush