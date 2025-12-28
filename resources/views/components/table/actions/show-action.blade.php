@can($permission)
    <li>
        <a class="bs-tooltip show" data-url="{{ $url }}" data-datatable-id="{{ $dataTableId }}" data-bs-toggle="tooltip" data-bs-placement="top"
           title="{{ __('general.common.show_detail') }}" data-bs-original-title="{{ __('general.common.show_detail') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-eye p-1 br-6 mb-1">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        </a>
    </li>
@endcan
