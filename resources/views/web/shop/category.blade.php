<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/8/2
 * Time: 14:48
 */
?>

@extends('layouts.app')
@section('title', '商品分类管理')


@section('content')
    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('web.nav', [])
                @includeIf('web.nav2', [])
            </div>

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        管理分类
                        <span class="pull-right">
                            <input type="button" class="btn btn-success btn-xs add" data-id="0" data-toggle="modal" data-target=".example-modal" value="添加规则">
                        </span>
                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table class="table">
                            <tr>
                                <th style="width: 3%"></th>
                                <th style="width: 6%">排序</th>
                                <th>名称</th>
                                <th>图片</th>
                                <th style="width: 26%">操作</th>
                            </tr>
                            @foreach($category as $cate)
                                <tr>
                                    <td>
                                        @if($cate['parentid'] == 0)
                                            <span class="glyphicon glyphicon-menu-down"></span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="displayorder{{ $cate['id'] }}">{{ $cate['displayorder'] }}</span>
                                    </td>
                                    <td>
                                        <span class="name{{ $cate['id'] }}">{{ $cate['name'] }}</span>

                                        <span class="sr-only id{{ $cate['id'] }}">{{ $cate['id'] }}</span>
                                        <span class="sr-only parentid{{ $cate['id'] }}">{{ $cate['parentid'] }}</span>
                                        <span class="sr-only description{{ $cate['id'] }}">{{ $cate['description'] }}</span>
                                        <span class="sr-only thumb{{ $cate['id'] }}">{{ url($cate['thumb']) }}</span>
                                        <span class="sr-only isrecommand{{ $cate['id'] }}">{{ $cate['isrecommand'] }}</span>
                                        <span class="sr-only enabled{{ $cate['id'] }}">{{ $cate['enabled'] }}</span>
                                    </td>
                                    <td><span><img src="{{ url($cate['thumb']) }}" class="img-circle" style="width: 30px;"></span></td>
                                    <td>
                                        <input type="button" class="btn btn-danger btn-xs del" data-id="{{ $cate['id'] }}" value="删除">
                                        <input type="button" class="btn btn-success btn-xs edit" data-id="{{ $cate['id'] }}" data-toggle="modal" data-target=".example-modal"
                                               value="编辑">
                                        @if($cate['parentid'] == 0)
                                            <input type="button" class="btn btn-info btn-xs add" data-id="{{ $cate['id'] }}" data-toggle="modal" data-target=".example-modal"
                                                   value="添加子分类">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        <div style="text-align: center;">
                            {!! $category->appends(['weid'=>$weid])->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--模态框--}}
        <div class="modal fade example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('shop.category', ['weid'=>$weid]) }}" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">添加分类</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <th style="width: 16%">名称</th>
                                    <td>
                                        <input type="text" name="name" class="form-control" maxlength="20">
                                        <input type="hidden" name="id" value="" class="form-control">
                                        <input type="hidden" name="parentid" value="" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th>图片</th>
                                    <td>
                                        <div style="position: relative; margin-bottom: 15px;">
                                            <div class="input-group" style="position: absolute;" type="text">
                                                <input id="txt-preview" class="form-control" placeholder="大图片建议尺寸：300像素 * 300像素 上传选择文件" disabled>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-open"></span>
                                                </span>
                                            </div>
                                            <input style="position: absolute; opacity: 0" id="thumb" name="thumb" type="file" class="form-control">
                                        </div>&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td class="sr-only">图片预览</td>
                                    <td><img id="thumb-preview" src="" class="img-thumbnail"></td>
                                </tr>
                                <tr>
                                    <th>描述</th>
                                    <td><textarea rows="3" name="description" class="form-control"></textarea></td>
                                </tr>
                                <tr>
                                    <th style="width: 16%">排序</th>
                                    <td>
                                        <input type="text" name="displayorder" class="form-control" maxlength="5" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th>首页推荐</th>
                                    <td>
                                        <label class="radio-inline"><input type="radio" name="isrecommand" value="1" checked="checked">是</label>
                                        <label class="radio-inline"><input type="radio" name="isrecommand" value="0">否</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>是否显示</th>
                                    <td>
                                        <label class="radio-inline"><input type="radio" name="enabled" value="1">是</label>
                                        <label class="radio-inline"><input type="radio" name="enabled" value="0" checked="checked">否</label>
                                    </td>
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
<script>
    $("#thumb").on('change', function (e) {
        $src = window.URL.createObjectURL(this.files[0]); //alert($src);
        $("#thumb-preview").attr("src", $src);
        $("#txt-preview").attr("placeholder", $src);
    });

    $(".add").on('click', function (e) {
        pid = $(this).attr("data-id");
        $(".modal-content").find("input[name=id]").val("");
        $(".modal-content").find("input[name=parentid]").val(pid);
        $(".modal-content").find("input[name=name]").val("");
        $(".modal-content").find("input[name=displayorder]").val("0");
        $(".modal-content").find("textarea[name=description]").val("描述");
        $(".modal-content").find("#thumb-preview").attr("src", "");
        $(".modal-content").find("#txt-preview").val("");
        $('.example-modal').css("z-index", 99999);
    });

    $(".del").on('click', function (e) {
        id = $(this).attr("data-id");
        if (confirm("确认要删除这条规则吗？【rid:" + id + "】")) {
            alert('正在删除...');
        } else {
            alert('放弃删除...');
        }
    });

    $(".edit").on('click', function (e) {
        id = $(this).attr("data-id");

        name = $(".name" + id).html();
        cid = $(".id" + id).html();
        parentid = $(".parentid" + id).html();
        description = $(".description" + id).html();
        displayorder = $(".displayorder" + id).html();

        thumb = $(".thumb" + id).html();
        isrecommand = $(".isrecommand" + id).html();
        enabled = $(".enabled" + id).html();
        console.log(123);
        console.log(name, cid, displayorder, description, thumb, isrecommand, enabled);

        $(".modal-content").find("input[name=name]").val(name);
        $(".modal-content").find("input[name=id]").val(cid);
        $(".modal-content").find("input[name=parentid]").val(parentid);
        $(".modal-content").find("textarea[name=description]").val(description);
        $(".modal-content").find("input[name=displayorder]").val(displayorder);
        $(".modal-content").find("#thumb-preview").attr("src", thumb);

        $(".modal-content").find("input[name=isrecommand]").removeAttr("checked"); //
        $(".modal-content").find("input[name=isrecommand][value=" + isrecommand + "]").attr("checked", true); //
        $(".modal-content").find("input[name=enabled]").removeAttr("checked"); //
        $(".modal-content").find("input[name=enabled][value=" + enabled + "]").attr("checked", true); //

        $('.example-modal').css("z-index", 99999);
    });
</script>
@endpush
