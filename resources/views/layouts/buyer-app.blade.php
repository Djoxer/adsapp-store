<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AdsApp') }} — Catalog</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* CRT scanline — auf einem eigenen div, KEIN pseudo-element, kein fixed stacking context */
        #crt-overlay {
            position: fixed;
            inset: 0;
            pointer-events: none;
            /* z-index bewusst UNTER den Overlays */
            z-index: 100;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0,0,0,0.03) 2px,
                rgba(0,0,0,0.03) 4px
            );
        }

        :root {
            --sidebar-collapsed: 50px;
            --sidebar-expanded:  210px;
            --topbar-h:          64px;
            --ticker-h:          50px;
        }

        #catalog-main::-webkit-scrollbar       { width: 3px; }
        #catalog-main::-webkit-scrollbar-track { background: transparent; }
        #catalog-main::-webkit-scrollbar-thumb { background: #3a2a2a; }
    </style>
</head>
<body class="font-mono" style="background:#222222;color:#A1A1AA;height:100vh;width:100vw;">

{{-- CRT Scanline als echtes Element statt ::before --}}
<div id="crt-overlay"></div>

<x-buyer.topbar />
<x-buyer.sidebar />

{{-- MAIN WRAP --}}
<div id="catalog-wrap" class="fixed right-0 bottom-[50px] top-[64px]"
     style="left:var(--sidebar-collapsed); transition:left 0.28s cubic-bezier(0.4,0,0.2,1); min-width:900px;">
    <div id="catalog-main" class="h-full overflow-y-auto overflow-x-auto">
        {{ $slot }}
    </div>
</div>

<x-buyer.ticker />

{{-- Overlays — direkt in body, außerhalb aller scroll/overflow Container --}}
<x-buyer.ad-overlay />
<x-profile-overlay />

@guest
    <div id="beta-notice" class="hidden fixed inset-0 z-[99999] flex items-center justify-center"
         style="background:rgba(0,0,0,0.85);pointer-events:all;">
        <div class="w-full max-w-[480px] mx-4 font-mono"
             style="background:#111111;border:1px solid #2a2a2a;box-shadow:0 0 60px rgba(245,183,0,0.2);">

            <div class="px-6 py-4" style="border-bottom:1px solid #2a2a2a;">
                <div class="text-[9px] tracking-[2px] mb-1" style="color:#454745;">SYSTEM_NOTICE // STATUS</div>
                <div class="text-[18px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">PORTFOLIO PROJECT</div>
            </div>

            <div class="px-6 py-5">
                <p class="text-[11px] leading-relaxed tracking-wider mb-4" style="color:#A1A1AA;">
                    Diese Plattform ist ein <span style="color:#F5B700;">Capstone-Projekt</span> im Rahmen einer
                    Fachinformatiker-Umschulung und befindet sich aktiv in Entwicklung.
                </p>
                <p class="text-[11px] leading-relaxed tracking-wider" style="color:#A1A1AA;">
                    Inhalte, Funktionen und Daten sind zu Demo-Zwecken. Nicht alle Features sind
                    vollständig implementiert.
                </p>
            </div>

            <div class="px-6 py-4 flex items-center justify-between" style="border-top:1px solid #2a2a2a;">
                <div class="text-[9px] tracking-[2px]" style="color:#454745;">BUILD: ALPHA · MVP</div>
                <button onclick="dismissBetaNotice()"
                        class="px-5 py-2 text-[10px] tracking-[2px] font-sans font-bold transition-colors"
                        style="background:#DC2626;color:white;"
                        onmouseover="this.style.background='#FF535B'"
                        onmouseout="this.style.background='#DC2626'">
                    VERSTANDEN &rarr;
                </button>
            </div>
        </div>
    </div>
@endguest

<script>
    function openAdOverlayFromCard(el) {
        openAdOverlay({
            id:          el.dataset.adId,
            title:       el.dataset.adTitle,
            price:       el.dataset.adPrice,
            rank:        el.dataset.adRank  || null,
            score:       el.dataset.adScore || '',
            merchant:    el.dataset.adMerchant,
            description: el.dataset.adDescription,
            bookmarked:  el.dataset.adBookmarked === 'true',
            image:       el.dataset.adImage || ''
        });
    }

    (function() {
        const KEY = 'adsapp_beta_seen';
        if (!localStorage.getItem(KEY)) {
            document.getElementById('beta-notice').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        window.dismissBetaNotice = function() {
            localStorage.setItem(KEY, '1');
            document.getElementById('beta-notice').classList.add('hidden');
            document.body.style.overflow = '';
        };
    })();
</script>

</body>
</html>
