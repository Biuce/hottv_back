@include('admin.header')

@php
    $user = \Auth::guard('admin')->user();
    $isSuperAdmin = in_array($user->id, config('light.superAdmin'));
@endphp
<header class="section page-header">
    <!--RD Navbar-->
    <div class="rd-navbar-wrap">
        <nav class="rd-navbar">
            <div class="navbar-panel">
                <div class="navbar-panel-inner">
                    <div class="navbar-panel-cell">
                        <button class="navbar-toggle mdi-blur-radial" title="multi-language"
                                data-multi-switch='{"targets":"#subpanel-multi-language","scope":"#subpanel-multi-language","isolate":"[data-multi-switch]"}'></button>
                        <div class="navbar-subpanel" id="subpanel-multi-language">
                            <div class="navbar-subpanel-inner">
                                <div class="navbar-subpanel-header">
                                    <h4>{{ trans('general.lang') }}</h4>
                                </div>
                                <div class="navbar-subpanel-body scroller scroller-vertical">
                                    <div class="group-5">
                                        @foreach (Config::get('app.locales') as $lang => $language)
                                            {{--@if ($lang != App::getLocale())--}}
                                            <a class="" href="{{ route('lang.change', $lang) }}">
                                                <div class="alert alert-dismissible alert-primary alert-darker alert-sm"
                                                     role="alert" style="margin-top: 10px;">
                                                    <span>{{$language}}</span>
                                                </div>
                                            </a>
                                            {{--@endif--}}
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="navbar-panel-cell">
                        <div class="navbar-toggle navbar-user" title="User Menu" id="picture"
                             data-multi-switch='{"targets":"#subpanel-user-menu","scope":"#subpanel-user-menu","isolate":"[data-multi-switch]"}'>
                            <img class="rounded"
                                 src="@if(!empty(\Auth::guard('admin')->user()->photo)){{ \Auth::guard('admin')->user()->photo }}@else /public/images/users/user-09-247x247.png @endif"
                                 alt="" style="height: 40px;"/></div>
                        <div class="navbar-subpanel" id="subpanel-user-menu">
                            <div class="navbar-subpanel-inner">
                                <div class="navbar-subpanel-header">
                                    <div class="h3">{{ \Auth::guard('admin')->user()->name }}</div>
                                </div>
                                <div class="navbar-subpanel-body p-0 scroller scroller-vertical">
                                    <div class="list-group list-group-flush">
                                        {{--<a class="list-group-item rounded-0" href="javascript:;"--}}
                                        {{--onclick="Ajaxcontent('{{ route('admin::adminUser.userInfo') }}', this)">--}}
                                        {{--<div class="media align-items-center">--}}
                                        {{--<div class="pr-2"><span class="fa-user"></span></div>--}}
                                        {{--<div class="media-body">--}}
                                        {{--<h5>{{trans('general.set_user_info')}}</h5>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</a>--}}
                                        {{--<a class="list-group-item rounded-0" href="javascript:;"--}}
                                        {{--onclick="Ajaxcontent('{{ route('admin::adminUser.changePwd') }}', this)">--}}
                                        {{--<div class="media align-items-center">--}}
                                        {{--<div class="pr-2"><span class="mdi-account-key"></span></div>--}}
                                        {{--<div class="media-body">--}}
                                        {{--<h5>{{trans('general.set_password')}}</h5>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</a>--}}
                                        {{--<a class="list-group-item rounded-0" href="javascript:;"--}}
                                        {{--onclick="Ajaxcontent('{{ route('admin::adminUser.cancel') }}', this)">--}}
                                        {{--<div class="media align-items-center">--}}
                                        {{--<div class="pr-2"><span class="mdi-account-off"></span></div>--}}
                                        {{--<div class="media-body">--}}
                                        {{--<h5>{{trans('general.cancel_account')}}</h5>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</a>--}}
                                        <a class="list-group-item rounded-0" href="{{ route('admin::logout') }}">
                                            <div class="media align-items-center">
                                                <div class="pr-2"><span class="mdi-account-remove"></span></div>
                                                <div class="media-body">
                                                    <h5>{{trans('general.sign_out')}}</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-sidebar-wrap">
                <div class="navbar-sidebar-panel">
                    <button class="navbar-toggle-sidebar linearicons-menu"
                            data-navigation-switch="data-navigation-switch"></button>
                    <div class="navbar-logo"><a class="navbar-logo-link" href="javascript:;"
                                                onclick="Ajaxcontent('/admin/aggregation', this)"><img
                                    class="navbar-logo-default" src="/public/images/logo-272x84.png" width="136"
                                    height="136"
                                    alt="PART"/></a></div>
                </div>
                <div class="navbar-sidebar scroller scroller-vertical">
                    <div class="tabs tabs-horizontal-left">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#menu-1" role="tab"
                                                    aria-selected="true" title="Menu"><span
                                            class="linearicons-icons"></span></a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu-3" role="tab"
                                                    aria-selected="false" title="Tools"><span
                                            class="linearicons-cog"></span></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="menu-1" role="tabpanel">
                                <!-- 左侧导航区域 -->
                                <ul class="navbar-navigation rd-navbar-nav">
                                    <li class="navbar-navigation-item active">
                                        <a class="navbar-navigation-link" href="javascript:;" title="Dashboard"
                                           onclick="Ajaxcontent('/admin/aggregation', this)"><span
                                                    class="navbar-navigation-text">{{trans('general.index')}}</span></a>
                                    </li>
                                    <?php
                                    $where = ['27', '158', '160', '213', '301'];
                                    if (\Auth::guard('admin')->user()->level_id > 3) {
                                        $where = ['27', '158', '160', '301'];
                                    } elseif (\Auth::guard('admin')->user()->level_id == 3) {
                                        $where = ['27', '158', '160', '212', '213', '301'];
                                    }
                                    $lists = App\Repository\Admin\MenuRepository::group($where);
                                    ?>
                                    @foreach($lists as $key => $menu)
                                        @if($key == '工具管理')
                                            @foreach($menu as $sub)
                                                @if(intval($sub['status']) === App\Model\Admin\Menu::STATUS_ENABLE)
                                                    <li class="navbar-navigation-item">
                                                        <a class="navbar-navigation-link"
                                                           href="javascript:;" title="{{ $sub['name'] }}"
                                                           onclick="Ajaxcontent('{{ $sub['url'] }}', this)"><span
                                                                    class="navbar-navigation-text">{{ $sub['name'] }}</span></a>
                                                    </li>
                                                @endif
                                            @endforeach
                                            @break
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="menu-2" role="tabpanel">
                                <ul class="navbar-navigation rd-navbar-nav">
                                    <?php  $lists = App\Repository\Admin\MenuRepository::group(); ?>
                                    @foreach($lists as $key => $menu)
                                        @if($key == '订单功能')
                                            @foreach($menu as $sub)
                                                @if(intval($sub['status']) === App\Model\Admin\Menu::STATUS_ENABLE && ($isSuperAdmin || $user->can($sub['name'])))
                                                    <li class="navbar-navigation-item">
                                                        <a class="navbar-navigation-link"
                                                           href="javascript:;" title="{{ $sub['name'] }}"
                                                           onclick="Ajaxcontent('{{ $sub['url'] }}', this)"><span
                                                                    class="navbar-navigation-text">{{ $sub['name'] }}</span></a>
                                                    </li>
                                                @endif
                                            @endforeach
                                            @break
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="menu-3" role="tabpanel">
                                <ul class="navbar-navigation rd-navbar-nav">
                                    <?php
                                    $where = ['189', '192', '198', '211'];
                                    if (\Auth::guard('admin')->user()->level_id > 3 && \Auth::guard('admin')->user()->level_id < 8) {
                                        $where = ['189', '192', '194'];
                                    } elseif (\Auth::guard('admin')->user()->level_id <= 3 && \Auth::guard('admin')->user()->level_id > 0) {
                                        $where = ['163', '189', '192', '198'];
                                    } elseif (\Auth::guard('admin')->user()->level_id == 8) {
                                        $where = ['189', '192'];
                                    }
                                    $lists = App\Repository\Admin\MenuRepository::group($where);
                                    ?>
                                    @foreach($lists as $key => $menu)
                                        @if($key == '基础功能')
                                            @foreach($menu as $sub)
                                                @if(intval($sub['status']) === App\Model\Admin\Menu::STATUS_ENABLE)
                                                    <li class="navbar-navigation-item">
                                                        <a class="navbar-navigation-link"
                                                           href="javascript:;" title="{{ $sub['name'] }}"
                                                           onclick="Ajaxcontent('{{ $sub['url'] }}', this)"><span
                                                                    class="navbar-navigation-text">{{ $sub['name'] }}</span></a>
                                                    </li>
                                                @endif
                                            @endforeach
                                            @break
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<!-- 内容主体区域 -->
<!-- 第一种自适应，不包含js -->
{{--<iframe class="section-sm" name="iframeMain" id="iframeMain"  src="/admin/aggregation"  onload="this.height=iframeMain.document.body.scrollHeight" scrolling="no" frameborder="0" width="100%">--}}
{{--该浏览器不支持iframe，请使用其他浏览器！--}}
{{--</iframe>--}}
<!-- 第二种自适应，包含js（iframeAutoHeight） -->
{{--<iframe style='height:100%; width:100%;' frameborder=0 onload='iframeAutoHeight(this)' scrolling='no'--}}
{{--src="/admin/aggregation" name="iframeMain" id="iframeMain">--}}
{{--</iframe>--}}
<!-- 第三种自适应，包含js（reinitIframe） -->
<iframe src="/admin/aggregation" frameborder="0" scrolling="no" onload="this.height=100" width="100%" name="iframeMain"
        id="iframeMain"></iframe>

