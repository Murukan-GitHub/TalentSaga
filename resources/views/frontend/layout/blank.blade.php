<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="description" content="Talentsaga - Connecting Talents">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <meta property="og:url" content="{{ request()->url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{settings('brandname')}}">
    <meta property="og:title" content="{{ $title or 'Talentsaga - Connecting Talents' }}" />
    <meta property="og:description" content="{{ $description or 'Talentsaga - Connecting Talents' }}" />
    <meta property="og:image" content="{{ $pageImage = isset($pageImage) ? $pageImage : asset('frontend/assets/img/apple-icon.png') }}" />
    <meta property="og:image:secure_url" content="{{ $pageImage = isset($pageImage) ? $pageImage : asset('frontend/assets/img/apple-icon.png') }}" />
    <meta property="fb:app_id" content="{{ env('FB_CLIENT_ID', '') }}">

    <meta name="twitter:card" content="{{ $pageImage ? 'summary' : 'summary'}}">
    <meta name="twitter:site" content="{{ request()->url() }}">
    <meta name="twitter:title" content="{{ $title or 'Talentsaga - Connecting Talents' }}">
    <meta name="twitter:description" content="{{ $description or 'Talentsaga - Connecting Talents' }}">
    <meta name="twitter:image" content="{{ $pageImage = isset($pageImage) ? $pageImage : asset('frontend/assets/img/apple-icon.png') }}">  

    <link rel="apple-touch-icon" href="{{ asset('frontend/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/img/favicon.png') }}">

    <title>{{ isset($pageTitle) && !empty($pageTitle) ? ($pageTitle . " | ") : "" }}Talentsaga - Connecting Talents</title>

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">
    <script src="{{ asset('frontend/assets/js/vendor/modernizr.min.js') }}"></script>
</head>
<body>
    <!--[if lt IE 9]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->


    @yield('content')
    

    <div class="modal">
        <div class="modal-dialog">
            <button class="modal-dialog-close">&times;</button>
            <div class="modal-dialog-content"></div>
        </div>
    </div>

    <script>window.myPrefix = '{{ asset('frontend').'/' }}';</script>
    <script src="{{ asset('frontend/assets/js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendor/angular.min.js') }}"></script>
    <script src="https://cdn.polyfill.io/v2/polyfill.js?features=default,promise,fetch"></script>
    <script src="{{ asset('frontend/assets/js/ts-notification.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.min.js') }}"></script>

    <script>
    @if (session()->has('message') || session()->has('success') || session()->has('error'))
        @if( $type = (session()->has('success') ? 'success' : (session()->has('error') ? 'error' : 'warning') ) )
        tsNotif.show("{{session()->has('message') ? session()->pull('message') : (session()->has('success') ? session()->pull('success') : (session()->has('error') ? session()->pull('error') : 'Oops, something went wront, please try again!'))}}", "{{ $type }}")
        @endif
    @endif
    </script>

    <!-- GOOGLE ANALYTICS -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', '{{ env('GA_TRACKING_ID', 'UA-79788319-1') }}', 'auto');
        ga('send', 'pageview');
    </script>

    @stack('js-script')
</body>
</html>
