<div class="basic-form">
    <div class="form-group col-md-12">
        @php
            $isMultiple = isset($multiple) && ($multiple === true || $multiple === 'true');
            $selectName = $name . ($isMultiple ? '[]' : '');
            $current = old($oldName ?: $name, ${'selected'} ?? null);
            $valueKey = $selectValueAttribute ?? 'value';
            $labelKey = $selectValueLabel ?? 'label';
        @endphp

        @if($label)
            <label for="{{ $id }}">{{ $label }}@if($isRequired) <strong class="text-danger">*</strong> @endif</label>
        @endif
        <select name="{{ $selectName }}" id="{{ $id }}" {{ $isMultiple ? 'multiple' : '' }} {!! $attributes->merge(['class' => 'form-control input-rounded ' . ($errors->has($oldName ?: $name) ? 'is-invalid' : '')]) !!}>
            @if(!empty($placeholder))
                <option value="" disabled {{ empty($current) && !$isMultiple ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif

            @if(isset($dataValues) && is_iterable($dataValues))
                @foreach($dataValues as $option)
                    @php
                        $optValue = data_get($option, $valueKey);
                        $optLabel = data_get($option, $labelKey);

                        if ($isMultiple) {
                            $currentVals = is_array($current) ? $current : (is_string($current) && strlen($current) ? explode(',', $current) : []);
                            $selectedAttr = in_array((string)$optValue, array_map('strval', $currentVals)) ? 'selected' : '';
                        } else {
                            $selectedAttr = ((string)$optValue === (string)$current) ? 'selected' : '';
                        }
                    @endphp

                    <option value="{{ $optValue }}" {{ $selectedAttr }}>{{ $optLabel }}</option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>

        @error($oldName ?: $name)
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>
