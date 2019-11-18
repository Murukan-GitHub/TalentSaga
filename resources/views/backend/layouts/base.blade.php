<?php $userRole = auth()->user()->role; ?>
@inject('baseConfig', 'App\Config\BaseConfig')

@section('sidebar_menus')
@foreach($baseConfig::$data['pageId'] as $key => $value)
@if (in_array($userRole, $value['roles']))
<?php
if ($pageId == $key) $pageTitle = $pageTitle ?: $value['label'];
$user = auth()->user();
$personalPicture = isset(session('personal')['logo_url_medium_square']) ? session('personal')['logo_url_medium_square'] : asset('frontend/img/icon-candidate.png');
?>
<li class="nav-item start {{ $pageId[0] == $key || $pageId == $key ? "active open" : "" }}">
    <a href="{{ sizeof($value['submenu']) > 0 ? 'javascript:;' : route($value['route'], isset($value['routeParams']) ? $value['routeParams'] : [])}}" class="nav-link {{ sizeof($value['submenu']) > 0 ? 'nav-toggle' : '' }}" href="{{ sizeof($value['submenu']) > 0 ? '#' : route($value['route'], isset($value['routeParams']) ? $value['routeParams'] : []) }}">
        <i class="{{ $value['icon'] }}"></i>
        <span class="title">{{ trans($value['label']) }}</span>
        @if (sizeof($value['submenu']) > 0)
        <span class="arrow {{ $pageId[0] == $key ? "open" : "" }}"></span>
        @endif
        {!! $pageId[0] == $key ? "<span class=\"selected\"></span>" : "" !!}
    </a>
    @if (sizeof($value['submenu']) > 0)
    <ul class="sub-menu">
        @foreach ($value['submenu'] as $submenuKey => $submenuValue)
        <?php if ($pageId == $submenuKey) $pageTitle = $pageTitle ?: $submenuValue['label']; ?>
        <li class="nav-item start {{ $pageId == $submenuKey ? "active open" : "" }}">
            <a class="nav-link " href="{{ route($submenuValue['route'], isset($submenuValue['routeParams']) ? $submenuValue['routeParams'] : []) }}">
                <i class="{{ array_key_exists('icon', $submenuValue) ? $submenuValue['icon'] : 'icon-doc' }}"></i>
                <span class="title">{{ trans( $submenuValue['label'] ) }}</span>
                {!! $pageId[0] == $key ? "<span class=\"selected\"></span>" : "" !!}
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</li>
@endif
@endforeach
@endsection

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>{{ trans(empty($pageTitle) ? "Page Title" : $pageTitle) }} | Talentsaga</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Talentsaga - Connecting Talents" name="description" />
    <meta content="Talentsaga" name="author" />
    {{-- csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="/metronic/global/plugins/pace/pace.min.js"></script>
    <link href="/metronic/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" />

    <style>
        .pace-waiting .page-content-wrapper:before,
        .pace-running .page-content-wrapper:before {
            content: " ";
            width: 100%;
            height: 100%;
            position: absolute;
            background: rgba(255, 255, 255, 0.86);
            z-index: 9999;
            top: 0;
            left: 0;
        }
        .form-control-static > p {
            margin: 0;
        }
    </style>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="/metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="/metronic/css/main.css?v={{ env('CSS_VERSION', 5) }}" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />

        <link href="/metronic/global/plugins/nouislider/nouislider.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/nouislider/nouislider.pips.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap-summernote/summernote.css" rel="stylesheet" type="text/css" />

        <link href="/metronic/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    @yield('styles-plugins')

    @yield('styles-pages')

        <!-- CROPPIE -->
        <link href="/metronic/global/plugins/croppie/sweetalert.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/plugins/croppie/croppie.css" rel="stylesheet" type="text/css" />
        <!-- END CROPPIE -->

        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/metronic/layouts/layout/css/themes/components-md.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/metronic/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/css/equal-height-columns.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/global/css/custom-components.min.css" rel="stylesheet" type="text/css" />

        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/metronic/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/metronic/layouts/layout/css/themes/colorbrand.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="/metronic/layouts/layout/css/themes/custom.css?v={{ env('CSS_VERSION', 1) }}" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/metronic/js/vendor/modernizr.min.js"></script>
        <!-- END THEME LAYOUT STYLES -->
    @yield('styles-layouts')
    <!-- END THEME LAYOUT STYLES -->
    <link href="{{ Theme::url('metronic/img/apple-icon.png') }}" rel="apple-touch-icon" type="image/png"/>
    <link href="{{ Theme::url('metronic/img/favicon.png') }}" rel="shortcut icon" type="image/png"/>
    <!-- BEGIN PAGE STYLES -->
    @yield('page_styles')
    <!-- END PAGE STYLES -->

    @yield('style-head')
    @yield('script-head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>
        .page-header.navbar .top-menu .navbar-nav > li.dropdown-extended .dropdown-menu,
        .page-header.navbar .top-menu  .dropdown-menu {
            border-color: #464c59;
        }
        .no-data {
            text-align: center; font-size: 2em; color: #999; padding: 2em 10em;
        }
        .dropdown-company img {
            max-width: 28px;
            margin-top: -10px;
        }
        .dropdown-company .dropdown-menu li a {
            padding: 10px;
        }
        .btn.btn-outline.green {
            color: #ffffff;
        }
        [disabled] .noUi-connect,[disabled].noUi-connect {
            background: #B8B8B8;
        }
    </style>
    @stack('start_script')
    <!-- END HEAD -->
    <script src="https://www.google.com/recaptcha/api.js?hl={{app()->getLocale()}}" async defer></script>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md page-sidebar-fixed {{ Theme::config('sidebar-closed') !== null ? (Theme::config('sidebar-closed') ? 'page-sidebar-closed' : '') : '' }}">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="/">
                    <img src="{{ Theme::url('metronic/layouts/layout/img/site-logo.png') }}" alt="logo" class="logo-default" />
                </a>
                <div class="menu-toggler sidebar-toggler">
                    <span></span>
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a class="menu-toggler responsive-toggler" data-target=".navbar-collapse" data-toggle="collapse" href="javascript:;">
                <span></span>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <?php 
                            $latestBookings = latestBookings(10);
                        ?>
                        <a class="dropdown-toggle" data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="javascript:;">
                            <i class="icon-bell"></i>
                            <span class="badge badge-default"> {{ $latestBookings->count() }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="external">
                                <h3><span class="bold"> {{ trans('backendnav.ten_last_booking') }}</span> 
                            </li>
                            <li>
                                <ul class="dropdown-menu-list scroller" data-handle-color="#637283" style="height: 250px;">
                                @foreach($latestBookings as $candidate)
                                    <li>
                                        <a href="{{ url('/admin/userbooking/'.$candidate->id) }}">
                                            <span class="time"> {{ $candidate->created_at->diffForHumans() }} </span>
                                            <span class="details">
                                                <span class="label label-sm label-icon {{ in_array($candidate->status, ['created', 'approved', 'done']) ? 'label-success' : 'label-danger' }}">
                                                    <i class="fa {{ in_array($candidate->status, ['created']) ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                </span>
                                                {{ $candidate->user }} booking {{ $candidate->talentUser }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!--
                    <li class="dropdown dropdown-extend dropdown-lang">
                        <a class="dropdown-toggle" data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="javascript:;">
                            {{ (app()->getLocale() == 'en' ? 'ENG' : (app()->getLocale() == 'de' ? 'GER' : 'IND')) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['locale' => 'de']) }}">Germany</a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}">English</a>
                            </li>
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['locale' => 'id']) }}">Indonesia</a>
                            </li>
                        </ul>
                    </li>
                    -->
                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle" data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="javascript:;">
                            <img src="{{$user->picture_small_square or asset('metronic/layouts/layout/img/avatar3_small.jpg')}}" alt="" class="img-circle" />
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default dropdown-menu-user">
                            <li>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="user-avatar">
                                            <a href="#">
                                                <img class="img-thumbnail" src="{{$user->picture_small_square}}" style="width: 100%;" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-xs-8">
                                        <div class="block-half user-contact">
                                            <h4 class="no-space">
                                                <a class="text-ellipsis" href="#">
                                                    {{auth()->user()->name ? auth()->user()->name : 'No Name'}}
                                                </a>
                                            </h4>
                                            <div class="user-contact-mail">
                                                <a class="text-ellipsis" href="mailto:">
                                                    {{auth()->user()->email}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="{{route('sessions.logout')}}" class="btn btn-block">
                                    <i class="icon-key"></i>
                                    Log Out
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- BEGIN SIDEBAR -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <ul class="page-sidebar-menu  page-header-fixed {{ Theme::config('sidebar-closed') !== null ? (Theme::config('sidebar-closed') ? 'page-sidebar-menu-closed' : '') : '' }}" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                    <li class="sidebar-toggler-wrapper hide">
                        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                        <div class="sidebar-toggler">
                            <span></span>
                        </div>
                        <!-- END SIDEBAR TOGGLER BUTTON -->
                    </li>
                    <li class="sidebar-search-wrapper">
                        <div class="sidebar-search  sidebar-search-bordered">
                            <a href="javascript:;" class="remove">
                                <i class="icon-close"></i>
                            </a>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{{ date('D, d M Y') }}" readonly />
                                <span class="input-group-btn">
                                    <a href="javascript:;" class="btn submit">
                                        <i class="icon-clock"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <!-- DATE/TIME -->
                    </li>
                    @yield('sidebar_menus')
                </ul>
                <!-- END SIDEBAR MENU -->
                <!-- END SIDEBAR MENU -->
            </div>
            <!-- END SIDEBAR -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content {{ isset($pageId) && ($pageId == 'A1' || $pageId == 'K1') ? 'page-dashboard' : '' }}">
                <!-- BEGIN LARAVEL CONTENT BODY -->
                @yield('featured-content')
                @section('breadcrumb')
                <!-- BEGIN PAGE BAR -->
                <div class="page-bar">
                    <ul class="page-breadcrumb">
                        @if (isset($pageId) && isset($baseConfig::$data['pageId'][$pageId[0]]))
                        <li>
                            <a href="{{ !empty($baseConfig::$data['pageId'][$pageId[0]]['route']) ? route($baseConfig::$data['pageId'][$pageId[0]]['route']) : '#'}}">{{ trans( $baseConfig::$data['pageId'][$pageId[0]]['label'] ) }}</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        @endif
                        @section('breadcrumb_additionals')
                        @if (isset($baseObject) && Route::has($routeBaseName . '.index'))
                            @if (isset($pageId) && isset($baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]))
                            <li>
                                <a href="{{ !empty($baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]['route']) ? route($baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]['route']) : '#'}}">{{ trans( $baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]['label'] ) }}</a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            @else
                            <li>
                                <a href="{{ route($routeBaseName . '.index') }}">{{ trans( $baseObject->getLabel() ) }}</a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            @endif
                        @endif
                        @endsection
                        @yield('breadcrumb_additionals')
                        <li>
                            <span>
                                {{ trans( isset($title) && $title != 'Home' ? $title : (isset($pageTitle) && $pageTitle != 'Home' ? $pageTitle : '') ) }}
                            </span>
                        </li>
                        @yield('breadcrumb_custom_additionals')
                    </ul>
                </div>
                <!-- END PAGE BAR -->
                @endsection
                @yield('breadcrumb')
                @section('page_title')
                <!-- BEGIN PAGE TITLE-->
                @if(isset($customTitle)) 
                    <h3 class="page-title">
                        {{ trans($customTitle) }}
                    </h3>
                @else
                    @if (isset($baseObject))
                    <h3 class="page-title">
                        {{ trans(isset($pageId) && isset($baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]) ? $baseConfig::$data['pageId'][$pageId[0]]['submenu'][$pageId]['label'] : $baseObject->getLabel()) }} <small>{{ trans(isset($title) ? $title : (isset($pageTitle) ? $pageTitle : '')) }}</small>
                    </h3>
                    @else
                    <h3 class="page-title">
                        {{ trans(isset($title) ? $title : (isset($pageTitle) ? $pageTitle : '')) }}
                    </h3>
                    @endif
                @endif
                <!-- END PAGE TITLE-->
                @endsection
                @yield('page_title')
                
                <!-- FLASH NOTIFICATION -->
                @include('backend.partials.flashnotification')

                <!-- CONTENT -->
                @yield('content')
                
                <!-- END LARAVEL CONTENT BODY -->
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner" style="margin-left: 25px;"> Copyright &copy; {{ date('Y') }} {{settings('company_legalname')}}, All Rights Reserved.
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->
<!--[if lt IE 9]>
<script src="/metronic/global/plugins/respond.min.js"></script>
<script src="/metronic/global/plugins/excanvas.min.js"></script> 
<![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="/metronic/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/metronic/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/morris/morris.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <style>
        .label-check {
        display: block;
        position: relative;
        padding: 4px 7px;
        cursor: pointer;
        border: 1px solid #DDD;
        border-radius: 4px;
        }
        .label-check.is-checked {
        border-color: #ee7d30;
        background: #FFE5C3;
        }
        .select-btn-container {
        position: relative;
        }
        .select-btn-container .btn-action {
        position: absolute;
        z-index: 9;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        }
        .selected-check {
        padding: 5px;
        border: 1px solid #DDD;
        vertical-align: middle;
        }
        .selected-check .btn-action {
        margin-bottom: 5px;
        }
        .selected-check label {
        display: inline-block;
        padding: 5px;
        border: 1px solid #CCC;
        line-height: 1;
        margin-bottom: 4px;
        margin-right: 4px;
        border-radius: 3px;
        cursor: pointer;
        background: #f3f3f3;
        }
        .selected-check .label-text {
        display: inline-block;
        margin-left: 4px;
        }
        .selected-check label:hover {
        border-color: #ca525b;
        }
        .selected-check label .fa {
        color: #CCC;
        }
        .selected-check label:hover .fa {
        color: #ca525b;
        }
    </style>
    <!-- START EXTRA PAGE PLUGIN -->
    <script src="/metronic/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="https://cdn.rawgit.com/johnnyreilly/BootstrapMvcSample/62ae5cb8/BootstrapMvcSample/scripts/globalize.js"></script>
    <script src="https://cdn.rawgit.com/johnnyreilly/BootstrapMvcSample/62ae5cb8/BootstrapMvcSample/scripts/globalize-cultures/globalize.culture.id.js"></script>
    <script src="/metronic/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/nouislider/wNumb.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/nouislider/nouislider.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/check-all.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/ui-modals.min.js" type="text/javascript"></script>
    <script src="/metronic/global/scripts/app.min.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/components-editors.min.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/components-select2.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/tagsinput-object.min.js" type="text/javascript"></script>
    <script src="/metronic/js/vendor/ckeditor/ckeditor.js" type="text/javascript" ></script>
    <script src="/metronic/js/vendor/ckeditor/adapters/jquery.js" type="text/javascript" ></script>
    <script src="/metronic/js/vendor/autosize.min.js" type="text/javascript" ></script>
    <script src="/metronic/js/vendor/colorpicker.js" type="text/javascript" ></script>
    <script src="/metronic/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/location-fetchers.js" type="text/javascript"></script>

    <script type="text/javascript" src="/metronic/js/vendor/highcharts.min.js"></script>
    <script type="text/javascript" src="/metronic/js/vendor/highcharts.funnel.js"></script>
    <script type="text/javascript" src="/metronic/js/vendor/highcharts.exporting.js"></script>

    <script src="/metronic/pages/scripts/vacancy-nouisliders.min.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/degree-fetchers.min.js" type="text/javascript"></script>
    <script src="/metronic/pages/scripts/selects-fetchers.min.js" type="text/javascript"></script>

   <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/metronic/global/scripts/app.min.js" type="text/javascript"></script>
    <script src="/metronic/global/scripts/theme.min.js" type="text/javascript"></script>
    <script src="/metronic/global/scripts/equalheight.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script type="text/javascript">            window.myPrefix = '';</script>

    @stack('pre-main-js-script')

    <script type="text/javascript" src="/metronic/js/vendor/jquery.dataTables.yadcf.js?v={{env('JS_VERSION', 5)}}"></script>
    <script type="text/javascript" src="/metronic/js/vendor/rome.min.js"></script>
    <script type="text/javascript" src="/metronic/js/vendor/jquery.inputmask.min.js"></script>
    <script type="text/javascript" src="/metronic/js/vendor/locationpicker.jquery.min.js"></script>
    <script src="/metronic/js/helpers.min.js?v={{env('JS_VERSION', 5)}}" type="text/javascript" ></script>
    <script src="/metronic/js/main.min.js?v={{env('JS_VERSION', 5)}}" type="text/javascript" ></script>
    <script src="/metronic/pages/scripts/charts-highcharts-custom.js" type="text/javascript"></script>

    <!-- CROPPIE -->
    <script src="/metronic/global/plugins/croppie/sweetalert.min.js" type="text/javascript"></script>
    <script src="/metronic/global/plugins/croppie/croppie.min.js" type="text/javascript"></script>
    <!-- <script src="/metronic/global/plugins/croppie/exif.js" type="text/javascript"></script> -->
    <!-- END CROPPIE -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <!-- END THEME LAYOUT SCRIPTS -->
    <!-- BEGIN PAGE SCRIPTS -->
    @yield('page_script')
    @stack('end_script')
    <!-- END PAGE SCRIPTS -->
<script type="text/javascript">
    //(function(window, $) {
        // extra script if any
    //})(window, jQuery);
    //
<?php
$errorMessages = trans('label.fill_compulsory');
// if ($errors->any()) {
//     foreach ($errors->getMessages() as $this_error) {
//         $errorMessages .= '<p style="color: red;">'.$this_error[0].'</p>';
//     }
// }
?>
    @if (session()->has('first_login'))
        <?php session()->forget('first_login'); ?>
    @endif

    @if (session()->has('message') || session()->has('success') || session()->has('errors') || session()->has('status'))
    window.onloadNotif = {
        type: '{!! session()->has('success') || session()->has('status') ? 'good' : (session()->has('errors') ? 'bad' : 'bad')!!}',
        text: '{!! session()->has('message') ? session()->pull('message') : (session()->has('success') ? session()->pull('success') : (session()->has('status') ? session()->pull('status') : ($errors->any() ? ($errors->has('message') ? $errors->first('message') : $errorMessages) : 'Ops, terjadi kesalahan. Silakan cek kembali')))!!}'
    }
    if (window.onloadNotif.type == 'good') {
        swal({
            title: "Nice!",
            text: window.onloadNotif.text,
            type: "success",
            confirmButtonColor: "#ed6a10",
            html: true,
        });
    } else {
        swal({
            title: "Warning!",
            text: '<p style="color: red;">'+window.onloadNotif.text+'</p>',
            type: "error",
            confirmButtonColor: "#4d4d4d",
            html: true,
        }, function () {
            var error = $('.has-error').first();
            if (error.length) {
                var input = error.find('input, select').first();
                $('html,body').animate({
                    scrollTop: error.offset().top - 50},
                'slow', function () {
                    input.length && input[0].focus();
                });
            }
        });
    }
    @endif

    @if (session()->has('welcome_message'))
    swal({
      title: "{{session()->get('welcome_message.title')}}",
      text: "{{session()->get('welcome_message.text')}}",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#ed6a10",
      confirmButtonText: "{{trans('label.backend.yes_now')}}",
      cancelButtonText: "{{trans('label.backend.add_later')}}",
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm){
      if (isConfirm) {
        @if (session()->has('welcome_message.link'))
        window.location.href = "{{session()->get('welcome_message.link')}}";
        @else
        window.location.reload();
        @endif
      }
    });
    <?php session()->forget('welcome_message'); ?>
    @endif
</script>

    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/metronic/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/metronic/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
    <script src="/metronic/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->

</body>
</html>