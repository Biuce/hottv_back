@extends('admin.js')

{{--@section('content')--}}
    {{--@include('admin.breadcrumb')--}}

    <div class="col-sm-12">
        <div class="panel">
            <div class="panel-header">
                <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                    <h4 class="panel-title">
                        <span class="panel-icon fa-tasks"></span>
                        <span>{{trans('adminUser.managers')}}</span>
                    </h4>
                    <div class="form-group">
                        <a href="{{ route('admin::adminUser.create') }}"><button class="btn btn-primary" type="button" id="submitBtn">{{trans('adminUser.newAdministrator')}}</button></a>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <form name="admin_list_sea" class="form-search" method="get" action="{{ route('admin::category.index') }}" onsubmit="return false">
                        {{--{{ csrf_field() }}--}}
                        <div class="row row-15">
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input class="form-control" id="text" type="text" placeholder="{{trans('adminUser.name')}}" name="name">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" name="status">
                                    <option value="">{{ trans('general.select') }}</option>
                                    <option value="0">{{trans('general.forbidden')}}</option>
                                    <option value="1">{{trans('general.enable')}}</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <button class="btn btn-success" type="submit" lay-submit lay-filter="formAdminUser" id="submitBtn" onclick="Ajaxpage()">{{trans('general.search')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover data-table" data-page-length="20">
                        <thead class="border-bottom">
                        <tr class="long-tr">
                            <th>{{trans('adminUser.id')}}</th>
                            <th>{{trans('adminUser.name')}}</th>
                            <th>{{trans('general.status')}}</th>
                            <th>{{trans('general.create')}}</th>
                            <th>{{trans('general.update')}}</th>
                            <th>{{trans('general.action')}}</th>
                        </tr>
                        </thead>
                        <tbody id="list-content">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ???????????? -->
    <div class="spiner-example">
        <div class="sk-spinner sk-spinner-three-bounce">
            <div class="sk-bounce1"></div>
            <div class="sk-bounce2"></div>
            <div class="sk-bounce3"></div>
        </div>
    </div>
{{--@endsection--}}

@section('js')
    <script>
        //laypage??????
        Ajaxpage();

        function Ajaxpage(curr){
            var html = '';
            var page = curr || 1;
            var data = $('form[name="admin_list_sea"]').serialize()+"&page="+page;//????????????key ??????
            var url = $("form").attr("action");
            $.ajax({
                url:url,????????//?????????url??????
                dataType:"html",??????//???????????????json
                data:data,????????//?????????
                type:"GET",??????//????????????
                success:function(result){
                    //?????????????????????
                    $(".spiner-example").css('display','none'); //???????????????????????????
                    console.log(result);
                    if(result == '') {
                        $("#list-content").html('<td colspan="20" style="padding-top:10px;padding-bottom:10px;font-size:16px;text-align:center">????????????</td>');
                        $('#allpage').html('');
                        $('#AjaxPage').html('');
                        $('#listcount').text(0);//????????????
                    } else {
                        $("#list-content").html(result);//????????????
                        //laypage???????????????
                        laypage({
                            cont: $('#AjaxPage'),//??????????????????id????????????dom?????????jquery??????,
                            pages: $('#allpagecount').val(),//?????????
                            skip: true,//??????????????????
                            skin: '#1AB5B7',//??????????????????
                            curr: curr || 1,
                            groups: 3,//?????????????????????
                            jump: function(obj, first){
                                //obj???????????????????????????????????????first??????????????????????????????????????????????????????
                                //???????????????
                                if(!first){
                                    Ajaxpage(obj.curr) //obj.curr??????????????????
                                }
                                $('#allpage').html('???'+ obj.curr +'?????????'+ obj.pages +'???');
                                $('#listcount').text($('#count').val());//????????????
                            }
                        });
                    }
                },
                error:function(){
                    //??????????????????
                }
            });
        }

        var sure = "{{trans('general.deleteSure')}}";
        function deleteUser (url) {
            layer.confirm(sure, function(index){
                $.ajax({
                    url: url,
                    type: "DELETE",??????//????????????
                    headers: {'X-CSRF-Token': csrf_token},
                    success: function (result) {
                        if (result.code !== 0) {
                            layer.msg(result.msg, {shift: 6});
                            return false;
                        }
                        layer.msg(result.msg, {icon: 1}, function () {
                            if (result.reload) {
                                location.reload();
                            }
                            if (result.redirect) {
                                location.href = '{!! url()->current() !!}';
                            }
                        });
                    }
                });

                layer.close(index);
            });
        }
    </script>
@endsection