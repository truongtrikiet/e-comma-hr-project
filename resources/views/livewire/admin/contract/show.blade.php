<form>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{__('general.common.contract_code')}}</th>
                    <th>{{__('general.common.name')}}</th>
                    <th>{{__('general.common.object')}}</th>
                    <th>{{__('general.common.status')}}</th>
                    <th>{{__('general.common.signed_at')}}</th>
                    <th>{{__('general.common.expired_at')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $contract->code }}</td>
                    <td>{{ $contract->contractType->name }}</td>
                    <td>{{ $contract->contractable->name }}</td>
                    <td><span class="ms-2 badge badge-{{ $contract->status->getBadge() }}">{{ __(Str::title(str_replace('_', ' ', $contract->status->name))) }}</span></td>
                    <td>{{ $contract->signed_at->format('d-m-Y') }}</td>
                    <td>{{ $contract->expired_at->format('d-m-Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12 col-12 text-end">
            <a
                href="javascript:void(0)"
                class="btn btn-secondary mt-2 mb-2 me-4"
                data-bs-toggle="modal"
                data-bs-target="#selectAppendixContractModal"
                onclick="openModalSelectAppendixContract(this)"
            >
                {{ __('general.common.show_detail') }}
            </a>
        </div>
    </div>

    <label for="appendix_contract">
        {{ __('general.menu.appendix_contract_management.title') }}
    </label>
    <div class="row ms-1 mb-4">
        * Danh sách phụ lục của hợp đồng: {{ $contract->contractType->name }}
    </div>
    <div class="row layout-top-spacing">
        <div id="contract-type-management" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header"></div>
                <div class="widget-content widget-content-area" style="padding: 20px;">
                    <div class="row">
                        @can(Acl::PERMISSION_CONTRACT_EDIT)
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <a wire:click = 'createAppendixContract'
                                    class="btn btn-block btn-outline-secondary mt-2 mb-2 me-4"
                                    data-bs-toggle="modal"
                                >
                                    {{ __('general.common.new') }}
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th>{{__('general.common.name')}}</th>
                                    <th>{{__('general.common.status')}}</th>
                                    <th width="20%" class="text-center">{{__('general.common.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appendixContracts as $item)
                                    <tr wire:key='{{ $item['id'] }}'>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $item->name ?? 'N/A' }}</td>
                                        <td><span class="ms-2 badge badge-light-{{ $item->status->getBadge() }}">{{ __(Str::title(str_replace('_', ' ', $item->status->name))) }}</span></td>
                                        <td class="text-center">
                                            <div class="action-btns">
                                                <a
                                                    href="javascript:void(0);"
                                                    class="action-btn bs-tooltip me-2 viewAppendixContract"
                                                    data-appendix-contract-id="{{ $item->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#showAppendixContractModal"
                                                    title="{{ __('general.common.show') }}"
                                                    data-bs-original-title="{{ __('general.common.show') }}"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a>
                                                @can(Acl::PERMISSION_CONTRACT_EDIT)
                                                    <a href="javascript:void(0);"
                                                        class="action-btn btn-edit btn-edit-phone bs-tooltip me-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#updatePhoneModal"
                                                        title="{{ __('general.common.edit') }}"
                                                        data-bs-original-title="{{ __('general.common.edit') }}"
                                                        wire:click="editAppendixContract({{ $item->id }})"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-edit-2">
                                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                        </svg>
                                                    </a>
                                                    <a
                                                        href="javascript:void(0);"
                                                        class="action-btn btn-delete bs-tooltip"
                                                        wire:click.prevent="confirmDeleteAppendixContract({{ $item->id }})"
                                                        title="{{ __('general.common.delete') }}"
                                                        data-bs-original-title="{{ __('general.common.delete') }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteItemModal"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-trash-2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">{{ __('Chưa có phụ lục hợp đồng nào') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-primary" wire:click.prevent='store'>
        {{ __('general.common.complete') }}
    </button>

    <div class="modal fade" wire:ignore.self id="deleteItemModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('general.selected_item.delete')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{__('general.selected_item.delete_ask_confirm')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">{{__('general.common.cancel')}}</button>
                    <button type="button" class="btn btn-primary" wire:click="destroyAppendixContract"> {{__('general.common.confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="appendixContracts" value="{{ json_encode($appendixContracts) }}">

    <div class="modal modal-xl fade" id="showAppendixContractModal" tabindex="-1" role="dialog" aria-labelledby="showAppendixContractModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showAppendixContractModalLabel">{{ __('general.common.content') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">{{__('general.common.cancel')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="selectAppendixContractModal" tabindex="-1" aria-labelledby="selectAppendixContractModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectAppendixContractModalLabel">{{ __('general.menu.appendix_contract_management.title') }}</h5>
                </div>
                <div class="modal-body pt-0 pb-0">
                    <div class="widget-content widget-content-area tags-content p-0" style="border: 0;">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-9 filtered-list-search mx-auto w-100" style="margin-bottom: 20px;">
                                <div class="mt-2 d-flex justify-content-start mt-4 mb-2">
                                    <label class="form-check-label" for="check-select-all">
                                        <input class="form-check-input" type="checkbox" id="check-select-all">
                                        {{ __('general.common.select_all') }}
                                    </label>
                                </div>
                                <div class="d-flex" style="padding: 0; margin: 0;">
                                    <select
                                        id="select-appendix-contracts"
                                        name="appendix-contracts[]"
                                        multiple
                                        placeholder="{{ __('Chọn phụ lục muốn hiển thị') }}..."
                                        autocomplete="off"
                                        style="width: 100%;"
                                    >
                                        @foreach($appendixContracts as $item)
                                        <option
                                            value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="searchable-container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="searchable-items" style="max-height: 350px; margin-bottom: 10px;">
                                                @forelse($appendixContracts as $item)
                                                    <div
                                                        class="items"
                                                        onclick="highlightObject(this)"
                                                        data-object-id="{{ $item->id }}"
                                                    >
                                                        <div class="row w-100">
                                                            <div
                                                                class="user-name col-5 d-flex align-items-center justify-content-left"
                                                            >
                                                                <p>{{ $item->name }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <h6>{{ __('Chưa có phụ lục hợp đồng nào.') }}</h6>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">{{__('general.common.cancel')}}</button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        onclick="viewContractDetail()"
                    >
                        {{ __('general.common.show_detail') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('footerFiles')
    <script>
        window.addEventListener('hide-delete-modal', event => {
            $('#deleteItemModal').modal('hide');
            Snackbar.show({
                text: "{{ __('success.appendix_contract.delete') }}",
                textColor: '#ddf5f0',
                backgroundColor: '#00ab55',
                actionText: '{{ __('general.common.dismiss') }}',
                actionTextColor: '#3b3f5c'
            });
        });

        $(document).on('click', '.viewAppendixContract', function(e) {
            e.preventDefault();
            $('#showAppendixContractModal .modal-body').html('');

            let appendixContractId = $(this).data('appendix-contract-id');

            let appendixContracts = JSON.parse($('#appendixContracts').val());

            let appendixContract = appendixContracts.find(item => item.id == appendixContractId);

            if(!appendixContract) {
                appendixContractHtml = `
                <h6 class="text-black">
                    {{ __('general.common.no_domain_account') }}
                </h6>`;
            } else {
                appendixContractHtml = appendixContract.content;
            }

            $('#showAppendixContractModal .modal-body').html(appendixContractHtml);
        });

        let tomSelectAppendixContracts = new TomSelect('#select-appendix-contracts', {
            render: {
                no_results: function(data, escape) {
                    return '<div class="no-results">{{ __('general.common.no_results_found') }}</div>';
                }
            },
        });

        let checkSelectAll = document.querySelector("#check-select-all");

        const openModalSelectAppendixContract = (element) => {
            tomSelectAppendixContracts.clear();

            checkSelectAll.checked = false;

            removeAllHighlight();
        }

        const removeAllHighlight = () => {
            let items = document.querySelectorAll(`#selectAppendixContractModal .searchable-items .items`);
            items.forEach(item => {
                item.classList.remove("active");
            });
        }

        const addAllHighlight = () => {
            let items = document.querySelectorAll(`#selectAppendixContractModal .searchable-items .items`);
            items.forEach(item => {
                item.classList.add("active");
            });
        }

        const handleCheckSelectAll = (checked, tomSelect) => {
            if(checked){
                addAllHighlight();
                let items = document.querySelectorAll(`#selectAppendixContractModal .searchable-items .items.active`);
                items.forEach(item => {
                    tomSelect.addItem(item.getAttribute('data-object-id'));
                });
            }
            else {
                tomSelect.clear();
                removeAllHighlight();
            }
        }

        checkSelectAll.onchange = () => {
            handleCheckSelectAll(checkSelectAll.checked, tomSelectAppendixContracts);
        };

        const highlightObject = (element) => {
            let checkActive = element.classList.contains('active');
            let objectId = element.getAttribute('data-object-id');

            if(checkActive) {
                element.classList.remove('active')
                tomSelectAppendixContracts.removeItem(objectId);
            }
            else {
                element.classList.add('active');
                tomSelectAppendixContracts.addItem(objectId);
            }
        }

        const handleChangeTomSelect = (tomSelect) => {
            let selectedValues = tomSelect.getValue();
            let items = document.querySelectorAll(`#selectAppendixContractModal .searchable-items .items`);

            items.forEach(item => {
                let objectId = item.getAttribute('data-object-id');

                if(selectedValues.includes(objectId)){
                    item.classList.add("active");
                }
                else {
                    item.classList.remove("active");
                }
            });
        }

        function debounce(func, timeout = 300){
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }

        const processChange = debounce((tomSelect) => {
            handleChangeTomSelect(tomSelect);
        });

        tomSelectAppendixContracts.on("change", function() {
            processChange(tomSelectAppendixContracts);
        });

        const viewContractDetail = () => {
            let appendixContracts = tomSelectAppendixContracts.getValue();

            let appendixContractsString = JSON.stringify(appendixContracts);

            let encodedAppendixContracts = encodeURIComponent(appendixContractsString);

            window.open(`{{ route('admin.contractDetail', ['contract' => $contract]) }}?appendixContracts=${encodedAppendixContracts}`, '_blank');
        }
    </script>
@endpush



