@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('adminUser.check')}}
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
                                {{ trans('adminUser.name')}}: {{ $info['name'] }}
                            </li>
                            @if($info['is_cancel'] == 0)
                                <li class="list-inline-item">
                                    {{ trans('adminUser.email')}}: {{ $info['email'] }}
                                </li>
                            @endif
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{ trans('adminUser.phone')}}: {{ $info['phone'] }}
                            </li>
                            <li class="list-inline-item">
                                {{ trans('adminUser.level')}}: {{ $info->levels->level_name }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{ trans('adminUser.account')}}: {{ $info['account'] }}
                            </li>
                            @if($info['is_new'] == 0)
                                <li class="list-inline-item">
                                    {{ trans('adminUser.password')}}: {{ $info['password'] }}
                                </li>
                            @endif
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{ trans('adminUser.remark')}}: {{ $info['remark'] }}
                            </li>
                        </ul>
                        <HR align=center width="100%" color=#987cb9 SIZE=1 style="margin-top: 30px;">
                    </div>
                </div>
                <div class="media-item">
                    <span>{{trans('adminUser.balance')}}
                        : {{ number_format($info['balance'], 2) }}</span>
                    <span style="margin-left: 40px;">{{trans('adminUser.add_recharge')}}
                        : {{ number_format($info['recharge'], 2) }}</span>
                    <span style="margin-left: 40px;">{{trans('adminUser.add_profit')}}
                        : {{ number_format($profit, 2) }}</span>
                </div>
            </div>
            <div class="modal fade" id="modal-sample">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('general.message')}}</h5>
                            <button class="close" data-dismiss="modal" onclick="clears()">×</button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" id="form">
                                {{ csrf_field() }}
                                <input class="form-control" type="hidden" id="beizhu" value="{{ $info['remark'] }}">
                                <div class="row form-group">
                                    <div class="col-sm-2 text-sm-right">
                                        <label class="col-form-label"
                                               for="standardInput">{{trans('adminUser.remark')}}</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="input-group form-group">
                                            <textarea class="form-control" id="standardRemark" rows="3"
                                                      name="remark" maxlength="128">{{ $info['remark'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary"
                                    data-dismiss="modal" onclick="clears()">{{trans('general.cancel')}}</button>
                            <button class="btn btn-primary"
                                    onclick="adminRemark('{{ route('admin::adminUser.remark', ['id' => $info['id']]) }}')">{{trans('general.confirm')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="panel-body">
            <div class="col-md-12">
                <div class="tabs tabs-vertical-top tabs-accent">
                    <ul class="nav nav-tabs scroller scroller-horizontal" role="tablist" style="width: 30%;">
                        <li class="nav-item">
                            <a class="nav-link @if($tags == 0 || $tags == 1) active @endif" data-toggle="tab"
                               href="#panelTab6-1" role="tab"
                               aria-selected="true">{{trans('adminUser.profit_record')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if($tags == 2) active @endif" data-toggle="tab"
                               href="#panelTab6-2" role="tab" aria-selected="false">
                                <span class="fa-bolt"></span> {{trans('adminUser.recharge_record')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade @if($tags == 0 || $tags == 1) show active @endif" id="panelTab6-1"
                             role="tabpanel">
                            <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                                <thead class="border-bottom">
                                <tr class="long-tr">
                                    <th>{{trans('adminUser.create_time')}}</th>
                                    <th>{{trans('adminUser.code_func')}}</th>
                                    <th>{{trans('adminUser.make_profit')}}</th>
                                </tr>
                                </thead>
                                <tbody id="list-content">
                                @isset($user_profit)
                                    @foreach($user_profit as $k => $v)
                                        <?php
                                        if (\Auth::guard('admin')->user()->id == 1) {
                                            $assort_where = ['user_id' => $v['user_id'], 'assort_id' => $v['assort_id'], 'level_id' => 3];
                                            $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                                            $money = $assort_level->money ?? 0;
                                            $total = bcmul($money, $v['number'], 2);
                                        }
                                        ?>
                                        @if((\Auth::guard('admin')->user()->id == 1 && $total > 0) || (\Auth::guard('admin')->user()->id != 1 && $v['money'] > 0))
                                            <tr>
                                                <td>{{ $v['created_at'] }}</td>
                                                <td>
                                                    <?php
                                                    if (\Auth::guard('admin')->user()->id == 1) {
                                                        $details = App\Repository\Admin\AdminUserRepository::find($v['user_id']);
                                                    } else {
                                                        $details = App\Repository\Admin\AdminUserRepository::find($v['own_id']);
                                                    }
                                                    $assort = App\Repository\Admin\AssortRepository::find($v['assort_id']);
                                                    ?>
                                                    @if($v['status'] == 1 && $v['type'] == 2)
                                                        @if(isset($details->name))
                                                            {{ $details->name }} {{ trans('adminUser.lower') }}
                                                        @else
                                                            {{ trans('adminUser.lower') }}
                                                        @endif
                                                    @elseif($v['status'] == 1 && $v['type'] == 1)
                                                        {{ trans('adminUser.myself') }}
                                                    @elseif($v['status'] == 0 && $v['type'] == 1)
                                                        @if ($details->account == $v['user_account'])
                                                            {{ $details->name }} {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                        @else
                                                            {{ $details->name }} {{ trans('general.as_lower') }} {{ $v['user_account'] }} {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                        @endif

                                                    @elseif($v['status'] == 0 && $v['type'] == 2)
                                                        {{ $details->name }} {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(\Auth::guard('admin')->user()->id == 1)
                                                        {{ number_format($total, 2) }}
                                                    @else
                                                        {{ number_format($v['money'], 2) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                                {{ $user_profit->appends(['profit'=>1])->links() }}
                            </div>
                        </div>

                        <div class="tab-pane fade @if($tags == 2) active show @endif" id="panelTab6-2" role="tabpanel">
                            <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                                <thead class="border-bottom">
                                <tr class="long-tr">
                                    <th>{{trans('adminUser.recharge_time')}}</th>
                                    <th>{{trans('adminUser.recharge_num')}}</th>
                                </tr>
                                </thead>
                                <tbody id="list-content">
                                @isset($user_recharge)
                                    @foreach($user_recharge as $k => $v)
                                        <tr>
                                            <td>{{ $v['created_at'] }}</td>
                                            <td>{{ number_format($v['money'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                                {{ $user_recharge->appends(['profit'=>2])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-10 align-items-center">
                <div class="col-sm-12 text-sm-center">
                    {{--<div style="display:inline;float:right;">--}}
                    <button type="button" class="btn btn-warning"
                            id="backurl" onclick="javascript:history.back(-1);">{{trans('general.return')}}</button>
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </div>
</div>

@extends('admin.js')
@section('js')
    <script>
        laydate.render({
            elem: '#date2',
            type: 'month',
            lang: '{{\App::getLocale()}}'
        });

        //        var refer = document.referrer;
        //        document.getElementById('backurl').value = refer;

        function clears(id) {
            var remark = $("#beizhu").val();
            $("#standardRemark").val(remark);
        }

        var token = $("input[name='_token']").val();
        function adminRemark(url) {
            var remark = $("#standardRemark").val();
            $.ajax({
                url: url,
                type: "PUT",   //请求方式
                data: {remark: remark},
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    }
                    layer.msg(result.msg, {shift: 1}, function () {
                        if (result.reload) {
                            location.reload();
                        }
                        if (result.redirect) {
                            location.href = '{!! url()->current() !!}';
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
        }

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

            return false;
        });
    </script>
    @endsection
    </div>
    </body>
    </html>