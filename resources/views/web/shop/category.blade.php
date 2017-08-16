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
                            <a class="btn btn-primary btn-xs add" data-oid="-1">添加分类</a>
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

                        <div class="well">
                            <table class="table">
                                <tr>
                                    <th style="width: 3%"></th>
                                    <th style="width: 9%">排序</th>
                                    <th style="width: 40%">名称</th>
                                    <th style="width: 20%">图片</th>
                                    <th style="width: 28%">操作</th>
                                </tr>
                                @foreach($category as $cate)
                                    <tr>
                                        <td>
                                            @if($cate['parentid'] == 0)
                                                <span class="glyphicon glyphicon-menu-down"></span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{ $cate['displayorder'] }}</td>
                                        <td>{{ $cate['name'] }}</td>
                                        <td><img src="{{ url($cate['thumb']) }}" class="img-circle" style="width: 30px;"></td>
                                        <td>
                                            <a class="btn btn-danger btn-xs del" data-id="{{ $cate['id'] }}" data-pid="{{ $cate['parentid'] }}">删除</a>
                                            <a class="btn btn-success btn-xs edit" data-id="{{ $cate['id'] }}">编辑</a>
                                            @if($cate['parentid'] == 0)
                                                <a class="btn btn-info btn-xs add" data-pid="{{ $cate['id'] }}" data-oid="{{ $cate['displayorder'] }}">添加子分类</a>
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
        $('.example-modal').modal('show');
        //$('.example-modal').css("z-index", 99999);
        pid = $(this).attr("data-pid");
        oid = $(this).attr("data-oid");
        $("input[name=id]").val("");
        $("input[name=parentid]").val(pid);
        $("input[name=name]").val("");
        $("input[name=displayorder]").val(parseInt(oid)+1);
        $("textarea[name=description]").val("描述");
        $("#thumb-preview").attr("src", "");
        $("#txt-preview").val("");
    });

    $(".del").on('click', function (e) {
        id = $(this).attr("data-id");
        pid = $(this).attr("data-pid");
        if (confirm("确认要删除这条规则吗？【rid:" + id + "】")) {
            $.ajax({
                url     : '{{ route('shop.category', ['weid'=>$weid]) }}',
                Type    : 'POST',
                dataType: 'JSON',
                data    : {
                    _token  : '{{ csrf_token() }}',
                    op      : 'del',
                    id      : id,
                    pid     : pid
                },
                headers : {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (d, s) {
                    console.log(d, s);
                    //if(d.code == 1){
                    window.location.reload();
                    //}
                }
            });
        } else {
            alert('放弃删除...');
        }
    });

    $(".edit").on('click', function (e) {
        $('.example-modal').modal('show');
        //$('.example-modal').css("z-index", 99999);
        id = $(this).attr("data-id");
        baseUrl = "{{ url('') }}";

        $.ajax({
            url     : '{{ route('shop.category', ['weid'=>$weid]) }}',
            Type    : 'POST',
            dataType: 'JSON',
            data    : {
                _token  : '{{ csrf_token() }}',
                op      : 'edit',
                id      : id
            },
            headers : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (d, s) {
                console.log(d, s);
                $("input[name=id]").val(d.id);
                $("input[name=parentid]").val(d.parentid);
                $("input[name=name]").val(d.name);
                $("textarea[name=description]").val(d.description);
                $("input[name=displayorder]").val(d.displayorder);
                $("#thumb-preview").attr("src", baseUrl + '/' + d.thumb);

                $("input[name=isrecommand]").removeAttr("checked"); //
                $("input[name=isrecommand][value=" + d.isrecommand + "]").attr("checked", true); //
                $("input[name=enabled]").removeAttr("checked"); //
                $("input[name=enabled][value=" + d.enabled + "]").attr("checked", true); //
            }
        });

    });
</script>
@endpush
