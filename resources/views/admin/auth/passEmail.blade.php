<!DOCTYPE html>
<html class="rd-navbar-sidebar-active" lang="en">
<head>
    <title>Password Reset</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="og:title" content="Template Monster Admin Template">
    <meta property="og:description"
          content="brevis, barbatus clabulares aliquando convertam de dexter, peritus capio. devatio clemens habitio est.">
    <link rel="icon" href="/public/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/public/components/base/base.css">
    <script src="/public/components/base/script.js"></script>
</head>
<body>
<div class="page">
    <section class="section-md section-transparent">
        <div class="container-fluid">
            <div class="panel p-0">
                <div class="panel-body section-one-screen section-lg">
                    <canvas class="js-waves"></canvas>
                    <div class="container-fluid">
                        <div class="row row-30 justify-content-center align-items-center">
                            <div class="col-md-8 col-xl-5">
                                <h3 class="text-center">{{trans('general.forget_pass1')}}</h3>
                                <div class="row row-30 justify-content-center">
                                    <div class="col-xl-10">
                                        <form method="post" id="form" action="{{ route('admin::login.emailVerify') }}">
                                            {{ csrf_field() }}
                                            <input class="form-control" type="hidden" name="email"
                                                   value="{{ $info['email'] }}">
                                            <input class="form-control" type="hidden" name="name"
                                                   value="{{ $info['account'] }}">
                                            <div class="alert alert-info alert-border-left" role="alert"><span
                                                        class="alert-icon fa-info"></span><span>{{trans('general.enter_email')}}{{ $email }}{{trans('general.enter_email1')}}</span>
                                            </div>
                                            <div class="input-group mt-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="icon-addon1">
                                                        <span class="fa-user"></span>
                                                    </span>
                                                </div>
                                                <input class="form-control" type="text"
                                                       placeholder="{{trans('general.enter_code')}}"
                                                       aria-describedby="icon-addon1" name="code">
                                                <div class="input-group-append">
                                                    <button class="btn btn-light"
                                                            type="submit">{{trans('general.confirm')}}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer footer-small">
        <footer class="footer footer-small">
            <div class="container-fluid">
                {{--<p><span class="d-inline-block pr-2">PART</span>© 2019. Design by Zemez</p>--}}
            </div>
        </footer>
    </footer>
</div>
<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script src="/public/admin/js/admin.js"></script>
<script>
    if (window != top) {
        top.location.href = location.href;
    }

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
                layer.msg(result.msg, {shift: 1}, function () {
                    if (result.reload) {
                        location.reload();
                    }
                    if (result.redirect) {
                        location.href = '{{ route('admin::login.newPass') }}' + '?name=' + result.data.account;
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
</body>
</html>