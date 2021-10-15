@include('admin.header')
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('level.info')}}
            </h4>
        </div>

        <div class="panel-body">
            <div class="row form-group">
                <div class="col-sm-1 text-sm-right">
                    <label class="col-form-label" for="standardInput">{{trans('level.level_name')}}</label>
                </div>
                <div class="col-sm-5">
                    <div class="input-group form-group">
                        <span class="form-control">{{ $level->level_name ?? ''  }}</span>
                    </div>
                </div>
            </div>
            <div class="row row-10 align-items-center">
                <div class="col-sm-12 text-sm-center">
                    <a href="{{ route('admin::level.edit', ['id' => $level->id]) }}">
                        <button class="btn btn-primary" type="submit" id="submitBtn">{{trans('level.editLevel')}}</button>
                    </a>
                    <div style="display:inline;float:right;">
                        <button type="button" class="btn btn-warning"
                                onclick="history.go(-1);">{{trans('general.return')}}</button>
                    </div>
                </div>
            </div>
        </div>

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