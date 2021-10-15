<script src="/public/components/base/script.js"></script>
<script src="/public/admin/js/laydate/laydate.js"></script>
<script src="/public/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/public/admin/js/laypage/laypage.js"></script>
<script src="/public/vendor/layui-v2.4.5/layui.all.js"></script>
<script src="/public/admin/js/moment.js"></script>
{{--<script src="/public/admin/js/daterangepicker.js"></script>--}}
{{--<script type="text/javascript" src="/public/admin/js/disable.js"></script>--}}
<script type="text/javascript" src="/public/admin/js/hlz_rsa.js"></script>

@yield('js')
<script>
    window.onload = function() {
        window.parent.scrollTo(0, -1);
        document.body.scrollTop = 0;
    }
</script>
