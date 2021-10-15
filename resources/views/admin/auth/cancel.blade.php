<!DOCTYPE html>
<html class="rd-navbar-sidebar-active" lang="en">
<head>
    <title>Password Reset</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="og:title" content="Template Monster Admin Template">
    <meta property="og:description"
          content="brevis, barbatus clabulares aliquando convertam de dexter, peritus capio. devatio clemens habitio est.">
    <link rel="icon" href="/public/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/public/components/base/base.css">
    <script src="/public/components/base/script.js"></script>
</head>
<body>
<div class="page">
    <section class="section-md section-transparent">
        <div class="container-fluid">
            <div class="panel p-0">
                <div class="panel-body section-one-screen section-lg">
                    <canvas class="js-waves"></canvas>
                    <div class="container-fluid">
                        <div class="row row-30 justify-content-center align-items-center">
                            <div class="col-md-8 col-xl-5">
                                <h3 class="text-center">{{trans('adminUser.user_tips1')}}</h3>
                                <div class="row row-30 justify-content-center">
                                    <div class="col-xl-10">
                                        <div class="alert alert-info alert-border-left" role="alert"><span
                                                    class="alert-icon fa-info"></span><span>{{trans('adminUser.user_tips2')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer footer-small">
        <footer class="footer footer-small">
            <div class="container-fluid">
                {{--<p><span class="d-inline-block pr-2">PART</span>© 2019. Design by Zemez</p>--}}
            </div>
        </footer>
    </footer>
</div>
<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script src="/public/admin/js/admin.js"></script>
<script>
    if (window != top) {
        top.location.href = location.href;
    }
</script>
</body>
</html>