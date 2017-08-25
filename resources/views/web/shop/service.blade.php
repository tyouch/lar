<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/8/2
 * Time: 14:48
 */
?>

@extends('layouts.app')
@section('title', '物流管理')


@section('content')
    <link rel="stylesheet" href="{{ url('css/fileinput.css') }}">
    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('web.nav', [])
                @includeIf('web.nav2', [])
            </div>

            <div class="col-md-9">
                <form action="{{ route('shop.service',['weid'=>$weid]) }}" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>售后管理</span>
                            <span class="pull-right"></span>
                        </div>

                        <div class="panel-body">



                        </div>

                        <div class="panel-footer">
                            {{ csrf_field() }} &nbsp;
                            <span class="pull-right">
                                <botton class="btn btn-primary btn-xs" value="">保存参数</botton>
                            </span>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')
    <script>

    </script>
@endpush
