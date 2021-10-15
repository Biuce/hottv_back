@include('admin.header')

{{--@section('content')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('authCode.try_records')}}</span>
                </h4>
            </div>

        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('authCode.id')}}</th>
                        <th>{{trans('authCode.try_time')}}</th>
                        <th>{{trans('authCode.try_condition')}}
                            <a class="dropdown-item" href="javascript:;" data-modal-trigger='{"target":"#modal-sample"}'>
                                <span class="mdi-help-circle"></span>
                            </a>
                        </th>
                        <th>{{trans('authCode.number')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>{{ $v['description'] }}</td>
                                <td>{{ $v['number'] }}</td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>

                <div class="modal fade" id="modal-sample">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{trans('general.message')}}</h5>
                                <button class="close" data-dismiss="modal">×</button>
                            </div>
                            <div class="modal-body">
                                <div style="margin-left: 60px">
                                    <p>{{trans('authCode.one_tips')}}</p>
                                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_one')}} {{ $assort[0]['try_num'] }}</p>
                                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_two')}} {{ $assort[1]['try_num'] }}</p>
                                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_three')}} {{ $assort[2]['try_num'] }}</p>
                                    <p style="margin-left: 15px;">{{trans('authCode.one_tips_four')}} {{ $assort[3]['try_num'] }}</p>
                                    <p>{{trans('authCode.two_tips')}}</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal">{{trans('general.confirm')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

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

    </script>
    @endsection
    </div>
    </body>
    </html>