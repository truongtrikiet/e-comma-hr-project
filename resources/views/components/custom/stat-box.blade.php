<div class="row layout-top-spacing">
    <div id="{{ $boxId }}"
        @class([
            'col-lg-6 col-xxl-12' => empty($customCol),
            $customCol,
            'layout-spacing',
        ])
    >
        <div class="card">
            @if(!empty($boxTitle))
                <div class="card-header">
                    {{ $boxTitle }}
                </div>
            @endif

            @php
                $bodyClass = isset($box_of_datatable) && $box_of_datatable ? 'card-body p-0' : 'card-body';
            @endphp

            <div class="{{ $bodyClass }}">
                @if(isset($action))
                    <div class="mb-2">{!! $action !!}</div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</div>
