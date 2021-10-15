@include('admin.header')

{{--@section('content')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('authCode.try_managers')}}</span>
                </h4>
                @if(\Auth::guard('admin')->user()->id != 1)
                    <div class="form-group">
                        <a href="{{ route('admin::try.records') }}">
                            <button class="btn btn-primary" type="button"
                                    id="submitBtn">{{trans('authCode.access_records')}}</button>
                        </a>

                        <a href="{{ route('admin::try.add') }}" style="margin-left: 30px;">
                            <button class="btn btn-primary" type="button"
                                    id="submitBtn">{{trans('authCode.tryNewAuthCode')}}</button>
                        </a>
                    </div>
                @endif
            </div>

            <div style="margin-top: 20px;">
                <form name="admin_list_sea" class="form-search" method="get" id="export" action="{{ route('admin::try.list') }}">
                    {{ csrf_field() }}
                    <div class="row row-15">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input class="form-control" id="text" type="text"
                                       placeholder="{{trans('authCode.try_code')}}" name="auth_code"
                                       value="{{ $condition['auth_code'] ?? ''  }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" name="status">
                                <option value="-1">{{ trans('general.select') }}</option>
                                <option value="0"
                                        @if(isset($condition['status']) && $condition['status'] == 0) selected @endif>{{trans('authCode.status_unused')}}</option>
                                <option value="1"
                                        @if(isset($condition['status']) && $condition['status'] == 1) selected @endif>{{trans('authCode.status_have_used')}}</option>
                                {{--<option value="2" @if(isset($condition['status']) && $condition['status'] == 2) selected @endif>{{trans('authCode.status_was_due')}}</option>--}}
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-success" type="submit" lay-submit lay-filter="formorder"
                                    id="submitBtn" onclick="check(1)">{{trans('general.search')}}</button>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-success" type="submit" onclick="check(2)">{{trans('general.excel')}}</button>
                        </div>
                        <div class="col-sm-4">
                            <span style="font-size: 20px;">{{trans('authCode.free_code')}} <span
                                        style="color: #fad430">{{ \Auth::guard('admin')->user()->try_num }}</span></span>
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
                        <th>{{trans('authCode.id')}}</th>
                        <th>{{trans('authCode.try_code')}}</th>
                        <th>{{trans('authCode.status')}}</th>
                        <th>{{trans('authCode.remark')}}</th>
                        <th>{{trans('authCode.expire_at')}}</th>
                        <th>{{trans('general.create')}}</th>
                        <th>{{trans('general.action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                <td>{{ $v['auth_code'] }}</td>
                                <td @if($v['status'] == 0)
                                    class="text-danger"
                                    @elseif($v['status'] == 1)
                                    class="text-warning"
                                    @else
                                    class="text-primary"
                                        @endif>
                                    @if($v['status'] == 0)
                                        {{trans('authCode.status_unused')}}
                                    @elseif($v['status'] == 1)
                                        {{trans('authCode.status_have_used')}}
                                    @elseif($v['status'] == 2)
                                        {{trans('authCode.status_was_due')}}
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
                                <td>{{ isset($v['expire_at']) ? date("Y-m-d", strtotime($v['expire_at'])) : "" }}</td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{trans('general.action')}}</span>
                                        </button>
                                        @if(\Auth::guard('admin')->user()->id != 1)
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="javascript:;"
                                                   data-modal-trigger='{"target":"#modal-sample-{{$v['id']}}"}'>{{trans('authCode.up_remark')}}</a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="modal-sample-{{ $v['id'] }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{trans('general.message')}}</h5>
                                            <button class="close" data-dismiss="modal" onclick="cancle('{{$v['id']}}')">
                                                ×
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" id="form">
                                                {{ csrf_field() }}
                                                <input class="form-control" type="hidden" id="beizhu-{{ $v['id'] }}"
                                                       value="{{ $v['remark'] }}">
                                                <div class="row form-group">
                                                    <div class="col-sm-2 text-sm-right">
                                                        <label class="col-form-label"
                                                               for="standardInput">{{trans('authCode.remark')}}</label>
                                                    </div>
                                                    <div class="col-sm-10" style="margin-bottom: 20px;">
                                                        <div class="input-group form-group">
                                                            <textarea id="standardRemark-{{ $v['id'] }}"
                                                                      class="form-control" rows="3"
                                                                      name="remark"
                                                                      maxlength="128">{{ $v['remark'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary"
                                                            data-dismiss="modal"
                                                            onclick="cancle('{{$v['id']}}')">{{trans('general.cancel')}}</button>
                                                    <button class="btn btn-primary" type="submit"
                                                            onclick="authCode('{{ route('admin::code.update', ['id' => $v['id']]) }}', '{{$v['id']}}')">{{trans('general.confirm')}}</button>
                                                </div>
                                            </form>
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
                {!! $lists->appends(['auth_code'=>$lists->auth_code, 'status'=>$lists->status])->render() !!}
            </div>
        </div>
    </div>
</div>

{{--@endsection--}}

@extends('admin.js')
@section('js')
    <script>
        var token = $("input[name='_token']").val();

        function check(id) {
            var url = "{{ route('admin::try.list') }}";
            var excel_url = "{{ route('admin::try.tryExport') }}";
            if (id == 1) {
                console.log(url);
                $( "#export" ).attr( 'action' ,url);     //通过jquery为action属性赋值
            } else {
                console.log(excel_url);
                $( "#export" ).attr( 'action' ,excel_url);     //通过jquery为action属性赋值
            }
        }

        function excel(url) {
            $.ajax({
                url: url,
                type: "GET",   //请求方式
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
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

        function excelaaa(url) {
            $.ajax({
                url: url,
                type: "GET",   //请求方式
//                data: {remark: remark},
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    if (result.code !== 0) {
                        layer.msg(result.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    }
                    if (result.redirect) {
                        location.href = '{{ route('admin::try.list') }}';
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
        function cancle(id) {
            var remark = $("#beizhu-" + id).val();
            $("#standardRemark-" + id).val(remark);
        }

        function authCode(url, id) {
            var remark = $("#standardRemark-" + id).val();
            console.log(url);
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
//                    layer.msg(result.msg, {shift: 1}, function () {
//                        if (result.reload) {
//                            location.reload();
//                        }
                    if (result.redirect) {
                        location.href = '{{ route('admin::try.list') }}';
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
        }
    </script>
    @endsection
    </div>
    </body>
    </html>