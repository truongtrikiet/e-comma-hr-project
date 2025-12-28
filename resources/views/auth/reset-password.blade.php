<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('images/acomma-logo/logo-3.png') }}"/>
    <title>{{ config('app.name') }} | {{ 'Reset Password' }}</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">

    <!-- Styles -->
    @vite([
        'public/assets/css/lib/font-awesome.min.css',
        'public/assets/css/lib/themify-icons.css',
        'public/assets/css/lib/bootstrap.min.css',
        'public/assets/css/lib/helper.css',
        'public/assets/css/style.css',
    ])
    @php use App\Models\Setting; @endphp
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

<body class="bg-primary">
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
    <div class="unix-login">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="{{ route('auth.login.show-form') }}"><span>{{ 'Reset Password' }}</span></a>
                        </div>
                        <div class="login-form">
                            <!-- <h4>Reset Password</h4> -->
                            <form action="{{ route('auth.password.reset') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email">
                                </div>
                                <button type="submit" class="btn btn-primary btn-flat m-b-15">Submit</button>
                                <div class="register-link text-center">
                                    <p>Back to <a href="{{ route('auth.login.show-form') }}"> Home</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>