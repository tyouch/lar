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
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#tyouNavbar">
                    <span class="sr-only">切换导航</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ url('/') }}" class="navbar-brand">Tyoupub</a>
            </div>

            <div id="tyouNavbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a class="" href="{{ route('hosts', 1) }}"> 资产监控</a></li>
                    <li><a class="" href="{{ route('test.index') }}"> 漏洞监控</a></li>
                    <li><a class="" href="{{ route('detection.map') }}"> 入侵检测</a></li>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 威胁智能</a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a class="" href="<?= url('/') ?>"> 搜索主机</a></li>
                            <li><a class="" href="<?= url('/') ?>"> 搜索漏洞</a></li>
                            <li><a class="" href="<?= url('/') ?>"> 搜索攻击日志</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 配置管理</a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" style="left: -86%;">
                            <li><a class="" href="<?= url('/') ?>"> 操作审计</a></li>
                            <li><a class="" href="<?= url('user') ?>"> 权限管理</a></li>
                        </ul>
                    </li>
                </ul>


                <form class="form-inline header-search" action="" method="post">
                    <div class="input-group">
                        <input class="form-control" type="text" name="keyword" placeholder="search">
                        <input type="hidden" name="type" value="">
                        <div class="input-group-btn">
                            <button class="btn btn-info" type="submit">Search</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </nav>


    @yield('content')


    <script src="{{ url('js/app.js') }}"></script>
    {{--<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>--}}
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        //$(".js-popover").popover(); //init弹出框
    </script>
    @stack('scripts')

</body>
<footer>
    <div class="container">

        <!--
                <a href="/"><div class="block"><span class="glyphicon glyphicon-home"></span><br>首页</div></a>
                <a href="lend?pageNow=1"><div class="block"><span class="glyphicon glyphicon-yen"></span><br>我要投资</div></a>
                <a href="login"><div class="block"><span class="glyphicon glyphicon-user"></span><br>我的账户</div></a>
        -->
        <p>
            <a href="<?= url('/') ?>">首页</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="<?= url('/') ?>">数据分析设置</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="<?= url('/') ?>">关键词设置</a>
        </p>
        <p>
            &copy;2015-<?= date('Y') ?>
            <a href="http://www.tyoupub.com" target="_blank">tyoupub.com</a>&nbsp;&nbsp;
            <a href="http://www.miitbeian.gov.cn/" target="_blank">京ICP备15057572号</a>
        </p>
    </div>
</footer>
</html>
