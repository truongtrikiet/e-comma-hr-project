<div
    @class([
        'col-lg-6 col-xxl-12' => empty($customCol),
        $customCol
    ])
>
    <div class="card">
        @if(!empty($cardTitle))
            <div class="card-header">
                {{ $cardTitle }}
            </div>
        @endif

        <div class="card-body">
            <form
                id="{{ $formId ?? '' }}"
                method="POST"
                action="{{ $formUrl }}"
                {{ $attributes }}
            >
                @csrf

                @if(!empty($formMethod) && strtoupper($formMethod) !== 'POST')
                    @method($formMethod)
                @endif

                {{ $slot }}
            </form>
        </div>
    </div>
</div>
