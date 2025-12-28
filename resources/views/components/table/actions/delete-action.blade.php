@can($permission)
    <li>
        <a class="bs-tooltip delete" data-url="{{ $url }}" data-datatable-id="{{ $dataTableId }}" data-bs-toggle="tooltip" data-bs-placement="top"
           title="{{ __('general.common.delete') }}" data-bs-original-title="{{ __('general.common.delete') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-trash p-1 br-6 mb-1">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
        </a>
    </li>
@endcan
