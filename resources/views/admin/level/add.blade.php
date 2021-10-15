@include('admin.header')
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                @if(isset($id))
                    {{trans('level.editLevel')}}
                @else
                    {{trans('level.newLevel')}}
                @endif
            </h4>
        </div>

        <div class="panel-body">
            <form action="@if(isset($id)){{ route('admin::level.update', ['id' => $id]) }}@else{{ route('admin::level.save')}}@endif"
                  method="post" id="form">
                @if(isset($id)) {{ method_field('PUT') }} @endif
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('level.level_name')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="level_name" aria-describedby="icon-addon1"
                                   value="{{ $info->level_name ?? ''  }}">
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    @if(isset($id))
                        <div class="col-sm-12 text-sm-center">
                            <button class="btn btn-primary" type="submit" id="submitBtn">{{trans('level.editLevel')}}</button>
                            <div style="display:inline;float:right;">
                                <button type="button" class="btn btn-warning"
                                        onclick="history.go(-1);">{{trans('general.return')}}</button>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-12 text-sm-center">
                            <button class="btn btn-primary" type="submit" id="submitBtn">{{trans('level.newLevel')}}</button>
                            <div style="display:inline;float:right;">
                                <button type="reset" class="btn btn-secondary">{{trans('general.reset')}}</button>
                                <button type="button" class="btn btn-warning"
                                        onclick="history.go(-1);">{{trans('general.return')}}</button>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>

    </div>
</div>

@extends('admin.js')
@section('js')
    <script>
        var id = '<?php echo isset($id) ? $id : 0; ?>';

        //监听提交
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            if (id == 0) {
                var method = $("#form").attr("method");
            } else {
                var method = "PUT";
            }
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
                            location.href = '{{ route('admin::level.index') }}';
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