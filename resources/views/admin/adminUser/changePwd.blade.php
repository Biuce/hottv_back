@include('admin.header')
{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('general.set_password')}}
            </h4>
            <span>{{trans('home.pass_tips1')}}</span>
        </div>
        <div class="panel-body">
            <form method="PUT" action="{{ route('admin::adminUser.savePwd') }}" id="form">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.old_password')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text fa-lock"></span></div>
                            <input class="form-control" type="password" name="old_password" aria-label="Password"
                                   aria-describedby="icon-addon1" id="old_password" maxlength="18">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.new_password')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text fa-lock"></span></div>
                            <input class="form-control" type="password" name="password" aria-label="Password"
                                   aria-describedby="icon-addon1" id="password" maxlength="18">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.password_confirmation')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text fa-lock"></span></div>
                            <input class="form-control" type="password" name="password_confirmation" aria-label="Password"
                                   aria-describedby="icon-addon1" id="password_confirmation" maxlength="18">
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    <div class="col-sm-12 text-sm-center">
                        <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                id="submitBtn">{{trans('general.set_password')}}</button>
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
    <script>
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
                        layer.msg(result.msg, {shift: 5});
                        return false;
                    }
//                    layer.msg(result.msg, {shift: 1}, function () {
//                        if (result.reload) {
//                            location.reload();
//                        }
                        if (result.redirect) {
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