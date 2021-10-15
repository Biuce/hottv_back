@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('general.set_user_info')}}
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
                             alt="" style="height: 165px">
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
                                {{trans('adminUser.level')}}: {{ isset($info->levels->level_name) ? $info->levels->level_name : trans('adminUser.admin') }}
                            </li>
                        </ul>
                        <ul class="list-inline">
                            <li class="list-inline-item col-sm-5">
                                {{trans('adminUser.account')}}: {{ $info['account'] }}
                            </li>
                            {{--<li class="list-inline-item">--}}
                                {{--{{trans('adminUser.password')}}: {{ $info['password'] }}--}}
                            {{--</li>--}}
                        </ul>
                        {{--<ul class="list-inline">--}}
                            {{--<li class="list-inline-item col-sm-5">--}}
                                {{--{{trans('adminUser.remark')}}: {{ $info['remark'] }}--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    </div>
                </div>
            </div>
            <div class="row row-10 align-items-center">
                <div class="col-sm-12 text-sm-center">
                    <a href="{{ route('admin::adminUser.userEdit') }}">
                        <button class="btn btn-primary" type="submit" lay-submit lay-filter="formAdminUser"
                                id="submitBtn">{{trans('general.update_user_info')}}</button>
                    </a>
                    <div style="display:inline;float:right;margin-right: 50px;">
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

    </script>
    @endsection
    </div>
    </body>
    </html>