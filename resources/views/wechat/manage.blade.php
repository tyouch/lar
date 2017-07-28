<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.app')
@section('title', '公众号管理')


@section('content')
    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('wechat.nav', [])
                @includeIf('wechat.nav2', [])
            </div>

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">公众号信息</div>
                    <div class="panel-body">
                        <div class="well">
                            <table class="table">
                                <tr>
                                    <th>接口地址:</th>
                                    <td>{{ $apiAddress }}</td>
                                </tr>
                                <tr>
                                    <th>微信Token:</th>
                                    <td>{{ $account['token'] }}</td>
                                </tr>
                                <tr>
                                    <th>EncodingAESKey:</th>
                                    <td>{{ $account['EncodingAESKey'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')

@endpush
