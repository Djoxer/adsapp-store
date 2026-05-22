@if(Auth::check())
    @if(Auth::user()->role === 'buyer')
        <script>window.location = "{{ route('catalog') }}"</script>
    @else
        <script>window.location = "{{ route('dashboard') }}"</script>
    @endif
@endif
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdsApp.store — Werbung, die verdient gesehen zu werden</title>
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
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes signalPulse {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0.4; }
        }
        .ticker-track  { animation: tickerScroll 40s linear infinite; }
        .ticker-track2 { animation: tickerScroll 50s linear infinite; }
        .logo-blink::after {
            content: '■'; color: #FF535B; font-size: 10px; margin-left: 6px;
            animation: blink 1.2s step-end infinite;
        }
        .hero-content { animation: fadeUp 0.8s 0.3s both; }
        .signal-dot   { animation: signalPulse 2s ease-in-out infinite; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-black font-mono text-copy-warm overflow-x-hidden">

{{-- TOPBAR --}}
<nav class="fixed top-0 left-0 right-0 h-[64px] flex items-center justify-between px-7 border-b-2 border-line-yellow bg-black z-50">
    <a href="/" class="font-sans font-bold text-4xl italic tracking-[3px] text-brand-red no-underline logo-blink leading-none mt-1">
        ADSAPP.STORE
    </a>
    <div class="flex items-center gap-6">
        <div class="hidden md:flex items-center gap-2 text-[10px] tracking-widest text-copy-neutral">
            <span class="signal-dot w-1.5 h-1.5 bg-brand-yellow rounded-full inline-block"></span>
            SIGNAL_STRENGTH: 98.4%
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="text-[10px] tracking-[2px] text-copy-neutral hover:text-brand-yellow transition-colors">
                    CONSOLE
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="text-[10px] tracking-[2px] text-copy-neutral hover:text-brand-yellow transition-colors px-3 py-1.5 border border-line-warm hover:border-brand-yellow">
                    LOGIN
                </a>
                <a href="{{ route('register') }}"
                   class="text-[10px] tracking-[2px] text-white bg-brand-red hover:bg-red-700 transition-colors px-3 py-1.5 font-sans font-bold">
                    REGISTRIEREN
                </a>
            @endauth
        </div>
        <div class="w-8 h-8 border border-line-warm rounded-full flex items-center justify-center text-copy-neutral text-sm">ℹ</div>
    </div>
</nav>

{{-- ① HERO — schwarz --}}
<section class="min-h-screen bg-black flex flex-col items-center justify-center pt-[64px] px-6 relative overflow-hidden">

    {{-- Background grid --}}
    <div class="absolute inset-0 opacity-5"
         style="background-image: linear-gradient(#5B403F 1px, transparent 1px), linear-gradient(90deg, #5B403F 1px, transparent 1px); background-size: 60px 60px;">
    </div>

    <div class="hero-content text-center max-w-3xl relative z-10">

        {{-- Signal Badge --}}
        <div class="inline-flex items-center gap-2 border border-brand-yellow/40 bg-brand-yellow/5 px-4 py-1.5 mb-8">
            <span class="signal-dot w-1.5 h-1.5 bg-brand-yellow rounded-full"></span>
            <span class="text-[10px] tracking-[2px] text-brand-yellow">SIGNAL_STRENGTH: 98.4%</span>
        </div>

        <div class="text-[10px] tracking-[3px] text-copy-neutral mb-3">WERBUNG, DIE VERDIENT</div>
        <h1 class="font-sans font-bold text-5xl md:text-7xl tracking-[4px] text-copy-soft leading-none mb-2">
            GESEHEN ZU WERDEN
        </h1>

        <div class="w-16 h-[2px] bg-brand-yellow mx-auto my-6"></div>

        <p class="text-[13px] tracking-wider text-copy-neutral leading-relaxed max-w-xl mx-auto mb-10 border-l-2 border-brand-red pl-4 text-left">
            Der erste Pull-basierte Werbe-Katalog. Keine Tracker. Kein Spam. Nur Relevanz. Übernimm die Kontrolle über dein Konsumerlebnis.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#katalog"
               class="font-sans font-bold text-sm tracking-[3px] uppercase bg-brand-red hover:bg-red-700 text-white px-8 py-4 transition-colors">
                KATALOG ENTDECKEN →
            </a>
            <a href="{{ route('register') }}"
               class="font-sans font-bold text-sm tracking-[3px] uppercase border border-line-warm hover:border-brand-yellow text-copy-neutral hover:text-brand-yellow px-8 py-4 transition-colors">
                HÄNDLER WERDEN
            </a>
        </div>

    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-copy-ticker text-[10px] tracking-[2px] flex flex-col items-center gap-2">
        <span>SCROLL</span>
        <span class="text-brand-yellow">↓</span>
    </div>

</section>

{{-- TICKER STREIFEN --}}
<div class="bg-ink-deep border-y border-line-warm overflow-hidden h-[32px] flex items-center">
    <div class="ticker-track flex whitespace-nowrap">
        @foreach([1,2] as $i)
            <div class="text-[10px] tracking-[1.5px] text-copy-ticker px-8">
                SYSTEM_ID: TERMINAL_77
                <span class="text-brand-yellow mx-3">///</span> LOGGING REAL-TIME DATA
                <span class="text-brand-yellow mx-3">///</span> HOT_SLOT: HYPERION GT-X (+14.2%)
                <span class="text-brand-yellow mx-3">///</span> NEW_ADVERTISER: VISION-CORE SYSTEMS
                <span class="text-brand-yellow mx-3">///</span> NETWORK_LEAD: STABLE [8.6MB]
                <span class="text-brand-yellow mx-3">///</span> PULL_RATED: 98:12
                <span class="text-brand-yellow mx-3">///</span>
            </div>
        @endforeach
    </div>
</div>

{{-- ② PROTOCOL FLOW — dunkelrot --}}
<section class="bg-ink-dark py-20 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-12">
            <div class="text-[9px] tracking-[4px] text-copy-ticker mb-3">— PROTOCOL_FLOW —</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['phase'=>'PHASE_01','title'=>'STÖBERN','icon'=>'△◇','desc'=>'Kein Push. Du entscheidest, wann du bereit bist. Durchsuche unseren Katalog nach echten Bedürfnissen, nicht nach manipulativen Algorithmen.'],
                ['phase'=>'PHASE_02','title'=>'ENTDECKEN','icon'=>'(·)','desc'=>'Organische Signale steuern die Relevanz. Transparenz ist unsere einzige Währung. Transparency ist unsere einzige Währung.'],
                ['phase'=>'PHASE_03','title'=>'KAUFEN','icon'=>'⊡','desc'=>'Direkter Kontakt, faire Preise. Keine versteckten Gebühren für Klicks, die niemand wollte. Eine reine Transaktion zwischen Angebot und Nachfrage.'],
            ] as $phase)
                <div class="border border-line-warm p-6 bg-ink-panel relative">
                    <div class="text-[9px] tracking-[2px] text-copy-ticker mb-4">{{ $phase['phase'] }}</div>
                    <div class="text-2xl text-copy-ticker mb-3">{{ $phase['icon'] }}</div>
                    <div class="font-sans font-bold text-lg tracking-[2px] text-copy-soft mb-3">{{ $phase['title'] }}</div>
                    <p class="text-[11px] tracking-wider text-copy-neutral leading-relaxed">{{ $phase['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ③ KATALOG PREVIEW — dunkelrot --}}
<section id="katalog" class="bg-ink-dark py-20 px-6">
    <div class="max-w-5xl mx-auto">

        <div class="mb-8">
            <div class="text-[9px] tracking-[3px] text-copy-ticker mb-1">V_01_TERMINAL_PREVIEW</div>
            <h2 class="font-sans font-bold text-3xl tracking-[3px] text-copy-soft">DER KATALOG — LIVE</h2>
        </div>

        <div class="grid grid-cols-3 gap-4">
            {{-- Premium Slot --}}
            <div class="col-span-1 bg-ink-panel border border-line-warm relative overflow-hidden group">
                <div class="absolute top-2 left-2 z-10 bg-brand-yellow px-2 py-0.5 text-[8px] tracking-[1.5px] text-black font-bold">PREMIUM SLOT</div>
                <div class="h-48 bg-ink-surface flex items-center justify-center text-copy-ticker text-4xl">◈</div>
                <div class="p-3">
                    <div class="text-[8px] tracking-[1.5px] text-copy-ticker">PREMIUM SLOT // #4.8 SCORE</div>
                    <div class="text-[12px] tracking-wider text-copy-soft mt-1">HYPERION GT-X</div>
                    <div class="text-[9px] text-copy-ticker mt-1">R_TYPE: VEHICLE</div>
                </div>
            </div>

            {{-- Organic Ad --}}
            <div class="col-span-1 bg-ink-panel border border-line-warm relative overflow-hidden">
                <div class="h-48 bg-ink-surface flex items-center justify-center text-copy-ticker text-4xl">▦</div>
                <div class="p-3">
                    <div class="text-[8px] tracking-[1.5px] text-copy-ticker">ORGANISCHES RANKING</div>
                    <div class="text-[12px] tracking-wider text-copy-soft mt-1">VISION-CORE V2</div>
                    <div class="text-[9px] text-copy-ticker mt-1">R_TYPE: TECH</div>
                </div>
            </div>

            {{-- Hotspot --}}
            <div class="col-span-1 bg-ink-panel border border-line-warm relative overflow-hidden flex flex-col items-center justify-center py-12">
                <div class="text-brand-red text-4xl mb-3">◉</div>
                <div class="text-[10px] tracking-[2px] text-copy-neutral">HOTSPOTS</div>
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-1">CHECK CURRENT MARKET ANOMALIES</div>
            </div>
        </div>

    </div>
</section>

{{-- ④ 4 ZONEN --}}
<section class="bg-ink-dark border-t border-line-warm py-12 px-6">
    <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['zone'=>'ZONE_01','title'=>'PREMIUM SLOTS','desc'=>'Exklusive Platzierung für Validierte Partner.'],
            ['zone'=>'ZONE_02','title'=>'OPEN CATALOG','desc'=>'Durchsuche 14.000+ organische Einträge.'],
            ['zone'=>'ZONE_03','title'=>'TICKER','desc'=>'Echtzeit-Updates der Werbe-Landschaft.'],
            ['zone'=>'ZONE_04','title'=>'HOTSPOTS','desc'=>'Wo die Aufmerksamkeit gerade brennt.'],
        ] as $zone)
            <div class="border border-line-warm p-4 hover:border-brand-yellow transition-colors group">
                <div class="text-[9px] tracking-[2px] text-copy-ticker mb-2 group-hover:text-brand-yellow transition-colors">{{ $zone['zone'] }}</div>
                <div class="font-sans font-bold text-sm tracking-[2px] text-copy-soft mb-2">{{ $zone['title'] }}</div>
                <p class="text-[10px] tracking-wider text-copy-neutral leading-relaxed">{{ $zone['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ⑤ MERCHANT PITCH — dunkelrot --}}
<section class="bg-ink-dark py-20 px-6 border-t border-line-warm">
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        <div>
            <div class="text-[9px] tracking-[3px] text-copy-ticker mb-4">FOR_MERCHANTS</div>
            <h2 class="font-sans font-bold text-4xl tracking-[2px] text-copy-soft leading-tight mb-8">
                EFFIZIENZ OHNE<br>STREUVERLUST.
            </h2>
            <div class="space-y-4 mb-8">
                @foreach([
                    ['title'=>'Keine Fixkosten','desc'=>'Zahle nur für echte Pull-Interaktionen.'],
                    ['title'=>'Transparente Rankings','desc'=>'Dein Erfolg basiert auf Nutzerfeedback, nicht auf Gebotschlachten.'],
                    ['title'=>'Direkt-Vertrieb','desc'=>'Kein Mittelsmann, kein Tracking-Pixel-Wahnsinn.'],
                ] as $point)
                    <div class="flex items-start gap-3">
                        <span class="text-brand-yellow mt-0.5 flex-shrink-0">☑</span>
                        <div>
                            <div class="text-[11px] tracking-wider text-copy-soft">{{ $point['title'] }}</div>
                            <div class="text-[10px] tracking-wider text-copy-neutral mt-0.5">{{ $point['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('register') }}"
               class="inline-block font-sans font-bold text-sm tracking-[3px] uppercase bg-brand-red hover:bg-red-700 text-white px-8 py-4 transition-colors">
                JETZT ALS HÄNDLER REGISTRIEREN →
            </a>
        </div>

        {{-- Dashboard Mockup --}}
        <div class="border border-line-warm bg-ink-panel p-4">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-line-warm">
                <span class="w-2 h-2 bg-brand-red rounded-full"></span>
                <span class="text-[9px] tracking-[2px] text-copy-ticker">COMMAND_CENTER v4.0</span>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-ink-surface p-3 border border-line-warm">
                    <div class="text-[8px] tracking-[1.5px] text-copy-ticker mb-1">PULL_REVENUE</div>
                    <div class="font-sans font-bold text-xl text-copy-soft">€42,180.00</div>
                </div>
                <div class="bg-ink-surface p-3 border border-line-warm">
                    <div class="text-[8px] tracking-[1.5px] text-copy-ticker mb-1">CONVERSION_RATE</div>
                    <div class="font-sans font-bold text-xl text-brand-yellow">12.4%</div>
                </div>
            </div>
            <div class="h-16 flex items-end gap-1 border-b border-l border-line-warm px-1 pb-1">
                @foreach([40,60,45,75,55,80,65,90,70,85,75,95,80,100,85] as $h)
                    <div class="flex-1 bg-brand-red/50 hover:bg-brand-red transition-colors" style="height:{{ $h }}%"></div>
                @endforeach
            </div>
        </div>

    </div>
</section>

{{-- ⑥ MANIFEST QUOTE — schwarz --}}
<section class="bg-black py-24 px-6 border-t border-line-warm">
    <div class="max-w-3xl mx-auto text-center">
        <div class="text-[9px] tracking-[4px] text-copy-ticker mb-8">SYSTEM_MANIFESTO_0.1</div>
        <blockquote class="font-sans font-bold text-3xl md:text-4xl tracking-[2px] text-copy-soft leading-tight mb-6">
            "DAS INTERNET HAT EIN WERBEPROBLEM.<br>WIR HABEN EINE LÖSUNG."
        </blockquote>
        <a href="https://nopushads.com" target="_blank"
           class="text-[10px] tracking-[2px] text-copy-ticker hover:text-brand-yellow transition-colors underline underline-offset-4">
            READ OUR FULL STATEMENT AT NOPUSHADS.COM
        </a>
    </div>
</section>

{{-- ⑦ FOOTER — schwarz --}}
<footer class="bg-black border-t border-line-warm px-7 py-12">
    <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">

        <div>
            <div class="font-sans font-bold text-xl italic tracking-[2px] text-brand-red mb-3 logo-blink">ADSAPP.STORE</div>
            <p class="text-[10px] tracking-wider text-copy-ticker leading-relaxed">
                Pull-basierter Werbe-Katalog für Pull-Nutzer. Keine Algorithmen. Nur Transparenz.
            </p>
        </div>

        <div>
            <div class="text-[9px] tracking-[3px] text-copy-neutral mb-4">NAVIGATION</div>
            <div class="space-y-2">
                @foreach(['KATALOG','HOTSPOTS','TICKER','HÄNDLER WERDEN','MANIFEST'] as $link)
                    <div><a href="#" class="text-[10px] tracking-wider text-copy-ticker hover:text-brand-yellow transition-colors">{{ $link }}</a></div>
                @endforeach
            </div>
        </div>

        <div>
            <div class="text-[9px] tracking-[3px] text-copy-neutral mb-4">RESOURCES</div>
            <div class="space-y-2">
                @foreach(['HÄNDLER PORTAL','API DOKUMENTATION','HÄNDLER AGB'] as $link)
                    <div><a href="#" class="text-[10px] tracking-wider text-copy-ticker hover:text-brand-yellow transition-colors">{{ $link }}</a></div>
                @endforeach
            </div>
        </div>

        <div>
            <div class="text-[9px] tracking-[3px] text-copy-neutral mb-4">CONNECT</div>
            <div class="space-y-2">
                @foreach(['GITHUB','X / TWITTER','DISCORD'] as $link)
                    <div><a href="#" class="text-[10px] tracking-wider text-copy-ticker hover:text-brand-yellow transition-colors">{{ $link }}</a></div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="max-w-5xl mx-auto mt-10 pt-6 border-t border-line-warm flex items-center justify-between">
        <div class="text-[9px] tracking-[1.5px] text-copy-ticker">© 2024 ADSAPP_TERMINAL. ALL RIGHTS RESERVED.</div>
        <div class="flex gap-6 text-[9px] tracking-[1.5px] text-copy-ticker">
            <a href="#" class="hover:text-brand-yellow transition-colors">IMPRESSUM</a>
            <a href="#" class="hover:text-brand-yellow transition-colors">DATENSCHUTZ</a>
            <a href="#" class="hover:text-brand-yellow transition-colors">AGB</a>
        </div>
    </div>
</footer>

</body>
</html>
