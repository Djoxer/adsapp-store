<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full bg-brand-red hover:bg-red-700 active:scale-[0.99] text-white font-sans font-bold text-sm tracking-[3px] uppercase py-4 flex items-center justify-center gap-2.5 transition-colors']) }}>
    <span>🔒</span> {{ $slot }}
</button>
