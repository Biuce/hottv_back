@include('admin.header')

{{--@section('content')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('level.managers')}}</span>
                </h4>
                <div class="form-group">
                    <a href="{{ route('admin::level.create') }}">
                        <button class="btn btn-primary" type="button"
                                id="submitBtn">{{trans('level.newLevel')}}</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('level.id')}}</th>
                        <th>{{trans('level.level_name')}}</th>
                        <th>{{trans('level.mini_amount')}}</th>
                        <th>{{trans('general.create')}}</th>
                        <th>{{trans('general.action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                <td>{{ $v['level_name'] }}</td>
                                <td>{{ $v['mini_amount'] }}</td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{trans('general.action')}}</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            {{--<a class="dropdown-item"--}}
                                               {{--href="{{ route('admin::level.edit', ['id' => $v['id']]) }}">{{trans('general.edit')}}</a>--}}
                                            <a class="dropdown-item"
                                               href="{{ route('admin::level.info', ['id' => $v['id']]) }}">{{trans('level.info')}}</a>
                                            <a class="dropdown-item" href="javascript:;"
                                               data-modal-trigger='{"target":"#modal-sample"}'>{{trans('general.delete')}}</a>
                                        </div>
                                    </div>
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
                                                    onclick="deleteUser('{{ route('admin::level.delete', ['id' => $v['id']]) }}')">{{trans('general.confirm')}}</button>
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