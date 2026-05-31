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
</script>

</body>
</html>
