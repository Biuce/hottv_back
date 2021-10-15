@include('admin.header')

<section class="section-md section-transparent">
    <div class="container-fluid">
        <div class="panel p-0">
            <div class="panel-body section-one-screen section-lg">
                <canvas class="js-waves"></canvas>
                <div class="container-fluid">
                    <div class="row row-30 justify-content-center align-items-center">
                        <div class="col-md-8 col-xl-5">
                            <h3 class="text-center">{{trans('adminUser.check_email')}}</h3>
                            <div class="row row-30 justify-content-center">
                                <div class="col-xl-10">
                                    <form method="post" id="form" action="{{ route('admin::adminUser.checkEmail') }}">
                                        {{ csrf_field() }}
                                        <div class="alert alert-info alert-border-left" role="alert"><span
                                                    class="alert-icon fa-info"></span><span>{{trans('home.content_1')}}{{ \Auth::guard('admin')->user()->email }}{{trans('home.content_2')}}</span>
                                        </div>
                                        <div class="input-group mt-3">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text" id="icon-addon1">
                                                        <span class="fa-envelope-o"></span>
                                                    </span>
                                            </div>
                                            <input class="form-control" type="text" aria-describedby="icon-addon1"
                                                   name="code" placeholder="{{trans('home.enter_code')}}">
                                            <div class="input-group-append">
                                                <button class="btn btn-light"
                                                        type="submit">{{trans('home.affirm')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@extends('admin.js')
@section('js')
    <script>
        $('#form').submit(function () {
            window.form_submit = $('#form').find('[type=submit]');
            form_submit.prop('disabled', true);
            var method = $("#form").attr("method");
            var action = $('#form').attr("action");
            console.log(action);
            $.ajax({
                type: method,
                url: action,
                data: $('#form').serializeArray(),
                success: function (result) {
                    console.log(result);
                    if (result.code !== 0) {
                        form_submit.prop('disabled', false);
                        layer.msg(result.msg, {shift: 6});
                        return false;
                    }
                    layer.msg(result.msg, {shift: 1}, function () {
                        if (result.reload) {
                            location.reload();
                        }
                        if (result.redirect) {
                            location.href = '{{ route('admin::adminUser.reCancel') }}';
                        }
                    });
                },
                error: function (resp, stat, text) {
                    if (window.form_submit) {
                        form_submit.prop('disabled', false);
                    }
                    if (resp.status === 422) {
                        var parse = $.parseJSON(resp.responseText);
                        if (parse) {
                            layer.msg(parse.msg, {shift: 6, skin: 'alert-secondary alert-lighter'});
                        }
                        return false;
                    } else if (resp.status === 404) {
                        layer.msg("{{trans('general.resources_not')}}", {icon: 5, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 401) {
                        layer.msg("{{trans('general.login_first')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 429) {
                        layer.msg("{{trans('general.Overvisiting')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 419) {
                        layer.msg("{{trans('general.illegal_request')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else if (resp.status === 500) {
                        layer.msg("{{trans('general.internal_error')}}", {shift: 6, skin: 'alert-secondary alert-lighter'});
                        return false;
                    } else {
                        var parse = $.parseJSON(resp.responseText);
                        // if (parse && parse.err) {
                        if (parse) {
                            layer.alert(parse.msg);
                        }
                        return false;
                    }
                }
            });
                return false;
        });
    </script>
    @endsection
    </div>
    </body>
    </html>