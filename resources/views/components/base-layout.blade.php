<!doctype html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>{{ config('app.name') }} | {{ $pageTitle }}</title>
        <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/acomma-logo/logo-3.png') }}"/>
        <link rel="stylesheet" href="{{ asset('vendor/owl-carousel/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/owl-carousel/css/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/owl-carousel/css/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/jqvmap/css/jqvmap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css.map') }}">

        @vite([
            'resources/scss/_preloader.scss',
            'resources/css/style.css'
        ])
    </head>
    <body>
        <!-- BEGIN LOADER -->
            <x-layout-loader />
        <!--  END LOADER -->
        <div id="main-wrapper">

            <!--  BEGIN NAVBAR  -->
                <x-navbar.style-vertical-menu />
            <!--  END NAVBAR  -->

            <!--  BEGIN NAVBAR  -->
                <x-menu.vertical-menu />
            <!--  END NAVBAR  -->

            <!--**********************************
                Content body start
            ***********************************-->
            <div class="content-body">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </div>
            <!--**********************************
                Content body end
            ***********************************-->

            <!-- BEGIN FOOTER -->
                <x-layout-footer />
            <!-- END FOOTER -->
        </div>

        <script src="{{ asset('vendor/global/global.min.js') }}"></script>
        <script src="{{ asset('js/quixnav-init.js') }}"></script>
        <script src="{{ asset('js/custom.min.js') }}"></script>

        @stack('scripts')
    </body>
</html>
