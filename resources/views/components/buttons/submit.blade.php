<button type="submit" {{ $attributes->merge(['class' => 'btn btn-default btn-'.$type, 'id' => 'submit-button']) }}>
    {{ $label }}
</button>
