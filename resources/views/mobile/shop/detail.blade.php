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

                {{--轮播图 商品详情--}}
                <div id="banner_box" class="box_swipe">
                    <ul>
                        @foreach($good['advs'] as $adv)
                            <li style="text-align:center;">
                                <a href="javascript:;">
                                    <img src="{{ url($adv['attachment']) }}" alt="" style="height: 375px;"/>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <ol>
                        @foreach($good['advs'] as $adv)
                            <li @if ($loop->index === 0)class="on"@endif></li>
                        @endforeach
                    </ol>
                </div>

                商品详情 {{ $url['link'] }}

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('/js/jquery.touchwipe.js') }}"></script>
    <script src="{{ asset('/js/swipe.js') }}"></script>
    <script>
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