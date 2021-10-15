@include('admin.header')

{{--@section('content')--}}
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between group-5">
                <h4 class="panel-title">
                    <span class="panel-icon fa-tasks"></span>
                    <span>{{trans('channel.managers')}}</span>
                </h4>
                <div class="form-group">
                    <a href="{{ route('admin::channel.create') }}">
                        <button class="btn btn-primary" type="button"
                                id="submitBtn">{{trans('channel.newChannel')}}</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" style="padding-bottom: 20px;">
                    <thead class="border-bottom">
                    <tr class="long-tr">
                        <th>{{trans('channel.id')}}</th>
                        <th>{{trans('channel.channel_name')}}</th>
                        <th>{{trans('general.action')}}</th>
                    </tr>
                    </thead>
                    <tbody id="list-content">
                    @isset($lists)
                        @foreach($lists as $k => $v)
                            <tr>
                                <td>{{ $v['channel_id'] }}</td>
                                <td>{{ $v['channel_name'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle btn-info btn-sm" data-toggle="dropdown"
                                                aria-expanded="false">
                                            <span>{{trans('general.action')}}</span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{ route('admin::channel.info', ['id' => $v['channel_id']]) }}">{{trans('general.info')}}</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
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