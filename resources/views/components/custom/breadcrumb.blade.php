@php
    $items = $breadcrumbItems ?? [];
    $lastKey = array_key_last($items);
@endphp
<div class="row page-titles mx-0 mb-3">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>{{ $title ?? 'Hi, welcome back!' }}</h4>
            @if (!empty($subtitle))
                <span class="ml-1">{{ $subtitle }}</span>
            @endif
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            @foreach ($items as $label => $url)
                <li class="breadcrumb-item{{ $loop->last ? ' active' : '' }}">
                    @if ($url && !$loop->last)
                        <a href="{{ $url }}">{{ $label }}</a>
                    @else
                        <span>{{ $label }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</div>
