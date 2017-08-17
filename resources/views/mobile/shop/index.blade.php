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
    <style>
        .good-box{background-color: wheat; height: 150px; position: relative; padding: 0;}
        .good-box .good-span{position: absolute; padding: 5px; background-color: rgba(0,0,0,.5);}
        .good-box .good-price{left: 0px; top: 15px; z-index: 1;}
        .good-box .good-title{right: 0px; bottom: 15px; z-index: 1;}
        .good-box .good-img{height: 150px; z-index: 0;}
    </style>
    <div class="container container-mobile">
        <div class="row">
            <div class="col-xs-12" style="text-align: center;">
                {{--轮播图广告--}}
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="margin: -5px;">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        @foreach($advs as $adv)
                        <li data-target="#carousel-example-generic" data-slide-to="{{ $loop->index }}" class="@if($loop->index===0){{ 'active' }}@endif"></li>
                        @endforeach
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        @foreach($advs as $adv)
                        <div class="item @if($loop->index===0){{ 'active' }}@endif">
                            <a href="{{ $adv->link }}"><img src="{{ url($adv->thumb) }}" alt="{{ $adv->advname }}"></a>
                            <div class="carousel-caption">{{ $adv->advname }}</div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Controls <-  ->  -->
                    <a id="carleft" class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a id="carright" class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>

        @foreach($goods as $good)
        <div class="row" style="margin-top: 10px;">
            <div class="col-xs-12 good-box">
                <span class="good-span good-price">￥{{ $good->productprice }}</span>
                <span class="good-span good-title">{{ $good->title }}</span>
                <a href="{{ route('mobile.shop.detail', ['weid'=>$weid, 'id'=>$good->id]) }}" class="good-img"><img src="" alt="{{ $good['title'] }}"></a>
            </div>
        </div>
        @endforeach
    </div>
@endsection


@push('scripts')
<script src="{{ asset('/js/toucher.js') }}"></script>
<script>
    /*轮播拖动*/
    var myTouch = util.toucher(document.getElementById('carousel-example-generic'));
    myTouch.on('swipeLeft',function(e){
        $('#carright').click();
    }).on('swipeRight',function(e){
        $('#carleft').click();
    });
</script>
@endpush