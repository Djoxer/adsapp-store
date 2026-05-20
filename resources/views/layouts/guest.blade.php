@props(['title' => 'LOGIN', 'subtitle' => 'IDENTITÄT BESTÄTIGEN UM FORTZUFAHREN'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdsApp.store — System Access</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 100;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.07) 2px, rgba(0,0,0,0.07) 4px);
        }
        @keyframes scanDown {
            0%   { top: -2px; opacity: 0; }
            5%   { opacity: 1; }
            95%  { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        @keyframes tickerScroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes fadeInLine {
            from { opacity: 0; transform: translateX(-8px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes panelIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0; }
        }
        .scan-line {
            position: absolute; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,0.4), transparent);
            animation: scanDown 4s linear infinite;
            pointer-events: none; z-index: 2;
        }
        .ticker-track { animation: tickerScroll 35s linear infinite; }
        .boot-1 { animation: fadeInLine 0.5s 0.3s both; }
        .boot-2 { animation: fadeInLine 0.5s 0.8s both; }
        .boot-3 { animation: fadeInLine 0.5s 1.3s both; }
        .panel-in { animation: panelIn 0.6s 0.2s both; }
        .logo-blink::after {
            content: '■'; color: #FF535B; font-size: 10px; margin-left: 6px;
            animation: blink 1.2s step-end infinite;
        }
        .panel-left-border {
            background: linear-gradient(180deg, #DC2626 0%, #7a1a06 60%, transparent 100%);
        }
    </style>
</head>
<body class="bg-black font-mono text-copy-warm h-screen overflow-hidden">

{{-- NAVBAR --}}
<nav class="fixed top-0 left-0 right-0 h-[64px] flex items-center justify-between px-7 border-b-2 border-line-yellow bg-black z-50">
    <a href="/" class="font-sans font-bold text-4xl italic tracking-[3px] text-brand-red no-underline logo-blink leading-none mt-1">
        ADSAPP.STORE
    </a>
    <div class="flex items-center gap-4">
        <div class="text-right text-[10px] tracking-widest leading-relaxed">
            <div>STATUS: <span class="text-brand-yellow-dim">STANDBY</span></div>
            <div>LOC: <span class="text-copy-neutral">127.0.0.1</span></div>
        </div>
        <div class="w-8 h-8 border border-line-warm rounded-full flex items-center justify-center text-copy-neutral text-sm">⊙</div>
    </div>
</nav>

{{-- BOOT TEXT --}}
<div class="fixed left-7 bottom-20 text-[11px] tracking-wider text-line-warm leading-loose z-10">
    <div class="boot-1">&gt; BOOT_SEQUENCE: INITIALIZING...</div>
    <div class="boot-2">&gt; KERNEL_LOAD: OK</div>
    <div class="boot-3">&gt; NETWORK_SYNC: ACTIVE</div>
</div>

{{-- SYS INFO --}}
<div class="fixed right-7 bottom-20 text-[10px] tracking-wider text-line-warm text-right leading-loose z-10">
    <div>SYS_VER: 0.9.4-ALPHA</div>
    <div>AUTH_PROTOCOL: RSA-4096</div>
</div>

{{-- MAIN --}}
<main class="fixed inset-0 top-[64px] bottom-[60px] flex items-start pt-[5%] justify-center p-5 bg-ink-dark">
    <div class="panel-in w-full max-w-[520px] bg-ink-panel border border-line-warm relative overflow-hidden">

        <div class="scan-line"></div>
        <div class="panel-left-border absolute left-0 top-0 bottom-0 w-[3px]"></div>

        {{-- Panel Header --}}
        <div class="px-6 py-5 border-b border-line-warm">
            <div class="flex items-center gap-2 text-[13px] tracking-[2px]">
                <span class="text-brand-yellow">▌ SYSTEM_ACCESS // {{ $title ?? 'LOGIN' }}</span>
            </div>
            <div class="mt-1.5 text-[10px] tracking-[2px] text-copy-neutral">
                {{ $subtitle ?? 'IDENTITÄT BESTÄTIGEN UM FORTZUFAHREN' }}
            </div>
        </div>

        {{-- Slot --}}
        <div class="px-6 py-6">
            {{ $slot }}
        </div>

        {{-- Panel Footer --}}
        <div class="px-6 py-3 border-t border-line-warm flex items-center justify-between">
            <div class="flex gap-1.5">
                <div class="w-2 h-2 bg-brand-red border border-brand-red"></div>
                <div class="w-2 h-2 border border-line-warm"></div>
                <div class="w-2 h-2 border border-line-warm"></div>
            </div>
            <div class="text-[9px] tracking-[2px] text-line-warm">SECURE_CHANNEL_v4.2</div>
        </div>

    </div>
</main>

{{-- TICKER --}}
<div class="fixed bottom-[40px] left-0 right-0 h-[20px] overflow-hidden flex items-center z-50"
     style="background: rgba(24,10,10,0.7); border-top: 1px solid rgba(91,64,63,0.8);">
    <div class="overflow-hidden flex-1">
        <div class="ticker-track flex whitespace-nowrap">
            @foreach([1,2] as $i)
                <div class="text-[12px] tracking-[1.5px] text-copy-ticker px-8">
                    +++ SYSTEM STATUS: NOMINAL
                    <span class="mx-2">+++</span>
                    UPTIME: 99.98%
                    <span class="mx-2">+++</span>
                    ACTIVE_OPERATORS: 142
                    <span class="mx-2">+++</span>
                    GLOBAL_REACH: 8.2M
                    <span class="mx-2">+++</span>
                    DEPLOYMENTS: 1,204
                    <span class="mx-2">+++</span>
                    SYSTEM STATUS: NOMINAL
                    <span class="mx-2">+++</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- BOTTOM BAR --}}
<div class="fixed bottom-0 left-0 right-0 h-[40px] border-t border-line-yellow bg-black flex items-center justify-between px-7 z-50">
    <div class="text-[9px] tracking-[1.5px] text-copy-soft">
        © 2024 ADSAPP_TERMINAL. ALL RIGHTS RESERVED.
    </div>
    <div class="flex gap-6 text-[9px] tracking-[1.5px] text-copy-soft">
        <a href="#" class="hover:text-brand-yellow-dim transition-colors">Terms</a>
        <a href="#" class="hover:text-brand-yellow-dim transition-colors">Privacy</a>
        <a href="#" class="hover:text-brand-yellow-dim transition-colors">Support</a>
    </div>
</div>

</body>
</html>
