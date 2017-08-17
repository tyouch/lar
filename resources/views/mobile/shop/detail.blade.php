<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '商品详情')


@section('content')
    <div class="container container-mobile">
        <div class="row assets">
            <div class="col-xs-12">
                商品详情 {{ $url['link'] }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush