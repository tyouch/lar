<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/6/26
 * Time: 10:59
 */
?>
@extends('layouts.app')
@section('title', 'VueTest')

@section('content')

    <div id="app">
        <div :class="msg"></div>
        <example></example>
        <div class="container">
            <passport-clients></passport-clients>
            <passport-authorized-clients></passport-authorized-clients>
            <passport-personal-access-tokens></passport-personal-access-tokens>
        </div>
    </div>

    <div class="container">
        <div class="row assets">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Vue Test</div>
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
    $("#app a").click(function (e) {
        /*$.ajax({
            url: 'https://hello.tyoupub.com/lar/public/cors',
            //url: 'http://local.tyoupub.com/lar/public/cors',
            type: 'GET',
            dataType: 'JSON',
            data:{

            },
            success: function (d, s) {
                console.log(d, s);
            },
            complete: function (d, s) {

            }
        });*/
    });
</script>
@endpush