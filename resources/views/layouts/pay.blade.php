<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/27
 * Time: 14:39
 */
?>
<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}">
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{{ url('css/app.css') }}">--}}
    <link rel="stylesheet" href="{{ url('css/style.css?v=6') }}">
</head>
<body>

    @yield('content')

    <footer class="footer-mobile">
        <div class="container">
            <a href="#"><div class="block"><span class="glyphicon glyphicon-home"></span><br>首页</div></a>
            <a href="#"><div class="block"><span class="glyphicon glyphicon-th-list"></span><br>分类</div></a>
            <a href="#"><div class="block"><span class="glyphicon glyphicon-shopping-cart"></span><br>购物车</div></a>
            <a href="#"><div class="block"><span class="glyphicon glyphicon-user"></span><br>我的</div></a>
        </div>
    </footer>


    <script src="{{ asset('/js/manifest.js') }}"></script>
    <script src="{{ asset('/js/vendor.js') }}"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        //$(".js-popover").popover(); //init弹出框
    </script>

    <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
        // 通过config接口注入权限验证配置
        wx.config({
            debug       : false,
            appId       : '{{ $signPackage["appId"] }}',
            timestamp   : '{{ $signPackage["timestamp"] }}',
            nonceStr    : '{{ $signPackage["nonceStr"] }}',
            signature   : '{{ $signPackage["signature"] }}',
            jsApiList   : [
                // 所有要调用的 API 都要加到这个列表中
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem',
                'translateVoice',
                'startRecord',
                'stopRecord',
                'onRecordEnd',
                'playVoice',
                'pauseVoice',
                'stopVoice',
                'uploadVoice',
                'downloadVoice',
                'chooseImage',
                'previewImage',
                'uploadImage',
                'downloadImage',
                'getNetworkType',
                'openLocation',
                'getLocation',
                'hideOptionMenu',
                'showOptionMenu',
                'closeWindow',
                'scanQRCode',
                'chooseWXPay',
                'openProductSpecificView',
                'addCard',
                'chooseCard',
                'openCard'
            ]
        });

    </script>
    @stack('scripts')
</body>
</html>
