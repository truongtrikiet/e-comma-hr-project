@can($permission)
    <li>
        <a href="{{ $url }}" role="button" target="_blank" rel="noopener noreferrer"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="{{ __('general.common.download_pdf') }}"
            data-bs-original-title="{{ __('general.common.download_pdf') }}"
            {{ $attributes->merge(['class' => 'bs-tooltip']) }}
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="24" height="24"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 class="feather feather-download p-1 br-6 mb-1">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
        </a>
    </li>
@endcan
