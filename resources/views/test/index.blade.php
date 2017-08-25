<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/6/15
 * Time: 17:26
 */
?>
@extends('layouts.app')
@section('title', 'HxBankApi')

@section('content')
    <div class="container">
        <div class="row assets">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">HxBank Test</div>
                    <div class="panel-body">
                        <div class="well">
                            <ul class="">
                                @foreach ($data as $k=>$d)
                                    <li class=""><a href="{{ route('hxcalling.ogw', substr($k, -2)) }}" target="_blank">{{ $d }}</a></li>
                                @endforeach
                            </ul>
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
    <script>
        event.preventDefault();
        $.ajax({
            type: 'post',
            url: 'http://local,tyoupub.com/oauth/token',
            dataType: 'json',
            data: {
                'grant_type': 'password',
                'client_id': '2',
                'client_secret': '$2y$10$qs3BXE7mDxrjPRrdSycRVO0oaxNC93T/zackakrJS/CB0T1K380pO',
                'username': $('#username').val(),
                'password': $('#password').val(),
                'scope': ''
            },
            success: function (data) {
                console.log(data);
                alert(JSON.stringify(data));
            },
            error: function (err) {
                console.log(err);
                alert('statusCode:' + err.status + '\n' + 'statusText:' + err.statusText + '\n' + 'description:\n' + JSON.stringify(err.responseJSON));
            }
        });
    </script>
@endpush