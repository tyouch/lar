<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.mobile.shop')
@section('title', '确认订单')


@section('content')
    <style>
        .confirm-list{color: #333; padding: 10px; margin: 0 -5px 5px; background: white;}

        .address{background: white url("{{ url('images/location-border.png') }}") repeat-x bottom;}
        .invoice{}

        .good-list{position: relative; height: 120px; color: #333; background: white}
        .good-list .thumb{width: 30%; margin: 0 10px 0 0;}
        .good-list img{width: 100px; height: 100px;}
        .good-list .content{width: 42%; font-size: 16px; padding: 5px 0 0 0;}
        .good-list .price{width: 24%; text-align: right; padding: 15px 0 0 0;}

        .cart{position: fixed; bottom: 0; z-index: 100; width: 100%;}
        .cart button{border-radius: 0; color: white; width: 50%; height: 60px;}
    </style>
    <div class="container container-mobile">
        <div class="row assets">
            <div class="col-xs-12">

                <form action="{{ route('mobile.shop.orders', ['weid'=>$weid]) }}" method="post">
                    {{--地址信息--}}
                    <div class="confirm-list address">
                        <p>收货地址：{{ $address['province'].$address['city'].$address['area'].$address['address'] }}</p>
                        <p>{{ $address['realname'].' '.$address['mobile'] }}</p>
                        <p><input type="hidden" name="addressid" value="{{ $address['id'] }}"></p>
                    </div>

                    {{--商品列表--}}
                    @foreach($goods as $good)
                        <div class="confirm-list good-list">
                            <div class="thumb pull-left">
                                <img src="{{ url($good['thumb']) }}">
                            </div>
                            <div class="content pull-left">
                                <p>{{ $good['title'] }}</p>
                                <p style="font-size: 12px; margin: 0">{{ $good['sub_title'] }}</p>
                            </div>
                            <div class="price pull-right">
                                <p style="color: red;">￥{{ $good['productprice'] }}</p>
                                <p>x {{ $good['total1'] }}</p>
                            </div>
                            <div>
                                <input type="hidden" name="goods[{{ $loop->index }}][id]" value="{{$good['id']}}">
                                <input type="hidden" name="goods[{{ $loop->index }}][title]" value="{{$good['title']}}">
                                <input type="hidden" name="goods[{{ $loop->index }}][productprice]" value="{{ $good['productprice'] }}">
                                <input type="hidden" name="goods[{{ $loop->index }}][num]" value="{{$good['num']}}">
                            </div>
                        </div>
                    @endforeach

                    {{--发票信息--}}
                    <div class="confirm-list invoice">
                        <p>发票抬头：{{ $invoice['title'] }}</p>
                        <p><input type="hidden" name="invoiceid" value="{{ $invoice['id'] }}"></p>
                    </div>


                    {{ csrf_field() }}
                    <input type="hidden" name="weid" value="{{ $weid }}">
                    <div class="cart">
                        {{--<button type="submit" class="btn btn-success btn-lg pull-right" name="submit">微信支付</button>--}}
                        <button id="wxPay" class="btn btn-success btn-lg pull-right">微信支付</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".footer-mobile").hide();

        $("#wxPay").on("click", function (e) {
            //location.href = '{!! route('mobile.shop.orders', ['weid'=>$weid, 'ids'=>$qsParam['ids'], 'total'=>$qsParam['total']]) !!}';
        });
    </script>
@endpush