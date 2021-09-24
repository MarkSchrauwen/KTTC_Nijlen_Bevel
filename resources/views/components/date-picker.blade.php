@props([
    'options' => "{dateFormat:'Y-m-d', altFormat:'D d/m/Y', altInput:true, enableTime:false}",
])

<div wire:ignore>
    <input 
        x-data="{value:@entangle($attributes->wire('model')), instance: undefined}"
        x-init="() => {
            $watch('value', value => instance.setDate(value, true));
            instance = flatpickr($refs.input, {{ $options }} );
        }" 
        x-ref="input" type="text" data-input
        {{ $attributes }} />
</div>