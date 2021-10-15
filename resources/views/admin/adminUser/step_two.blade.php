@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<section>
    <div class="tabs tabs-vertical-top tabs-bg">
        <ul class="nav nav-tabs tabs-default scroller scroller-horizontal" role="tablist">
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.visual', ['id' => $id]) }}')"><a
                        class="nav-link" data-toggle="tab" href="#panelTab1" role="tab"
                        aria-controls="panelTab1" aria-selected="true" style="color: #a5a7a9">{{trans('general.index')}}</a></li>
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.stepOne', ['id' => $id]) }}')"><a
                        class="nav-link" data-toggle="tab" href="#panelTab2" role="tab"
                        aria-controls="panelTab2" aria-selected="false" style="color: #a5a7a9">{{trans('adminUser.managers')}}</a></li>
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.stepTwo', ['id' => $id]) }}')"><a
                        class="nav-link active" data-toggle="tab" href="#panelTab3" role="tab"
                        aria-controls="panelTab3" aria-selected="false">{{trans('huobi.managers')}}</a></li>
        </ul>
    </div>
</section>

<div class="container-fluid" style="margin-top: 50px;">
    <div class="row row-30">
        <div class="col-md-6 col-xxl-3">
            <div class="widget-counter widget-counter-simple-fill widget-counter-simple-primary-fill">
                <div class="row align-items-center">
                    <div class="col-12">
                        <p>{{trans('huobi.huobi_balance')}}</p>
                        <h1 class="mt-0 widget-counter-title">{{ number_format($userInfo->balance, 2) }}</h1>
                    </div>
                </div>
            </div>
        </div>
        {{--@if(\Auth::guard('admin')->user()->level_id != 8)--}}
        <div class="col-md-6 col-xxl-3">
            <div class="widget-counter widget-counter-simple-fill widget-counter-simple-info-fill">
                <div class="row align-items-center">
                    <div class="col-12">
                        <p>{{trans('huobi.this_month_recharge')}}</p>
                        <h1 class="mt-0 widget-counter-title">{{ number_format($lower_recharge, 2) }}</h1>
                    </div>
                </div>
            </div>
        </div>
        {{--@endif--}}
        <div class="col-md-6 col-xxl-3">
            <div class="widget-counter widget-counter-simple-fill widget-counter-simple-secondary-fill">
                <div class="row align-items-center">
                    <div class="col-12">
                        <p>{{trans('huobi.add_recharge')}}</p>
                        <h1 class="mt-0 widget-counter-title">{{ number_format($userInfo->recharge, 2) }}</h1>
                    </div>
                </div>
            </div>
        </div>
        {{--@if(\Auth::guard('admin')->user()->level_id != 8)--}}
        <div class="col-md-6 col-xxl-3">
            <div class="widget-counter widget-counter-simple-fill widget-counter-simple-success-fill">
                <div class="row align-items-center">
                    <div class="col-12">
                        <p>{{trans('huobi.add_lower_profit')}}</p>
                        <h1 class="mt-0 widget-counter-title">{{ number_format($add_profit, 2) }}</h1>
                    </div>
                </div>
            </div>
        </div>
        {{--@endif--}}
    </div>
</div>

<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div style="margin-top: 20px;">
                <form name="admin_list_sea" class="form-search" method="get" action="{{ route('admin::adminUser.stepTwo', ['id' => $id]) }}">
                    {{ csrf_field() }}
                    <div class="row row-15">
                        <div class="col-sm-4">
                            <select class="form-control" name="status">
                                <option value="0">{{ trans('general.select') }}</option>
                                <option value="1"
                                        @if(isset($condition['status']) && $condition['status'] == 1) selected @endif>{{trans('huobi.into_code')}}</option>
                                <option value="2"
                                        @if(isset($condition['status']) && $condition['status'] == 2) selected @endif>{{trans('huobi.for_subordinates')}}</option>
                                <option value="3"
                                        @if(isset($condition['status']) && $condition['status'] == 3) selected @endif>{{trans('huobi.generate_code')}}</option>
                                <option value="4"
                                        @if(isset($condition['status']) && $condition['status'] == 4) selected @endif>{{trans('huobi.lower_generate_code')}}</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <input class="form-control" type="text" name="date2" id="date2"
                                   value="{{ $condition['date2'] ?? ''  }}" autocomplete="off"
                                   placeholder="{{trans('general.range')}}">
                            <input type="hidden" id="startTime" name="startTime" class="form-control"/>
                            <input type="hidden" id="endTime" name="endTime" class="form-control"/>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-success" type="submit" lay-submit lay-filter="formorder"
                                    id="submitBtn">{{trans('general.search')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('huobi.id')}}</th>
                        <th>{{trans('general.create')}}</th>
                        <th>{{trans('huobi.event')}}</th>
                        <th>{{trans('huobi.money')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            @if($v['user_id'] != $id && $v['status'] == 1 && $v['type'] == 1)
                            @else
                                @if($v['money'] > 0)
                                    <tr>
                                        <td>{{ $v['id'] }}</td>
                                        <td>{{ $v['created_at'] }}</td>
                                        <td>
                                            <?php
                                                if (\Auth::guard('admin')->user()->id == 1) {
                                                    $details = App\Repository\Admin\AdminUserRepository::find($v['user_id']);
                                                } else {
                                                    $details = App\Repository\Admin\AdminUserRepository::find($v['own_id']);
                                                }
                                                $assort = App\Repository\Admin\AssortRepository::find($v['assort_id']);
                                            ?>
                                            @if($v['status'] == 1 && $v['type'] == 2)
                                                @if(isset($details->name))
                                                    {{ trans('adminUser.by') }} {{ $details->name }} {{ trans('adminUser.lower') }}
                                                @else
                                                    {{ trans('adminUser.lower') }}
                                                @endif
                                            @elseif($v['status'] == 1 && $v['type'] == 1)
                                                {{ trans('adminUser.myself') }}
                                            @elseif($v['status'] == 0 && $v['type'] == 1)
                                                @if(isset($details->name))
                                                    {{ $details->name }} {{ trans('general.as_lower') }} {{ $v['user_account'] }} {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                @else
                                                    {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                @endif
                                            @elseif($v['status'] == 0 && $v['type'] == 2)
                                                @if(isset($details->name))
                                                    {{ $details->name }} {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                @else
                                                    {{ trans('general.generate') }} {{ $assort->assort_name }}
                                                @endif
                                            @endif
                                        </td>
                                        <td
                                                @if($v['type'] == 2)
                                                class="text-danger"
                                                @else
                                                class="text-primary"
                                                @endif
                                        >
                                            @if($v['type'] == 2)
                                                -{{ number_format($v['money'], 2) }}
                                            @else
                                                +{{ number_format($v['money'], 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                {!! $lists->appends(['status'=>$lists->status, 'date2'=>$lists->date2])->render() !!}
            </div>
        </div>
    </div>
</div>

@extends('admin.js')
@section('js')
    <script>
        function choose(url) {
            window.location.href = url;
        }

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