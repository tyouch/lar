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
                @includeIf('web.nav', [])
                @includeIf('web.nav2', [])
            </div>

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">自定义菜单管理
                        <span class="pull-right">
                            <input id="menuAdd" type="button" class="btn btn-success btn-xs" value="添加主菜单">
                        </span>
                    </div>
                    <form action="" method="post">
                        <div id="menuSet" class="panel-body">
                            @if(!empty($button))
                            @foreach($button as $menu)

                                <div class="input-group button">
                                    <input type="text" class="form-control form-meun" name="button[{{ $loop->index }}][name]" value="{{ $menu['name'] }}" maxlength="8">
                                    <input type="hidden" id="url{{ $loop->index }}" name="button[{{ $loop->index }}][url]" value="{{ $menu['url'] }}">
                                    <input type="hidden" id="type{{ $loop->index }}" name="button[{{ $loop->index }}][type]" value="{{ $menu['type'] }}">
                                    <span class="input-group-btn">
                                        <button data-id="{{ $loop->index }}" class="btn btn-primary add" type="button">
                                            <span class="glyphicon glyphicon-file"></span>
                                        </button>
                                        <button data-id="m|{{ $loop->index }}" class="btn btn-danger del" type="button">
                                            <span class="glyphicon glyphicon-remove-sign"></span>
                                        </button>
                                        <button data-id="m|{{ $loop->index }}" class="btn btn-info edit" type="button" data-toggle="modal" data-target=".bs-example-modal">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </button>
                                    </span>
                                </div>
                                <div id="subMenuSet{{ $loop->index }}" class="subMenuSet">
                                    @php $i = $loop->index @endphp
                                    @if(!empty($button[$i]['sub_button']))
                                    @foreach($button[$i]['sub_button'] as $subMenu)
                                        <div class="input-group subButton" style="margin-left: 37px;">
                                            <input type="text" class="form-control form-meun" name="button[{{ $i }}][sub_button][{{ $loop->index }}][name]" value="{{ $subMenu['name'] }}">
                                            <input type="hidden" id="subUrl{{ $loop->index }}" name="button[{{ $i }}][sub_button][{{ $loop->index }}][url]" value="{{ $subMenu['url'] }}">
                                            <input type="hidden" id="subType{{ $loop->index }}" name="button[{{ $i }}][sub_button][{{ $loop->index }}][type]" value="{{ $subMenu['type'] }}">
                                            <span class="input-group-btn">
                                                <button data-id="s|{{ $loop->index  }}" class="btn btn-warning del" type="button">
                                                    <span class="glyphicon glyphicon-remove-sign"></span>
                                                </button>
                                                <button data-id="s|{{ $loop->index  }}" class="btn btn-info edit" type="button" data-toggle="modal" data-target=".bs-example-modal">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </button>
                                            </span>
                                        </div>
                                    @endforeach
                                    @endif
                                </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="panel-footer">
                            {{ csrf_field() }}&nbsp;
                            <span class="pull-right"><input type="submit" class="btn btn-success btn-xs" value="保存主菜单"></span>
                        </div>

                    </form>

                </div>
            </div>
        </div>


        {{--模态框--}}
        <div class="modal fade bs-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">编辑菜单选项</h4>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <span class="input-group-btn"><button class="btn btn-info" type="button">URL:</button></span>
                            <input type="text" class="form-control edit-url" data-id="" name="" value="">
                            <span class="input-group-btn"><input type="submit" value="提交" class="btn btn-primary update1"></span>
                        </div>
                        <div style="margin-top: 15px;">
                            <p>
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="view" class="edit-type" checked="checked"> 链接
                                </label>
                            </p>
                            <p>指定点击此菜单时要跳转的链接（注：链接需加http://）</p>
                            <p>
                                注意: 由于接口限制. 如果你没有网页oAuth接口权限, 这里输入链接直接进入微站个人中心时将会有缺陷(有可能获得不到当前访问用户的身份信息. 如果没有oAuth接口权限, 建议你使用图文回复的形式来访问个人中心)
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')
<script>

    // 添加主按钮 和 子菜单
    $("#menuAdd").on('click', function (e) {

        i = $("#menuSet .button").length;
        if(i < 3) {
            el = '<div class="input-group button">' +
                '<input type="text" class="form-control form-meun" name="button['+i+'][name]" value="" maxlength="8">'+
                '<input type="hidden" id="url'+i+'" name="button['+i+'][url]" value="">'+
                '<input type="hidden" id="type'+i+'" name="button['+i+'][type]" value="">'+
                '<span class="input-group-btn">'+
                '<button data-id="'+i+'" class="btn btn-primary add" type="button"><span class="glyphicon glyphicon-file"></span></button>'+
                '<button data-id="m|'+i+'" class="btn btn-danger del" type="button"><span class="glyphicon glyphicon-remove-sign"></span></button>'+
                '<button data-id="m|'+i+'" class="btn btn-info edit" type="button" data-toggle="modal" data-target=".bs-example-modal"><span class="glyphicon glyphicon-edit"></span></button>'+
                '</span>'+
                '</div>'+
                '<div id="subMenuSet"'+i+'></div>';
            $("#menuSet").append(el);
        }
    });

    $("#menuSet").on("click", ".add", function (e) {
        id = $(this).attr("data-id");
        $("#type"+id).val("click");
        i = $("#subMenuSet"+id+" .subButton").length;
        if(i < 5) {
            els = '<div class="input-group subButton" style="margin-left: 37px;">' +
                '<span class="input-group-btn">'+
                '<button class="btn btn-success" type="button"><span class="glyphicon glyphicon-arrow-right"></span></button>'+
                '</span>'+
                '<input type="text" class="form-control form-meun" name="button['+id+'][sub_button]['+i+'][name]" value="">'+
                '<input type="hidden" id="subUrl'+i+'" name="button['+id+'][sub_button]['+i+'][url]" value="">'+
                '<input type="hidden" id="subType'+i+'" name="button['+id+'][sub_button]['+i+'][type]" value="">'+
                '<span class="input-group-btn">'+
                '<button data-id="s|'+i+'" class="btn btn-warning del" type="button"><span class="glyphicon glyphicon-remove-sign"></span></button>'+
                '<button data-id="s|'+i+'" class="btn btn-info edit" type="button" data-toggle="modal" data-target=".bs-example-modal"><span class="glyphicon glyphicon-edit"></span></button>'+
                '</span>'+
                '</div>';
            $("#subMenuSet"+id).append(els);
        }
    });


    // 删除条目
    $("#menuSet").on("click", ".del", function (e) {
        id = $(this).attr("data-id").split("|");
        if(id[0] == 'm'){
            $("#subMenuSet"+id[1]).remove();
        }
        $(this).parent().parent().remove();
    });

    $("#subMenuSet").on("click", ".del", function (e) {
        $(this).parent().parent().remove();
    });


    // 编辑条目    //$(".subMenuSet").on("click", ".edit", {se:'#subUrl'}, edit);{se:'#url'},edit

    $("#menuSet").on("click", ".edit", function (e) {
        //se = e.data.se;
        ids = $(this).attr("data-id");
        $(".edit-url").attr("data-id", ids);

        id = ids.split("|");
        console.log(id);

        //url = $(this).parent().parent().find(se+id[1]).val();
        if(id[0] == 'm'){
            url = $("#url"+id[1]).val();
        }
        if(id[0] == 's'){
            url = $("#subUrl"+id[1]).val();
        }

        //type = $(this).parent().parent().find("#type"+id).val();
        //console.log(url, type);
        $(".edit-url").val(url);
        //$(".edit-type").val(type);
        $('.bs-example-modal').css("z-index", 99999);
    });

    // 模态框提交
    $(".update1").on('click', function (e) {
        id = $(".edit-url").attr("data-id").split("|");
        url = $(".edit-url").val();
        type = $(".edit-type").val();
        //console.log($(".edit-url").attr("data-id"));
        if(id[0] == 'm') {
            $("#url"+id[1]).val(url);
            $("#type"+id[1]).val(type);
        }
        if(id[0] == 's') {
            $("#subUrl"+id[1]).val(url);
            $("#subType"+id[1]).val(type);
        }

        $('.bs-example-modal').modal('hide');
    });

</script>
@endpush
