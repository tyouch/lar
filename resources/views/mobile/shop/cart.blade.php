<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '购物车')

<style>
    .good{display: flex; justify-content: space-between; margin-bottom: 1%; padding: 0 2%; background: white;}
    .select{display: flex; justify-content: center; align-items: center; width: 10%;}
    .thumb{display: flex; align-items: flex-start; width: 30%}
    .content{display: flex; flex-direction: column; justify-content: space-around; width: 60%; color: #404a59;}
    .price-num{display: flex; justify-content: space-between;}
    .title{font-size: 20px;}
    .price-num{}
    .price{color: red;}
    .num{}
    .confirm{display:flex; justify-content: flex-end; position: fixed; left:0; bottom: 0; z-index: 100; width: 100%; height: 60px; background-color: rgba(255,255,210,.7);}
    .confirm a{border-radius: 0; font-size: 18px; padding: 4% 6%;}
    .total{display: flex; flex-direction: column; justify-content: space-around; padding: 0 4%; color: #404a59;}
</style>

@section('content')
    <div class="container container-mobile">
        <div class="row assets">
            <div class="col-xs-12">

                @foreach($carts as $cart)
                    <div class="good">
                        <div class="select">
                            <label class="checkbox-inline">
                                <input type="checkbox" class="cb" value="{{ $cart->id }}" data-id="{{ $cart->goodsid }}" data-num="{{ $cart->total }}">&nbsp;&nbsp;
                            </label>
                        </div>
                        <div class="thumb">
                            <img src="{{ url($cart->good['thumb']) }}" class="img-thumbnail" style="width: 100px;">
                        </div>
                        <div class="content">
                            <div class="title">{{ $cart->good['title'] }}</div>
                            <div class="spec">{{ $cart->good['sub_title'] }}</div>
                            <div class="price-num">
                                <div class="price">￥{{ $cart->productprice }}</div>
                                <div class="num">{{ $cart->total }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="confirm">
                    <div class="total">共计：￥99元</div>
                    <a id="confirm" href="javascript:;" class="btn btn-default">去结算</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('/js/jquery.cookie.js') }}"></script>
    <script>
        // 隐藏主导航
        $(".footer-mobile").hide();

        var idsA = []; //$.cookie('ids')!="" ? $.cookie('ids') : [];
        var totalA = []; //$.cookie('total')!="" ? $.cookie('total') : [];
        $(".cb").on('click', function (e) {
            var id = $(this).attr("data-id");
            var num = $(this).attr("data-num");
            var i = idsA.indexOf(id); //var e = $.inArray(id, ids);
            var j = totalA.indexOf(num);


            console.log('position:' + i);
            if ($(this).is(':checked')) {
                // -1 不存在
                i < 0 && idsA.push(id);
                j < 0 && totalA.push(num);
            } else {
                // i=0,1,2... 存在
                i >= 0 && idsA.splice(i, 1);
                j >= 0 && totalA.splice(j, 1);
            }

            var ids = idsA.join(',');
            var total = totalA.join(',');
            console.log(ids, total);
            $.cookie('ids', ids);
            $.cookie('total', total);
            ($.cookie('ids')=="" || $.cookie('total')=="") ? resetConfirm() : buildConfirm(ids, total);
        });

        $(function () {
            var ids = $.cookie('ids');
            var total = $.cookie('total');
            console.log(1, ids, total);

            (ids=="" || total=="") ? resetConfirm() : buildConfirm(ids, total);


            $(".cb").each(function(i, e){
                //console.log(2, i, e, $(this).attr("data-id"));
                idsA = ids.split(",");
                totalA = total.split(",");
                id = $(this).attr("data-id");
                console.log(id, idsA, $.inArray(id, idsA));
                i = $.inArray(id, idsA);
                if (i != -1) {
                    $(this).attr("checked", true);
                }
            });
        })

        function resetConfirm() {
            $("#confirm").attr("href", "javascript:;");
            $("#confirm").removeClass("btn-danger").addClass("btn-default");
            $.cookie('ids', null);
            $.cookie('total', null);
        }

        function buildConfirm(ids, total) {
            $("#confirm").attr("href", "confirm?weid={{ $weid }}&ids=" + ids + "&total=" + total);
            $("#confirm").removeClass("btn-default").addClass("btn-danger");
        }

    </script>
@endpush