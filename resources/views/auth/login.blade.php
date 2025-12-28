<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name') }} | {{ 'Login' }}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('images/acomma-logo/logo-3.png') }}"/>
    @php use App\Models\Setting; @endphp

    @vite([
        'resources/scss/_preloader.scss',
        'resources/css/style.css'
    ])

    <style>
        #load_screen {
            display: none;
        }
        .auth-container {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.2);
        }
        .auth-cover-bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .bg-media {
            object-fit: cover;
            width: 100%;
            height: 100%;
            border-radius: inherit;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .auth-overlay {
            z-index: 2;
            padding: 20px;
            max-width: 450px;
            width: 100%;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
        }
        .auth-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 767px) {
            .auth-container {
                padding: 20px;
            }
            .auth-overlay {
                width: 100%;
                max-width: 100%;
                padding: 15px;
            }
            .auth-card {
                border-radius: 8px;
            }
        }
        .authincation {
            position: relative;
            z-index: 1;
        }
    </style>

</head>

<body class="h-100">
    <div class="auth-cover-bg-image">
        @if ($bannerSetting && $bannerSetting->banner_url)
            @php
                $media = $bannerSetting->getFirstMedia(Setting::BANNER_URL_COLLECTION);
            @endphp
            @if ($media)
                @if (Str::contains($media->mime_type, 'image'))
                    <img src="{{ $media->getUrl() }}" alt="Banner Background" class="bg-media">
                @elseif (Str::contains($media->mime_type, 'video'))
                    <video class="bg-media" autoplay muted loop>
                        <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                    </video>
                @endif
            @endif
        @endif
    </div>
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form method="POST" action="{{ route('auth.login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control" name="email">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <div class="form-check ml-2">
                                                    <input class="form-check-input" type="checkbox" id="basic_checkbox_1">
                                                    <label class="form-check-label" for="basic_checkbox_1">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="{{ route('auth.reset.show-form') }}">Forgot Password?</a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
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

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('plugins/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/quixnav-init.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>

</body>

</html>