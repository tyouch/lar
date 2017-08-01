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
                    <form action="{{ route('account.payment', ['weid'=>$weid]) }}" method="post">
                        <div class="panel-heading">公众号信息</div>
                        <div class="panel-body">
                            <div class="well">

                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <table class="table">
                                    <tr>
                                        <th style="width: 30%;"><label>身份标识<br>(appId)</label></th>
                                        <td><input type="text" name="wechat[appid]" class="form-control" value="{{ @$wechat['appid'] }}" placeholder="公众号身份标识"></td>
                                    </tr>
                                    <tr>
                                        <th><label>身份密钥<br>(appSecret)</label></th>
                                        <td><input type="text" name="wechat[secret]" class="form-control" value="{{ @$wechat['secret'] }}" placeholder="公众平台API(参考文档API 接口部分)的权限获取所需密钥Key"></td>
                                    </tr>
                                    <tr>
                                        <th><label>微信支付商户号<br>(MchId)</label></th>
                                        <td><input type="text" name="wechat[mchid]" class="form-control" value="{{ @$wechat['mchid'] }}" placeholder="公众号支付请求中用于加密的密钥Key"></td>
                                    </tr>
                                    <tr>
                                        <th><label>通信密钥/商户支付密钥<br>(paySignKey/api密钥)</label></th>
                                        <td><input type="text" name="wechat[signkey]" class="form-control" value="{{ @$wechat['signkey'] }}" placeholder="公众号支付请求中用于加密的密钥Key，新版支付请"></td>
                                    </tr>
                                    <tr>
                                        <th>状态</th>
                                        <td>
                                            <label class="radio-inline"><input type="radio" name="wechat[switch]" value="true" @if(@$wechat['switch']=='true')checked="checked"@endif>开启</label>
                                            <label class="radio-inline"><input type="radio" name="wechat[switch]" value="false" @if(@$wechat['switch']=='false')checked="checked"@endif>关闭</label>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                        <div class="panel-footer">
                            {{ csrf_field() }} &nbsp;
                            <span class="pull-right">
                                <input type="submit" class="btn btn-primary btn-xs" value="保存参数">
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')

@endpush
