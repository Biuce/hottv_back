@include('admin.header')

{{--@section('content')--}}
{{--@include('admin.breadcrumb')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('adminUser.contact')}}</span>
                </h4>
            </div>

            <div style="margin-top: 20px;display: none;">
                <form name="admin_list_sea" class="form-search" method="get"
                      action="{{ route('admin::adminUser.logoff') }}">
                    {{ csrf_field() }}
                    <div class="row row-15">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input class="form-control" id="text" type="text"
                                       placeholder="{{trans('adminUser.name')}}" name="name"
                                       value="{{ $condition['name'] ?? ''  }}" autocomplete="off">
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
                        <th>{{trans('adminUser.email')}}</th>
                        <th>{{trans('adminUser.phone')}}</th>
                        <th>{{trans('general.status')}}</th>
                        <th>{{trans('general.create')}}</th>
                        <th>{{trans('general.action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"
                                    title="{{ $v['name'] }}">{{ $v['name'] }}</td>
                                <td>
                                    {{ isset($v->levels->level_name) ? $v->levels->level_name : "" }}
                                    @if(\Auth::guard('admin')->user()->level_id <= 3)
                                        @if($v->type == 2)
                                            <i>Pro</i>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ number_format($v['balance'], 2) }}</td>
                                <td>{{ $v['email'] }}</td>
                                <td>{{ $v['phone'] }}</td>
                                <td
                                        @if($v['is_relation'] == 1)
                                        class="text-danger"
                                        @elseif($v['is_relation'] == 2)
                                        class="text-success"
                                        @endif
                                >
                                    @if($v['is_relation'] == 1)
                                        {{trans('adminUser.not_contact')}}
                                    @elseif($v['is_relation'] == 2)
                                        {{trans('adminUser.already_contacted')}}
                                    @endif
                                </td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{trans('general.action')}}</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:;"
                                               data-modal-trigger='{"target":"#modal-sample-{{$v['id']}}"}'>{{trans('adminUser.already_contacted')}}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="modal-sample-{{ $v['id'] }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{trans('general.message')}}</h5>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{trans('adminUser.is_contact')}}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary"
                                                    onclick="changeStatus('{{ route('admin::adminUser.change', ['id' => $v['id']]) }}', '{{$v['id']}}')">{{trans('adminUser.already_contacted')}}</button>
                                            <button class="btn btn-secondary"
                                                    data-dismiss="modal">{{trans('general.cancel')}}</button>
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
        function changeStatus(url, id) {
            $.ajax({
                url: url,
                type: "PUT",   //请求方式
                data: {id: id},
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
                            location.href = '{!! url()->current() !!}';
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
        }
    </script>
    @endsection
    </div>
    </body>
    </html>