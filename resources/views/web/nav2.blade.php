<?php
/**
* Created by PhpStorm.
* User: zhaoyao
* Date: 2017/7/24
* Time: 9:55
*/
?>


<div class="panel panel-default">
    <div class="panel-heading">主要业务</div>
    <div class="panel-body">


        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">商店</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse @if(@substr($module, 0, 4) == 'shop'){{ 'in' }}@endif" role="tabpanel" aria-labelledby="headingOne">
                    <ul class="list-group">
                        <a href="{{ route('shop.category', ['weid'=>$weid]) }}" class="list-group-item list-group-item-info @if(@$module=='shopCategory'){{ 'active' }}@endif">商品分类</a>
                        <a href="{{ route('shop.goods', ['weid'=>$weid]) }}" class="list-group-item list-group-item-info @if(@$module=='shopGoods'){{ 'active' }}@endif">商品管理</a>
                        <a href="{{ route('shop.orders', ['weid'=>$weid]) }}" class="list-group-item list-group-item-info @if(@$module=='shopOrders'){{ 'active' }}@endif">订单管理</a>
                        <a href="{{ route('shop.distribution', ['weid'=>$weid]) }}" class="list-group-item list-group-item-info">物流管理</a>
                        <a href="{{ route('shop.service', ['weid'=>$weid]) }}" class="list-group-item list-group-item-info">售后管理</a>
                        <a href="{{ route('shop.slide', ['weid'=>$weid]) }}" class="list-group-item list-group-item-info">幻灯片管理</a>
                    </ul>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            其他模块 #2
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <ul class="list-group">
                        <a href="javascritp:;" class="list-group-item list-group-item-info">xxx</a>
                        <a href="javascritp:;" class="list-group-item list-group-item-info">xxx</a>
                        <a href="javascritp:;" class="list-group-item list-group-item-info">xxx</a>
                    </ul>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            其他模块 #3
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <ul class="list-group">
                        <a href="javascritp:;" class="list-group-item list-group-item-info">xxx</a>
                        <a href="javascritp:;" class="list-group-item list-group-item-info">xxx</a>
                        <a href="javascritp:;" class="list-group-item list-group-item-info">xxx</a>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>
