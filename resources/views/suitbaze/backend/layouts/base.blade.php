<!doctype html>
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head id="Head1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Talentsaga">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    {{-- csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link type="image/png" rel="apple-touch-icon" href="{{ Theme::url('backend/img/apple-icon.png') }}" />
    <link type="image/png" rel="icon" href="{{ Theme::url('backend/img/favicon.png') }}" />

    <title>{{ $pageTitle or "Page Title" }} | Talentsaga Admin Backend</title>
    <link rel="stylesheet" href="{{ Theme::url('backend/css/main.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ Theme::url('backend/css/custom.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ Theme::url('backend/css/vendor/jquery.growl.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ Theme::url('backend/css/vendor/dataTables.scroller.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ Theme::url('backend/css/vendor/responsive.dataTables.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ Theme::url('backend/css/vendor/jquery-ui/jquery.ui.1.10.0.ie.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ Theme::url('backend/css/vendor/jquery-ui/jquery-ui-1.10.0.custom.css') }}" type="text/css" />

    <style type="text/css">
        .highlight {
            background-color: #d2f7c3;
        }
        .DTFC_LeftHeadWrapper {
            height: 42px;
        }
        .DTFC_RightHeadWrapper {
            height: 42px;
        }
    </style>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/modernizr.min.js') }}"></script>

    @yield('style-head')
    @yield('script-head')
</head>
<body>
    <!--[if lt IE 9]>
        <p class="browsehappy" style="position: absolute; z-index: 1000; padding: 5px; width: 100%;">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <script>alert("You are using Internet Explorer lower than version 9. Please upgrade your browser to improve your experience.");</script>
    <![endif]-->

    <header class="main-header">
        <div class="container">
            <div class="main-header-sections">
                <div class="main-header-sections__section">
                    <img class="logo" src="{{ Theme::url('backend/img/logo.png') }}" alt="">

                    <button class="nav-trigger" id="primaryNavTrigger" data-nav-trigger>
                        <span class="nav-trigger__icon fa"></span>
                    </button>
                </div> <!-- /.bzg_c -->
                <div class="main-header-sections__section main-header-sections__section--right">
                    <div class="user-area text-right">
                        @if (Auth::check())
                            <a class="user-area-anchor" id="userAreaAnchor" href="#">
                                <span class="fa fa-fw fa-user"></span>
                                <span>Hi, {{Auth::user()->username}}</span>
                                <span class="fa fa-fw fa-caret-down"></span>
                            </a>

                            <nav class="user-area-menu" id="userAreaMenu">
                                <ul class="user-area-nav-list list-nostyle">
                                    @if (Auth::user()->role == 'admin')
                                        <li>
                                            <a href="{{route('backend.home.index')}}" target="_blank">
                                                Admin Dashboard
                                                <span class="fa fa-fw fa-globe"></span>
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{route('frontend.home')}}" target="_blank">
                                            Frontend Page
                                            <span class="fa fa-fw fa-globe"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('sessions.logout')}}">
                                            Sign Out
                                            <span class="fa fa-fw fa-sign-out"></span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        @else
                            <a class="user-area-anchor" id="userAreaAnchor-Guest" href="#">
                                <span class="fa fa-fw fa-user"></span>
                                <span>Hi, Guest</span>
                                <span class="fa fa-fw fa-caret-down"></span>
                            </a>
                        @endif
                    </div>
                </div> <!-- /.bzg_c -->
            </div>
        </div> <!-- /.container -->
    </header> <!-- /.main-header -->
    <div class="content-wrapper">
        <nav class="nav" id="nav">
            <div class="text-right">
                <button class="nav-lock-btn btn btn--link" type="button">
                    <span class="nav-lock-btn__icon fa fa-fw fa-unlock"></span>
                </button>
            </div>

            <div class="bzg_c">
                <div class="nav-calendar">
                    {{ date('D, d M Y') }}
                </div>
            </div>

            <!-- Using data in AppConfig Model. -->
            <ul class="nav-list nav-list--main list-nostyle">
                @foreach(\App\Config\BaseConfig::$data['pageId'] as $key => $value)
                    <li class="nav-list__item">
                    <a class="nav-anchor {{ sizeof($value['submenu']) > 0 ? 'nav-anchor--has-sub' : '' }} {{ $pageId[0] == $key ? "is-active" : "" }}" href="{{ sizeof($value['submenu']) > 0 ? '#' : route($value['route']) }}">
                        <span class="fa fa-fw {{ $value['icon'] }}"></span>
                        <span>{{ $value['label'] }}</span>
                    </a>
                    @if (sizeof($value['submenu']) > 0)
                    <ul class="nav-list nav-list--sub list-nostyle">
                        @foreach ($value['submenu'] as $submenuKey => $submenuValue)
                            <li class="nav-list__item">
                                <a class="nav-anchor {{ $pageId == $submenuKey ? "is-active" : "" }}" href="{{ route($submenuValue['route']) }}">
                                    {{ $submenuValue['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </nav>

        <main class="main" id="main">
            <div class="container">
                @yield('featured-content')
                @yield('content')
            </div> <!-- /.container -->
            <button class="scroll-to-top" data-scroll-to-top>
                <span class="fa fa-fw fa-arrow-circle-up"></span>
            </button>
        </main>
        <footer class="main-footer">
            <div class="container">
                <small class="text-muted">Copyright &copy; 2016 Suitmedia, All Rights Reserved.</small>
            </div>
        </footer>
    </div> <!-- /.content-wrapper -->

    <div class="modal">
        <div class="modal-dialog">
            <button class="modal-dialog-close-btn" onclick="return false">
                <span class="fa fa-fw fa-times"></span>
            </button>
            <header class="modal-dialog-header">
                <h3 class="modal-title"></h3>
            </header>
            <div class="modal-dialog-content"></div>
        </div>
    </div>

    <script type="text/javascript">            window.myPrefix = '';</script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.growl.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/highlight.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.dataTables.searchHighlight.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.dataTables.scroller.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.datatables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/highcharts.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/highcharts.funnel.js')}}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/highcharts.exporting.js')}}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.tooltip.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/sprintf.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/tabby.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.inputmask.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.inputmask.numeric.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/vanilla.masker.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/fastclick.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/baze.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/jquery.circliful.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/rome.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/ckeditor/adapters/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/autosize.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/vendor/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/helpers.min.js') }}"></script>
    <script type="text/javascript" src="{{ Theme::url('backend/js/main.min.js') }}"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            $('.readonly').keydown(function (e) {
                e.preventDefault();
            });
        });
    </script>
    <script type="text/javascript">
    <?php
        $generalNotification = null;
        $generalNotification = session()->get('webNotification');
        if ($generalNotification != null && is_array($generalNotification) && count($generalNotification) > 0)
        {
            foreach($generalNotification as $notification) {
                if (is_array($notification) && isset($notification['type']) && isset($notification['title']) && isset($notification['message'])) {
                    if ($notification['type'] == App\SuitCommerce\Controllers\BackendController::NOTICE_NOTIFICATION) {
                        ?>
                            $.growl.notice({ title: "{{ $notification['title'] }}", message: "{{ $notification['message'] }}" });
                        <?php
                    } else if ($notification['type'] == App\SuitCommerce\Controllers\BackendController::WARNING_NOTIFICATION) {
                        ?>
                            $.growl.warning({ title: "{{ $notification['title'] }}", message: "{{ $notification['message'] }}" });
                        <?php
                    } else if ($notification['type'] == App\SuitCommerce\Controllers\BackendController::ERROR_NOTIFICATION) {
                        ?>
                            $.growl.error({ title: "{{ $notification['title'] }}", message: "{{ $notification['message'] }}" });
                        <?php
                    } else {
                        ?>
                            $.growl({ title: "{{ $notification['title'] }}", message: "{{ $notification['message'] }}" });
                        <?php
                    }
                }
            }
            session()->put('webNotification', []); 
        }   
    ?>
    </script>

    @yield('page_script')

</body>
</html>
