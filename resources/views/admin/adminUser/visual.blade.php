@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<section>
    <div class="tabs tabs-vertical-top tabs-bg">
        <ul class="nav nav-tabs tabs-default scroller scroller-horizontal" role="tablist">
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.visual', ['id' => $id]) }}')"><a
                        class="nav-link active" data-toggle="tab" href="#panelTab1" role="tab"
                        aria-controls="panelTab1" aria-selected="true">{{trans('general.index')}}</a></li>
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.stepOne', ['id' => $id]) }}')"><a
                        class="nav-link" data-toggle="tab" href="#panelTab2" role="tab"
                        aria-controls="panelTab2" aria-selected="false">{{trans('adminUser.managers')}}</a></li>
            <li class="nav-item" onclick="choose('{{ route('admin::adminUser.stepTwo', ['id' => $id]) }}')"><a
                        class="nav-link" data-toggle="tab" href="#panelTab3" role="tab"
                        aria-controls="panelTab3" aria-selected="false">{{trans('huobi.managers')}}</a></li>
        </ul>
    </div>
</section>

<div class="container-fluid" style="margin-top: 50px;">
    <div class="row row-30">
        <div class="container-fluid">
            <div class="row row-30">
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-primary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($userInfo->balance, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.huobi_balance')}}</h5>
                        <div class="widget-counter-icon linearicons-user"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-info" style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($month_code, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.month_code')}}</h5>
                        @if($locale == 'en' || $locale == 'my')
                            <h5 class="widget-counter-text">{{trans('home.month_code1')}}</h5>
                        @endif
                        <div class="widget-counter-icon linearicons-pie-chart"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-secondary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($last_month_code, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.last_month_code')}}</h5>
                        @if($locale == 'en' || $locale == 'my')
                            <h5 class="widget-counter-text">{{trans('home.last_month_code1')}}</h5>
                        @endif
                        <div class="widget-counter-icon linearicons-paper-plane"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-success"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($month_expend, 2) }}</h1>
                        <h5 class="widget-counter-text">{{trans('home.last_month_huobi')}}</h5>
                        @if($locale == 'en' || $locale == 'my')
                            <h5 class="widget-counter-text">{{trans('home.last_month_huobi1')}}</h5>
                        @endif
                        <div class="widget-counter-icon linearicons-mailbox-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row row-30">
                <div class="col-12">
                    <h4>{{trans('home.lower_agency')}}</h4>
                </div>
                {{--@if($type != 1)--}}
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-primary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($month_profit, 2) }}</h1>
                        <h5 class="widget-counter-text"
                            style="margin-top: 20px;">{{trans('home.month_lower_profit')}}</h5>
                        <div class="widget-counter-icon linearicons-user"></div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-info"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($last_month_profit, 2) }}</h1>
                        <h5 class="widget-counter-text"
                            style="margin-top: 20px;">{{trans('home.last_month_profit')}}</h5>
                        <div class="widget-counter-icon linearicons-pie-chart"></div>
                    </div>
                </div>
                {{--@endif--}}
                {{--@if(\Auth::guard('admin')->user()->level_id <= 3)--}}
                <div class="col-md-6 col-xxl-3">
                    <div class="widget-counter widget-counter-simple widget-counter-simple-secondary"
                         style="height: 174px;">
                        <h1 class="widget-counter-title">{{ number_format($user_count, 2) }}</h1>
                        <h5 class="widget-counter-text"
                            style="margin-top: 20px;">{{trans('home.count_agency')}}</h5>
                        <div class="widget-counter-icon linearicons-paper-plane"></div>
                    </div>
                </div>
                {{--@endif--}}
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