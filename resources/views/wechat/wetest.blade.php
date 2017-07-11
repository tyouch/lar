<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">

        <div class="col-md-12">

            <div class="pay-btn" id="wechat-panel">
                <form action="{php echo create_url('mobile/cash/wechat', array('weid' => $_W['weid']));}" method="post">
                    <input type="hidden" name="params" value="{php echo base64_encode(json_encode($params));}" />
                    <button class="btn btn-warning btn-lg" disabled="disabled" type="submit" id="wBtn" value="wechat">微信支付(必须使用微信内置浏览器)</button>
                </form>
            </div>
            <script type="text/javascript">
                document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                    $('#wBtn').removeAttr('disabled');
                    $('#wBtn').html('微信支付');
                });
            </script>

        </div>

    </div>
</div>

<script>
    var HOST = '<?= $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'] . '/';?>';
    wx.config({
        debug: false,
        appId: '{{ $signPackage["appId"] }}',
        timestamp: '{{ $signPackage["timestamp"] }}',
        nonceStr: '{{ $signPackage["nonceStr"] }}',
        signature: '{{ $signPackage["signature"] }}',
        jsApiList: [
            // 所有要调用的 API 都要加到这个列表中
            'checkJsApi',
            'openLocation',
            'getLocation',
            'onMenuShareAppMessage',
            'onMenuShareTimeline'
        ]
    });
    wx.ready(function () {
        wx.checkJsApi({
            jsApiList: [
                'getLocation'
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

        wx.chooseWXPay({
            timestamp: 0, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
            nonceStr: '', // 支付签名随机串，不长于 32 位
            package: '', // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
            signType: '', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
            paySign: '', // 支付签名
            success: function (res) {
                // 支付成功后的回调函数
            }
        });

        wx.getLocation({
            type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                //alert("纬度:"+latitude+" 经度:"+longitude);


                console.log('微信位置',longitude, latitude);

            },
            cancel: function (res) {
                alert('用户拒绝授权获取地理位置');
            }
        });
    });
</script>
</body>
</html>
