@push('headerFiles')
    <style>
        .flatpickr-calendar .flatpickr-monthDropdown-months {
            display: inline-block;
            width: auto;
        }
        .flatpickr-calendar .flatpickr-current-month {
            display: flex;
            justify-content: space-between;
        }
        .flatpickr-calendar {
            z-index: 1050 !important;
        }
        .dropdown.show .flatpickr-calendar {
            position: absolute;
            top: 100%;
        }
    </style>
@endpush
<div class="form-group col-md-12">
    @if ($label) <label for="{{ $id }}">{{ $label }}@if ($isRequired) <strong class="text-danger">*</strong>@endif</label> @endif
    <input type="text" name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror input-rounded" id="{{ $id }}"
        placeholder="{{ $placeholder }}" value="{{ old($name, $value ?: '') }}" {{ $attributes }}>
        <input type="hidden"
        name="{{ $name }}_timestamp" id="{{ $id }}_timestamp"> @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
@push('footerFiles')
    <script>
        flatpickr("#{{ $id }}", {
            dateFormat: "Y-m-d",
            maxDate: "{{ $maxDate }}",
            minDate: "{{ $minDate }}",
            locale: "vn",
            onOpen: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
@endpush
