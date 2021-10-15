@include('admin.header')

{{--@section('content')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('logoffUser.managers')}}</span>
                </h4>
                @if($type != 1)
                    <div class="form-group">
                        <a href="{{ route('admin::cancel.index', ['type' => 1]) }}">
                            <button class="btn btn-primary" type="button"
                                    id="submitBtn">{{trans('logoffUser.cancelled_record')}}</button>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('logoffUser.id')}}</th>
                        <th>{{trans('adminUser.name')}}</th>
                        <th>{{trans('adminUser.level')}}</th>
                        <th>{{trans('adminUser.balance')}}</th>
                        <th>{{trans('logoffUser.create')}}</th>
                        <th>{{trans('general.action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                <td>{{ $v->users->name }}</td>
                                <td>{{ $v->users->levels->level_name }}</td>
                                <td>{{ number_format($v->users->balance, 2) }}</td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{trans('general.action')}}</span>
                                        </button>
                                        @if(\Auth::guard('admin')->user()->id == 1)
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::cancel.look', ['id' => $v['id']]) }}">{{trans('logoffUser.check')}}</a>
                                            </div>
                                        @else
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::cancel.check', ['id' => $v['id']]) }}">{{trans('logoffUser.check')}}</a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                {{ $lists->links() }}
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