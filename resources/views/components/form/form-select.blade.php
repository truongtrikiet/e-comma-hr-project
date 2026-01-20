<div class="basic-form">
    <div class="form-group col-md-12">
        @php
            $isMultiple = isset($multiple) && ($multiple === true || $multiple === 'true');
            $selectName = $name . ($isMultiple ? '[]' : '');
            $current = old($oldName ?: $name, ${'selected'} ?? ($isMultiple ? [] : null));
            $valueKey = $selectValueAttribute ?? 'value';
            $labelKey = $selectValueLabel ?? 'label';

            if ($isMultiple && is_string($current)) {
                $current = explode(',', $current);
            }
        @endphp

        @if($label)
            <label for="{{ $id }}">
                {{ $label }}
                @if($isRequired) <strong class="text-danger">*</strong> @endif
            </label>
        @endif

        <select
            name="{{ $selectName }}"
            id="{{ $id }}"
            {{ $isMultiple ? 'multiple' : '' }}
            data-enhanced="true"
            {!! $attributes->merge([
                'class' => 'form-control input-rounded js-enhanced-select ' .
                ($errors->has($oldName ?: $name) ? 'is-invalid' : '')
            ]) !!}
        >
            @if(!empty($placeholder))
                <option value=""
                    {{ (!$isMultiple && empty($current)) ? 'selected' : '' }}
                    {{ $isRequired ? 'disabled' : '' }}
                >
                    {{ $placeholder }}
                </option>
            @endif

            @foreach($dataValues as $option)
                @php
                    $optValue = data_get($option, $valueKey);
                    $optLabel = data_get($option, $labelKey);

                    $selected = $isMultiple
                        ? in_array((string)$optValue, array_map('strval', $current ?? []), true)
                        : ((string)$optValue === (string)$current);
                @endphp

                <option value="{{ $optValue }}" {{ $selected ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        </select>

        @error($oldName ?: $name)
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
