@include('admin.header')
<link rel="stylesheet" type="text/css" href="/public/admin/webupload/webuploader.css">
<link rel="stylesheet" type="text/css" href="/public/admin/webupload/style.css">
{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('general.update_user_info')}}
            </h4>
        </div>
        <div class="panel-body">
            <form method="PUT" action="{{ route('admin::adminUser.userUpdate') }}" id="form" onsubmit="return false;">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.photo')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input id="data_photo" class="form-control col-sm-7" type="hidden" name="photo"
                                   value="{{ $info->photo ?? ''  }}"/>
                            <div class="col-sm-4">
                                <div id="fileList" class="uploader-list" style="float:right"></div>
                                <div id="imgPicker" style="float:left">{{trans('adminUser.photo')}}</div>
                            </div>
                            <div class="col-sm-4">
                                <img id="img_data" class="img-circle" width="120"
                                     style="float:left;margin-left: 50px;margin-top: -10px; height: 120px;"
                                     src="@if(!empty($info->photo)){{$info->photo}}@else /public/images/users/user-09-247x247.png @endif"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.name')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <span class="form-control" style="border-style:none">{{ $info->name ?? ''  }}</span>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.account')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <span class="form-control" style="border-style:none">{{ $info->account ?? ''  }}</span>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.level')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <span class="form-control" style="border-style:none">{{ $info->levels->level_name ?? ''  }}</span>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.phone')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="phone" aria-label="phone"
                                   aria-describedby="icon-addon1" value="{{ $info->phone ?? ''  }}" oninput="value=value.replace(/[^\d]/g,'')" maxlength="15">
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    <div class="col-sm-12 text-sm-center">
                        <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                id="submitBtn">{{trans('general.update_user_info')}}</button>
                        <div style="display:inline;float:right;">
                            <button type="button" class="btn btn-warning"
                                    onclick="history.go(-1);">{{trans('general.return')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@extends('admin.js')
@section('js')
    <script type="text/javascript" src="/public/admin/webupload/webuploader.min.js"></script>
    <script>
        // 上传头像
        var $list = $('#fileList');
        var token = $("input[name='_token']").val();
        //上传图片,初始化WebUploader
        var uploader = WebUploader.create({
            auto: true,// 选完文件后，是否自动上传。
            method: 'POST',
            swf: '/public/admin/webupload/Uploader.swf',// swf文件路径
            server: "{{ route('admin::adminUser.material') }}",// 文件接收服务端。
            duplicate: true,// 重复上传图片，true为可重复false为不可重复
            pick: '#imgPicker',// 选择文件的按钮。可选。
            fileSizeLimit: 1024 * 1024 * 100 * 10, //验证文件总大小是否超出限制, 超出则不允许加入队列。
            fileSingleSizeLimit: 1024 * 1024 * 100, //验证单个文件大小是否超出限制, 超出则不允许加入队列。
            formData: {
                'folder': 'face',
                '_token': token
            },
            //限制传输文件类型
            accept: {
                title: '*',//描述
                extensions: 'jpg,jpeg,png,gif',//类型
                mimeTypes: '.jpg,.jpeg,.png,.gif'//mime类型
            },

            'onUploadSuccess': function (file, data, response) {
                if (data.code != 0) {
                    layer.msg(data.msg, {shift: 5, skin: 'alert-secondary alert-lighter'});
                } else {
                    var point = data.path.lastIndexOf(".");
                    var type = data.path.substr(point);
                    if (type == ".jpg" || type == ".gif" || type == ".JPG" || type == ".GIF" || type == ".jpeg" || type == ".JPEG" || type == ".png" || type == ".PNG") {
                        $("#img_data").attr('src', data.path);
                    }
                    $("#data_photo").val(data.path);
                }
            }
        });

        uploader.on('fileQueued', function (file) {
            $list.html('<div id="' + file.id + '" class="item">' +
                '<span class="info">' + file.name + '</span>' +
//                '<span class="state">正在上传...</span>' +
                '<span class="state">{{trans('general.uploading')}}</span>' +
                '</div>');
        });

        // 文件上传成功
        uploader.on('uploadSuccess', function (file, data) {
            if (data.code != 0) {
                $('#' + file.id).find('span.state').text('{{trans('general.upload_fail')}}');
            } else {
                $('#' + file.id).find('span.state').text('{{trans('general.upload_success')}}');
            }
//            $('#' + file.id).find('span.state').text('上传成功！');
        });

        // 文件上传失败，显示上传出错。
        uploader.on('uploadError', function (file) {
//            $('#' + file.id).find('span.state').text('上传出错!');
            $('#' + file.id).find('span.state').text('{{trans('general.upload_fail')}}');
        });

        // 文件上传失败，显示上传出错。
        uploader.on('error', function (file) {
//            $('#' + file.id).find('span.state').text('上传出错!');
            $('#' + file.id).find('span.state').text('{{trans('general.upload_fail')}}');
        });

        //监听提交
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            var method = $("#form").attr("method");
            var action = $('#form').attr("action");
            $.ajax({
                type: method,
                url: action,
                data: $('#form').serializeArray(),
                success: function (result) {
                    console.log(result);
                    if (result.code !== 0) {
                        form_submit.prop('disabled', false);
                        layer.msg(result.msg, {shift: 6});
                        return false;
                    }
//                    layer.msg(result.msg, {shift: 1}, function () {
//                        if (result.reload) {
//                            location.reload();
//                        }
                        if (result.redirect) {
                            location.href = parent.location.reload();
                            location.href = '{{ route('admin::adminUser.userInfo') }}';
                        }
//                    });
                },
                error: function (resp, stat, text) {
                    if (window.form_submit) {
                        form_submit.prop('disabled', false);
                    }
                    if (resp.status === 422) {
                        var parse = $.parseJSON(resp.responseText);
                        if (parse) {
                            layer.msg(parse.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                        }
                        return false;
                    } else if (resp.status === 404) {
                        layer.msg("{{trans('general.resources_not')}}", {icon: 5, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 401) {
                        layer.msg("{{trans('general.login_first')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 429) {
                        layer.msg("{{trans('general.Overvisiting')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 419) {
                        layer.msg("{{trans('general.illegal_request')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 500) {
                        layer.msg("{{trans('general.internal_error')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else {
                        var parse = $.parseJSON(resp.responseText);
                        // if (parse && parse.err) {
                        if (parse) {
                            layer.alert(parse.msg);
                        }
                        return false;
                    }
                }
            });

            return false;
        });
    </script>
    @endsection
    </div>
    </body>
    </html>