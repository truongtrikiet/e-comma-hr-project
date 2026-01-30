<style>
    .form-multiple-checkbox .form-check {
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .form-multiple-checkbox .form-check-label {
        color: #222 !important;
        font-weight: 600;
    }
    .form-multiple-checkbox .form-check-input {
        margin-top: 0 !important;
        transform: none !important;
    }
    .form-multiple-checkbox .form-check-primary .form-check-input:checked + .form-check-label {
        color: #0b5;
    }
</style>

<div class="form-group col-md-12 form-multiple-checkbox">
    <label for="{{ $id }}">{{ $label }}</label>
    <div class="@error($name) is-invalid @enderror">
        @foreach ($dataSource as $item)
            <div class="form-check form-check-primary">
                <input class="form-check-input"
                    type="checkbox"
                    value="{{ $item->name }}"
                    name="{{ $name }}[]"
                    id="{{ $id . $loop->index }}"
                    @checked(is_array(old($name, $value)) && in_array($item->name, old($name, $value)))>
                <label class="form-check-label text-capitalize" for="{{ $id . $loop->index }}">
                    @if($valueAttribute)
                        {{ $item->$valueAttribute }}
                    @else
                        {{ $slot }}
                    @endif
                </label>
            </div>
        @endforeach
    </div>
    @error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
