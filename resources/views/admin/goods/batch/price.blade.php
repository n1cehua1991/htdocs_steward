@extends('admin.layoutEdit')

@section('css')
    <link href="/libs/layui-v2.1.7/css/layui.css" rel="stylesheet">
    <style>
        .webuploader-pick {
            padding-top: 5px;
            margin-left: -10px;
            display: inline;
            float: left;
            margin-left: 0;

        }
        .form-control{
            display: inline;
            zoom:1;
            padding:0;
        }
        .download{
            float:left;
        }
        .download input{
            display: inline;
            float: left;
        }
        .download .upto{
            float: left;
            display: inline;
            zoom:1;
        }
    </style>
@endsection

@section('title')
    <li class="cur">
        <span>价格上传</span>
    </li>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <span>价格批量上传</span>
        </div>
        <div style="height:50px;"></div>
        <div class="col-lg-12">
            <div class="row col-md-12">
                <div class="col-md-5 download">
                    <input type="text" id="file" class="form-control col-md-3" style="width: 200px;margin-left: 100px;border-radius: 0px;">
                    <div class="btn btn-blue" style="margin-left:-3px;" id="file-view">浏览</div>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-blue" id="re-sale" type="button">上传</button>
                    <button class="btn btn-blue" id="re-export" type="button" onclick="dw();">模板下载</button>
                </div>
            </div>
        </div>
    </div><br>

@endsection

@section('js')

    <script src="/libs/layui-v2.1.7/layui.js"></script>
    <script>

        //下载模板
        function dw () {
            window.location.href = '/admin/price/download';
        }

        $(function() {

             var csrf = '{{ csrf_token() }}';

            layui.use('upload', function () {
                var $ = layui.jquery,
                        upload = layui.upload;

                var uploadInst = upload.render({
                    elem: '#file-view' //绑定元素
                    ,url: '/admin/price/batch_upload' //上传接口
                    ,auto: false
                    ,bindAction: '#re-sale'
                    ,accept: 'file'
                    ,data : {_token : csrf }
                    ,choose : function(obj){
                        obj.preview(function(index, file, result){
                            $('#file').val(file.name);
                        });
                    }
                    ,before : function( obj ){

                        if(!$('#file').val()){
                            layer.msg('请先选择文件',{icon:2,time:1000});
                            return false;
                        }
                    }
                    ,done: function (o) {

                        if (o.code == 200) {
                            layer.msg(o.message, {icon: 1, time: 1000});
                        } else {
                            layer.msg('上传失败', {icon: 2, time: 1000});
                        }
                    }
                });
            });
        });
    </script>

@endsection