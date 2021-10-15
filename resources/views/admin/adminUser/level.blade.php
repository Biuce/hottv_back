@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('adminUser.set_level')}}
            </h4>
        </div>
        <section class="section-sm bg-800">
            <div class="container-fluid">
                <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                    <div class="media-item">
                        <img class="rounded"
                             @if(!empty($info['photo']))
                             src="{{ $info['photo'] }}"
                             @else
                             src="/public/images/users/user-09-247x247.png"
                             @endif
                             width="165"
                             alt="" style="height: 165px;">
                    </div>
                    <div class="media-body">
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.name')}}: {{ $info['name'] }}
                            </li>
                            <li class="list-inline-item">
                                {{trans('adminUser.email')}}: {{ $info['email'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.phone')}}: {{ $info['phone'] }}
                            </li>
                            <li class="list-inline-item">
                                {{trans('adminUser.level')}}: {{ $info->levels->level_name }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.account')}}: {{ $info['account'] }}
                            </li>
                            @if($info['is_new'] == 0)
                                <li class="list-inline-item">
                                    {{trans('adminUser.password')}}: {{ $info['password'] }}
                                </li>
                            @endif
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.remark')}}: {{ $info['remark'] }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <div class="panel-body">
            <form method="put" action="{{ route('admin::adminUser.update', ['id' => $info['id']]) }}" id="form"  onsubmit="return false;">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.level')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <select class="form-control" id="standardSelect" name="level_id">
                            <option value="0">{{trans('general.select')}}</option>
                            @foreach($level ?? null as $v)
                                @if($info['level_id'] == 5 && $info['person_num'] >= 10)
                                    <option value="{{ $v['id'] }}" emoney="{{ $v['level_name'] }}"
                                            money="{{ $v['mini_amount'] }}"
                                            @isset($info) @if($v['id'] == $info->level_id) selected @endif @endisset
                                    >{{ $v['level_name'] }}</option>
                                @else
                                    @if($v['id'] != 4)
                                        <option value="{{ $v['id'] }}" emoney="{{ $v['level_name'] }}"
                                                money="{{ $v['mini_amount'] }}"
                                                @isset($info) @if($v['id'] == $info->level_id) selected @endif @endisset
                                        >{{ $v['level_name'] }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput"></label>
                    </div>
                    <div class="col-sm-11">
                        <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                            <thead class="border-bottom">
                            <tr class="long-tr">
                                <th>{{trans('adminUser.assort')}}</th>
                                <th>{{trans('equipment.retail_price')}}</th>
                                <th>{{trans('adminUser.u_cost')}}</th>
                                <th>{{trans('adminUser.a_cost')}}</th>
                                <th>{{trans('adminUser.u_profit')}}</th>
                            </tr>
                            </thead>
                            <tbody id="list-content">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput"></label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <span>{{trans('adminUser.use_huobi')}} <span
                                        style="color: yellow;">{{ number_format(\Auth::guard('admin')->user()->balance, 2)  }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.recharge')}}</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="balance" aria-label="Balance"
                                   aria-describedby="icon-addon1" value="" id="balance" onkeyup="onlyNumber(this)"
                                   onblur="onlyNumber(this)" onmouseover="onlyNumber(this)">
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="input-group form-group">
                            {{--<input class="form-control" type="text" id="choice" value="" readonly--}}
                                   {{--style="border-style:none">--}}
                            <span class="form-control" style="border-style:none" id="choice"></span>
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    <div class="col-sm-12 text-sm-center">
                        <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                id="submitBtn">{{trans('adminUser.set_level')}}</button>
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
        function onlyNumber(obj) {
            //得到第一个字符是否为负号    
            var t = obj.value.charAt(0);
            //先把非数字的都替换掉，除了数字和.和-号    
            obj.value = obj.value.replace(/[^\d\.]/g, '');
            //前两位不能是0加数字      
            obj.value = obj.value.replace(/^0\d[0-9]*/g, '');
            //必须保证第一个为数字而不是.       
            obj.value = obj.value.replace(/^\./g, '');
            //保证只有出现一个.而没有多个.       
            obj.value = obj.value.replace(/\.{2,}/g, '.');
            //保证.只出现一次，而不能出现两次以上       
            obj.value = obj.value.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.');
            //如果第一位是负号，则允许添加    
            obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
            var a = $('#balance').val();
            var b = TripartiteMethod(a);
            $('#balance').val(b);
            if (t == '-') {
                return;
            }
        }

        /**
         * 给金额添加千分位分隔符
         * @param num
         * @returns {string}
         * @constructor
        */
        function TripartiteMethod(num) {
            var type = true;
            var value = '';
            num = num.replace(/,/g, "");
            if (num.indexOf(".") < 0) {
                var t1 = num.toString().split('');
            } else {
                type = false;
                var arr = num.toString().split('.');
                var t1 = arr[0].toString().split('');
                var t2 = arr[1].toString();
            }

            var result = [],
                counter = 0;
            for (var i = t1.length - 1; i >= 0; i--) {
                counter++;
                result.unshift(t1[i]);
                if ((counter % 3) == 0 && i != 0) {
                    result.unshift(',');
                }
            }

            if (type === true) {
                value = result.join('');
            } else {
                value = result.join('') + '.' + t2;
            }
            return value;
        }

        var token = $("input[name='_token']").val();

        $('#standardSelect').change(function () {
            var level_id = $(this).val();
            var iteValue = $("#standardSelect").find("option:selected").attr("emoney");
            var money = $("#standardSelect").find("option:selected").attr("money");
            if (typeof(iteValue) == "undefined") {
                var str = "";
            } else {
                var str = "{{trans('adminUser.tips1')}}" + iteValue + "{{trans('adminUser.tips2')}}" + money + "{{trans('adminUser.tips3')}}";
            }
            $("#choice").html(str);
            var url = '<?php echo route('admin::adminUser.info') ?>';
            if (level_id > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {level_id: level_id},
                    headers: {'X-CSRF-Token': token},
                    success: function (result) {
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
            }
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