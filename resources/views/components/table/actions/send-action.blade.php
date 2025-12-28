@can($permission)
    <li>
        <a class="bs-tooltip send" data-url="{{ $url }}" data-datatable-id="{{ $dataTableId }}" data-bs-toggle="tooltip" data-bs-placement="top"
           title="{{ __('general.common.send') }}" data-bs-original-title="{{ __('general.common.send') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-send p-1 br-6 mb-1">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </a>
    </li>
@endcan
