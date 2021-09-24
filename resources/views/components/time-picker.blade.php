@props([
    'options' => "{dateFormat:'H:i:S', altFormat:'H:i ', altInput:true, enableTime:true, noCalendar: true}",
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
