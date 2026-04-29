<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.candidate_screening_management.manage_candidate_screening') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.candidate_screening_management.manage_candidate_screening') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.candidate_screening_management.manage_candidate_screening') }}
        </x-slot:boxTitle>
        <div></div>

        <div class="d-flex gap-2">
            @can(Acl::PERMISSION_CANDIDATE_SCREENING_ADD)
                <button id="candidate-scan-open-btn" class="btn btn-primary">{{ __('Scan') }}</button>
            @endcan

            @can(Acl::PERMISSION_CANDIDATE_SCREENING_DELETE)
                <button id="candidate-delete-open-btn" class="btn btn-outline-danger">{{ __('Delete') }}</button>
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'candidate-screenings-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sCandidateScreeningsTable'"
            :title="__('Candidate Screening List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.school') }}</th>
                    <th>{{ __('general.common.candidate') }}</th>
                    <th>{{ __('general.common.position') }}</th>
                    <th>{{ __('general.common.emailed_at') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.candidate-screening.index') }}",
                    "data": function (d) {
                        d.limit = d.length;
                        d.page = d.start / d.length + 1;
                    }
                },
                "columns": [
                    {
                        "data": "id",
                        "class": "text-center",
                        "orderable": true
                    },
                    {
                        "data": "school",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${data?.name ?? 'N/A'}</span>`;
                        }
                    },
                    { 
                        "data": "candidate_name",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            const name = full.candidate_name ?? 'N/A';
                            const email = full.candidate_email ?? 'N/A';
                            const phone = full.candidate_phone_number ?? 'N/A';
                            return `<div>${name}</div><div class="text-muted small">${email}${email && phone ? ' • ' : ''}${phone}</div>`;
                        }
                    },
                    {
                        "data": "position_type",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            const label = full.position_type_name ?? (full.position_type ?? 'N/A');
                            return `<span class="badge badge-light">${label}</span>`;
                        }
                    },
                    {
                        "data": "emailed_at",
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "status",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.status_badge}">${full.status_name}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            let urlShow = `{{ route('admin.candidate-screening.show', ':id') }}`.replace(':id', data);
                            let urlEdit = `{{ route('admin.candidate-screening.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.candidate-screening.destroy', ':id') }}`.replace(':id', data);
                            let urlSend = `{{ route('admin.candidate-screening.send-result-email', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.send-action
                                        :permission="Acl::PERMISSION_CANDIDATE_SCREENING_VIEW"
                                        :url="'${urlSend}'"
                                        :dataTableId="'sCandidateScreeningsTable'"
                                    />
                                    <x-table.actions.show-action
                                        :permission="Acl::PERMISSION_CANDIDATE_SCREENING_VIEW"
                                        :url="'${urlShow}'"
                                    />
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_CANDIDATE_SCREENING_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_CANDIDATE_SCREENING_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sCandidateScreeningsTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

<!-- Scan modal -->
    <div class="modal fade" id="candidateScanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                        <h5 class="modal-title">{{ __('Scan Resumes') }}</h5>
                        <button type="button"
                            class="btn btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">X</button>
                </div>

                <div class="modal-body">
                    <form id="candidate-scan-form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <x-form.form-select
                                :id="'sAIProfileSelect'"
                                :label="__('general.common.ai_profile')"
                                :data-values="$aiProfiles"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'ai_profile_id'"
                                :multiple="false"
                                :placeholder="__('general.common.ai_profile')"
                                :isRequired="true"
                            />
                        </div>

                        <div class="mb-3">
                            <x-form.form-select
                                :id="'sPositionTypeSelect'"
                                :label="__('general.common.position_type')"
                                :data-values="$positionTypes"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :name="'position_type'"
                                :multiple="false"
                                :placeholder="__('general.common.position_type')"
                                :isRequired="true"
                            />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Upload Resumes') }}</label>
                            <input type="file"
                                name="files[]"
                                id="scan-files-input"
                                class="form-control"
                                multiple
                                accept=".pdf,.doc,.docx,.txt"
                                required>
                            <div class="form-text">
                                {{ __('Each file will create one screening entry.') }}
                            </div>
                        </div>
                    </form>

                    <pre id="candidate-scan-result"
                        class="mt-3"
                        style="display:none; white-space:pre-wrap;"></pre>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        {{ __('general.common.cancel') }}
                    </button>

                    <button type="button"
                            id="candidate-scan-submit"
                            class="btn btn-primary">
                        {{ __('Import') }}
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="candidateDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete Candidate Screenings') }}</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <x-form.form-select
                            :id="'candidate-delete-status'"
                            :label="__('general.common.status')"
                            :data-values="$statuses"
                            :select-value-attribute="'value'"
                            :select-value-label="'label'"
                            :name="'status'"
                            :multiple="false"
                            :placeholder="__('general.common.choose')"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        {{ __('general.common.cancel') }}
                    </button>
                    <button type="button" id="candidate-delete-confirm" class="btn btn-danger">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="interviewEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Interview Invitation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="interviewEmailForm">
                        <input type="hidden" id="interviewSendUrl">

                        <div class="mb-3">
                            <label class="form-label">Interview Time</label>
                            <input type="datetime-local" class="form-control" id="interviewTime" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Interview Location</label>
                            <input type="text" class="form-control" id="interviewLocation" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note (optional)</label>
                            <textarea class="form-control" id="interviewNote" rows="3"></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmSendInterviewEmail">
                        Send Email
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalEl   = document.getElementById('candidateScanModal');
            if (!modalEl) return;

            const modal = new bootstrap.Modal(modalEl);
            modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    modal.hide();
                });
            });
            const form = document.getElementById('candidate-scan-form');
            const submitBtn = document.getElementById('candidate-scan-submit');
            const resultBox = document.getElementById('candidate-scan-result');

            const positionSelect = document.getElementById('sPositionTypeSelect');
            const fileInput = document.getElementById('scan-files-input');

            document.getElementById('candidate-scan-open-btn')
                ?.addEventListener('click', () => modal.show());

            modalEl.addEventListener('hidden.bs.modal', () => {
                form.reset();
                resultBox.style.display = 'none';
                resultBox.textContent = '';
                submitBtn.disabled = false;
                submitBtn.textContent = '{{ __('Import') }}';
            });

            submitBtn.addEventListener('click', async () => {
                if (!positionSelect?.value) {
                    alert('Please select a position type');
                    return;
                }

                if (!fileInput.files.length) {
                    alert('Please select at least one resume file');
                    return;
                }

                if (fileInput.files.length > 5) {
                    if (!confirm(`You are about to scan ${fileInput.files.length} CVs. Continue?`)) {
                        return;
                    }
                }

                const formData = new FormData(form);
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');

                submitBtn.disabled = true;
                submitBtn.textContent = '{{ __('Importing...') }}';

                try {
                    const response = await fetch(`{{ route('admin.candidate-screening.scan') }}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        body: formData,
                    });

                    const data = await response.json();

                    resultBox.style.display = 'block';
                    resultBox.textContent = JSON.stringify(data, null, 2);

                    if (data.success && window.LaravelDataTables?.sCandidateScreeningsTable) {
                        window.LaravelDataTables.sCandidateScreeningsTable.ajax.reload();
                    }

                } catch (error) {
                    resultBox.style.display = 'block';
                    resultBox.textContent = error?.message || String(error);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = '{{ __('Import') }}';
                }
            });

            // Delete modal logic
            const deleteModalEl = document.getElementById('candidateDeleteModal');
            const deleteBtn = document.getElementById('candidate-delete-open-btn');
            const deleteConfirmBtn = document.getElementById('candidate-delete-confirm');
            const deleteStatusSelect = document.getElementById('candidate-delete-status');
            const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

            if (deleteModalEl) {
                deleteModalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        deleteModal.hide();
                    });
                });
            }

            deleteBtn?.addEventListener('click', () => {
                deleteModal?.show();
            });

            deleteConfirmBtn?.addEventListener('click', async () => {
                const status = deleteStatusSelect?.value;
                if (!confirm(`Are you sure you want to delete all screenings?`)) {
                    return;
                }

                deleteConfirmBtn.disabled = true;
                deleteConfirmBtn.textContent = '{{ __('Deleting...') }}';

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    const res = await fetch("{{ route('admin.candidate-screening.delete-by-status') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ status })
                    });

                    const data = await res.json();

                    if (data.success) {
                        deleteModal?.hide();
                        if (window.LaravelDataTables?.sCandidateScreeningsTable) {
                            window.LaravelDataTables.sCandidateScreeningsTable.ajax.reload();
                        } else {
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'Delete failed');
                    }
                } catch (err) {
                    alert(err?.message || String(err));
                } finally {
                    deleteConfirmBtn.disabled = false;
                    deleteConfirmBtn.textContent = '{{ __('Delete') }}';
                }
            });

            // Send interview email logic
            let activeButton = null;
            let activeDatatableId = null;
            let activeModal = null;

            document.addEventListener('click', function (ev) {
                const btn = ev.target.closest('.bs-tooltip.send');
                if (!btn) return;

                ev.preventDefault();

                activeButton = btn;
                activeDatatableId = btn.getAttribute('data-datatable-id');

                const url = btn.getAttribute('data-url');
                document.getElementById('interviewSendUrl').value = url;

                document.getElementById('interviewEmailForm').reset();

                const modalEl = document.getElementById('interviewEmailModal');
                const modal = new bootstrap.Modal(modalEl);
                activeModal = modal;
                modalEl.addEventListener('hidden.bs.modal', function () {
                    activeModal = null;
                }, { once: true });

                modal.show();
            });

            // Confirm send
            document.getElementById('confirmSendInterviewEmail')
                .addEventListener('click', async function () {

                    const url = document.getElementById('interviewSendUrl').value;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    const interviewTime = document.getElementById('interviewTime').value;
                    const interviewLocation = document.getElementById('interviewLocation').value;
                    const interviewNote = document.getElementById('interviewNote').value;

                    if (!interviewTime || !interviewLocation) {
                        alert('Please fill interview time and location');
                        return;
                    }

                    this.disabled = true;

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                interview_time: interviewTime,
                                interview_location: interviewLocation,
                                interview_note: interviewNote,
                            }),
                        });

                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || 'Send failed');
                        }

                        if (activeModal && typeof activeModal.hide === 'function') {
                            activeModal.hide();
                            activeModal = null;
                        } else if (bootstrap.Modal && typeof bootstrap.Modal.getInstance === 'function') {
                            const inst = bootstrap.Modal.getInstance(document.getElementById('interviewEmailModal'));
                            inst && inst.hide();
                        }

                        if (window.LaravelDataTables && window.LaravelDataTables[activeDatatableId]) {
                            window.LaravelDataTables[activeDatatableId].ajax.reload();
                        }

                        alert(data.message || 'Email sent successfully');

                    } catch (err) {
                        alert(err.message || err);
                    } finally {
                        this.disabled = false;
                    }
                });
        });
    </script>

</x-base-layout>
