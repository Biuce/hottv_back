@include('admin.header')

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
            </div>

            <div style="margin-top: 20px;">
                <form name="admin_list_sea" class="form-search" method="get"
                      action="{{ route('admin::adminUser.all') }}">
                    {{ csrf_field() }}
                    <div class="row row-15">
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input class="form-control" id="text" type="text"
                                       placeholder="{{trans('adminUser.name')}}" name="name"
                                       value="{{ $condition['name'] ?? ''  }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" name="level_id">
                                <option value="-1">{{ trans('general.select_level') }}</option>
                                @foreach($level_list ?? null as $key => $v)
                                    <option value="{{ $v['id'] }}"
                                            @if(isset($condition['level_id']) && $condition['level_id'] == $v['id']) selected @endif>{{ $v['level_name'] }}</option>
                                @endforeach
                            </select>
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
                                        title="{{ $v['name'] }}">{{ $v['name'] }} {{trans('general.is_del')}}</td>
                                @else
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"
                                        title="{{ $v['name'] }}">{{ $v['name'] }}</td>
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
                                <td>{{ $v['created_at'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{trans('general.action')}}</span>
                                        </button>
                                        @if($v['is_cancel'] != 2)
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.visual', ['id' => $v['id']]) }}">{{trans('adminUser.detail')}}</a>
                                                @if($v['name'] != 'admin')
                                                <a class="dropdown-item"
                                                   href="{{ route('admin::adminUser.recharge', ['id' => $v['id']]) }}">{{trans('adminUser.chongzhi')}}</a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="modal-sample">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{trans('general.message')}}</h5>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{trans('general.deleteSure')}}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary"
                                                    data-dismiss="modal">{{trans('general.cancel')}}</button>
                                            <button class="btn btn-primary"
                                                    onclick="deleteUser('{{ route('admin::adminUser.delete', ['id' => $v['id']]) }}')">{{trans('general.confirm')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
            <div id="pages" style="margin-top: 30px;margin-bottom: -50px;">
                {!! $lists->appends(['name'=>$lists->name, 'level_id'=>$lists->level_id])->render() !!}
            </div>
        </div>
    </div>
</div>

{{--@endsection--}}

@extends('admin.js')
@section('js')
    <script>

    </script>
    @endsection
    </div>
    </body>
    </html>