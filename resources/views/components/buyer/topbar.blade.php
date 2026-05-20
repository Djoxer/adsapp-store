<style>
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
    .logo-blink::after {
        content:'■'; color:#FF535B; font-size:10px; margin-left:6px;
        animation: blink 1.2s step-end infinite;
    }
</style>

<nav class="fixed top-0 left-0 right-0 h-[64px] flex items-center justify-between px-7 border-b-2 border-line-yellow bg-black z-[150]">

    <a href="{{ route('catalog') }}"
       class="font-sans font-bold text-4xl italic tracking-[3px] text-brand-red no-underline logo-blink leading-none mt-1">
        ADSAPP.STORE
    </a>

    {{-- Search --}}
    <div class="relative" style="width:300px;">
        <div class="absolute left-3 inset-y-0 flex items-center pointer-events-none" style="color:#404040;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
        </div>
        <input type="text" id="catalog-search" placeholder="SEARCH_DB_QUERY..."
               class="w-full pl-9 pr-4 py-2.5 text-[11px] tracking-wider placeholder:text-[#454745] focus:outline-none transition-colors"
               style="background:#1a1a1a; border:1px solid #333333; color:#A1A1AA; width:300px;">
    </div>

    {{-- User info + dropdown --}}
    <div class="flex items-center gap-6">
        <div class="text-right text-[10px] tracking-widest leading-relaxed">
            <div class="text-copy-neutral">OPERATOR_ID: <span class="text-brand-yellow">{{ Auth::user()->id ?? '---' }}-X</span></div>
            <div class="text-copy-neutral">{{ strtoupper(Auth::user()->name ?? 'OPERATOR') }} · <span class="text-brand-yellow">{{ strtoupper(Auth::user()->role ?? 'BUYER') }}</span></div>
        </div>
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="w-8 h-8 border border-line-warm rounded-full flex items-center justify-center text-copy-neutral hover:border-brand-yellow hover:text-brand-yellow transition-colors">
                ⊙
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 top-10 w-48 bg-ink-panel border border-line-warm z-50"
                 style="display:none;">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:bg-ink-surface hover:text-brand-yellow transition-colors">
                    ⊞ PROFIL
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:bg-ink-surface hover:text-brand-red transition-colors border-t border-line-warm">
                        ⏻ LOGOUT
                    </button>
                </form>
            </div>
        </div>
    </div>

</nav>
