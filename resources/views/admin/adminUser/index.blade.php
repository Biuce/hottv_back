@include('admin.header')

{{--@section('content')--}}
{{--@include('admin.breadcrumb')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('adminUser.managers')}}</span>
                </h4>
                <div class="form-group">
                    <a href="{{ route('admin::adminUser.create') }}">
                        <button class="btn btn-primary" type="button"
                                id="submitBtn">{{trans('adminUser.newAdministrator')}}</button>
                    </a>
                </div>
            </div>

            <div style="margin-top: 20px;">
                <form name="admin_list_sea" class="form-search" method="get"
                      action="{{ route('admin::adminUser.index') }}">
                    {{ csrf_field() }}
                    <div class="row row-15">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input class="form-control" id="text" type="text"
                                       placeholder="{{trans('adminUser.name')}}" name="name"
                                       value="{{ $condition['name'] ?? '' }}" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <button class="btn btn-success" type="submit" lay-submit lay-filter="formAdminUser"
                                    id="submitBtn">{{trans('general.search')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('adminUser.id')}}</th>
                        <th>{{trans('adminUser.agency_name')}}</th>
                        <th>{{trans('adminUser.agency_level')}}</th>
                        <th>{{trans('adminUser.balance')}}</th>
                        <th>{{trans('huobi.add_lower_profit')}}</th>
                        <th>{{trans('adminUser.remark')}}</th>
                        {{--<th>{{trans('adminUser.is_cancel')}}</th>--}}
                        <th>{{trans('general.create')}}</th>
                        <th>{{trans('general.action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                @if($v['is_cancel'] != 0)
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;color: #505050"
                                        title="{{ $v['name'] ?? ""}}">{{ $v['name'] ?? "" }} {{trans('general.is_del')}}</td>
                                @else
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"
                                        title="{{ $v['name'] ?? "" }}">{{ $v['name'] ?? "" }}</td>
                                @endif
                                <td>
                                    {{ isset($v->levels->level_name) ? $v->levels->level_name : "" }}
                                    @if(\Auth::guard('admin')->user()->level_id <= 3)
                                        @if($v->type == 2)
                                            <i>Pro</i>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ number_format($v['balance'], 2) }}</td>
                                <td>
                                    <?php
                                    if (\Auth::guard('admin')->user()->id == 1) {
                                        $where = ["user_id" => $v['id'], 'status' => 0];
                                    } else {
                                        $where = ["user_id" => \Auth::guard('admin')->user()->id, 'status' => 0, 'type' => 1, 'create_id' => $v['id']];
                                    }
                                    $user_lirun = App\Model\Admin\Huobi::query()->where($where)->get();
                                    $user_pro = 0;
                                    foreach ($user_lirun as $value) {
                                        $assort_where = ['user_id' => $parent_id, 'assort_id' => $value['assort_id'], 'level_id' => 3];
                                        $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                                        $total = isset($assort_level->money) ? bcmul($assort_level->money, $value['number'], 2) : 0;
                                        $user_pro += $total;
                                    }
                                    $profit = App\Repository\Admin\HuobiRepository::levelByRecord($where);
                                    ?>
                                    @if(\Auth::guard('admin')->user()->id == 1)
                                        {{ number_format($user_pro, 2) }}
                                    @else
                                        {{ number_format($profit, 2) }}
                                    @endif
                                </td>
                                <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"
                                    title="{{ $v['remark'] }}">
                                    @if(mb_strlen($v['remark']) > 10)
                                        <?php $str = mb_substr($v['remark'], 0, 10); ?>
                                        {{ $str }}...
                                    @else
                                        {{ $v['remark'] }}
                                    @endif
                                </td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>
                                    @if(\Auth::guard('admin')->user()->id != 1)
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                <span>{{trans('general.action')}}</span>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.check', ['id' => $v['id']]) }}">{{trans('adminUser.check')}}</a>
                                                @if($v['is_cancel'] == 0)
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin::adminUser.recharge', ['id' => $v['id']]) }}">{{trans('adminUser.chongzhi')}}</a>
                                                @endif
                                                @if($v['is_cancel'] != 2)
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin::adminUser.lower', ['id' => $v['id']]) }}">{{trans('adminUser.lower_agent')}}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                    aria-expanded="false">
                                                <span>{{trans('general.action')}}</span>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.look', ['id' => $v['id']]) }}">{{trans('adminUser.check_cost')}}</a>
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.lower', ['id' => $v['id']]) }}">{{trans('adminUser.lower_agent')}}</a>
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.check', ['id' => $v['id']]) }}">{{trans('adminUser.check')}}</a>
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.recharge', ['id' => $v['id']]) }}">{{trans('adminUser.chongzhi')}}</a>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="modal-sample">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{trans('general.message')}}</h5>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{trans('general.deleteSure')}}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary"
                                                    data-dismiss="modal">{{trans('general.cancel')}}</button>
                                            <button class="btn btn-primary"
                                                    onclick="deleteUser('{{ route('admin::adminUser.delete', ['id' => $v['id']]) }}')">{{trans('general.confirm')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                {!! $lists->appends(['name'=>$lists->name])->render() !!}
            </div>
        </div>
    </div>
</div>

{{--@endsection--}}

@extends('admin.js')
@section('js')
    <script>
        var token = $("input[name='_token']").val();
        function deleteUser(url) {
            $.ajax({
                url: url,
                type: "DELETE",   //请求方式
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
    </script>
    @endsection
    </div>
    </body>
    </html>