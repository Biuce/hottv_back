@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('adminUser.cost')}}
            </h4>
        </div>
        <section class="section-sm bg-800">
            <div class="container-fluid">
                <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                    <div class="media-item">
                        <img class="rounded"
                             @if(!empty($info['photo']))
                             src="{{ $info['photo'] }}"
                             @else
                             src="/public/images/users/user-09-247x247.png"
                             @endif
                             width="165"
                             alt="" style="height: 165px;">
                    </div>
                    <div class="media-body">
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.name')}}: {{ $info['name'] }}
                            </li>
                            <li class="list-inline-item">
                                {{trans('adminUser.email')}}: {{ $info['email'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.phone')}}: {{ $info['phone'] }}
                            </li>
                            <li class="list-inline-item">
                                {{trans('adminUser.level')}}: {{ $info->levels->level_name }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.account')}}: {{ $info['account'] }}
                            </li>
                            @if($info['is_new'] == 0)
                                <li class="list-inline-item">
                                    {{trans('adminUser.password')}}: {{ $info['password'] }}
                                </li>
                            @endif
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.remark')}}: {{ $info['remark'] }}
                            </li>
                        </ul>
                        <HR align=center width="100%" color=#987cb9 SIZE=1 style="margin-top: 30px;">
                    </div>
                </div>
            </div>
        </section>

        <div class="panel-body">
            <form method="post" action="{{ route('admin::adminUser.adjust') }}" id="form" onsubmit="return false;">
                {{ csrf_field() }}
                <input class="form-control" type="hidden" name="id" value="{{ $id ?? ''  }}" id="agency_id">
                <div class="row form-group">
                    <div class="col-sm-12">
                        <table class="table table-striped table-hover" style="padding-bottom: 20px;" id="mytable">
                            <thead class="border-bottom">
                            <tr class="long-tr" id="define">
                                <th>{{trans('adminUser.assort')}}</th>
                                <th>{{trans('equipment.retail_price')}}</th>
                                <th>{{trans('adminUser.u_cost')}}</th>
                                <th>{{trans('adminUser.a_cost')}}</th>
                                <th>{{trans('adminUser.u_profit')}}</th>
                                {{--<th>{{trans('adminUser.a_cost_limit')}}</th>--}}
                            </tr>
                            </thead>
                            <tbody id="list-content">
                            @isset($lists)
                                @foreach($lists as $k => $v)
                                    <tr>
                                        <td>{{ $v['assort'][$k] }}</td>
                                        <td class="cost">{{ $v['cost'][$k] }}</td>
                                        <td class="own">{{ $v['own'][$k] }}</td>
                                        <td class="editable">
                                            <input class="form-control agency" type="text" name="agency" value="{{ $v['agency'][$k] }}" maxlength="8"
                                                   style="width: 100px;" id="defind" data-content-id="{{ $k }}" onkeyup="onlyNumber(this)">
                                        </td>
                                        <td class="profit">{{ $v['diff'][$k] }}</td>
                                        {{--<td class="choice">{{ $v['choice'][$k] }}</td>--}}
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row row-10 align-items-center" style="margin-top: 100px;">
                    <div class="col-sm-12 text-sm-center">
                        <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                id="submitBtn">{{trans('adminUser.cost')}}</button>
                        <div style="display:inline;float:right;">
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
        /**
         *    ?????????????????????
         * @param obj
         */
        function onlyNumber(obj, type) {
            //???????????????????????????????????? ?? ??
            var t = obj.value.charAt(0);
            //????????????????????????????????????????????????.???-???
            obj.value = obj.value.replace(/[^\d\.]/g, '');
            //??????????????????0?????????
            obj.value = obj.value.replace(/^0\d[0-9]*/g, '');
            //???????????????????????????????????????.
            obj.value = obj.value.replace(/^\./g, '');
            //????????????????????????.???????????????.
            obj.value = obj.value.replace(/\.{2,}/g, '.');
            //??????.?????????????????????????????????????????????
            obj.value = obj.value.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.');
            //??????????????????????????????????????????
            obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');
            if (t == '-') {
                return;
            }
        }

        var agencyList = new Array();
        var ownList = new Array();
        var choiceList = new Array();
        var assortList = new Array();
        var priceList = new Array();
        $(document).on('blur', "#defind", function () {
            var agency = $(this).val();   // ????????????
            var id = $(this).attr('data-content-id');
            var own = $(this).parent().prev().html();  // ????????????
            var price = $(this).parent().prev().prev().html();  // ?????????
            var assort = $(this).parent().prev().prev().prev().html();  // ???????????????
            var choice = $(this).parent().next().next().html();   // ??????????????????
            // ?????????????????????????????????????????????????????????????????????????????????
//            if (accSub(price, agency) < 2 || accSub(price, own) < 2) {
            if (id > 2) {
                if (accSub(price, own) < 2) {
                    $(this).val(price - 1);
                    var profit = accSub(price - 1, own);
                    $(this).parent().next().html(profit);
                } else {
                    if (Number(agency) < Number(own)) {
                        // ?????????????????????????????????
                        layer.msg("{{trans('adminUser.agency_tips')}}", {shift: 5});
                        $(this).val(0);
                        return false;
                    } else if (Number(agency) < Number(choice)) {
                        // ?????????????????????????????????
                        layer.msg("{{trans('adminUser.agency_limit')}}", {shift: 5});
                        $(this).val(0);
                        return false;
                    } else if (Number(agency) >= Number(price)) {
                        // ????????????????????????????????????
                        layer.msg("{{trans('equipment.gltPrice')}}", {shift: 5});
                        $(this).val(0);
                        return false;
                    } else if (Number(agency) - Number(own) < 1) {
                        // ??????????????????????????????????????????1
                        layer.msg("{{trans('equipment.gltZero')}}", {shift: 5});
                        $(this).val(0);
                        return false;
                    }
                    // ??????????????????
                    var profit1 = accSub(agency, own);
                    $(this).parent().next().html(profit1);
                }
            } else {  // ????????????1??????????????????????????????
                if (Number(agency) > Number(price)) {
                    // ?????????????????????????????????
                    layer.msg("{{trans('equipment.gtPrice')}}", {shift: 5});
                    $(this).val(0);
                    return false;
                } else if (Number(agency) < Number(own)) {
                    // ???????????????????????????????????????
                    layer.msg("{{trans('equipment.ltZero')}}", {shift: 5});
                    $(this).val(0);
                    return false;
                } else if (Number(agency) < Number(choice)) {
                    // ?????????????????????????????????
                    layer.msg("{{trans('adminUser.agency_limit')}}", {shift: 5});
                    $(this).val(0);
                    return false;
                }
            }
        });

        var token = $("input[name='_token']").val();
        //????????????
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            ownList = [];
            choiceList = [];
            assortList = [];
            agencyList = [];
            var agency_id = $("#agency_id").val();
            // ????????????
            var agency_one = $(".agency").eq(0).val();
            var agency_two = $(".agency").eq(1).val();
            var agency_three = $(".agency").eq(2).val();
            var agency_four = $(".agency").eq(3).val();
            var agency_five = $(".agency").eq(4).val();
            var agency_six = $(".agency").eq(5).val();
            agencyList.push(Number(agency_one));
            agencyList.push(Number(agency_two));
            agencyList.push(Number(agency_three));
            agencyList.push(Number(agency_four));
            agencyList.push(Number(agency_five));
            agencyList.push(Number(agency_six));
            // ????????????
            var own_one = $(".editable").prev().eq(0).text();
            var own_two = $(".editable").prev().eq(1).text();
            var own_three = $(".editable").prev().eq(2).text();
            var own_four = $(".editable").prev().eq(3).text();
            var own_five = $(".editable").prev().eq(4).text();
            var own_six = $(".editable").prev().eq(5).text();
            ownList.push(Number(own_one));
            ownList.push(Number(own_two));
            ownList.push(Number(own_three));
            ownList.push(Number(own_four));
            ownList.push(Number(own_five));
            ownList.push(Number(own_six));
            // ????????????
            var assort_one = $(".editable").prev().prev().prev().eq(0).text();
            var assort_two = $(".editable").prev().prev().prev().eq(1).text();
            var assort_three = $(".editable").prev().prev().prev().eq(2).text();
            var assort_four = $(".editable").prev().prev().prev().eq(3).text();
            var assort_five = $(".editable").prev().prev().prev().eq(4).text();
            var assort_six = $(".editable").prev().prev().prev().eq(5).text();
            assortList.push(assort_one);
            assortList.push(assort_two);
            assortList.push(assort_three);
            assortList.push(assort_four);
            assortList.push(assort_five);
            assortList.push(assort_six);
            // ?????????????????????
//            var choice_one = $(".editable").next().next().eq(0).text();
//            var choice_two = $(".editable").next().next().eq(1).text();
//            var choice_three = $(".editable").next().next().eq(2).text();
//            var choice_four = $(".editable").next().next().eq(3).text();
//            var choice_five = $(".editable").next().next().eq(4).text();
//            var choice_six = $(".editable").next().next().eq(5).text();
//            choiceList.push(Number(choice_one));
//            choiceList.push(Number(choice_two));
//            choiceList.push(Number(choice_three));
//            choiceList.push(Number(choice_four));
//            choiceList.push(Number(choice_five));
//            choiceList.push(Number(choice_six));

            var method = $("#form").attr("method");
            var action = $('#form').attr("action");
            $.ajax({
                type: method,
                url: action,
                data: {
                    id:agency_id,
                    agency: agencyList,
                    own: ownList,
                    price: priceList,
                    assort: assortList,
//                    choice: choiceList
                },
                headers: {'X-CSRF-Token': token},
                success: function (result) {
                    if (result.code !== 0) {
                        form_submit.prop('disabled', false);
                        layer.msg(result.msg, {shift: 5});
                        return false;
                    }
                    if (result.redirect) {
                        location.href = '{{ route('admin::adminUser.index') }}';
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

        /**
         ** ????????????????????????????????????????????????
         ** ?????????javascript?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
         ** ?????????accSub(arg1,arg2)
         ** ????????????arg1??????arg2???????????????
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
            m = Math.pow(10, Math.max(r1, r2)); //last modify by deeka //????????????????????????
            n = (r1 >= r2) ? r1 : r2;
            return ((arg1 * m - arg2 * m) / m).toFixed(n);
        }
    </script>
    @endsection
    </div>
    </body>
    </html>