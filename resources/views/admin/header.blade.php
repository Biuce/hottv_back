<!DOCTYPE html>
<html class="rd-navbar-sidebar-active page-small-footer" lang="en">
<head>
    <title>@isset($breadcrumb){{ last($breadcrumb)['title'] }}@endisset - {{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta property="og:title" content="Template Monster Admin Template">
    <meta property="og:description" content="brevis, barbatus clabulares aliquando convertam de dexter, peritus capio. devatio clemens habitio est.">
    <meta property="og:image" content="http://digipunk.netii.net/images/radar.gif">
    <meta property="og:url" content="http://digipunk.netii.net">
    <link rel="icon" href="/public/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/public/components/base/base.css">
    <link rel="stylesheet" href="/public/admin/css/daterangepicker.css">
    <script src="/public/components/base/script.js"></script>
    {{--<style type="text/css">--}}
        {{--/*禁止选中文字*/--}}
        {{--body{--}}
            {{---moz-user-select: none; /*火狐*/--}}
            {{---webkit-user-select: none; /*webkit浏览器*/--}}
            {{---ms-user-select: none; /*IE10*/--}}
            {{---khtml-user-select: none; /*早期浏览器*/--}}
            {{--user-select: none;--}}
        {{--}--}}
    {{--</style>--}}
</head>
<body>
<div class="page">
