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
    <style>
        .base-info{color: #333; background-color: white; padding: 1px 10px; margin: 0 -5px;}
        .base-info h4{color: #666;}
        .base-info .product-price{font-size: 28px; color: #ff4643; margin-right: 20px;}
        .base-info .product-price e{font-size: 18px;}
        .base-info .market-price{font-size: 18px; color: #999; text-decoration: line-through;}

        #total{text-align: center; height: 32px;}

        .detail{margin: 0 -5px -20px}
        .detail p{margin: 0;}
        .detail img{width: 100%; height: 100%;}

        .cart{position: fixed; left:0; bottom: 0; z-index: 100; width: 100%; height: 60px; background-color: rgba(255,255,210,.7);}
        .cart a:hover {color: #ff4546;}
        .cart-content, .action-list{float: left; height: 100%; overflow: hidden; padding: 0;}
        .cart-content{width: 39%; padding: 3.5% 0 0 0;}
        .cart-content a{width: 33%; text-align: center;}
        .action-list{width: 61%;}
        .action-list button{border-radius: 0; color: white; width: 50%; height: 60px;}
    </style>
    <div class="container container-mobile">
        <div class="row assets">
            <div class="col-xs-12">
                {{--轮播图 商品详情--}}
                <div id="banner_box" class="box_swipe">
                    <ul>
                        @foreach($good['advs'] as $adv)
                            <li style="text-align:center;">
                                <a href="javascript:;">
                                    <img src="{{ url(str_replace('/6/','/',$adv['attachment'])) }}" alt="" style="height: 375px;"/>
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

                <div class="base-info">
                    <h3>{{ $good['title'] }}</h3>
                    <h4>{{ $good['title'] }}</h4>
                    <p>
                        <span class="product-price"><e>￥</e>{{ $good['productprice'] }}</span>
                        <span class="market-price">市场价：￥{{ $good['productprice'] }}</span>
                    </p>
                    <p>
                        <h4>数量: </h4>
                        <div class="input-group" style="width: 36%">
                            <div class="input-group-btn">
                                <button id="reduce" class="btn btn-info">-</button>
                            </div>
                            <input type="text" id="total" name="total" class="form-control" value="1" maxlength="3">
                            <div class="input-group-btn">
                                <button id="plus" class="btn btn-info">+</button>
                            </div>
                        </div>
                    </p>

                </div>

                <div class="detail">
                    <?= $good['content']?>
                </div>
                <div class="cart">
                    <div class="cart-content">
                        <a href="javascript:;" class="pull-left">
                            <span class="glyphicon glyphicon-home"></span><br><sapn>首页</sapn>
                        </a>
                        <a href="javascript:;" class="pull-left">
                            <span class="glyphicon glyphicon-heart"></span><br><sapn>关注</sapn>
                        </a>
                        <a href="{{ route('mobile.shop.cart', ['weid'=>$weid]) }}" class="pull-left">
                            <span class="glyphicon glyphicon-shopping-cart"></span><br><sapn>购物车</sapn>
                        </a>
                    </div>
                    <div class="action-list">
                        <button id="buy" class="btn btn-danger btn-lg pull-right">立即购买</button>
                        <button id="cart" class="btn btn-warning btn-lg pull-right">加入购物车</button>
                    </div>


                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 通过ready接口处理成功验证
        wx.ready(function () {

            // 分享给朋友
            wx.onMenuShareAppMessage({
                title: '{{ $good['title'] }}', // 分享标题
                desc: '{{ $good['description'] }}', // 分享描述
                link: '{!! $url['link'] !!}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '{{ $url['host'].$good['thumb'] }}', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                    console.log('share success!');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log('share cancel!');
                }
            });

            // 分享到朋友圈
            wx.onMenuShareTimeline({
                title: '{{ $good['title'] }}', // 分享标题
                link: '{!! $url['link'] !!}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '{{ $url['host'].$good['thumb'] }}', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    console.log('share success!');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    console.log('share cancel!');
                }
            });
        });
    </script>
    <script src="{{ asset('/js/jquery.touchwipe.js') }}"></script>
    <script src="{{ asset('/js/swipe.js') }}"></script>
    <script>
        // 隐藏主导航
        $(".footer-mobile").hide();

        // 替换失效的内容图
        $.each($(".detail img"), function (i, o) {
            $src = o.src.replace('/mobile/shop/resource/attachment/images/6', '/images');
            $(".detail img:eq("+i+")").attr("src", $src);
        });

        // 去除多余的回车
        $(".detail p br").remove();

        // 轮播图
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

        // 加减按钮
        $("#plus").on("click", function (e) {
            $val = parseInt($("#total").val());
            if ($val < parseInt({{ $good['total'] }})) {
                $("#total").val($val + 1);
            }
        });
        $("#reduce").on("click", function (e) {
            $val = parseInt($("#total").val());
            if ($val > 1) {
                $("#total").val($val - 1);
            }
        });
        $("#total").on("blur", function (e) {
            $val = $(this).val();
            if(isNaN($val) || $val<1){
                $("#total").val(1);
            }
            if($val>parseInt({{ $good['total'] }})){
                $("#total").val(parseInt({{ $good['total'] }}));
            }
        });

        // 立即购买
        $("#buy").on("click", function (e) {
            location.href = "{!! route('mobile.shop.confirm',['weid'=>$weid, 'ids'=>$good['id']]) !!}" + "&total=" + $("#total").val();
        });
    </script>
@endpush