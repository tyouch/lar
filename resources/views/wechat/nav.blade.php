<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/24
 * Time: 9:55
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">基本设置</div>
    <div class="panel-body">
        <ul class="list-group">
            <a href="{{ route('account.rule', ['weid'=>$weid, 'module'=>'basic']) }}" class="list-group-item list-group-item-info @if(@$module=='basic'){{ 'active' }}@endif">文字回复</a>
            <a href="{{ route('account.rule', ['weid'=>$weid, 'module'=>'news']) }}" class="list-group-item list-group-item-info @if(@$module=='news'){{ 'active' }}@endif">图文回复</a>
            <a href="{{ route('account.menu', ['weid'=>$weid]) }}" class="list-group-item list-group-item-info">自定义菜单</a>
            <a href="javascritp:;" class="list-group-item list-group-item-info">支付参数</a>
        </ul>
    </div>

</div>