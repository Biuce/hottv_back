@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('general.agency_info')}}
            </h4>
        </div>
        <section class="section-sm bg-800">
            <div class="container-fluid">
                <div class="media flex-column flex-sm-row align-items-sm-center group-30">
                    <div class="media-body" style="text-align:center">
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-12">
                                {{trans('adminUser.name')}}: {{ $info['name'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-12">
                                {{trans('adminUser.remark')}}: {{ $info['remark'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-12">
                                {{trans('adminUser.account')}}: {{ $info['account'] }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-12">
                                {{trans('adminUser.password')}}: {{ $info['password'] }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row row-10 align-items-center" style="margin-top: 50px;margin-bottom: -30px;">
                <div class="col-sm-12 text-sm-center">
                    <span class="btn" data-clipboard-text="" id="copy">
                    <button class="btn btn-primary" type="button"
                            style="margin-right: 50px">{{trans('adminUser.copy_info')}}</button>
                    </span>
                    <a href="{{ route('admin::adminUser.index') }}">
                        <button type="button" class="btn btn-primary">{{trans('general.affirm')}}</button>
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

@extends('admin.js')
@section('js')
    <script type="text/javascript" src="/public/admin/js/clipboard.min.js"></script>
    <script>
        var account= "{{trans('adminUser.account')}}" + ':' + '<?php echo $info['account'] ?>';
        var password= "{{trans('adminUser.password')}}" + ':' + '<?php echo $info['password'] ?>';
        var str = account + ' ' + password;
        $("#copy").click(function(){
            $(".btn").attr("data-clipboard-text", str);
        });
        // 复制功能
        var clipboard = new ClipboardJS('#copy');
        clipboard.on('success', function (e) {
            layer.msg("{{trans('authCode.copy_success')}}", {shift: 6});
//            console.info('Action:', e.action);
//            console.info('Text:', e.text);
//            console.info('Trigger:', e.trigger);
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
        });
    </script>
    @endsection
    </div>
    </body>
    </html>