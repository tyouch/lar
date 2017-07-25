<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/28
 * Time: 15:40
 */
?>
@extends('layouts.app')
@section('title', '公众号管理')


@section('content')
<style>
    .file-size{width: 85px; height: 85px;}
    .file-size img{width: 100%; height: 100%;}
    .file-pos{position: relative}
    .file-btn{filter:alpha(opacity=0);-moz-opacity:0; opacity:0; cursor: pointer;}
    .cont-pos{position: absolute; top: 0; left: 0;}
    .btn50{width: 50px;}
</style>

<div class="container">
    <div class="row assets">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">公众号列表
                    <span class="pull-right">
                        <input type="button" class="btn btn-success btn-xs" value="添加公众号" data-toggle="modal" data-target=".bs-example-modal-lg">
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
                            <th>公众号Logo</th>
                            <th>公众号名称</th>
                            <th>所属用户</th>
                            <th>创建时间/到期时间</th>
                            <th style="width: 10%;">操作</th>
                        </tr>
                        @foreach ($account as $item)
                            <tr>
                                <td>
                                    @if (file_exists('imgs/uploads/headimg_'.$item['weid'].'.jpg'))
                                        <img src="{{'imgs/uploads/headimg_'.$item['weid'].'.jpg'}}" class="thumbnail file-size">
                                    @else
                                        <img src="" class="thumbnail file-size">
                                    @endif
                                </td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['username'] }}</td>
                                <td>{{ date('Y-m-d H:i:s',$item['lastupdate']) }}</td>
                                <td>
                                    <input type="button" class="btn btn-info btn-xs btn50" name="edit" data-id="{{ $item['weid'] }}" data-toggle="modal" data-target=".bs-example-modal-lg" value="编辑">
                                    <a href="{{ route('account.operate', ['id'=>$item['weid'], 'op'=>'del']) }}" class="btn btn-danger btn-xs btn50" style="margin: 5px 0;">删除</a>
                                    <a href="{{ route('account.manage',['weid'=>$item['weid']]) }}" class="btn btn-info btn-xs btn50">管理</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--模态框--}}
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加公众号</h4>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" action="{{ route('account.store') }}" method="post" enctype="multipart/form-data">
                        <table class="table">
                            <tr>
                                <th>公众号名称</th>
                                <td><input type="text" name="name" class="form-control" value="" autocomplete="off" placeholder="您可以为公众号起一个名字, 方便下次修改和查看."></td>
                            </tr>
                            <tr>
                                <th>公众号类型</th>
                                <td>
                                    <select name="type" id="type" class="form-control">
                                        <option value="1">微信公众平台</option>
                                        <option value="2" disabled="disabled">易信公众平台</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>公众号接口权限</th>
                                <td>
                                    <label class="radio-inline"><input autocomplete="off" type="radio" name="level" value="0"> 普通订阅号</label>
                                    <label class="radio-inline"><input autocomplete="off" type="radio" name="level" value="1"> 认证订阅号/普通服务号</label>
                                    <label class="radio-inline"><input autocomplete="off" type="radio" name="level" value="2" checked=""> 认证服务号</label>
                                </td>
                            </tr>
                            <tr>
                                <th>公众号AppId</th>
                                <td><input type="text" name="key" class="form-control" value="" autocomplete="off" placeholder="请填写微信公众平台后台的AppId"></td>
                            </tr>
                            <tr>
                                <th>公众号AppSecret</th>
                                <td><input type="text" name="secret" class="form-control" value="" autocomplete="off" placeholder="请填写微信公众平台后台的AppSecret, 只有填写这两项才能管理自定义菜单"></td>
                            </tr>
                            <tr>
                                <th>公众帐号</th>
                                <td><input type="text" name="account" class="form-control" value="" autocomplete="off" placeholder="您的微信帐号或是易信帐号，本平台支持管理多个公众号"></td>
                            </tr>
                            <tr>
                                <th>原始帐号</th>
                                <td><input type="text" name="original" class="form-control" value="" autocomplete="off" placeholder="微信公众帐号的原ID串"></td>
                            </tr>
                            <tr>
                                <th>二维码</th>
                                <td>
                                    <div class="file-size file-pos">
                                        <img id='img1' src="imgs/up_pic_bg.jpg" class="thumbnail cont-pos">
                                        <input id="file1" name="qrcode" type="file" class="file-size file-btn cont-pos">
                                    </div>
                                    <span class="help-block">只支持JPG图片</span>
                                </td>
                            </tr>
                            <tr>
                                <th>头像</th>
                                <td>
                                    <div class="file-size file-pos">
                                        <img id='img2' src="imgs/up_pic_bg.jpg" class="thumbnail cont-pos">
                                        <input id="file2" name="headimg" type="file" class="file-size file-btn cont-pos">
                                    </div>
                                    <span class="help-block">只支持JPG图片</span>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <input name="submit" type="submit" value="提交" class="btn btn-primary">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="weid" value="">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection

@push('scripts')
<script>
    function chUpladBg(e){
        $img    = e.data.img;
        $src = window.URL.createObjectURL(this.files[0]);

        //$file   = e.data.file;
        //console.log($file.attr("name"));
        /*var formData = new FormData();
        formData.append("myfile", document.getElementById("file1").files[0]);
        $.ajax({
            url     :   "{ route('account.upload') }}",
            type    :   "POST",
            dataType:   "JSON",
            data    :   formData,//new FormData($("#uploadForm")[0])
            headers     : {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            processData : false,// 告诉jQuery不要去处理发送的数据
            contentType : false,// 告诉jQuery不要去设置Content-Type请求头
            cache       : false,// 上传文件不需要缓存

            success :   function (d, s) {
                console.log(d, s);
            },
            complete:   function (d, s) {
                console.log(d, s);
            }
        });*/

        $img.attr("src",$src)
    }

    $("#file1").bind('change', {'img': $("#img1"), 'file': $("#file1")}, chUpladBg);
    $("#file2").bind('change', {'img': $("#img2"), 'file': $("#file2")}, chUpladBg);

    // edit
    $("input[name=edit]").click(function (e) {
        $.ajax({
            url     : '{{ route('account.operate') }}',
            type    : 'GET',
            dataType: 'JSON',
            data    : {
                'id'    : $(this).attr("data-id"),
                'op'    : 'edit'
            },
            success : function (d, s) {
                console.log(d, s);
                $("input[name=weid]").val(d.weid);
                $("input[name=name]").val(d.name);
                $("select[name=type]").val(d.type);
                $("input[name=level]").val(d.level);
                $("input[name=key]").val(d.key);
                $("input[name=secret]").val(d.secret);
                $("input[name=account]").val(d.account);
                $("input[name=original]").val(d.original);
                $("#img1").attr("src", "imgs/uploads/qrcode_"+d.weid+'.jpg');
                $("#img2").attr("src", "imgs/uploads/headimg_"+d.weid+'.jpg');
            }

        });
    });

    $("input[name=del]").click(function (e) {
        if (confirm("确认要删除吗？")) {
            location.href = "{{ route('account.operate', ['id'=>$item['weid'], 'op'=>'del']) }}";
        }
    });
</script>
@endpush