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
    <style type="text/css">
        /*禁止选中文字*/
        body {
            -moz-user-select: none; /*火狐*/
            -webkit-user-select: none; /*webkit浏览器*/
            -ms-user-select: none; /*IE10*/
            -khtml-user-select: none; /*早期浏览器*/
            user-select: none;
        }
    </style>
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
                                <form class="mt-3" method="post" id="form" onsubmit="return false;">
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
                                                    <button class="btn btn-primary"
                                                            type="submit">{{trans('general.submit')}}</button>
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
<script>
    if (window != top) {
        top.location.href = location.href;
    }

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
                    layer.msg("{{trans('general.resources_not')}}", {icon: 5, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 401) {
                    layer.msg("{{trans('general.login_first')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 429) {
                    layer.msg("{{trans('general.Overvisiting')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                    return false;
                } else if (resp.status === 419) {
                    layer.msg("{{trans('general.illegal_request')}}", {
                                shift: 5,
                                skin: 'alert-secondary alert-lighter',
                                time: 2000
                            }, function () {
                                location.reload();
                            });
                            return false;
                    // layer.msg("{{trans('general.illegal_request')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                    // return false;
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
            },
            complete: function (d) {
                if (d.responseText.indexOf('"errors"') >= 0) {
                    $('#get-vercode').click();
                }
            }
        });
        return false;
    });


    //这段js要放在页面最下方
    // var h = window.innerHeight, w = window.innerWidth;
    // // 禁用右键 （防止右键查看源代码）
    // window.oncontextmenu = function () {
    //     return false;
    // };

    // //禁用开发者工具F12
    // document.onkeydown = function (e) {
    //     var currKey = 0, evt = e || window.event;
    //     currKey = evt.keyCode || evt.which || evt.charCode;
    //     if (currKey == 123) {
    //         window.event.cancelBubble = true;
    //         window.event.returnValue = false;
    //     }
    // };

    //在本网页的任何键盘敲击事件都是无效操作 （防止F12和shift+ctrl+i调起开发者工具）
    //    window.onkeydown = window.onkeyup = window.onkeypress = function () {
    //        window.event.returnValue = false;
    //        return false;
    //    };
    //如果用户在工具栏调起开发者工具，那么判断浏览器的可视高度和可视宽度是否有改变，如有改变则关闭本页面
//    window.onresize = function () {
//        if (h != window.innerHeight || w != window.innerWidth) {
//            window.close();
//            window.location = "about:blank";
//        }
//    };
    //    /*好吧，你的开发者工具是单独的窗口显示，不会改变原来网页的高度和宽度，
    //     但是你只要修改页面元素我就重新加载一次数据,让你无法修改页面元素（不支持IE9以下浏览器）*/
    // if (window.addEventListener) {
    //     window.addEventListener("DOMCharacterDataModified", function () {
    //         window.location.reload();
    //     }, true);
    //     window.addEventListener("DOMAttributeNameChanged", function () {
    //         window.location.reload();
    //     }, true);
    //     window.addEventListener("DOMCharacterDataModified", function () {
    //         window.location.reload();
    //     }, true);
    //     window.addEventListener("DOMElementNameChanged", function () {
    //         window.location.reload();
    //     }, true);
//                window.addEventListener("DOMNodeInserted", function(){window.location.reload();}, true);
        //            window.addEventListener("DOMNodeInsertedIntoDocument", function(){window.location.reload();}, true);
        //            window.addEventListener("DOMNodeRemoved", function(){window.location.reload();}, true);
//                window.addEventListener("DOMNodeRemovedFromDocument", function(){window.location.reload();}, true);
//                window.addEventListener("DOMSubtreeModified", function(){window.location.reload();}, true);
    // }
</script>
</body>
</html>