{{-- TOPBAR --}}
<nav class="fixed top-0 left-0 right-0 h-[64px] flex items-center justify-between px-7 border-b-2 border-line-yellow bg-black z-50">
    <a href="{{ route('dashboard') }}" class="font-sans font-bold text-4xl italic tracking-[3px] text-brand-red no-underline logo-blink leading-none mt-1">
        ADSAPP.STORE
    </a>
    <div class="flex items-center gap-6">
        <div class="text-right text-[10px] tracking-widest leading-relaxed">
            <div class="text-copy-neutral">MERCHANT_ID: <span class="text-brand-yellow">{{ Auth::user()->id ?? '---' }}-X</span></div>
            <div class="text-copy-neutral">{{ strtoupper(Auth::user()->name ?? 'OPERATOR') }} · <span class="text-brand-yellow">{{ strtoupper(Auth::user()->role ?? 'USER') }}</span></div>
        </div>
        {{-- Notifications --}}
        <button class="w-8 h-8 border border-line-warm flex items-center justify-center text-copy-neutral hover:border-brand-yellow hover:text-brand-yellow transition-colors relative">
            <x-icons.bell class="w-4 h-4" />
            <span class="absolute -top-1 -right-1 w-3 h-3 bg-brand-red text-white text-[7px] flex items-center justify-center">3</span>
        </button>

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
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2.5 px-4 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:bg-ink-surface hover:text-brand-yellow transition-colors">
                    <x-icons.profile class="w-4 h-4" />
                    PROFIL
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:bg-ink-surface hover:text-brand-red transition-colors border-t border-line-warm">
                        <x-icons.logout class="w-4 h-4" />
                        LOGOUT
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- SIDEBAR --}}
<aside class="fixed top-[64px] left-0 bottom-[24px] w-[200px] bg-black border-r border-line-warm z-40 flex flex-col">

    <div class="px-5 py-4 border-b border-line-warm">
        <div class="text-[9px] tracking-[2px] text-brand-yellow">MERCHANT CONSOLE</div>
        <div class="text-[8px] tracking-[1px] text-copy-ticker mt-0.5">SYSTEM_ACTIVE_V.1.0</div>
    </div>

    <nav class="flex-1 py-3 overflow-y-auto">
        @php
            $navItems = [
                ['route' => 'dashboard', 'icon' => 'dashboard', 'label' => 'ÜBERSICHT'],
                ['route' => 'dashboard', 'icon' => 'fav',       'label' => 'MEINE ADS'],
                ['route' => 'dashboard', 'icon' => 'add',       'label' => 'AD ERSTELLEN'],
                ['route' => 'dashboard', 'icon' => 'log',       'label' => 'BESTELLUNGEN'],
                ['route' => 'dashboard', 'icon' => 'badge',     'label' => 'PREMIUM SLOTS'],
                ['route' => 'dashboard', 'icon' => 'analyze',   'label' => 'ANALYTICS'],
                ['route' => 'dashboard', 'icon' => 'cash',      'label' => 'ABRECHNUNGEN'],
                ['route' => 'dashboard', 'icon' => 'controls',  'label' => 'EINSTELLUNGEN'],
            ];
        @endphp

        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-5 py-2.5 text-[10px] tracking-[1.5px] transition-colors
              {{ request()->routeIs($item['route']) && $loop->first
                 ? 'text-brand-yellow bg-ink-surface border-l-2 border-brand-yellow'
                 : 'text-copy-neutral hover:text-brand-yellow hover:bg-ink-surface border-l-2 border-transparent' }}">
            <span class="w-4 h-4 flex-shrink-0">
                <x-dynamic-component :component="'icons.' . $item['icon']" class="w-4 h-4" />
            </span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-line-warm py-3">
        <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:text-brand-yellow hover:bg-ink-surface transition-colors">
        <span class="w-4 h-4 flex-shrink-0">
            <x-icons.quest class="w-4 h-4" />
        </span>
            SUPPORT
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-5 py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral hover:text-brand-red hover:bg-ink-surface transition-colors">
            <span class="w-4 h-4 flex-shrink-0">
                <x-icons.logout class="w-4 h-4" />
            </span>
                LOGOUT
            </button>
        </form>
    </div>

</aside>
