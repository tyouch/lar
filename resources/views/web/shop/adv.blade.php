<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/8/2
 * Time: 14:48
 */
?>

@extends('layouts.app')
@section('title', '商品幻灯片管理')


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
                        管理商品幻灯片
                        <span class="pull-right">
                            <a class="btn btn-primary btn-xs add">添加幻灯片</a>
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
                            <table class="table table1">
                                <tr>
                                    <th style="width: 3%">ID</th>
                                    <th style="width: 9%">排序</th>
                                    <th style="width: 28%">标题</th>
                                    <th style="width: 45%">链接</th>
                                    <th style="width: 15%">操作</th>
                                </tr>
                                @foreach($advs as $adv)
                                    <tr>
                                        <td>{{ $adv->id }}</td>
                                        <td>{{ $adv->displayorder }}</td>
                                        <td>{{ $adv->advname }}</td>
                                        <td>{{ $adv->link }}</td>
                                        <td>
                                            <a class="btn btn-danger btn-xs del" data-id="{{ $adv->id }}">删除</a>
                                            <a class="btn btn-success btn-xs edit" data-id="{{ $adv->id }}">编辑</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <div style="text-align: center;">
                                {!! $advs->appends($pagePram)->links() !!}
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
                    <form action="{{ route('shop.adv', ['weid'=>$weid]) }}" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">添加幻灯片</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <th style="width: 16%">幻灯片标题</th>
                                    <td>
                                        <input type="text" name="advname" class="form-control" maxlength="20">
                                        <input type="hidden" name="id" value="" class="form-control">
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
                                    <th>链接</th>
                                    <td><input name="link" class="form-control"></td>
                                </tr>
                                <tr>
                                    <th style="width: 16%">排序</th>
                                    <td>
                                        <input type="text" name="displayorder" class="form-control" maxlength="5" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <th>是否显示</th>
                                    <td>
                                        <label class="radio-inline"><input type="radio" name="enabled" value="1" checked="checked">是</label>
                                        <label class="radio-inline"><input type="radio" name="enabled" value="0">否</label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="保存幻灯片">
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
        $("input[name=id]").val("");
        $("input[name=advname]").val("");
        $("input[name=link]").val("");
        $("input[name=displayorder]").val(0);
        $("#thumb-preview").attr("src", "");
    });

    $(".del").on('click', function (e) {
        id = $(this).attr("data-id");
        if (confirm("确认要删除这条规则吗？【rid:" + id + "】")) {
            $.ajax({
                url: '{{ route('shop.adv', ['weid'=>$weid]) }}',
                Type: 'POST',
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    op: 'del',
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (d, s) {
                    console.log(d, s);
                    if(d.code == 1){
                        window.location.reload();
                    }
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
            url: '{{ route('shop.adv', ['weid'=>$weid]) }}',
            Type: 'POST',
            dataType: 'JSON',
            data: {
                _token: '{{ csrf_token() }}',
                op: 'edit',
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (d, s) {
                console.log(d, s);
                $("input[name=id]").val(d.id);
                $("input[name=advname]").val(d.advname);
                $("input[name=link]").val(d.link);
                $("input[name=displayorder]").val(d.displayorder);
                $("#thumb-preview").attr("src", baseUrl + '/' + d.thumb);

                $("input[name=enabled]").removeAttr("checked"); //
                $("input[name=enabled][value=" + d.enabled + "]").attr("checked", true); //
            }
        });
    });
</script>
@endpush
