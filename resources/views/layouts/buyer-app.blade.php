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
        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 9999;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.03) 2px, rgba(0,0,0,0.03) 4px);
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
<body class="font-mono h-screen overflow-hidden" style="background:#222222;color:#A1A1AA;">

<x-buyer.topbar />
<x-buyer.sidebar />

{{-- MAIN WRAP --}}
<div id="catalog-wrap" class="fixed right-0 bottom-[50px] top-[64px]"
     style="left:var(--sidebar-collapsed); transition:left 0.28s cubic-bezier(0.4,0,0.2,1);">
    <div id="catalog-main" class="h-full overflow-y-auto overflow-x-hidden">
        {{ $slot }}
    </div>
</div>

<x-buyer.ticker />
<x-buyer.ad-overlay />

</body>
</html>
