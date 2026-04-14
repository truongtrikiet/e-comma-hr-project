<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('general.menu.contract_management.title') }}</title>
    <style>
        @page {
            margin-top: 100px;
            margin-bottom: 100px;
        }

        * {
            font-family: 'DejaVu Sans', sans-serif !important;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .card {
            max-width: 800px;
            margin: 0 auto;
            background-color: transparent;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .appendix {
            margin-top: 30px;
            page-break-before: always;
        }

        .card-body {
            background-color: transparent !important;
        }

        header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            background-color: transparent;
        }

        .watermark {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: -1;
            background: url('{{ $contractWatermark?->image }}') no-repeat center center;
            background-size: contain;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div class="watermark"></div>

    <header>
        <h2>{!! $contractHeader?->value !!}</h2>
    </header>

    <div class="card">
        <div class="card-body">
            {!! $contractTypeContent !!}
            @foreach($appendixContracts as $index => $appendix)
                <div class="appendix">
                    <h3>Phụ lục {{ $index + 1 }}</h3>
                    <div class="appendix-content">
                        {!! $appendix->content !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
