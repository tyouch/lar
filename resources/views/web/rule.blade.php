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
    <style>
        #thumb-preview{max-width: 100%;}
        .table1{table-layout: fixed;}
        .table1 tr td{overflow: hidden; white-space: nowrap; text-overflow: ellipsis;}
    </style>

    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('web.nav', [])
                @includeIf('web.nav2', [])
            </div>

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">文字回复
                        <span class="pull-right">
                            <input type="button" class="btn btn-success btn-xs add" data-toggle="modal" data-target=".example-modal" value="添加规则">
                        </span>
                    </div>
                    <form action="" method="post">
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

                            <table class="table table1">
                                <tr>
                                    <th style="width: 20%">规则名称</th>
                                    <th style="width: 30%">触发关键字</th>
                                    <th style="width: 40%">回复</th>
                                    <th style="width: 15%; text-align: center;">状态</th>
                                    <th style="width: 15%; text-align: center;">操作</th>
                                </tr>

                                @foreach($rules as $rule)
                                <tr class="row{{ $rule['id'] }}">
                                    <td>
                                        <span class="name">{{ $rule['name'] }}</span>
                                        <span class="sr-only status">{{ $rule['status'] }}</span>
                                        <span class="sr-only displayOrder">{{ $rule['displayorder'] }}</span>
                                    </td>
                                    <td>
                                        @foreach($rule['keyword'] as $keyword)
                                            <span class="label label-primary keyword" title="{{ $keyword['content'] }}">{{ $keyword['content'] }}</span>
                                        @endforeach
                                    </td>

                                    @if($module == 'basic')
                                    <td>
                                        @foreach($rule['basicReply'] as $reply)
                                            <span class="label label-info reply" title="{{ $reply['content'] }}">{{ $reply['content'] }}</span>
                                        @endforeach
                                    </td>
                                    @endif

                                    @if($module == 'news')
                                    <td>
                                        @foreach($rule['newsReply'] as $reply)
                                            <span class="label label-info title" title="{{ $reply['title'] }}">{{ $reply['title'] }}</span>
                                            <span class="sr-only reply">{{ $reply['content'] }}</span>
                                            <span class="sr-only thumb">{{ url($reply['thumb']) }}</span>
                                            <span class="sr-only description">{{ $reply['description'] }}</span>
                                            <span class="sr-only url">{{ $reply['url'] }}</span>
                                        @endforeach
                                    </td>
                                    @endif
                                    <td>
                                        @if($rule['status']>0)
                                            <input type="button" class="btn btn-primary btn-xs status" value="启用">
                                        @else
                                            <input type="button" class="btn btn-warning btn-xs status" value="禁用">
                                        @endif
                                        @if($rule['displayorder']==1)
                                            <input type="button" class="btn btn-info btn-xs status" value="置顶">
                                        @endif
                                    </td>
                                    <td>
                                        <input type="button" class="btn btn-danger btn-xs del" data-id="{{ $rule['id'] }}" value="删除">
                                        <input type="button" class="btn btn-success btn-xs edit" data-id="{{ $rule['id'] }}" data-toggle="modal" data-target=".example-modal" value="编辑">
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="panel-footer"></div>

                    </form>

                </div>
            </div>
        </div>


        {{--模态框--}}
        <div class="modal fade example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('account.rule', ['weid'=>$weid, 'module'=>$module]) }}" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">添加规则</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <th style="width: 16%">规则名称</th>
                                    <td>
                                        <input type="text" name="name" class="form-control" maxlength="20">
                                        <input type="hidden" name="id" value="" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th>状态</th>
                                    <td>
                                        <label class="radio-inline"><input type="radio" name="status" value="1" checked="checked">启用</label>
                                        <label class="radio-inline"><input type="radio" name="status" value="0">禁用</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>置顶</th>
                                    <td>
                                        <label class="radio-inline"><input type="radio" name="displayOrder" value="1">置顶</label>
                                        <label class="radio-inline"><input type="radio" name="displayOrder" value="0" checked="checked">普通</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>触发关键字</th>
                                    <td><input type="text" name="keyword" class="form-control" maxlength="20"></td>
                                </tr>

                                @if($module == 'news')
                                    <tr>
                                        <th>回复标题</th>
                                        <td><input type="text" name="title" class="form-control" maxlength="20"></td>
                                    </tr>
                                    <tr>
                                        <th>回复封面</th>
                                        <td>
                                            <div style="position: relative; margin-bottom: 15px;">
                                                <div class="input-group" style="position: absolute;" type="text">
                                                    <input id="txt-preview" class="form-control" placeholder="大图片建议尺寸：700像素 * 300像素 上传选择文件" disabled>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-open"></span>
                                                    </span>
                                                </div>
                                                <input style="position: absolute; opacity: 0" id="thumb" name="thumb" type="file" class="form-control">
                                            </div>&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="sr-only">封面预览</td>
                                        <td><img id="thumb-preview" src="" class="img-thumbnail"></td>
                                    </tr>
                                    <tr>
                                        <th>回复描述</th>
                                        <td><textarea rows="3" name="description" class="form-control"></textarea></td>
                                    </tr>
                                @endif

                                <tr>
                                    <th>回复内容</th>
                                    <td><textarea rows="3" name="reply" class="form-control"></textarea></td>
                                </tr>

                                @if($module == 'news')
                                    <tr>
                                        <th>来源</th>
                                        <td><input type="text" name="url" class="form-control" maxlength="100"></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="modal-footer">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="保存规则">
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
        $(".modal-content").find("input[name=id]").val("");
        $(".modal-content").find("input[name=name]").val("");
        $(".modal-content").find("input[name=keyword]").val("");
        $(".modal-content").find("input[name=title]").val("");
        $(".modal-content").find("textarea[name=description]").val("");
        $(".modal-content").find("textarea[name=reply]").val("");
        $(".modal-content").find("input[name=url]").val("");
        $(".modal-content").find("#thumb-preview").attr("src", "");
        $('.example-modal').css("z-index", 99999);
    });

    $(".del").on('click', function (e) {
        id = $(this).attr("data-id");
        if(confirm("确认要删除这条规则吗？【rid:"+id+"】")) {
            alert('正在删除...');
        }else{
            alert('放弃删除...');
        }
    });

    $(".edit").on('click', function (e) {
        id = $(this).attr("data-id");
        name = $(".row"+id).find(".name").html();
        status = $(".row"+id).find(".status").html();
        displayOrder = $(".row"+id).find(".displayOrder").html();
        name = $(".row"+id).find(".name").html();
        keyword = $(".row"+id).find(".keyword").html();
        title = $(".row"+id).find(".title").html();
        thumb = $(".row"+id).find(".thumb").html();
        description = $(".row"+id).find(".description").html();
        reply = $(".row"+id).find(".reply").html();
        url = $(".row"+id).find(".url").html();
        console.log(name, keyword, title, thumb, description, reply, url, status, displayOrder);

        $(".modal-content").find("input[name=id]").val(id);
        $(".modal-content").find("input[name=name]").val(name);

        $(".modal-content").find("input[name=status]").removeAttr("checked"); //
        $(".modal-content").find("input[name=status][value="+status+"]").attr("checked", true); //
        $(".modal-content").find("input[name=displayOrder]").removeAttr("checked"); //
        $(".modal-content").find("input[name=displayOrder][value="+displayOrder+"]").attr("checked", true); //

        $(".modal-content").find("input[name=keyword]").val(keyword);
        $(".modal-content").find("input[name=title]").val(title);
        $(".modal-content").find("textarea[name=description]").val(description);
        $(".modal-content").find("textarea[name=reply]").val(reply);
        $(".modal-content").find("input[name=url]").val(url);

        $(".modal-content").find("#thumb-preview").attr("src", thumb);
        $('.example-modal').css("z-index", 99999);
    });
</script>
@endpush
