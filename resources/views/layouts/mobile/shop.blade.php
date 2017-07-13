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
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{{ url('css/app.css') }}">--}}
    <link rel="stylesheet" href="{{ url('css/style.css?v=4') }}">
</head>
<body>

    @yield('content')

    <script src="{{ asset('/js/manifest.js') }}"></script>
    <script src="{{ asset('/js/vendor.js') }}"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        //$(".js-popover").popover(); //init弹出框
    </script>

    <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
        var HOST = '<?= $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'] . '/';?>';

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

        // 通过ready接口处理成功验证
        wx.ready(function () {

            //判断当前客户端版本是否支持指定JS接口
            wx.checkJsApi({
                jsApiList: [
                    'getLocation',
                    'onMenuShareAppMessage',
                    'onMenuShareTimeline'
                ],
                success: function (res) {
                    // alert(JSON.stringify(res));
                    // alert(JSON.stringify(res.checkResult.getLocation));
                    if (res.checkResult.getLocation == false) {
                        alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                        return;
                    }
                }
            });

            // 分享给朋友
            wx.onMenuShareAppMessage({
                title   : '打赏店主', // 分享标题
                desc    : '如果您感到满意请您给个赏钱，如果感到不满意，1分2分不算啥，有钱的捧个钱场，没钱的捧个人场，多谢啦！', // 分享描述
                link    : HOST, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl  : HOST + 'imgs/headimg.jpg', // 分享图标
                type    : '', // 分享类型,music、video或link，不填默认为link
                dataUrl : '', // 如果type是music或video，则要提供数据链接，默认为空
                success : function () {
                    // 用户确认分享后执行的回调函数
                    console.log('share success!');
                },
                cancel  : function () {
                    // 用户取消分享后执行的回调函数
                    console.log('share cancel!');
                }
            });

            // 分享到朋友圈
            wx.onMenuShareTimeline({
                title: '打赏店主', // 分享标题
                link: HOST, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: HOST + 'imgs/headimg.jpg', // 分享图标
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
    @stack('scripts')

</body>
<footer class="footer-mobile">
    <div class="container">
        <a href="{{ route('mobile.shop.index') }}" class="@if($navActive=='index'){{ 'active' }}@endif"><div class="block"><span class="glyphicon glyphicon-home"></span><br>首页</div></a>
        <a href="{{ route('mobile.shop.category') }}" class="@if($navActive=='category'){{ 'active' }}@endif"><div class="block"><span class="glyphicon glyphicon-th-list"></span><br>分类</div></a>
        <a href="{{ route('mobile.shop.cart') }}" class="@if($navActive=='cart'){{ 'active' }}@endif"><div class="block"><span class="glyphicon glyphicon-shopping-cart"></span><br>购物车</div></a>
        <a href="{{ route('mobile.shop.home') }}" class="@if($navActive=='home'){{ 'active' }}@endif"><div class="block"><span class="glyphicon glyphicon-user"></span><br>我的</div></a>
    </div>
</footer>
</html>
