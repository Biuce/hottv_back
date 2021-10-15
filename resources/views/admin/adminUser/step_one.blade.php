@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<section>
    <div class="tabs tabs-vertical-top tabs-bg">
        <ul class="nav nav-tabs tabs-default scroller scroller-horizontal" role="tablist">
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.visual', ['id' => $id]) }}')"><a
                        class="nav-link" data-toggle="tab" href="#panelTab1" role="tab"
                        aria-controls="panelTab1" aria-selected="true">{{trans('general.index')}}</a></li>
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.stepOne', ['id' => $id]) }}')"><a
                        class="nav-link active" data-toggle="tab" href="#panelTab2" role="tab"
                        aria-controls="panelTab2" aria-selected="false">{{trans('adminUser.managers')}}</a></li>
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.stepTwo', ['id' => $id]) }}')"><a
                        class="nav-link" data-toggle="tab" href="#panelTab3" role="tab"
                        aria-controls="panelTab3" aria-selected="false">{{trans('huobi.managers')}}</a></li>
        </ul>
    </div>
</section>

<div class="col-sm-12" style="margin-top: 50px;">
    <div class="panel">
        <div class="panel-header">
            <div style="margin-top: 20px;">
                <form name="admin_list_sea" class="form-search" method="get"
                      action="{{ route('admin::adminUser.stepOne', ['id' => $id]) }}">
                    {{ csrf_field() }}
                    <div class="row row-15">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input class="form-control" id="text" type="text"
                                       placeholder="{{trans('adminUser.name')}}" name="name"
                                       value="{{ $condition['name'] ?? '' }}" autocomplete="off">
                            </div>
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
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('adminUser.id')}}</th>
                        <th>{{trans('adminUser.agency_name')}}</th>
                        <th>{{trans('adminUser.agency_level')}}</th>
                        <th>{{trans('adminUser.balance')}}</th>
                        <th>{{trans('huobi.add_lower_profit')}}</th>
                        <th>{{trans('adminUser.remark')}}</th>
                        {{--<th>{{trans('adminUser.is_cancel')}}</th>--}}
                        <th>{{trans('general.create')}}</th>
                        <th>{{trans('general.action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                @if($v['is_cancel'] != 0)
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;color: #505050"
                                        title="{{ $v['name'] ?? ""}}">{{ $v['name'] ?? "" }} {{trans('general.is_del')}}</td>
                                @else
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"
                                        title="{{ $v['name'] ?? "" }}">{{ $v['name'] ?? "" }}</td>
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
                                <td>
                                    <?php
                                    if ($id == 1) {
                                        $where = ["user_id" => $v['id'], 'status' => 0];
                                    } else {
                                        $where = ["user_id" => $id, 'status' => 0, 'type' => 1, 'create_id' => $v['id']];
                                    }
                                    $user_lirun = App\Model\Admin\Huobi::query()->where($where)->get();
                                    $user_pro = 0;
                                    foreach ($user_lirun as $value) {
                                        $assort_where = ['user_id' => $parent_id, 'assort_id' => $value['assort_id'], 'level_id' => 3];
                                        $assort_level = App\Repository\Admin\EquipmentRepository::findByWhere($assort_where);
                                        $total = isset($assort_level->money) ? bcmul($assort_level->money, $value['number'], 2) : 0;
                                        $user_pro += $total;
                                    }
                                    $profit = App\Repository\Admin\HuobiRepository::levelByRecord($where);
                                    ?>
                                    @if(\Auth::guard('admin')->user()->id == 1)
                                        {{ number_format($user_pro, 2) }}
                                    @else
                                        {{ number_format($profit, 2) }}
                                    @endif
                                </td>
                                <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"
                                    title="{{ $v['remark'] }}">
                                    @if(mb_strlen($v['remark']) > 10)
                                        <?php $str = mb_substr($v['remark'], 0, 10); ?>
                                        {{ $str }}...
                                    @else
                                        {{ $v['remark'] }}
                                    @endif
                                </td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{trans('general.action')}}</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            {{--<a class="dropdown-item"--}}
                                            {{--href="{{ route('admin::adminUser.look', ['id' => $v['id']]) }}">{{trans('adminUser.check_cost')}}</a>--}}
                                            @if($v['is_cancel'] != 2)
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.lower', ['id' => $v['id']]) }}">{{trans('adminUser.lower_agent')}}</a>
                                            @endif
                                            <a class="dropdown-item"
                                               href="{{ route('admin::adminUser.examine', ['id' => $v['id']]) }}">{{trans('adminUser.check')}}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                {!! $lists->appends(['name'=>$lists->name])->render() !!}
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
    </script>
    @endsection
    </div>
    </body>
    </html>