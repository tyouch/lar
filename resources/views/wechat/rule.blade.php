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
        #menuSet .input-group{width: 200px; margin: 10px 0;}
        #menuSet .form-meun{width: 277px;}
        .input-group-sub{margin: 0 20px;}
    </style>

    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('wechat.nav', [])
                @includeIf('wechat.nav2', [])
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

                            <table class="table">
                                <tr>
                                    <th style="width: 10%">规则名称</th>
                                    <th>触发关键字</th>
                                    <th>回复</th>
                                    <th style="width: 12%">操作</th>
                                </tr>
                                @if($module == 'basic')
                                    @foreach($rules as $rule)
                                    <tr class="row{{ $rule['id'] }}">
                                        <td class="name">{{ $rule['name'] }}</td>
                                        <td>
                                            @foreach($rule['keyword'] as $keyword)
                                                <span class="label label-primary keyword">{{ $keyword['content'] }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($rule['basicReply'] as $reply)
                                                <span class="label label-info basicReply">{{ $reply['content'] }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-danger btn-xs">删除</a>
                                            <input type="button" class="btn btn-success btn-xs edit" data-id="{{ $rule['id'] }}" data-toggle="modal" data-target=".example-modal" value="编辑">
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif

                                @if($module == 'news')
                                    @foreach($rules as $rule)
                                        <tr>
                                            <td>{{ $rule['name'] }}</td>
                                            <td>
                                                @foreach($rule['keyword'] as $keyword)
                                                    <span class="label label-primary">{{ $keyword['content'] }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($rule['newsReply'] as $reply)
                                                    <span class="label label-info">{{ $reply['title'] }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="" class="btn btn-danger btn-xs">删除</a>
                                                <a href="" class="btn btn-success btn-xs edit">编辑</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                        <div class="panel-footer">
                            {{ csrf_field() }}
                        </div>

                    </form>

                </div>
            </div>
        </div>


        {{--模态框--}}
        <div class="modal fade example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('account.rule', ['weid'=>$weid, 'module'=>$module]) }}" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">添加规则</h4>
                        </div>
                        <div class="modal-body">
                            @if($module == 'basic')
                            <table class="table">
                                <tr>
                                    <th>规则名称</th>
                                    <td>
                                        <input type="text" name="name" class="form-control">
                                        <input type="hidden" name="id" value="" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th>触发关键字</th>
                                    <td><input type="text" name="keyword" class="form-control"></td>
                                </tr>
                                    <th>回复</th>
                                    <td><textarea type="text" name="basicReply" class="form-control"></textarea></td>
                                </tr>
                            </table>
                            @endif

                            @if($module == 'news')
                            <table class="table">
                                <tr>
                                    <th>规则名称</th>
                                    <td>
                                        <input type="text" name="name" class="form-control" maxlength="20">
                                        <input type="hidden" name="type" value="news" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th>触发关键字</th>
                                    <td><input type="text" name="keyword" class="form-control" maxlength="20"></td>
                                </tr>
                                    <th>回复标题</th>
                                    <td><input type="text" name="title" class="form-control" maxlength="20"></td>
                                </tr>
                                </tr>
                                    <th>回复封面</th>
                                    <td><input type="file" name="thumb" class="form-control"></td>
                                </tr>
                                </tr>
                                    <th>回复描述</th>
                                    <td><textarea type="text" name="description" class="form-control"></textarea></td>
                                </tr>
                                </tr>
                                    <th>回复内容</th>
                                    <td><textarea type="text" name="reply" class="form-control"></textarea></td>
                                </tr>
                                <tr>
                                    <th>来源</th>
                                    <td><input type="text" name="url" class="form-control" maxlength="100"></td>
                                </tr>
                            </table>
                            @endif
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
    $(".add").on('click', function (e) {
        $(".modal-content").find("input[name=id]").val("");
        $(".modal-content").find("input[name=name]").val("");
        $(".modal-content").find("input[name=keyword]").val("");
        $(".modal-content").find("textarea[name=basicReply]").val("");
        $('.example-modal').css("z-index", 99999);
    });

    $(".edit").on('click', function (e) {
        id = $(this).attr("data-id");
        name = $(".row"+id).find(".name").html();
        keyword = $(".row"+id).find(".keyword").html();
        basicReply = $(".row"+id).find(".basicReply").html();
        console.log(keyword,basicReply);
        $(".modal-content").find("input[name=id]").val(id);
        $(".modal-content").find("input[name=name]").val(name);
        $(".modal-content").find("input[name=keyword]").val(keyword);
        $(".modal-content").find("textarea[name=basicReply]").val(basicReply);
        $('.example-modal').css("z-index", 99999);
    });
</script>
@endpush
