<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/8/2
 * Time: 14:48
 */
?>

@extends('layouts.app')
@section('title', '物流管理')


@section('content')
    <link rel="stylesheet" href="{{ url('css/fileinput.css') }}">
    <div class="container">
        <div class="row assets">
            <div class="col-md-3">
                @includeIf('web.nav', [])
                @includeIf('web.nav2', [])
            </div>

            <div class="col-md-9">
                <form action="{{ route('shop.express',['weid'=>$weid]) }}" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>物流管理</span>
                            <span class="pull-right"><a class="btn btn-primary btn-xs add">x</a></span>
                        </div>

                        <div class="panel-body">

                            <div class="form-group has-feedback">
                                <input id="thumb_url" name="fileinput[]" type="file" multiple>
                            </div>
                            <div class="thumb_url">
                                @foreach($good['advs'] as $adv)
                                    <input type="hidden" name="goods[thumb_url][{{ $loop->index }}][thumb]" value="{{ $adv['thumb'] }}">
                                @endforeach
                            </div>

                        </div>

                        <div class="panel-footer">
                            {{ csrf_field() }} &nbsp;
                            <span class="pull-right">
                                <input type="submit" class="btn btn-primary btn-xs" value="保存参数">
                            </span>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')
    <script src="{{ asset('/js/fileinput.js') }}"></script>
    <script src="{{ asset('/js/fileinput_zh.js') }}"></script>
    <script>
        // 批量上传幻灯片
        $('#thumb_url').fileinput({
            language: 'zh',
            allowedFileExtensions : ['jpg', 'png','gif'],
            maxFileCount: 6,
            //uploadAsync: false,
            uploadUrl: '{{ route('shop.express', ['weid'=>$weid]) }}',
            uploadExtraData: {
                _token  : '{{ csrf_token() }}',
                op      : 'fileinput',
            },
            initialPreview: []

        }).on("fileuploaded", function(event, data, previewId, index, jqXHR) {
            console.log(event, data, previewId, index, jqXHR);

            i = parseInt($(".thumb_url").children("input").length);
            if(data.response){
                el = '<input type="hidden" name="goods[thumb_url]['+(i++)+'][thumb]" value="'+data.response.thumb_url+'">';
                $(".thumb_url").append(el);
            }
        }).on('filecleared', function(event, data) {
            console.log('del:', data);
        });

        // 回填幻灯片图
        var Preview = [];
        var PreviewConfig = [];
        $.each(JSON.parse('{{ $thumbJson }}'), function (i, o) {
            Preview[i] ='<img src="' + baseUrl + o.thumb + '" class="file-preview-image" style="width:auto; height:160px;">';
            el = '<input type="hidden" name="goods[thumb_url][' + i + '][thumb]" value="'+ o.thumb +'">';
            $(".thumb_url").append(el);

            PreviewConfig[i] = {
                caption : o.thumb,//.split("/")[5]
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
    </script>
@endpush
