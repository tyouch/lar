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
        .good-box{background-color: wheat; position: relative; width: 49.7%; padding: 0; margin-bottom: 0.6%;}
        .good-box .good-span{position: absolute; padding: 5px; background-color: rgba(0,0,0,.5);}
        .good-box .good-price{left: 0px; top: 15px; z-index: 1;}
        .good-box .good-title{right: 5px; bottom: 15px; z-index: 1; border-radius: 3px;
            width: 80%; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;}
        .good-box img{width: 100%; height: 100%; z-index: 0;}

        .col-xs-6:nth-child(odd){float: left; margin-right: 1px; background-color: white;}
        .col-xs-6:nth-child(even){float: right; margin-left: 1px; background-color: white;}
    </style>
    <div class="container container-mobile">
        <div class="row">
            <div class="col-xs-12" style="text-align: center;">

                {{--轮播图广告--}}
                <div id="banner_box" class="box_swipe">
                    <ul>
                        @foreach($advs as $adv)
                            <li>
                                <a href="@if (empty($adv->link)){{ '#' }}@else{{ $adv->link }}@endif">
                                    <img src="{{ url($adv->thumb) }}" alt="" width='100%' height='100%'/>
                                </a>
                                <span class="title">{{ $adv->advname }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <ol>
                        @foreach($advs as $adv)
                            <li @if ($loop->index === 0)class="on"@endif></li>
                        @endforeach
                    </ol>
                </div>


                {{--轮播图广告 bootstrap--}}
                <div id="carousel-example-generic" class="carousel slide sr-only" data-ride="carousel" style="margin: -5px;">
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

        {{--首页商品展示--}}
        <div class="row">
            @foreach($goods as $good)
                <div class="col-xs-6 good-box">
                    <span class="good-span good-price">￥{{ $good->productprice }}</span>
                    <span class="good-span good-title">{{ $good->title }}</span>
                    <a href="{{ route('mobile.shop.detail', ['weid'=>$weid, 'id'=>$good->id]) }}" class="good-img">
                        <img src="{{ url($good['thumb']) }}" alt="{{ $good['title'] }}">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        /*轮播拖动 bootstrap // { asset('/js/toucher.js') }}
        var myTouch = util.toucher(document.getElementById('carousel-example-generic'));
        myTouch.on('swipeLeft',function(e){
            $('#carright').click();
        }).on('swipeRight',function(e){
            $('#carleft').click();
        });*/
    </script>


    <script src="{{ asset('/js/jquery.touchwipe.js') }}"></script>
    <script src="{{ asset('/js/swipe.js') }}"></script>
    <script>
        //轮播拖动
        $(function () {
            new Swipe($('#banner_box')[0], {
                speed: 500,
                auto: 3000,
                callback: function () {
                    var lis = $(this.element).next("ol").children();
                    lis.removeClass("on").eq(this.index).addClass("on");
                }
            });
        });
    </script>
@endpush