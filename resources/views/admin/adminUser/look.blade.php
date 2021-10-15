@include('admin.header')

{{--@include('admin.breadcrumb')--}}
<div class="col-md-10 col-lg-12">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title">
                {{trans('adminUser.check_cost')}}
            </h4>
        </div>
        <section class="section-sm bg-800">
            <div class="row form-group">
                <div class="col-sm-1 text-sm-right">
                    <label class="col-form-label" for="standardInput">{{trans('equipment.money')}}</label>
                </div>
                <div class="col-sm-10">
                    <table class="table table-striped table-hover" style="padding-bottom: 20px;" id="mytable">
                        <thead class="border-bottom">
                        <tr class="long-tr" id="define">
                            <th>{{trans('adminUser.assort')}}</th>
                            <th>{{trans('equipment.retail_price')}}</th>
                            <th>{{trans('equipment.country')}}</th>
                            <th>{{trans('equipment.diamond')}}</th>
                            <th>{{trans('equipment.medal')}}</th>
                            <th>{{trans('equipment.silver')}}</th>
                            <th>{{trans('equipment.copper')}}</th>
                            <th>{{trans('equipment.defined')}}</th>
                        </tr>
                        </thead>
                        <tbody id="list-content">
                        @isset($lists)
                            <?php $i = 0; ?>
                            @foreach($lists as $k => $v)
                                <tr>
                                    <td>{{ $v ?? 0 }}</td>
                                    <td>{{ $retail[$k] ?? 0 }}</td>
                                    <td>{{ $data[3][$k] ?? 0 }}</td>
                                    <td>{{ $data[4][$k] ?? 0 }}</td>
                                    <td>{{ $data[5][$k] ?? 0 }}</td>
                                    <td>{{ $data[6][$k] ?? 0 }}</td>
                                    <td>{{ $data[7][$k] ?? 0 }}</td>
                                    <td>{{ $data[8][$k] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-1 text-sm-right">
                    <label class="col-form-label" for="standardInput"></label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-1 text-sm-right">
                    <label class="col-form-label"
                           for="standardInput">{{trans('adminUser.entry_barriers')}}</label>
                </div>
                <div class="col-sm-10">
                    <table class="table table-striped table-hover" style="padding-bottom: 20px;" id="mytable">
                        <thead class="border-bottom">
                        <tr class="long-tr" id="define">
                            <th>{{trans('equipment.medal_sill')}}</th>
                            <th>{{trans('equipment.silver_sill')}}</th>
                            <th>{{trans('equipment.copper_sill')}}</th>
                            <th>{{trans('equipment.defined_sill')}}</th>
                        </tr>
                        </thead>
                        <tbody id="list-content">
                        <tr>
                            @isset($cost)
                                @foreach($cost as $k => $v)
                                    <td>{{ $v ?? 0 }}</td>
                                @endforeach
                            @endisset
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-1 text-sm-right">
                    <label class="col-form-label"
                           for="standardInput"></label>
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