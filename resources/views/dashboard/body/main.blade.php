<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>AfanJaya</title>

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="stylesheet" href="{{ asset('assets/css/backend-plugin.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/backend.css?v=1.0.0') }}">

        <link rel="stylesheet" href="{{ asset('assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/fonts/remixicon.css') }}">

        @yield('specificpagestyles')

        <!-- Custom CSS -->
        <style>
            .hover-popup {
                position: absolute;
                left: 100%;
                top: 50%;
                transform: translateY(-50%);
                background-color: #333;
                color: #ea7a7a;
                padding: 5px 10px;
                border-radius: 3px;
                white-space: nowrap;
                z-index: 1000;
                transition: opacity 0.3s ease;
                opacity: 0;
                display: none;
            }

            /* Hanya menu utama yang memiliki hover */
            .iq-sidebar-menu .iq-menu > li:hover > a .hover-popup,
            .iq-sidebar-menu .iq-menu > li:focus > a .hover-popup {
                display: block;
                opacity: 1;
            }

            /* Hanya menu utama yang memiliki latar belakang dan warna teks saat dihover atau difokus */
            .iq-sidebar-menu .iq-menu > li:hover > a,
            .iq-sidebar-menu .iq-menu > li:focus > a {
                background-color: #ea7a7a;
                color: #333;
                border-radius: 5px;
            }

            /* Menghilangkan efek hover pada sub-menu */
            .iq-submenu > li > a {
                background-color: transparent;
                color: inherit;
            }

            .iq-submenu > li > a:hover,
            .iq-submenu > li > a:focus {
                background-color: transparent;
                color: inherit;
            }
        </style>




    </head>
<body>
    <!-- loader Start -->
    {{-- <div id="loading">
        <div id="loading-center"></div>
    </div> --}}
    <!-- loader END -->

    <!-- Wrapper Start -->
    <div class="wrapper">
        @include('dashboard.body.sidebar')

        @include('dashboard.body.navbar')

        <div class="content-page">
            @yield('container')
        </div>
    </div>
    <!-- Wrapper End-->

    @include('dashboard.body.footer')

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('assets/js/backend-bundle.min.js') }}"></script>
    <script src="https://kit.fontawesome.com/4c897dc313.js" crossorigin="anonymous"></script>

    @yield('specificpagescripts')

    <!-- App JavaScript -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- jQuery untuk efek hover -->
    <script>
        $(document).ready(function() {
            $('.svg-icon').hover(
                function() {
                    $(this).find('.hover-popup').fadeIn(200);
                },
                function() {
                    $(this).find('.hover-popup').fadeOut(200);
                }
            );
        });
    </script>
</body>
</html>
