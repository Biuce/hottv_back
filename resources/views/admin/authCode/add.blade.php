@include('admin.header')
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('authCode.newAuthCode')}}
            </h4>
        </div>

        <div class="panel-body">
            <form action="{{ route('admin::code.save')}}" method="post" id="form" onsubmit="return false;">
                {{ csrf_field() }}
                <input class="form-control" type="hidden" name="mini_money" value="" id="mini_money">
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('authCode.type')}}</label>
                    </div>
                    <div class="col-sm-11">
                        <div class="input-group form-group">
                            <select class="form-control" id="standardSelect" name="assort_id">
                                <option value="0">{{trans('authCode.choice_code')}}</option>
                                @foreach($equipment ?? null as $v)
                                    <option value="{{ $v->assort_id }}"
                                            emoney="{{ $v->money }}">{{ $v->assorts->assort_name }} {{ $v->money }} {{trans('huobi.money')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('authCode.number')}}</label>
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
                <div class="row form-group">
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('authCode.need')}}</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="huobi" aria-describedby="icon-addon1"
                                   value="0" readonly id="huobi">
                        </div>
                    </div>
                    <div class="col-sm-1 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('authCode.own')}}</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="input-group form-group">
                            <span class="form-control"
                                  style="border-style:none">{{ number_format(\Auth::guard('admin')->user()->balance, 2) }}</span>
                            {{--<input class="form-control" type="text" name="own" aria-describedby="icon-addon1"--}}
                            {{--value="{{ number_format(\Auth::guard('admin')->user()->balance, 2) }}" readonly id="own" style="border-style:none">--}}
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    <div class="col-sm-12 text-sm-center">
                        <button class="btn btn-primary" type="submit"
                                id="submitBtn">{{trans('authCode.newAuthCode')}}</button>
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

        /**
         *  金额分隔符
         * @param num 值
         * @param del 小数位
         * @returns {string}
         * @constructor
        */
        function RetainedDecimalPlaces(num, del) {
            if (del != 0) {
                num = parseFloat(num).toFixed(del); //天花板函数保留小数并四舍五入
            }
            var source = String(num).split(".");//按小数点分成2部分
            source[0] = source[0].replace(new RegExp('(\\d)(?=(\\d{3})+$)', 'ig'), "$1,");//只将整数部分进行都好分割
            return source.join(".");//再将小数部分合并进来
        }


        $("#number").blur(function () {
            var iteValue = $("#standardSelect").find("option:selected").attr("emoney");
            var num = $("#number").val();
            var total = iteValue * num;
            var own = Math.floor($("#own").val());
            // 赋值给id为huobi的input
            var ival = parseInt(total);
            if (!isNaN(ival)) {
                $("#huobi").val(RetainedDecimalPlaces(total, 2));
            } else {
                $("#huobi").val(0);
            }

            $("#mini_money").val(iteValue);
            // 判断添加火币总金额是否大过自己所拥有的金额，如果超过则进行提示
            if (total > own) {
                layer.msg("{{trans('authCode.exceed')}}", {shift: 5, skin: 'alert-secondary alert-lighter'});
            }
        });

        $("#standardSelect").blur(function () {
            var iteValue = $("#standardSelect").find("option:selected").attr("emoney");
            var num = $("#number").val();
            var total = iteValue * num;
            var own = Math.floor($("#own").val());
            // 赋值给id为huobi的input
            var ival = parseInt(total);
            if (!isNaN(ival)) {
                $("#huobi").val(total);
            } else {
                $("#huobi").val(0);
            }
            $("#mini_money").val(iteValue);
            // 判断添加火币总金额是否大过自己所拥有的金额，如果超过则进行提示
            if (total > own) {
                layer.msg("{{trans('authCode.exceed')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
            }
        });

        //监听提交
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            var value = $("#standardSelect").val();
            var num = $("#number").val();
            // 授权码类型必填且大于0
            if (value <= 0) {
                layer.msg("{{trans('authCode.code_type')}}", {shift: 6});
                window.location.reload();
                return false;
            }
            // 授权码数量必填且大于0
            if (num <= 0) {
                layer.msg("{{trans('authCode.code_num')}}", {shift: 6});
                window.location.reload();
                return false;
            }
            var method = $("#form").attr("method");
            var action = $('#form').attr("action");
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
//                    layer.msg(result.msg, {shift: 1}, function () {
//                        if (result.reload) {
//                            location.reload();
//                        }
                    if (result.redirect) {
                        location.href = '{{ route('admin::code.index') }}';
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

            return false;
        });

    </script>
    @endsection
    </div>
    </body>
    </html>