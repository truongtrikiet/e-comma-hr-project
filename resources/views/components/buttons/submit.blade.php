<button type="submit" {{ $attributes->merge(['class' => 'btn btn-rounded btn-'.$type, 'id' => $id]) }}>
    {{ $label }}
</button>
