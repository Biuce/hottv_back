@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('logoffUser.check')}}
            </h4>
        </div>
        <section class="section-sm bg-800">
            <div class="container-fluid">
                <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                    <div class="media-item">
                        <img class="rounded"
                             @if(!empty($info->users->photo))
                             src="{{ $info->users->photo }}"
                             @else
                             src="/public/images/users/user-09-247x247.png"
                             @endif
                             width="165"
                             height="165" alt="">
                    </div>
                    <div class="media-body">
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.name')}}: {{ $info->users->name }}
                            </li>
                            <li class="list-inline-item">
                                {{trans('adminUser.email')}}: {{ $info->users->email }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.phone')}}: {{ $info->users->phone }}
                            </li>
                            <li class="list-inline-item">
                                {{trans('adminUser.level')}}: {{ $info->users->levels->level_name }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.account')}}: {{ $info->users->account }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.remark')}}: {{ $info->users->remark }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-sm bg-800">
            <div class="container-fluid">
                <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                    <div class="media-body">
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('logoffUser.usable_huobi')}}: {{ number_format($info->users->balance, 2) }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('logoffUser.name')}}: {{ $info['name'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('logoffUser.bank_name')}}: {{ $info['bank_name'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('logoffUser.bank_account')}}: {{ $info['bank_account'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('logoffUser.phone')}}: {{ $info['phone'] }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row row-10 align-items-center">
                <div class="col-sm-12 text-sm-center">
                    @if($info['status'] == 0)
                        <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                id="submitBtn"
                                onclick="Cancel('{{ route('admin::cancel.update', ['id' => $info['id'], 'type' => 1]) }}')">{{trans('logoffUser.pass')}}</button>
                        <div style="display:inline;float:right;margin-right: 50px;">
                            <button class="btn btn-danger" type="submit" lay-submit lay-filter="formAdminUser"
                                    id="submitBtn"
                                    onclick="Cancel('{{ route('admin::cancel.update', ['id' => $info['id'], 'type' => 2]) }}')">{{trans('logoffUser.reject')}}</button>
                            @endif
                            <button type="button" class="btn btn-warning"
                                    onclick="history.go(-1);">{{trans('general.return')}}</button>
                        </div>
                </div>
            </div>
        </section>

    </div>
</div>

@extends('admin.js')
@section('js')
    <script>
        var token = $("input[name='_token']").val();
        //监听提交
        function Cancel(url) {
            $.ajax({
                type: "PUT",
                url: url,
//                data: {type: type},
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
//                headers: {'X-CSRF-Token': token},
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
                            location.href = '{{ route('admin::cancel.index') }}';
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