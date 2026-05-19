@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'w-full bg-black/40 border border-line-warm text-copy-soft font-mono text-[12px] tracking-wider px-3.5 py-3 outline-none hover:border-brand-yellow focus:border-brand-yellow focus:bg-brand-red/5 placeholder-copy-warm transition-colors rounded-none'
    ]) !!}
>