<footer class="footer footer-small" style="width: 1250px;">
    {{--<div class="container-fluid" style="text-align:center;">--}}
    {{--<span style="font-size:12px;">Copyright &copy; 2020 test.hottvapp.com. All rights reserved.</span>--}}
    {{--</div>--}}
</footer>
<div class="sidebar scroller">
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-title"><span class="panel-icon fa-trophy"></span><span>Right Sidebar Content</span></h4>
        </div>
        <div class="panel-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce volutpat ac tortor eu viverra. Etiam ipsum
                neque, fermentum quis sagittis nec, hendrerit id diam. Mauris a tincidunt odio. Sed porttitor ex
                pulvinar, tristique sapien sed, malesuada nunc.</p>
        </div>
    </div>
</div>
</div>

@extends('admin.js')
{{--@yield('js')--}}
<script>
    window.onload = function () {
        window.parent.scrollTo(0, -1);
        document.body.scrollTop = 0;
    };
    //    function show_body_click() {
    //        $("#picture").removeClass("active");
    //        $("#subpanel-user-menu").removeClass("active");
    //    }
    //
    //    function show() {
    //        $("#picture").addClass("active");
    //        $("#subpanel-user-menu").addClass("active");
    //    }

    function reinitIframe() {
        var iframe = document.getElementById("iframeMain");
        try {
            var bHeight = iframe.contentWindow.document.body.scrollHeight;
            var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
            var height = Math.max(bHeight, dHeight);
            iframe.height = height;
            console.log(height);
        } catch (ex) {
        }
    }
    window.setInterval("reinitIframe()", 200);

    // iframe高度自适应
    // function iframeAutoHeight(el) {
    //     // el.style.height = el.contentWindow.document.body.offsetHeight + 'px'
    //     var agent = navigator.userAgent.toLowerCase();
    //     console.log($(el).height());
    //     if (agent.indexOf("chrome") > -1) { //chrome
    //         $(el).height($(el).parent().height());
    //     }
    // }

    // 左侧菜单选择状态
    function Ajaxcontent(url, obj) {
        $("#iframeMain").attr("src", url);
        $(obj).parent().addClass('active');
        $(obj).parent().addClass('active');
        $(obj).parent().siblings('li').removeClass('active');

        var node = $(obj).parent().parent().parent();
        node.siblings('li').removeClass('active');
        node.addClass('active');
    }

    //这段js要放在页面最下方
