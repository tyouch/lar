<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.app')
@section('title', '公众号管理')


@section('content')
    <link rel="stylesheet" href="{{ url('css/fileinput.css') }}">
    <style>
        .table1 {
            table-layout: fixed;
        }

        .table1 tr td {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .assets input, .assets select, .daterangepicker .calendar-date,
        .daterangepicker.ltr .calendar-table, .daterangepicker .input-mini, .calendar-time {
            color: white;
            background: #323c48;
            border-radius: 5px;
        }
        .daterangepicker td.in-range, .daterangepicker select{
            background: #5bc0de;
        }
        .daterangepicker td.off,
        .daterangepicker td.off.in-range,
        .daterangepicker td.off.start-date,
        .daterangepicker td.off.end-date ,
        .file-preview {
            background: #555;
        }
        .assets .input-group-addon{
            color: #fff;
            background: #5bc0de;
        }
    </style>

    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('web.nav', [])
                @includeIf('web.nav2', [])
            </div>

            <div class="col-md-9">
                <div class="panel panel-default">

                    <form action="{{ route('shop.goods', ['weid'=>$weid]) }}" method="get">
                        <div class="panel-heading">
                            商品管理
                            <span class="pull-right">
                            <input type="button" class="btn btn-success btn-xs add" data-id="0" data-toggle="modal" data-target=".example-modal" value="添加商品">
                        </span>
                        </div>
                        <div class="panel-body">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <div class="input-group">
                                                <span class="input-group-addon">商品ID</span>
                                                <input type="text" name="gid" class="form-control" value="{{ @$pagePram['gid'] }}" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">关键字</span>
                                            <input type="text" name="keyword" class="form-control" value="{{ @$pagePram['keyword'] }}" maxlength="20">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">状态</span>
                                            <select name="status" class="form-control">
                                                <option value="" readonly>请选择状态</option>
                                                <option value="1" @if(@$pagePram['status']==1)selected="selected"@endif>上架</option>
                                                <option value="2" @if(@$pagePram['status']==2)selected="selected"@endif>下架</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <select name="pcate" class="form-control pcate" style="color: #999;">
                                                <option value="" readonly>请选择一级分类</option>
                                                @foreach($category1 as $cate)
                                                    <option value="{{ $cate['id'] }}" @if(@$pagePram['pcate']==$cate['id'])selected="selected"@endif>{{ $cate['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <select name="ccate" class="form-control ccate" style="color: #999;">
                                                <option value="" readonly>请选择二级分类</option>
                                                @foreach($category2 as $cate)
                                                    <option value="{{ $cate['id'] }}" @if(@$pagePram['ccate']==$cate['id'])selected="selected"@endif>{{ $cate['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" name="weid" value="{{ $weid }}">
                                        <button type="submit" class="btn btn-primary" style="width: 100%">
                                            <span class="glyphicon glyphicon-search"></span>
                                            <span>搜索</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="well">
                                <table class="table table1">
                                    <tr>
                                        <th style="width: 6%">#ID</th>
                                        <th style="width: 30%">商品标题</th>
                                        <th style="width: 20%">商品属性</th>
                                        <th style="width: 10%">状态</th>
                                        <th style="width: 15%">操作</th>
                                    </tr>
                                    @foreach($goods as $good)
                                        <tr>
                                            <th>{{ $good['id'] }}</th>
                                            <td><span data-toggle="tooltip" data-placement="top" title="{{ $good['title'] }}">「{{ $good['category']['name'] }}」 {{ $good['title'] }}</span></td>
                                            <td>
                                                <span class="label @if($good['isnew']){{ 'label-info' }}@else{{ 'label-default' }}@endif">新品</span>
                                                <span class="label @if($good['ishot']){{ 'label-info' }}@else{{ 'label-default' }}@endif">热卖</span>
                                                <span class="label @if($good['isrecommand']){{ 'label-info' }}@else{{ 'label-default' }}@endif">首页</span>
                                                <span class="label @if($good['isdiscount']){{ 'label-info' }}@else{{ 'label-default' }}@endif">折扣</span>
                                            </td>
                                            <td>
                                                @if($good['status']==1)<span class="label label-info">上架</span>@endif
                                                    @if($good['status']==2)<span class="label label-default">下架</span>@endif

                                            </td>
                                            <td>
                                                <input type="button" class="btn btn-danger btn-xs del" data-id="{{ $good['id'] }}" value="删除">
                                                <input type="button" class="btn btn-success btn-xs edit" data-id="{{ $good['id'] }}" data-toggle="modal" data-target=".example-modal" value="编辑">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                                <div style="text-align: center;">
                                    {!! $goods->appends($pagePram)->links() !!}
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{--模态框--}}
        <div class="modal fade example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('shop.goods', ['weid'=>$weid]) }}" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">添加商品</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <th style="width: 16%">商品名称</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="text" name="goods[title]" class="form-control" maxlength="60">
                                                <input type="hidden" name="goods[id]" value="" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">单位</span>
                                                    <input type="text" name="goods[unit]" class="form-control" placeholder="如: 个,件,包" maxlength="2">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>副标题</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="text" name="goods[sub_title]" class="form-control" maxlength="60">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">重量</span>
                                                    <input type="text" name="goods[weight]" class="form-control" placeholder="克" maxlength="2">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品属性</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="checkbox-inline"><input type="checkbox" name="goods[isrecommand]" value="1">首页</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="goods[isnew]" value="1">新品</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="goods[ishot]" value="1">热卖</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="goods[isdiscount]" value="1">折扣</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="goods[istime]" value="1">限时</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    <input type="text" name="goods[timestart]" id="reservation" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>分类</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="goods[pcate]" class="form-control pcate" style="color: #999;">
                                                    <option value="0" readonly>请选择一级分类</option>
                                                    @foreach($category1 as $cate)
                                                        <option value="{{ $cate['id'] }}">{{ $cate['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="goods[ccate]" class="form-control ccate" style="color: #999;">
                                                    <option value="0" readonly>请选择二级分类</option>
                                                    @foreach($category2 as $cate)
                                                        <option value="{{ $cate['id'] }}">{{ $cate['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>价格</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">销售价</span>
                                                    <input type="text" name="goods[productprice]" class="form-control" value="">
                                                    <span class="input-group-addon">元</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">市场价</span>
                                                    <input type="text" name="goods[marketprice]" class="form-control" value="">
                                                    <span class="input-group-addon">元</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">成本价</span>
                                                    <input type="text" name="goods[costprice]" class="form-control" value="">
                                                    <span class="input-group-addon">元</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>图片</th>
                                    <td>
                                        <div class="row" style="margin-bottom: 15px;">
                                            <div class="col-md-4" style="position: relative;">
                                                <div class="input-group" style="position: absolute; width: 88%;">
                                                    <input type="text" id="txt-preview" class="form-control" placeholder="商品图" readonly>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-open"></span></span>
                                                </div>
                                                <input type="file" id="thumb" name="goods[thumb]" class="form-control" style="position: absolute; width: 88%; opacity: 0">
                                            </div>&nbsp;
                                            <div class="col-md-4">
                                                <img id="thumb-preview" src="" class="img-thumbnail">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="sr-only1">幻灯片</th>
                                    <td>
                                        <div class="form-group has-feedback">
                                            <input id="thumb_url" name="thumb_url[]" type="file" multiple>
                                        </div>
                                    </td>
                                </tr>


                                <tr>
                                    <th>其他项</th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">品牌</span>
                                                    <input type="text" name="goods[brand]" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">货号</span>
                                                    <input type="text" name="goods[goodssn]" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">条码</span>
                                                    <input type="text" name="goods[productsn]" class="form-control" maxlength="55" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">排序</span>
                                                    <input type="text" name="goods[displayorder]" class="form-control" maxlength="5" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">库存</span>
                                                    <input type="text" name="goods[total]" class="form-control" value="">
                                                    <span class="input-group-addon">件</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">最多购</span>
                                                    <input type="text" name="goods[maxbuy]" class="form-control" value="">
                                                    <span class="input-group-addon">件</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">已售</span>
                                                    <input type="text" name="goods[sales]" class="form-control" maxlength="55" value="">
                                                    <span class="input-group-addon">件</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">积分</span>
                                                    <input type="text" name="goods[credit]" class="form-control" maxlength="5" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td>

                                    </td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td>
                                        <span class="label label-info">库存</span>
                                        <label class="radio-inline"><input type="radio" name="goods[totalcnf]" value="0">拍下减</label>
                                        <label class="radio-inline"><input type="radio" name="goods[totalcnf]" value="1" checked="checked">付款减</label>
                                        <label class="radio-inline"><input type="radio" name="goods[totalcnf]" value="2">永不减</label>&nbsp;&nbsp;

                                        <span class="label label-info">状态</span>
                                        <label class="radio-inline"><input type="radio" name="goods[status]" value="1">上架</label>
                                        <label class="radio-inline"><input type="radio" name="goods[status]" value="2" checked="checked">下架</label>&nbsp;&nbsp;

                                        <span class="label label-info">类型</span>
                                        <label class="radio-inline"><input type="radio" name="goods[type]" value="1" checked="checked">实体商品</label>
                                        <label class="radio-inline"><input type="radio" name="goods[type]" value="2">虚拟商品</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品详情</th>
                                    <td><textarea rows="3" name="goods[description]" class="form-control" placeholder="改造编辑器"></textarea></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="保存分类">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')
<script src="{{ asset('/js/moment.js') }}"></script>
<script src="{{ asset('/js/daterangepicker.js') }}"></script>
<script>
    // 日期插件 必须放在前面
    $('#reservation').daterangepicker({
        "autoApply": true,
        "timePicker": true,
        "timePicker24Hour": true,
        "timePickerSeconds": true,
        "opens": "left",
        "locale": {
            "format": "YYYY-MM-DD HH:mm",
        }
    }, function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });
</script>
<script>
    // title tip
    $('[data-toggle="tooltip"]').tooltip();

    $("#thumb").on('change', function (e) {
        $src = window.URL.createObjectURL(this.files[0]); //alert($src);
        $("#thumb-preview").attr("src", $src);
        $("#txt-preview").attr("placeholder", $src);
    });

    $(".pcate").on('change', function (e) {
        $.ajax({
            url         : '{{ route('shop.goods', ['weid'=>$weid]) }}',
            Type        : 'POST',
            dataType    : 'JSON',
            data        : {
                _token  : '{{ csrf_token() }}',
                op      : 'ccate',
                id      : $(this).val()
            },
            headers     : {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            success     : function (d, s) {
                console.log(d, s);
                el = '<option value="" readonly>请选择二级分类</option>';
                $.each(d, function(i, o) {
                    el += '<option value="' + o.id + '">' + o.name + '</option>';
                    $(".ccate").html(el);
                });
            }
        });
    });

    $(".edit").on('click', function (e) {
        $.ajax({
            url         : '{{ route('shop.goods', ['weid'=>$weid]) }}',
            Type        : 'POST',
            dataType    : 'JSON',
            data        : {
                _token  : '{{ csrf_token() }}',
                op      : 'modalFill',
                id      : $(this).attr('data-id')
            },
            headers     : {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            success   : function (d, s) {
                console.log(d, s);
                $("input[name='goods[id]']").val(d.id);
                $("input[name='goods[title]']").val(d.title);
                $("input[name='goods[unit]']").val(d.unit);
                $("input[name='goods[sub_title]']").val(d.sub_title);
                $("input[name='goods[weight]']").val(d.weight);
                $("select[name='goods[pcate]']").val(d.pcate);
                $("select[name='goods[ccate]']").val(d.ccate);
                $("input[name='goods[brand]']").val(d.brand);
                $("input[name='goods[productprice]']").val(d.productprice);
                $("input[name='goods[marketprice]']").val(d.marketprice);
                $("input[name='goods[costprice]']").val(d.costprice);

                $("input[name='goods[style]']").val(d.style);
                $("input[name='goods[total]']").val(d.total);
                $("input[name='goods[thumb]']").val(d.thumb);

                $("input[name='goods[goodssn]']").val(d.goodssn);
                $("input[name='goods[productsn]']").val(d.productsn);
                $("input[name='goods[maxbuy]']").val(d.maxbuy);
                $("input[name='goods[sales]']").val(d.sales);
                $("textarea[name='goods[description]']").val(d.description);

                $("input[name='goods[timestart]']").val(d.timestart);
            }
        });
    });
</script>
<script src="{{ asset('/js/fileinput.js') }}"></script>
<script src="{{ asset('/js/fileinput_zh.js') }}"></script>
<script>
    $('#thumb_url').fileinput({
        language: 'zh',
        uploadUrl: '#',
        allowedFileExtensions : ['jpg', 'png','gif'],
        uploadUrl: '{{ route('shop.goods', ['weid'=>$weid]) }}',
        uploadExtraData: {
            _token  : '{{ csrf_token() }}',
            op      : 'fileinput',
        }
    }).on("filebatchselected", function(event, files) {
        //$(this).fileinput("upload");
        //console.log(event, files);
    }).on("fileuploaded", function(event, data, previewId, index) {
        console.log(data, previewId, index);
            if(data.response){
                //alert('处理成功');
            }
        });
</script>
@endpush
