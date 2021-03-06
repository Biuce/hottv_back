@include('admin.header')

{{--@section('content')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('equipment.managers')}}</span>
                </h4>
                {{--<div class="form-group">--}}
                {{--<a href="{{ route('admin::equipment.create') }}">--}}
                {{--<button class="btn btn-primary" type="button"--}}
                {{--id="submitBtn">{{trans('equipment.newEquipment')}}</button>--}}
                {{--</a>--}}
                {{--</div>--}}
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;" id="mytable">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('equipment.assort')}}</th>
                        <th>{{trans('equipment.retail_price')}}</th>
                        <th>{{trans('equipment.ours')}}</th>
                        <th>{{trans('equipment.diamond')}}</th>
                        <th>{{trans('equipment.medal')}}</th>
                        <th>{{trans('equipment.silver')}}</th>
                        <th>{{trans('equipment.copper')}}</th>
                        <th>{{trans('equipment.defined')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            {{--<tr>--}}
                                {{--<td>{{ $k }}</td>--}}
                                {{--<td>{{ $prices[$i++] ?? 0 }}</td>--}}
                                {{--@isset($v['money'])--}}
                                    {{--@foreach($v['money'] as $key => $my)--}}
                                        {{--<td>{{ $my ?? 0 }}</td>--}}
                                    {{--@endforeach--}}
                                {{--@endisset--}}
                            {{--</tr>--}}
                            <tr>
                            <td>{{ $v ?? 0 }}</td>
                            <td>{{ $retail[$k] ?? 0 }}</td>
                            <td>{{ $data[3][$k] ?? 0 }}</td>
                            <td>{{ $data[4][$k] ?? 0 }}</td>
                            <td>{{ $data[5][$k] ?? 0 }}</td>
                            <td>{{ $data[6][$k] ?? 0 }}</td>
                            <td>{{ $data[7][$k] ?? 0 }}</td>
                            <td>{{ $data[8][$k] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{--@endsection--}}

@extends('admin.js')
@section('js')
    <script type="text/javascript" src="/public/admin/js/bootstable.js"></script>
    <script>
        var pid = '<?php echo \Auth::guard('admin')->user()->pid; ?>';
        var str = "";
        if (pid == 0) {
            str = "2,3,4,5,6,7";
        } else if (pid == 1) {
            str = "3,4,5,6,7";
        } else {
            str = "70";
        }
        $('#mytable').SetEditable({
            columnsEd: str,  //???????????????
            onEdit: function (row) {
                var str = row[0].innerText;
                var arr = new Array();
                arr = str.trim().split(/\s+/);
                var url = '<?php echo route('admin::equipment.edit') ?>';
                $.ajax({
                    url: url,
                    type: "POST",??????//????????????
                    data: {param: arr},
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    success: function (result) {
                        if (result.code !== 0) {
                            layer.msg(result.msg, {
                                shift: 5,
                                skin: 'alert-secondary alert-lighter',
                                time: 2000
                            }, function () {
                                location.reload();
                            });
                            return false;
                        }
                        if (result.redirect) {
                            location.href = '{!! url()->current() !!}';
                        }
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

        var token = $("input[name='_token']").val();
        function deleteUser(url) {
            $.ajax({
                url: url,
                type: "DELETE",??????//????????????
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
                        layer.msg('???????????????', {icon: 5, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 401) {
                        layer.msg('????????????', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 429) {
                        layer.msg('????????????????????????????????????', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 419) {
                        layer.msg('??????????????????????????????????????????', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 500) {
                        layer.msg('?????????????????????????????????', {shift: 6, skin: 'alert-secondary alert-lighter'});
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