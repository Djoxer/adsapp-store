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
    <div class="relative" style="width:350px;">
        <div class="absolute left-2.5 inset-y-0 flex items-center pointer-events-none" style="color:#404040;">
            <x-icons.search class="w-3.5 h-3.5" />
        </div>
        <input type="text" id="catalog-search" placeholder="SEARCH_DB_QUERY..."
               class="w-full pl-8 pr-3 py-1 text-[14px] tracking-wider placeholder:text-[#454745] focus:outline-none transition-colors"
               style="background:#1a1a1a; border:1px solid #333333; color:#A1A1AA; width:350px;">
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
                <x-icons.profile class="w-4 h-4" />
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

                {{-- Profil --}}
                <button onclick="openProfileOverlay(); $dispatch('close')"
                        class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:bg-ink-surface hover:text-brand-yellow transition-colors">
                    <x-icons.profile class="w-3.5 h-3.5" /> PROFIL
                </button>

                {{-- Dashboard/Catalog Toggle — role-aware --}}
                @if(in_array(Auth::user()->role, ['merchant','agency','admin']))
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:bg-ink-surface hover:text-brand-yellow transition-colors border-t border-line-warm">
                        <x-icons.dashboard class="w-3.5 h-3.5" />
                        DASHBOARD
                    </a>
                @endif

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:bg-ink-surface hover:text-brand-red transition-colors border-t border-line-warm">
                        <x-icons.logout class="w-3.5 h-3.5" /> LOGOUT
                    </button>
                </form>

            </div>
        </div>
    </div>

</nav>
