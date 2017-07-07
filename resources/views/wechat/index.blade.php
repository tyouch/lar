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

<style>
    .file-size{width: 85px; height: 85px;}
    .file-size img{width: 100%; height: 100%;}
    .file-pos{position: relative}
    .file-btn{filter:alpha(opacity=0);-moz-opacity:0; opacity:0; cursor: pointer;}
    .cont-pos{position: absolute; top: 0; left: 0;}
</style>

@section('content')
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

                    <table class="table">
                        <tr>
                            <th>公众号Logo</th>
                            <th>公众号名称</th>
                            <th>所属用户</th>
                            <th>创建时间/到期时间</th>
                            <th>操作</th>
                        </tr>
                        <tr>
                            <td>1234</td>
                            <td>1234</td>
                            <td>1234</td>
                            <td>1234</td>
                            <td>1234</td>
                        </tr>
                        <tr>
                            <td>
                                0000
                            </td>
                            <td>0000</td>
                            <td>1234</td>
                            <td>1234</td>
                            <td>1234</td>
                        </tr>
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
                    <form id="uploadForm" action="{{ route('account.upload') }}" method="post" enctype="multipart/form-data">
                        <table class="table">
                            <tr>
                                <th>公众号名称</th>
                                <td><input type="text" name="name" class="form-control" value="" autocomplete="off" placeholder="您可以给此公众号起一个名字, 方便下次修改和查看."></td>
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
                                    <label class="radio-inline"><input autocomplete="off" type="radio" name="level" id="status_1" value="0"> 普通订阅号</label>
                                    <label class="radio-inline"><input autocomplete="off" type="radio" name="level" id="status_2" value="1"> 认证订阅号/普通服务号</label>
                                    <label class="radio-inline"><input autocomplete="off" type="radio" name="level" id="status_3" value="2" checked=""> 认证服务号</label>
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
        $file   = e.data.file;
        console.log($file.attr("name"));
        $src = window.URL.createObjectURL(this.files[0]);

        var formData = new FormData();
        formData.append("myfile", document.getElementById("file1").files[0]);
        $.ajax({
            url     :   "{{ route('account.upload') }}",
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
        });
        $img.attr("src",$src)
    }

    $("#file1").bind('change', {'img': $("#img1"), 'file': $("#file1")}, chUpladBg);
    $("#file2").bind('change', {'img': $("#img2"), 'file': $("#file2")}, chUpladBg);
</script>
@endpush