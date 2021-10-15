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
            <section class="section-md section-transparent">
                <div class="container-fluid">
                    <div class="panel p-0">
                        <div class="panel-body section-one-screen section-lg">
                            <canvas class="js-waves"></canvas>
                            <div class="container-fluid">
                                <div class="row row-30 justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="text-center mt-4">
                                            <h4>{{trans('adminUser.cancel_account')}}</h4>
                                            <p>{{trans('adminUser.account_one')}}</p>

                                            <p>{{trans('adminUser.account_two')}}</p>
                                            <div class="mt-4">
                                                <a href="{{ route('admin::adminUser.code') }}">
                                                    <button class="btn btn-primary" type="submit" lay-submit
                                                            lay-filter="formAdminUser"
                                                            id="submitBtn">{{trans('adminUser.cancel_account_conf')}}</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@extends('admin.js')
@section('js')
    <script>
        var token = $("input[name='_token']").val();
        $('#standardSelect').change(function () {
            var level_id = $(this).val();
            var iteValue = $("#standardSelect").find("option:selected").attr("emoney");
            var money = $("#standardSelect").find("option:selected").attr("money");
            var str = "{{trans('adminUser.tips1')}}" + iteValue + "{{trans('adminUser.tips2')}}" + money + "{{trans('adminUser.tips3')}}";
            $("#choice").val(str);
            console.log(money);
            var url = '<?php echo route('admin::adminUser.info') ?>';
            $.ajax({
                type: "POST",
                url: url,
                data: {level_id: level_id},
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    console.log(result);
                    if (result.code !== 0) {
                        $("#list-content").html(result);//列表内容
                        return false;
                    }
                    layer.msg(result.msg, {shift: 1}, function () {
                        if (result.reload) {
                            location.reload();
                        }
                        if (result.redirect) {
                            location.href = '{!! url()->previous() !!}';
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
        });

        //监听提交
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            var mini_money = $("#standardSelect").find("option:selected").attr("money");
            var own_money = "<?php echo \Auth::guard('admin')->user()->balance; ?>";
            var money = $("#balance").val() + '.00';
            if (Number(money) > Number(own_money)) {
                layer.msg("{{trans('adminUser.recharge_tips')}}", {shift: 6});
                return false;
            } else if (Number(money) < Number(mini_money)) {
                layer.msg("{{trans('adminUser.recharge_tips1')}}", {shift: 6});
                return false;
            }
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
                            location.href = '{{ route('admin::adminUser.index') }}';
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