//    var h = window.innerHeight, w = window.innerWidth;
    // 禁用右键 （防止右键查看源代码）
//    window.oncontextmenu = function () {
//        return false;
//    };

    //禁用开发者工具F12
//    document.onkeydown = function (e) {
//        var currKey = 0, evt = e || window.event;
//        currKey = evt.keyCode || evt.which || evt.charCode;
//        if (currKey == 123) {
//            window.event.cancelBubble = true;
//            window.event.returnValue = false;
//        }
//    };

    //在本网页的任何键盘敲击事件都是无效操作 （防止F12和shift+ctrl+i调起开发者工具）
    //    window.onkeydown = window.onkeyup = window.onkeypress = function () {
    //        window.event.returnValue = false;
    //        return false;
    //    };
    //如果用户在工具栏调起开发者工具，那么判断浏览器的可视高度和可视宽度是否有改变，如有改变则关闭本页面
//    window.onresize = function () {
//        if (h != window.innerHeight || w != window.innerWidth) {
//            window.close();
//                window.location = "about:blank";
            {{--window.location = '{{ route('admin::index') }}';--}}
        {{--}--}}
    {{--};--}}
    /*好吧，你的开发者工具是单独的窗口显示，不会改变原来网页的高度和宽度，
     但是你只要修改页面元素我就重新加载一次数据,让你无法修改页面元素（不支持IE9以下浏览器）*/
//    if (window.addEventListener) {
//        window.addEventListener("DOMCharacterDataModified", function () {
//            window.location.reload();
//        }, true);
//        window.addEventListener("DOMAttributeNameChanged", function () {
//            window.location.reload();
//        }, true);
//        window.addEventListener("DOMCharacterDataModified", function () {
//            window.location.reload();
//        }, true);
//        window.addEventListener("DOMElementNameChanged", function () {
//            window.location.reload();
//        }, true);
        //                window.addEventListener("DOMNodeInserted", function(){window.location.reload();}, true);
        //                window.addEventListener("DOMNodeInsertedIntoDocument", function(){window.location.reload();}, true);
        //                window.addEventListener("DOMNodeRemoved", function(){window.location.reload();}, true);
        //                window.addEventListener("DOMNodeRemovedFromDocument", function(){window.location.reload();}, true);
        //                window.addEventListener("DOMSubtreeModified", function(){window.location.reload();}, true);
//    }
</script>
</body>
</html>