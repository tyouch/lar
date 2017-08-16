<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/11
 * Time: 16:21
 */
?>

@extends('layouts.app')
@section('title', '商品管理')


@section('content')
    <link rel="stylesheet" href="{{ url('css/fileinput.css') }}">

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
                            <input type="button" class="btn btn-success btn-xs add" data-id="0" value="添加商品">
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
                                                <a class="btn btn-danger btn-xs del" data-id="{{ $good['id'] }}">删除</a>
                                                <a class="btn btn-success btn-xs edit" data-id="{{ $good['id'] }}">编辑</a>
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
                    <form action="{{ route('shop.goods', $pagePram) }}" method="post" enctype="multipart/form-data">
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
                                                    <option value="0" selected readonly>请选择一级分类</option>
                                                    @foreach($category1 as $cate)
                                                        <option value="{{ $cate['id'] }}">{{ $cate['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="goods[ccate]" class="form-control ccate" style="color: #999;">
                                                    <option value="0" selected readonly>请选择二级分类</option>
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
                                                <img id="thumb-preview" src="" data-id="{{ url('') }}" class="img-thumbnail">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="sr-only1">幻灯片</th>
                                    <td>
                                        <div class="form-group has-feedback">
                                            <input id="thumb_url" name="fileinput[]" type="file" multiple>
                                        </div>
                                        <div class="thumb_url"></div>
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
                                <tr>
                                    <th></th>
                                    <td>
                                        @include('UEditor::head')
                                        <script id="ueditor" class="ueditorRet" name="goods[content]" type="text/plain"></script>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="保存商品">
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
    // title 提示
    $('[data-toggle="tooltip"]').tooltip();

    // 图像预览
    $("#thumb").on('change', function (e) {
        $src = window.URL.createObjectURL(this.files[0]); //alert($src);
        $("#thumb-preview").attr("src", $src);
        $("#txt-preview").attr("placeholder", $src);
    });

    // 选择一级分类 自动选择二级分类
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
                $(".ccate").html('<option value="" readonly>请选择二级分类</option>');
                $.each(d, function(i, o) {
                    $(".ccate").append('<option value="' + o.id + '">' + o.name + '</option>');
                });
            }
        });
    });

    // 添加商品
    $(".add").on('click', function (e) {
        //window.location.reload();
        $('.example-modal').modal('show');
        $("input[type=text]").val("");
        $("select option").removeAttr("selected");
        $("input[type=checkbox]").removeAttr("checked");
        $("#thumb-preview").attr("src", "");
        $("textarea").html("");

        // 清除批量上传编辑回填
        $(".thumb_url").html("");
        $('#thumb_url').fileinput('clear');

        // 清除内容
        ue.ready(function() {
            ue.setContent("");
        });
    });

    // 删除商品
    $(".del").on('click', function (e) {
        id = $(this).attr('data-id');
        if (confirm("确认要删除这条规则吗？【rid:" + id + "】")) {
            $.ajax({
                url         : '{{ route('shop.goods', ['weid'=>$weid]) }}',
                Type        : 'POST',
                dataType    : 'JSON',
                data        : {
                    _token  : '{{ csrf_token() }}',
                    op      : 'delete',
                    id      : id
                },
                headers     : {
                    'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
                },
                success   : function (d, s) {
                    console.log(d, s);
                    alert('删除[' + s +']');
                    window.location.reload();
                }
            });
        } else {
            alert('放弃删除...');
        }
    });

    //...
    $("button").on('click', function (e) {
        //alert('del.ing');
    });

    // 编辑回填
    $(".edit").on('click', function (e) { //alert(parseInt($(".thumb_url").children().length));
        $('.example-modal').modal('show');
        id = $(this).attr('data-id');
        baseUrl = $("#thumb-preview").attr("data-id")+'/';
        $.ajax({
            url         : '{{ route('shop.goods', ['weid'=>$weid]) }}',
            Type        : 'POST',
            dataType    : 'JSON',
            data        : {
                _token  : '{{ csrf_token() }}',
                op      : 'modalFill',
                id      : id
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
                $("input[name='goods[brand]']").val(d.brand);
                $("input[name='goods[productprice]']").val(d.productprice);
                $("input[name='goods[marketprice]']").val(d.marketprice);
                $("input[name='goods[costprice]']").val(d.costprice);
                $("input[name='goods[total]']").val(d.total);
                $("input[name='goods[goodssn]']").val(d.goodssn);
                $("input[name='goods[productsn]']").val(d.productsn);
                $("input[name='goods[maxbuy]']").val(d.maxbuy);
                $("input[name='goods[sales]']").val(d.sales);
                $("textarea[name='goods[description]']").html(d.description);

                // 回填商品属性
                if(d.isrecommand == 1) {
                    $("input[name='goods[isrecommand]']").attr("checked", true);
                }
                if(d.isnew == 1) {
                    $("input[name='goods[isnew]']").attr("checked", true);
                }
                if(d.ishot == 1) {
                    $("input[name='goods[ishot]']").attr("checked", true);
                }
                if(d.istime == 1) {
                    $("input[name='goods[istime]']").attr("checked", true);
                }

                // 回填日期范围
                $("input[name='goods[timestart]']").val(d.timestart);

                // 回填分类
                $("select[name='goods[pcate]'] option").removeAttr("selected");
                $("select[name='goods[pcate]'] option[value=" + d.pcate + "]").attr("selected", true);

                $(".ccate").html('<option value="" readonly>请选择二级分类</option>');
                $(".ccate").append('<option value="'+d.ccate+'" selected>'+d.category.name+'</option>');

                // 回填预览图
                //$("input[name='goods[thumb]']").val(d.thumb);
                $("#thumb-preview").attr("src", baseUrl + '/' + d.thumb);

                // 回填幻灯片图
                var Preview = [];
                var PreviewConfig = []; 
                $.each(d.thumb_url, function (i, o) {
                    Preview[i] ='<img src="' + baseUrl + o + '" class="file-preview-image" style="width:auto;height:160px;">';
                    el = '<input type="hidden" name="goods[thumb_url][' + i + ']" value="'+ o +'">';
                    $(".thumb_url").append(el);

                    PreviewConfig[i] = {
                        caption : o,//.split("/")[5]
                        width   : '160px',
                        url     : '{{ route('shop.goods', ['weid'=>$weid]) }}', // server delete action
                        key     : id,
                        extra   : {
                            _token  : '{{ csrf_token() }}',
                            op      : 'fileinput-del',
                            id      : id
                        }
                    }
                });

                console.log(Preview, PreviewConfig);
                $(".thumb_url").html("");
                $('#thumb_url').fileinput('clear');
                $('#thumb_url').fileinput('refresh', {
                    initialPreview: Preview, //预览图片的设置
                    initialPreviewConfig: PreviewConfig,
                });

                // 回填富文本内容
                ue.ready(function() {
                    ue.setContent(d.content);
                });


                //$("select[name='goods[ccate]']").val(d.ccate);
                //$(".modal-content").find("input[name=isrecommand]").removeAttr("checked"); //
                //$(".modal-content").find("input[name=isrecommand][value=" + isrecommand + "]").attr("checked", true); //

            }
        });
    });
