<!DOCTYPE html>
<html lang="de" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ADMIN // {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --admin-bg:#0a0f1a; --admin-panel:#111a2b; --admin-line:#1e3050; --admin-accent:#4fc3f7; }
        body { background: var(--admin-bg); }
    </style>
</head>
<body class="font-sans antialiased" style="background:#0a0f1a;color:#c8d8e8;">
<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-56 flex-shrink-0 flex flex-col" style="background:#111a2b;border-right:1px solid #1e3050;">
        <div class="px-5 py-5" style="border-bottom:1px solid #1e3050;">
            <div class="text-[9px] tracking-[3px]" style="color:#4fc3f7;">ADSAPP // CONTROL</div>
            <div class="text-[16px] font-sans font-bold tracking-wider mt-1" style="color:#e8f4ff;">ADMIN PANEL</div>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#43d685;box-shadow:0 0 6px #43d685;"></span>
                <span class="text-[8px] tracking-[1.5px]" style="color:#5a7a9a;">SYSTEM ONLINE</span>
            </div>
        </div>

        <nav class="flex-1 py-3">
            @php
                $adminNav = [
                    ['admin.dashboard', 'ÜBERSICHT', '◰'],
                    ['admin.slots',     'SLOT-ANTRÄGE', '◴'],
                    ['admin.merchants', 'HÄNDLER-FREIGABE', '◷'],
                ];
            @endphp
            @foreach($adminNav as [$route, $label, $icon])
                <a href="{{ route($route) }}"
                   class="flex items-center gap-3 px-5 py-2.5 text-[11px] tracking-[1.5px] transition-colors"
                   style="{{ request()->routeIs($route) || request()->routeIs($route.'.*')
                           ? 'color:#4fc3f7;background:rgba(79,195,247,0.08);border-left:2px solid #4fc3f7;'
                           : 'color:#8aa8c8;border-left:2px solid transparent;' }}"
                   onmouseover="if(!this.style.borderLeftColor.includes('79'))this.style.color='#c8e4f5'"
                   onmouseout="if(!this.style.borderLeftColor.includes('79'))this.style.color='#8aa8c8'">
                    <span style="color:#4fc3f7;">{{ $icon }}</span>{{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="px-5 py-4" style="border-top:1px solid #1e3050;">
            <a href="{{ route('catalog') }}" class="text-[9px] tracking-[1.5px] transition-colors" style="color:#5a7a9a;"
               onmouseover="this.style.color='#4fc3f7'" onmouseout="this.style.color='#5a7a9a'">← ZURÜCK ZUR APP</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="text-[9px] tracking-[1.5px] transition-colors" style="color:#5a7a9a;"
                        onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#5a7a9a'">LOGOUT</button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 overflow-y-auto">
        <div class="px-8 py-4 flex items-center justify-between" style="background:#111a2b;border-bottom:1px solid #1e3050;">
            <div class="text-[10px] tracking-[2px]" style="color:#5a7a9a;">{{ $header ?? 'CONTROL_PANEL' }}</div>
            <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">{{ auth()->user()->name }} // ADMIN</div>
        </div>
        <div class="p-8">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
