@props([
    'name' => 'content',
    'id' => null,
    'value' => null,
    'label' => null,
    'height' => 200,
    'placeholder' => null,
])

<x-custom.summernote :name="$name" :id="$id" :value="$value" :label="$label" :height="$height" :placeholder="$placeholder" {{ $attributes }} />
