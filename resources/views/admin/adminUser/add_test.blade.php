@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                @if(isset($id))
                    {{trans('adminUser.editAdministrator')}}
                @else
                    {{trans('adminUser.newAdministrator')}}
                @endif
            </h4>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ route('admin::adminUser.save') }}" id="form" onsubmit="return false;">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.agency_name')}}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="input-group form-group">
                            <div class="input-group-prepend"><span class="input-group-text" id="icon-addon1"><span
                                            class="fa-user"></span></span></div>
                            <input class="form-control" type="text" name="name" aria-describedby="icon-addon1" value=""
                                   maxlength="20" id="agency_name">
                        </div>
                    </div>
                </div>
                @if(\Auth::guard('admin')->user()->id == 1)
                    <div class="row form-group">
                        <div class="col-sm-2 text-sm-right">
                            <label class="col-form-label" for="standardInput">{{trans('adminUser.channel')}}</label>
                        </div>
                        <div class="col-sm-10">
                            <select class="form-control" id="channel_id" name="channel_id">
                                <option value="0">{{trans('general.select')}}</option>
                                @foreach($channels ?? null as $v)
                                    <option value="{{ $v['channel_id'] }}">{{ $v['channel_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.agency_level')}}</label>
                    </div>
                    <div class="col-sm-10">
                        <select class="form-control" id="standardSelect" name="level_id">
                            <option value="0">{{trans('general.select')}}</option>
                            @foreach($level ?? null as $v)
                                @if($v['id'] != 4)
                                    <option value="{{ $v['id'] }}" emoney="{{ $v['level_name'] }}"
                                            money="{{ $v['mini_amount']}}"
                                            @isset($info) @if($v['id'] == $info->level_id) selected @endif @endisset
                                    >{{ $v['level_name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput"></label>
                    </div>
                    <div class="col-sm-10">
                        <table class="table table-striped table-hover" style="padding-bottom: 20px;" id="mytable">
                            <thead class="border-bottom">
                            <tr class="long-tr" id="define">
                                <th>{{trans('adminUser.assort')}}</th>
                                <th>{{trans('equipment.retail_price')}}</th>
                                <th>{{trans('adminUser.u_cost')}}</th>
                                <th>{{trans('adminUser.a_cost')}}</th>
                                <th>{{trans('adminUser.u_profit')}}</th>
                            </tr>
                            </thead>
                            <tbody id="list-content">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.remark')}}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="input-group form-group">
                            <textarea class="form-control" id="standardRemark" rows="3"
                                      name="remark" maxlength="128"> </textarea>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput"></label>
                    </div>
                    <div class="col-sm-10">
                        <div class="input-group form-group">
                            <span>{{trans('adminUser.use_huobi')}} <span
                                        style="color: yellow;">{{ number_format(\Auth::guard('admin')->user()->balance, 2)  }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-2 text-sm-right">
                        <label class="col-form-label" for="standardInput">{{trans('adminUser.recharge')}}</label>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group form-group">
                            <input class="form-control" type="text" name="balance" aria-label="Balance"
                                   aria-describedby="icon-addon1" value="" id="balance" onkeyup="onlyNumber(this, 2)"
                                   onblur="onlyNumber(this, 2)" onmouseover="onlyNumber(this, 2)" maxlength="8">
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <div class="input-group form-group">
                            {{--<input class="form-control" type="text" id="need" value="" readonly--}}
                            {{--style="border-style:none">--}}
                            <span class="form-control" style="border-style:none" id="need"></span>
                        </div>
                    </div>
                </div>
                <div class="row row-10 align-items-center">
                    @if(isset($id))
                        <div class="col-sm-12 text-sm-center">
                            <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                    id="submitBtn">{{trans('adminUser.editAdministrator')}}</button>
                            <div style="display:inline;float:right;">
                                <button type="button" class="btn btn-warning"
                                        onclick="history.go(-1);">{{trans('general.return')}}</button>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-12 text-sm-center">
                            <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                    id="submitBtn">{{trans('adminUser.newAdministrator')}}</button>
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
        /**
         *  充值金额验证
         * @param obj
         */
        function onlyNumber(obj, type) {
            //得到第一个字符是否为负号    
            var t = obj.value.charAt(0);
            //先把非数字的都替换掉，除了数字和.和-号
            obj.value = obj.value.replace(/[^\d\.]/g, '');
            //前两位不能是0加数字
            obj.value = obj.value.replace(/^0\d[0-9]*/g, '');
            //必须保证第一个为数字而不是.
            obj.value = obj.value.replace(/^\./g, '');
            //保证只有出现一个.而没有多个.
            obj.value = obj.value.replace(/\.{2,}/g, '.');
            //保证.只出现一次，而不能出现两次以上
            obj.value = obj.value.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.');
            //如果第一位是负号，则允许添加
            obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
            if (type == 2) {
                var a = $('#balance').val();
                var b = TripartiteMethod(a);
                $('#balance').val(b);
            }
            if (t == '-') {
                return;
            }
        }

        /**
         * 给金额添加千分位分隔符
         * @param num
         * @returns {string}
         * @constructor
        */
        function TripartiteMethod(num) {
            var type = true;
            var value = '';
            num = num.replace(/,/g, "");
            if (num.indexOf(".") < 0) {
                var t1 = num.toString().split('');
            } else {
                type = false;
                var arr = num.toString().split('.');
                var t1 = arr[0].toString().split('');
                var t2 = arr[1].toString();
            }

            var result = [],
                counter = 0;
            for (var i = t1.length - 1; i >= 0; i--) {
                counter++;
                result.unshift(t1[i]);
                if ((counter % 3) == 0 && i != 0) {
                    result.unshift(',');
                }
            }

            if (type === true) {
                value = result.join('');
            } else {
                value = result.join('') + '.' + t2;
            }
            return value;
        }

        var agencyList = new Array();
        var ownList = new Array();
        var choiceList = new Array();
        var assortList = new Array();
        var priceList = new Array();

        // 国代初始化
        var daysThirtyList = new Array();
        var daysNinetyList = new Array();
        var daysEightyList = new Array();
        var yearsList = new Array();

        $(document).on('blur', "#defind", function () {
            var agency = $(this).val();   // 代理成本
            var own = $(this).parent().prev().html();  // 自己成本
            var price = $(this).parent().prev().prev().html();  // 零售价
            var assort = $(this).parent().prev().prev().prev().html();  // 授权码类型
            var choice = $(this).parent().next().next().html();   // 成本限制金额
            // 如果代理成本小于您的成本或者代理成本大于零售价，则提示
//            if (accSub(price, own) < 2 || accSub(price, agency) < 2) {
            if (accSub(price, own) < 2) {
                $(this).val(price - 1);
                var profit = accSub(price - 1, own);
                $(this).parent().next().html(profit);
            } else {
                if (Number(agency) < Number(own)) {
                    // 代理成本小于自己的价格
                    layer.msg("{{trans('adminUser.agency_tips')}}", {shift: 5});
                    $(this).val(0);
                    return false;
                } else if (Number(agency) < Number(choice)) {
                    // 代理成本小于成本限制价
                    layer.msg("{{trans('adminUser.agency_limit')}}", {shift: 5});
                    $(this).val(0);
                    return false;
                } else if (Number(agency) >= Number(price)) {
                    // 代理成本大于或等于零售价
                    layer.msg("{{trans('equipment.gltPrice')}}", {shift: 5});
                    $(this).val(0);
                    return false;
                } else if (Number(agency) - Number(own) < 1) {
                    // 代理成本和自己的成本不能小于1
                    layer.msg("{{trans('equipment.gltZero')}}", {shift: 5});
                    $(this).val(0);
                    return false;
                }
                // 填充您的利润
                var profit1 = accSub(agency, own);
                $(this).parent().next().html(profit1);
            }
        });

        var token = $("input[name='_token']").val();
        var self_id = '<?php echo \Auth::guard('admin')->user()->level_id?>';
        $('#standardSelect').change(function () {
            var level_id = $(this).val();
            var iteValue = $("#standardSelect").find("option:selected").attr("emoney");
            var money = $("#standardSelect").find("option:selected").attr("money");
            if (typeof(iteValue) == "undefined"){
                var str = "";
            } else {
                var str = "{{trans('adminUser.tips1')}}" + iteValue + "{{trans('adminUser.tips2')}}" + money + "{{trans('adminUser.tips3')}}";
            }
            $("#need").html(str);
            if (level_id == 8) {
                if (self_id == 8) {
                    $("#define").append('<th style="display:none;">{{trans('adminUser.a_cost_limit')}}</th>');
                } else {
                    $("#define").append('<th>{{trans('adminUser.a_cost_limit')}}</th>');
                }
            } else if(level_id == 3) {
                $("#define").children('th').eq(2).text('{{trans('equipment.ours')}}');
                $("#define").children('th').eq(3).text('{{trans('equipment.diamond')}}');
                $("#define").children('th').eq(4).text('{{trans('equipment.medal')}}');
                $("#define").append('<th>{{trans('equipment.silver')}}</th>');
                $("#define").append('<th>{{trans('equipment.copper')}}</th>');
                $("#define").append('<th>{{trans('equipment.defined')}}</th>');
            } else {
                $("#define th:nth-child(6)").remove();
            }

            var url = '<?php echo route('admin::adminUser.info') ?>';
//            if (level_id != 3 && level_id > 0) {
            if (level_id > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {level_id: level_id},
                    headers: {'X-CSRF-Token': token},
                    success: function (result) {
                        if (result.code !== 0) {
                            $("#list-content").html(result);//列表内容
                            return false;
                        }
                        layer.msg(result.msg, {shift: 1}, function () {
                            if (result.reload) {
                                location.reload();
                            }
                            if (result.redirect) {
                                location.href = '{!! url()->previous() !!}';
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
                            layer.msg('资源不存在', {icon: 5, skin: 'alert-secondary alert-lighter'});
                            return false;
                        } else if (resp.status === 401) {
                            layer.msg('请先登录', {shift: 6, skin: 'alert-secondary alert-lighter'});
                            return false;
                        } else if (resp.status === 429) {
                            layer.msg('访问过于频繁，请稍后再试', {shift: 6, skin: 'alert-secondary alert-lighter'});
                            return false;
                        } else if (resp.status === 419) {
                            layer.msg('非法请求。请刷新页面后重试。', {shift: 6, skin: 'alert-secondary alert-lighter'});
                            return false;
                        } else if (resp.status === 500) {
                            layer.msg('内部错误，请联系管理员', {shift: 6, skin: 'alert-secondary alert-lighter'});
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

        //监听提交
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            ownList = [];
            choiceList = [];
            assortList = [];
            agencyList = [];
            var channel_id = 0;
            var level_id = $('#standardSelect').val();
            var name = $("#agency_name").val();
            var remark = $("#standardRemark").val();
            var balance = $("#balance").val();
            if (level_id == 8) {
                // 代理成本
                var agency_one = $(".agency").eq(0).val();
                var agency_two = $(".agency").eq(1).val();
                var agency_three = $(".agency").eq(2).val();
                var agency_four = $(".agency").eq(3).val();
                agencyList.push(Number(agency_one));
                agencyList.push(Number(agency_two));
                agencyList.push(Number(agency_three));
                agencyList.push(Number(agency_four));
                // 自己成本
                var own_one = $(".editable").prev().eq(0).text();
                var own_two = $(".editable").prev().eq(1).text();
                var own_three = $(".editable").prev().eq(2).text();
                var own_four = $(".editable").prev().eq(3).text();
                ownList.push(Number(own_one));
                ownList.push(Number(own_two));
                ownList.push(Number(own_three));
                ownList.push(Number(own_four));
                // 配套类型
                var assort_one = $(".editable").prev().prev().prev().eq(0).text();
                var assort_two = $(".editable").prev().prev().prev().eq(1).text();
                var assort_three = $(".editable").prev().prev().prev().eq(2).text();
                var assort_four = $(".editable").prev().prev().prev().eq(3).text();
                assortList.push(assort_one);
                assortList.push(assort_two);
                assortList.push(assort_three);
                assortList.push(assort_four);
                // 代理人成本限制
                var choice_one = $(".editable").next().next().eq(0).text();
                var choice_two = $(".editable").next().next().eq(1).text();
                var choice_three = $(".editable").next().next().eq(2).text();
                var choice_four = $(".editable").next().next().eq(3).text();
                choiceList.push(Number(choice_one));
                choiceList.push(Number(choice_two));
                choiceList.push(Number(choice_three));
                choiceList.push(Number(choice_four));
            }
            if (level_id == 3) {
                daysThirtyList = [];
                daysNinetyList = [];
                daysEightyList = [];
                yearsList = [];
                channel_id = $('#channel_id').val();
                // 30天授权码
                var thirty_one = $(".own").find("input").val();
                var thirty_two = $(".diamond").find("input").val();
                var thirty_three = $(".medal").find("input").val();
                var thirty_four = $(".silver").find("input").val();
                var thirty_five = $(".copper").find("input").val();
                var thirty_six = $(".defined").find("input").val();
                daysThirtyList.push(Number(thirty_one));
                daysThirtyList.push(Number(thirty_two));
                daysThirtyList.push(Number(thirty_three));
                daysThirtyList.push(Number(thirty_four));
                daysThirtyList.push(Number(thirty_five));
                daysThirtyList.push(Number(thirty_six));
                console.log(daysThirtyList);
                // 90天授权码
                var ninety_one = $(".own").find("input").val();
                var ninety_two = $(".diamond").find("input").val();
                var ninety_three = $(".medal").find("input").val();
                var ninety_four = $(".silver").find("input").val();
                var ninety_five = $(".copper").find("input").val();
                var ninety_six = $(".defined").find("input").val();
                daysNinetyList.push(Number(ninety_one));
                daysNinetyList.push(Number(ninety_two));
                daysNinetyList.push(Number(ninety_three));
                daysNinetyList.push(Number(ninety_four));
                daysNinetyList.push(Number(ninety_five));
                daysNinetyList.push(Number(ninety_six));
                console.log(daysNinetyList);
            }

            var method = $("#form").attr("method");
            var action = $('#form').attr("action");
            $.ajax({
                type: method,
                url: action,
//                data: $('#form').serializeArray(),
                data: {
                    channel_id: channel_id,
                    level_id: level_id,
                    name: name,
                    remark: remark,
                    balance: balance,
                    agency: agencyList,
                    own: ownList,
                    price: priceList,
                    assort: assortList,
                    choice: choiceList
                },
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    if (result.code !== 0) {
                        form_submit.prop('disabled', false);
                        layer.msg(result.msg, {shift: 6});
                        return false;
                    }

                    if (result.redirect) {
                        location.href = '{{ route('admin::adminUser.detail') }}' + '?id=' + result.id;
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
                        layer.msg('资源不存在', {icon: 5, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 401) {
                        layer.msg('请先登录', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 429) {
                        layer.msg('访问过于频繁，请稍后再试', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 419) {
                        layer.msg('非法请求。请刷新页面后重试。', {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 500) {
                        layer.msg('内部错误，请联系管理员', {shift: 6, skin: 'alert-secondary alert-lighter'});
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

        /**
         ** 减法函数，用来得到精确的减法结果
         ** 说明：javascript的减法结果会有误差，在两个浮点数相减的时候会比较明显。这个函数返回较为精确的减法结果。
         ** 调用：accSub(arg1,arg2)
         ** 返回值：arg1加上arg2的精确结果
         **/
        function accSub(arg1, arg2) {
            var r1, r2, m, n;
            try {
                r1 = arg1.toString().split(".")[1].length;
            } catch (e) {
                r1 = 0;
            }
            try {
                r2 = arg2.toString().split(".")[1].length;
            } catch (e) {
                r2 = 0;
            }
            m = Math.pow(10, Math.max(r1, r2)); //last modify by deeka //动态控制精度长度
            n = (r1 >= r2) ? r1 : r2;
            return ((arg1 * m - arg2 * m) / m).toFixed(n);
        }
    </script>
    @endsection
    </div>
    </body>
    </html>