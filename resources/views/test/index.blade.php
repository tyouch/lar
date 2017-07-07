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