@include('admin.header')

{{--@section('content')--}}
{{--@include('admin.breadcrumb')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('adminUser.lower_agent')}}</span>
                </h4>
            </div>

            <div style="margin-top: 20px;">
                <form name="admin_list_sea" class="form-search" method="get"
                      action="{{ route('admin::adminUser.lower', ['id' => $id]) }}">
                    {{ csrf_field() }}
                    <div class="row row-15">
                        <div class="col-sm-4">
                            <input class="form-control" type="text" name="date2" id="date2"
                                   value="{{ $condition['date2'] ?? ''  }}" autocomplete="off"
                                   placeholder="{{trans('general.range')}}">
                            <input type="hidden" id="startTime" name="startTime" class="form-control"/>
                            <input type="hidden" id="endTime" name="endTime" class="form-control"/>
                        </div>

                        <div class="col-sm-2">
                            <button class="btn btn-success" type="submit" lay-submit lay-filter="formAdminUser"
                                    id="submitBtn">{{trans('general.search')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div class="row form-group">
                <div class="text-sm-right" style="margin-left: 20px;">
                    <label class="col-form-label" for="standardInput">{{trans('adminUser.all_num')}} :
                        {{ $total_person }}</label>
                </div>
                @isset($group)
                    @foreach($group as $item => $value)
                        @if ($item == 4)
                            <div class="text-sm-right" style="margin-left: 80px;">
                                <label class="col-form-label" for="standardInput">{{trans('adminUser.diamond_num')}}
                                    :
                                    {{ $value }}</label>
                            </div>
                        @elseif($item == 5)
                            <div class="text-sm-right" style="margin-left: 80px;">
                                <label class="col-form-label" for="standardInput">{{trans('adminUser.gold_num')}} :
                                    {{ $value }}</label>
                            </div>
                        @elseif($item == 6)
                            <div class="text-sm-right" style="margin-left: 80px;">
                                <label class="col-form-label" for="standardInput">{{trans('adminUser.silver_num')}}
                                    :
                                    {{ $value }}</label>
                            </div>
                        @elseif($item == 7)
                            <div class="text-sm-right" style="margin-left: 80px;">
                                <label class="col-form-label" for="standardInput">{{trans('adminUser.copper_num')}}
                                    :
                                    {{ $value }}</label>
                            </div>
                        @elseif($item == 8)
                            <div class="text-sm-right" style="margin-left: 80px;">
                                <label class="col-form-label" for="standardInput">{{trans('adminUser.defined_num')}}
                                    :
                                    {{ $value }}</label>
                            </div>
                        @endif
                    @endforeach
                @endisset
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('adminUser.id')}}</th>
                        <th>{{trans('adminUser.agency_account')}}</th>
                        <th>{{trans('adminUser.agency_level')}}</th>
                        <th>{{trans('adminUser.balance')}}</th>
                        <th>{{trans('adminUser.get_profit')}}</th>
                        <th>{{trans('adminUser.recharge')}}</th>
                        <th>{{trans('general.create')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                @if($v['is_cancel'] != 0)
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;color: #505050"
                                        title="{{ $v['account'] ?? ""}}">{{ $v['account'] ?? "" }} {{trans('general.is_del')}}</td>
                                @else
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"
                                        title="{{ $v['account'] ?? "" }}">{{ $v['account'] ?? "" }}</td>
                                @endif
                                <td>
                                    {{ isset($v->levels->level_name) ? $v->levels->level_name : "" }}
                                    @if(\Auth::guard('admin')->user()->level_id <= 3)
                                        @if($v->type == 2)
                                            <i>Pro</i>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ number_format($v['balance'], 2) }}</td>
                                <td>{{ $v['total_balance'] }}</td>
                                <td>{{ $v['recharge'] }}</td>
                                <td>{{ $v['created_at'] }}</td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                {{ $lists->links() }}
            </div>
        </div>
    </div>
</div>

{{--@endsection--}}

@extends('admin.js')
@section('js')
    <script>
        laydate.render({
            elem: '#date2',
            range: true,
            trigger: 'click'
        });
    </script>
    @endsection
    </div>
    </body>
    </html>