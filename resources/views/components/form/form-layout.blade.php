<div
    @class([
       'col-lg-6 col-xxl-12' => !($customCol),
       $customCol
    ])
>
    <div class="card">
        <div class="card-header">
            {{ $cardTitle ?? '' }}
        </div>
        <div class="card-body">
            <form id="{{ $formId }}" {{ $attributes }} method="POST" action="{{ $formUrl }}">
                @csrf
                @method($formMethod)

                {{ $slot }}
            </form>
        </div>
    </div>
</div>
