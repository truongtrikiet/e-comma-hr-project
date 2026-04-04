@php
    $isMultiple = true;
    $selectName = $selectName ?? (($name ?? 'tags') . '[]');
    $current = old($oldName ?? $name, $selected ?? []);
    if (is_null($current)) {
        $current = [];
    } elseif (is_string($current)) {
        $current = $current === '' ? [] : explode(',', $current);
    } elseif (!is_array($current)) {
        $current = (array) $current;
    }

    $valueKey = $selectValueAttribute ?? 'value';
    $labelKey = $selectValueLabel ?? 'label';

    $classes = $classes ?? 'form-control input-default js-multi-select';
    $placeholderText = $placeholder ?? '';
    $enableTags = isset($tags) ? (bool) $tags : false;
@endphp

<div class="basic-form">
    <div class="form-group col-md-12">
        @if(isset($label) && $label)
            <label for="{{ $id ?? ($name ?? 'multi-select') }}">
                {{ $label }}
                @if(!empty($isRequired)) <strong class="text-danger">*</strong> @endif
            </label>
        @endif

        <select
            name="{{ $selectName }}"
            id="{{ $id ?? ($name ?? 'multi-value-select') }}"
            multiple
            data-tags="{{ $enableTags ? 'true' : 'false' }}"
            data-enhanced="true"
            data-placeholder="{{ $placeholderText }}"
            {!! $attributes->merge(['class' => $classes . ($errors->has($oldName ?? $name) ? ' is-invalid' : '')]) !!}
        >
            {{-- Render provided options --}}
            @if(!empty($dataValues) && is_iterable($dataValues))
                @foreach($dataValues as $option)
                    @php
                        if (is_array($option) || is_object($option)) {
                            $optValue = data_get($option, $valueKey);
                            $optLabel = data_get($option, $labelKey);
                        } else {
                            $optValue = $option;
                            $optLabel = $option;
                        }

                        $selected = in_array((string)$optValue, array_map('strval', (array) $current), true);
                    @endphp

                    <option value="{{ $optValue }}" {{ $selected ? 'selected' : '' }}>
                        {{ $optLabel }}
                    </option>
                @endforeach
            @endif
        </select>

        @error($oldName ?? $name)
            <span class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@once
    @push('scripts')
        <script>
            (function () {
                var initId = '{{ $id ?? ($name ?? 'multi-value-select') }}';
                var enableTags = {{ $enableTags ? 'true' : 'false' }};
                var placeholder = {!! json_encode($placeholderText) !!};

                function initMultiSelect() {
                    if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
                        var $el = jQuery('#' + initId);
                        if ($el.length && !$el.data('select2')) {
                            $el.select2({
                                tags: enableTags,
                                placeholder: placeholder,
                                // width: '100%'
                            });
                        }
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initMultiSelect);
                } else {
                    initMultiSelect();
                }
            })();
        </script>
    @endpush
@endonce