</script>

<script src="{{ asset('/js/fileinput.js') }}"></script>
<script src="{{ asset('/js/fileinput_zh.js') }}"></script>
<script>
    // 批量上传幻灯片
    $('#thumb_url').fileinput({
        language: 'zh',
        allowedFileExtensions : ['jpg', 'png','gif'],
        maxFileCount: 6,
        //uploadAsync: false,
        uploadUrl: '{{ route('shop.goods', ['weid'=>$weid]) }}',
        uploadExtraData: {
            _token  : '{{ csrf_token() }}',
            op      : 'fileinput',
        },
        initialPreview: []

    }).on("fileuploaded", function(event, data, previewId, index, jqXHR) {
        console.log(event, data, previewId, index, jqXHR);

        i = parseInt($(".thumb_url").children("input").length);
        if(data.response){
            el = '<input type="hidden" name="goods[thumb_url]['+(i++)+']" value="'+data.response.thumb_url+'">';
            $(".thumb_url").append(el);
        }
    }).on('filecleared', function(event, data) {
        console.log('del:', data);
    });
</script>


<script>
    // ueditor富文本编辑器
    var ue = UE.getEditor("ueditor",{
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo'],
            ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
        ],
        autoHeightEnabled: true,
        autoFloatEnabled: true,
        initialFrameWidth: '715px',
        initialFrameHeight: '400px'
    });
    ue.ready(function(){
        //因为Laravel有防csrf防伪造攻击的处理所以加上此行
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
    });
    $("#edui1").css('width','715px');
</script>
@endpush
