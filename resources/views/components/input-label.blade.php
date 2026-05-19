@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[10px] tracking-[2px] text-copy-neutral mb-2']) }}>
    {{ $value ?? $slot }}
</label>
