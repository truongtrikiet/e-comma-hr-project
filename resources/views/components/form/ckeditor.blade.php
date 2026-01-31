@props([
    'name' => 'content',
    'id' => null,
    'value' => null,
    'label' => null,
    'rows' => 8,
])

<x-custom.ckeditor :name="$name" :id="$id" :value="$value" :label="$label" :rows="$rows" {{ $attributes }} />
