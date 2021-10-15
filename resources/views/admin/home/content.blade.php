@include('admin.header')
@section('title', '首页')
<div class="container-fluid">
    <div class="row row-30">
        @if (\Auth::guard('admin')->user()->id != 1)
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-header" style="background-color: #15171c">
                        <div style="margin-top: 20px; margin-bottom: 50px;">
                            {{ csrf_field() }}
                            <div class="row row-15">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4" style="text-align: center;">
                                    @if($locale == 'zh')
                                        <img src="/public/images/index_zh.png"/>
                                    @else
                                        <img src="/public/images/index_en.png"/>
                                    @endif
                                </div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="row row-15" style="margin-top: 50px;">
                                <div class="col-sm-12" style="display: inline-block;text-align: center;">
                                    <div style="display: inline-block;">
                                        <select class="form-control" id="standardSelect" name="assort_id"
                                                style="border-color:#898989;background-color: #1b1e22;color: #c1c2c3;border-width: 2px;text-align: center;min-width: 400px;max-width: 500px; width: 20%;">
                                            <option value="0"
                                                    style="color: #c1c2c3">{{trans('general.select_code')}}</option>
                                            @foreach($equipment ?? null as $v)
                                                <option value="{{ $v->assort_id }}"
                                                        money="{{ $v->money ?? 0 }}"
                                                        emoney="{{ $v->assorts->assort_name ?? "" }}"
                                                        duration="{{ $v->assorts->duration ?? 0 }}">{{ $v->assorts->assort_name ?? "" }} {{ $v->money ?? 0 }}
                                                    {{trans('huobi.money')}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="display: inline-block;margin-left: 20px;">
                                        <a class="dropdown-item" href="javascript:;"
                                           data-modal-trigger='{"target":"#modal-sample"}'>
                                            <button class="btn" type="submit" lay-submit lay-filter="formorder"
                                                    id="submitBtn"
                                                    style="background-color: #f7941d;color: white;height: 49px;">{{trans('general.authorization_code')}}</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-sample">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{trans('general.message')}}</h5>
                                    <button class="close" data-dismiss="modal">×</button>
                                </div>
                                <div class="modal-body">
                                    <form id="form">
                                        {{ csrf_field() }}
                                        <input class="form-control" type="hidden" name="mini_money" value=""
                                               id="mini_money">
                                        <p style="text-align: center;" id="users">会员30天授权码</p>
                                        <div style="text-align:center;margin-top: 20px;margin-bottom: 20px">
                                            <h3 style="display : inline" id="auth_code"></h3>
                                            <span class="btn" data-clipboard-text="" id="copy">
                                                <button class="btn btn-primary"
                                                        type="button">{{trans('home.copy')}}</button>
                                            </span>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-sm-2 text-sm-right">
                                                <label class="col-form-label"
                                                       for="standardInput">{{trans('authCode.remark')}}</label>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="input-group form-group">
                                                <textarea class="form-control" id="standardRemark" rows="3"
                                                          name="remark" maxlength="128"> </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    {{--<button class="btn btn-secondary"--}}
                                    {{--data-dismiss="modal">{{trans('general.cancel')}}</button>--}}
                                    <button class="btn btn-primary"
                                            onclick="authCode()"
                                            style="display:block;margin:0 auto">{{trans('general.confirm')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
        <div class="container-fluid">
            <div class="row row-30">
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-primary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format(\Auth::guard('admin')->user()->balance, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.huobi_balance')}}</h5>
                        <div class="widget-counter-icon linearicons-user"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-info" style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($month_code, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.month_code')}}</h5>
                        @if($locale == 'en' || $locale == 'my')
                            <h5 class="widget-counter-text">{{trans('home.month_code1')}}</h5>
                        @endif
                        <div class="widget-counter-icon linearicons-pie-chart"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-secondary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($last_month_code, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.last_month_code')}}</h5>
                        @if($locale == 'en' || $locale == 'my')
                            <h5 class="widget-counter-text">{{trans('home.last_month_code1')}}</h5>
                        @endif
                        <div class="widget-counter-icon linearicons-paper-plane"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-success"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($count_code, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.count_code')}}</h5>
                        <div class="widget-counter-icon linearicons-mailbox-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row row-30">
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-primary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($month_expend, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.last_month_huobi')}}</h5>
                        @if($locale == 'en' || $locale == 'my')
                            <h5 class="widget-counter-text">{{trans('home.last_month_huobi1')}}</h5>
                        @endif
                        <div class="widget-counter-icon linearicons-user"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row row-30">
                <div class="col-12">
                    <h4>{{trans('home.lower_agency')}}</h4>
                </div>
                {{--@if($type != 1)--}}
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-primary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($month_profit, 2) }}</h1>
                        <h5 class="widget-counter-text"
                            style="margin-top: 20px;">{{trans('home.month_lower_profit')}}</h5>
                        <div class="widget-counter-icon linearicons-user"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-info"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($last_month_profit, 2) }}</h1>
                        <h5 class="widget-counter-text"
                            style="margin-top: 20px;">{{trans('home.last_month_profit')}}</h5>
                        <div class="widget-counter-icon linearicons-pie-chart"></div>
                    </div>
                </div>
                {{--@endif--}}
                {{--@if(\Auth::guard('admin')->user()->level_id <= 3)--}}
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-secondary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($sum_profit, 2) }}</h1>
                        <h5 class="widget-counter-text"
                            style="margin-top: 20px;">{{trans('home.sum_profit')}}</h5>
                        <div class="widget-counter-icon linearicons-paper-plane"></div>
                    </div>
                </div>
                {{--@endif--}}
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-success"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($user_count, 2) }}</h1>
                        <h5 class="widget-counter-text" style="margin-top: 20px;">{{trans('home.count_agency')}}</h5>
                        <div class="widget-counter-icon linearicons-mailbox-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--@endsection--}}

@extends('admin.js')
@section('js')
    <script type="text/javascript" src="/public/admin/js/clipboard.min.js"></script>
    <script>
        //        $("body").click(function () {
        //            parent.show_body_click();
        //        });

        // 复制功能
        var clipboard = new ClipboardJS('#copy');
        clipboard.on('success', function (e) {
            layer.msg("{{trans('authCode.copy_success')}}", {shift: 5});
//            console.info('Action:', e.action);
//            console.info('Text:', e.text);
//            console.info('Trigger:', e.trigger);
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
        });

        var code_id = 0;
        $("#submitBtn").click(function () {
            var mini_money = $("#standardSelect").find("option:selected").attr("money");
            var iteValue = $("#standardSelect").find("option:selected").attr("emoney");
            var duration = $("#standardSelect").find("option:selected").attr("duration");
            var assort_id = $("#standardSelect").find("option:selected").val();
            $("#users").html(iteValue + "授权码");
            if (typeof(iteValue) == "undefined") {
                layer.msg("{{trans('authCode.choice_code')}}", {shift: 6});
                return false;
            }

            var url = '<?php echo route('admin::code.save') ?>';
            $.ajax({
                url: url,
                type: "POST",   //请求方式
                data: {day: duration, type: 1, number: 1, assort_id: assort_id, mini_money: mini_money},
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    }
//                    layer.msg(result.msg, {shift: 1}, function () {
//                        if (result.reload) {
//                            location.reload();
//                        }
                    if (result.redirect) {
                        $("#auth_code").html(result.data);
                        $(".btn").attr("data-clipboard-text", result.data);
                        code_id = result.id;
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
                        layer.msg("{{trans('general.resources_not')}}", {
                            icon: 5,
                            skin: 'alert-secondary alert-lighter'
                        });
                        return false;
                    } else if (resp.status === 401) {
                        layer.msg("{{trans('general.login_first')}}", {
                            shift: 6,
                            skin: 'alert-secondary alert-lighter'
                        });
                        return false;
                    } else if (resp.status === 429) {
                        layer.msg("{{trans('general.Overvisiting')}}", {
                            shift: 6,
                            skin: 'alert-secondary alert-lighter'
                        });
                        return false;
                    } else if (resp.status === 419) {
                        layer.msg("{{trans('general.illegal_request')}}", {
                            shift: 6,
                            skin: 'alert-secondary alert-lighter'
                        });
                        return false;
                    } else if (resp.status === 500) {
                        layer.msg("{{trans('general.internal_error')}}", {
                            shift: 6,
                            skin: 'alert-secondary alert-lighter'
                        });
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
        });

        var token = $("input[name='_token']").val();

        function authCode() {
            var remark = $("#standardRemark").val();
            var code = $("#auth_code").html();
            var url = '{{ route('admin::code.remark') }}';
            $.ajax({
                url: url,
                type: "PUT",   //请求方式
                data: {remark: remark, code: code},
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    }
//                    layer.msg(result.msg, {shift: 1}, function () {
//                        if (result.reload) {
//                            location.reload();
//                        }
                    if (result.redirect) {
                        location.href = '{{ route('admin::code.index') }}';
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
                }
            });
        }
    </script>
    @endsection
    </div>
    </body>
    </html>