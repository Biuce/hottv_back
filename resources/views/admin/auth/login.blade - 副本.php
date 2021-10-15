<!DOCTYPE html>
<html class="rd-navbar-sidebar-active" lang="en">
<head>
    <title>{{trans('general.log_in')}} - {{ config('app.name') }}</title>
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
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-xl-5">
                                <h3 class="text-center">Welcome</h3>
                                <div class="mt-3 text-center"><img class="rounded-circle"
                                                                   src="/public/images/users/user-09-247x247.png"
                                                                   width="100" height="100" alt=""></div>
                                <form class="mt-3" method="post" id="form">
                                    {{ csrf_field() }}
                                    <div class="row row-30 justify-content-center">
                                        <div class="col-12 col-md-10">
                                            <div class="form-group">
                                                <label for="user">{{trans('adminUser.account')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span
                                                                class="input-group-text fa-user"></span></div>
                                                    <input class="form-control" id="user" type="text" name="login"
                                                           placeholder="{{trans('general.enter_name')}}"
                                                           autocomplete="off" maxlength="32">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pass">{{trans('adminUser.password')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span
                                                                class="input-group-text fa-lock"></span></div>
                                                    <input class="form-control" id="pass" type="password"
                                                           name="password" placeholder="{{trans('general.enter_pass')}}"
                                                           autocomplete="off" maxlength="18">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pass">{{trans('adminUser.captcha')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span
                                                                class="input-group-text fa-lock"></span></div>
                                                    <input class="form-control" type="text" name="captcha"
                                                           id="login-vercode"
                                                           placeholder="{{trans('general.captcha_code')}}"
                                                           autocomplete="off" maxlength="4">
                                                    <div style="margin-left: 10px;height: 50px;">
                                                        <img src="{{ captcha_src() }}" id="get-vercode"
                                                             title="{{trans('general.captcha_refresh')}}"
                                                             onclick="$(this).prop('src', $(this).prop('src').split('?')[0] + '?' + Math.random())"
                                                             style="height: 49px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <div class="row row-10 align-items-center">
                                                <div class="col-sm-6">
                                                    <div class="custom-control">
                                                        <a class="dropdown-item"
                                                           href="{{ route('admin::login.passOne') }}">{{trans('general.forget_pass')}}</a>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 text-sm-right">
                                                    <button class="btn btn-primary" type="submit"
                                                            id="denglu">{{trans('general.submit')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer footer-small">
        <div class="container-fluid">
            {{--<p><span class="d-inline-block pr-2">PART</span>© 2019. Design by Zemez</p>--}}
        </div>
    </footer>
</div>
<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script src="/public/admin/js/admin.js"></script>
<script src="/public/admin/js/jquery.md5.js"></script>
<script>
    if (window != top) {
        top.location.href = location.href;
    }

    function getEncryption(password, uin, vcode) {
        var str1 = $.md5(password);
        var str2 = $.md5(str1 + uin);
        var str3 = $.md5(str2 + vcode.toUpperCase());
        return str3
    }

    var token = $("input[name='_token']").val();
    $("#denglu").click(function () {
        var account = $("#user").val();
        var pass = $("#pass").val();
        $.ajax({
            type: "POST",
            url: '{{ route('admin::login.check') }}',
            data: {name: account},
            headers: {'X-CSRF-Token': token},
            success: function (result) {
                if (result.code !== 0) {
                    layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                }

                if (result.redirect) {
                    // 对用户密码进行加密
                    var new_pass = getEncryption(pass, account, result.random);
                    console.log(new_pass);
                    $("#pass").val(new_pass);
                }
            },
            error: function (resp, stat, text) {
                if (window.form_submit) {
                    form_submit.prop('disabled', false);
                }
                $("#get-vercode").prop('src', $("#get-vercode").prop('src').split('?')[0] + '?' + Math.random());
                if (resp.status === 422) {
                    var parse = $.parseJSON(resp.responseText);
                    if (parse) {
                        layer.msg(parse.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                    }
                    return false;
                } else if (resp.status === 404) {
                    layer.msg('资源不存在', {icon: 5, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 401) {
                    layer.msg('请先登录', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 429) {
                    layer.msg('访问过于频繁，请稍后再试', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 419) {
                    layer.msg('非法请求。请刷新页面后重试。', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 500) {
                    layer.msg('内部错误，请联系管理员', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else {
                    var parse = $.parseJSON(resp.responseText);
                    // if (parse && parse.err) {
                    if (parse) {
                        layer.alert(parse.msg);
                    }
                    return false;
                }
            },
            complete: function (d) {
                if (d.responseText.indexOf('"errors"') >= 0) {
                    $('#get-vercode').click();
                }
            }
        });
        return true;
    });

    $('#form').submit(function () {
        window.form_submit = $('#form').find('[type=submit]');
        form_submit.prop('disabled', true);
        var method = $("#form").attr("method");
        $.ajax({
            type: method,
            url: '{{ route('admin::login') }}',
            data: $('#form').serializeArray(),
            success: function (result) {
                if (result.code !== 0) {
                    form_submit.prop('disabled', false);
                    layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                }
                layer.msg(result.msg, {shift: 1}, function () {
                    if (result.reload) {
                        location.reload();
                    }
                    if (result.redirect) {
                        if (result.is_new == 1) {
                            location.href = '{{ route('admin::index') }}';
                        } else {
                            location.href = '{{ route('admin::home.email') }}';
                        }

                        if (result.is_cancel == 1) {
                            location.href = '{{ route('admin::login.cancel') }}';
                        } else if (result.is_cancel == 2) {
                            return false;
                        }
                    }
                });
            },
            error: function (resp, stat, text) {
                if (window.form_submit) {
                    form_submit.prop('disabled', false);
                }
                $("#get-vercode").prop('src', $("#get-vercode").prop('src').split('?')[0] + '?' + Math.random());
                if (resp.status === 422) {
                    var parse = $.parseJSON(resp.responseText);
                    if (parse) {
                        layer.msg(parse.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                    }
                    return false;
                } else if (resp.status === 404) {
                    layer.msg('资源不存在', {icon: 5, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 401) {
                    layer.msg('请先登录', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 429) {
                    layer.msg('访问过于频繁，请稍后再试', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 419) {
                    layer.msg('非法请求。请刷新页面后重试。', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 500) {
                    layer.msg('内部错误，请联系管理员', {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else {
                    var parse = $.parseJSON(resp.responseText);
                    // if (parse && parse.err) {
                    if (parse) {
                        layer.alert(parse.msg);
                    }
                    return false;
                }
            },
            complete: function (d) {
                if (d.responseText.indexOf('"errors"') >= 0) {
                    $('#get-vercode').click();
                }
            }
        });
        return false;
    });
</script>
</body>
</html>