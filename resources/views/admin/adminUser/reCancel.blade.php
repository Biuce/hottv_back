@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('adminUser.cancel_account')}}
            </h4>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ route('admin::adminUser.saveCancel') }}" id="form">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput"></label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <span>{{trans('adminUser.cancel_tips1')}}<span style="color:orangered;">{{ $money }}</span>{{trans('adminUser.cancel_tips2')}}{{trans('adminUser.cancel_tips3')}}</span>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('logoffUser.name')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="name" aria-label="name"
                                   aria-describedby="icon-addon1" value="">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('logoffUser.bank_name')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="bank_name" aria-label="bank_name"
                                   aria-describedby="icon-addon1" value="">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('logoffUser.bank_account')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="bank_account" aria-label="bank_account"
                                   aria-describedby="icon-addon1" value="">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('logoffUser.phone')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="phone" aria-label="phone"
                                   aria-describedby="icon-addon1" value="">
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    <div class="col-sm-12 text-sm-center">
                        <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                id="submitBtn">{{trans('adminUser.cancel_account')}}</button>
                        <div style="display:inline;float:right;">
                            <button type="reset" class="btn btn-secondary">{{trans('general.reset')}}</button>
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
        var token = $("input[name='_token']").val();
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
                    if (result.code !== 0) {
                        form_submit.prop('disabled', false);
                        layer.msg(result.msg, {shift: 6});
                        return false;
                    }
                    layer.msg(result.msg, {shift: 1}, function () {
                        if (result.reload) {
                            location.reload();
                        }
                        if (result.redirect) {
                            location.href = '{{ route('admin::login.show') }}';
                        }
                    });
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