<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AdsApp') }} — Einstellungen</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 100;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.05) 2px, rgba(0,0,0,0.05) 4px);
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0;} }
        .logo-blink::after {
            content: '■'; color: #FF535B; font-size: 10px; margin-left: 6px;
            animation: blink 1.2s step-end infinite;
        }
    </style>
</head>
<body class="bg-ink-dark font-mono text-copy-warm h-screen overflow-hidden">

{{-- MINIMAL TOPBAR — rollenneutral, kein Merchant-Menü --}}
<nav class="fixed top-0 left-0 right-0 h-[64px] flex items-center justify-between px-7 border-b-2 border-line-yellow bg-black z-50">
    <a href="{{ Auth::user()->homeRoute() }}" class="font-sans font-bold text-4xl italic tracking-[3px] text-brand-red no-underline logo-blink leading-none mt-1">
        ADSAPP.STORE
    </a>
    <div class="flex items-center gap-6">
        <div class="text-right text-[10px] tracking-widest leading-relaxed">
            <div class="text-copy-neutral">OPERATOR_ID: <span class="text-brand-yellow">{{ Auth::user()->id ?? '---' }}</span></div>
            <div class="text-copy-neutral">{{ strtoupper(Auth::user()->name ?? 'OPERATOR') }} · <span class="text-brand-yellow">{{ strtoupper(Auth::user()->role ?? 'USER') }}</span></div>
        </div>

        {{-- Zurück zur App (rollenkorrekt via homeRoute) --}}
        <a href="{{ Auth::user()->homeRoute() }}"
           class="flex items-center gap-2 px-4 py-2 text-[10px] tracking-[1.5px] border border-line-warm text-copy-neutral hover:border-brand-yellow hover:text-brand-yellow transition-colors">
            <x-icons.raster class="w-3.5 h-3.5" /> ZUR APP
        </a>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 text-[10px] tracking-[1.5px] border border-line-warm text-copy-neutral hover:border-brand-red hover:text-brand-red transition-colors">
                <x-icons.logout class="w-3.5 h-3.5" /> LOGOUT
            </button>
        </form>
    </div>
</nav>

{{-- PAGE CONTENT — voller Breite, kein Sidebar-Offset, kein Ticker-Offset --}}
<div class="fixed inset-0 top-[64px] overflow-y-auto bg-ink-dark">
    {{ $slot }}
</div>

</body>
</html>
