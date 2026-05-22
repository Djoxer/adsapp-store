<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AdsApp') }} — {{ $title ?? 'Console' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 100;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.05) 2px, rgba(0,0,0,0.05) 4px);
        }
        @keyframes tickerScroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0; }
        }
        .ticker-track { animation: tickerScroll 40s linear infinite; }
        .logo-blink::after {
            content: '■'; color: #FF535B; font-size: 10px; margin-left: 6px;
            animation: blink 1.2s step-end infinite;
        }
    </style>
</head>
<body class="bg-ink-dark font-mono text-copy-warm h-screen overflow-hidden">

@include('layouts.navigation')

{{-- PAGE CONTENT --}}
<div class="fixed inset-0 top-[64px] bottom-[24px] left-[200px] overflow-y-auto bg-ink-dark">
    {{ $slot }}
</div>

{{-- BOTTOM TICKER --}}
<div class="fixed bottom-0 left-0 right-0 h-[24px] overflow-hidden flex items-center z-50"
     style="background: rgba(24,10,10,0.9); border-top: 1px solid rgba(91,64,63,0.6);">
    <div class="flex-shrink-0 px-3 text-[8px] tracking-[2px] text-brand-red border-r border-line-warm h-full flex items-center">
        ◉
    </div>
    <div class="overflow-hidden flex-1">
        <div class="ticker-track flex whitespace-nowrap">
            @foreach([1,2] as $i)
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker px-6">
                    +++ SYSTEM_LOG_FEED: PERFORMANCE_STABLE
                    <span class="mx-2 text-brand-yellow">+++</span>
                    LATENCY_12MS
                    <span class="mx-2 text-brand-yellow">+++</span>
                    ARPU_UP_4.2%
                    <span class="mx-2 text-brand-yellow">+++</span>
                    NODE_FRANKFURT_CONNECTED
                    <span class="mx-2 text-brand-yellow">+++</span>
                    ENCRYPTION_AES256_ACTIVE
                    <span class="mx-2 text-brand-yellow">+++</span>
                    WARNING: AD_CAMPAIGN_ID_442 REACHING BUDGET
                    <span class="mx-2 text-brand-yellow">+++</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
<x-profile-overlay />
</body>
</html>
