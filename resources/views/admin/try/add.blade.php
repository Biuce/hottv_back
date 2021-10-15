@include('admin.header')
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('authCode.apply_code')}}
            </h4>
        </div>

        <div class="panel-body">
            <form action="{{ route('admin::try.hold')}}" method="post" id="form" onsubmit="return false;">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('authCode.av_number')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <span class="form-control" style="border-style:none" id="need">{{ \Auth::guard('admin')->user()->try_num }}</span>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('authCode.generate_code')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="number" aria-describedby="icon-addon1"
                                   value="" id="number" maxlength="3" onkeyup="onlyNumber(this)"
                                   onblur="onlyNumber(this)" onmouseover="onlyNumber(this)">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('authCode.remark')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <textarea class="form-control" id="standardRemark" rows="3"
                                      name="remark" maxlength="128"> </textarea>
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    <div class="col-sm-12 text-sm-center">
                        <button class="btn btn-primary" type="submit"
                                id="submitBtn">{{trans('authCode.apply_code')}}</button>
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
            //先把非数字的都替换掉，除了数字    
            obj.value = obj.value.replace(/[^\d]/g, '');
            //前两位不能是0加数字      
            obj.value = obj.value.replace(/^0\d[0-9]*/g, '');
            //必须保证第一个为数字而不是.       
            obj.value = obj.value.replace(/^\./g, '');
            if (t == '-') {
                return;
            }
        }

        //监听提交
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            var method = $("#form").attr("method");
            var action = $('#form').attr("action");
            console.log(method);
            console.log(action);
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