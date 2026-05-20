<style>
    @keyframes tickerScroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.8)} }
    .ticker-track { animation: tickerScroll 60s linear infinite; }
    .live-dot     { animation: pulse-dot 1.5s ease-in-out infinite; }
    .ticker-thumb { width:32px; height:32px; background:#1e1e1e; border:1px solid #2a2a2a; flex-shrink:0; object-fit:cover; }
</style>

<div class="fixed bottom-0 left-0 right-0 z-[150] flex items-center overflow-hidden"
     style="height:50px; background:#0a0505; border-top:1px solid rgba(245,183,0,0.55);">

    {{-- Label --}}
    <div class="flex-shrink-0 h-full flex flex-col items-center justify-center px-5 gap-1"
         style="background:#F5B700; border-right:1px solid #c49500; min-width:115px;">
        <div class="flex items-center gap-1.5">
            <span class="live-dot w-1.5 h-1.5 rounded-full inline-block" style="background:#DC2626;"></span>
            <span class="text-[9px] tracking-[2px] font-bold" style="color:#0a0505; font-family:'Rajdhani',sans-serif;">LIVE_FEED</span>
        </div>
        <span class="text-[8px] tracking-[1px]" style="color:#7a5500; font-family:'Share Tech Mono',monospace;">// TRENDING_HEUTE</span>
    </div>

    {{-- Scroll --}}
    <div class="overflow-hidden flex-1 h-full flex items-center">
        <div class="ticker-track flex whitespace-nowrap items-center">
            @php
            $tickerAds = [
                ['name'=>'HYPERION GT-X',  'change'=>'+44.2%','up'=>true],
                ['name'=>'VISION-CORE-U',  'change'=>'+18.5%','up'=>true],
                ['name'=>'TERRA-FORM-P',   'change'=>'+7.1%', 'up'=>true],
                ['name'=>'NEXUS_S',        'change'=>'+102%', 'up'=>true],
                ['name'=>'SHORT_SHELL',    'change'=>'-12.3%','up'=>false],
                ['name'=>'STATIC_AUDIO',   'change'=>'-8.1%', 'up'=>false],
                ['name'=>'NIGHT-PROWL X',  'change'=>'+22.7%','up'=>true],
                ['name'=>'BIO-GEL PACK',   'change'=>'+5.3%', 'up'=>true],
                ['name'=>'CORE_SWITCH',    'change'=>'+11.0%','up'=>true],
                ['name'=>'VOID_HEADSET',   'change'=>'-3.2%', 'up'=>false],
            ];
            @endphp
            @foreach([1,2] as $r)
            @foreach($tickerAds as $t)
            <div class="flex items-center gap-3 px-4 h-full" style="border-right:1px solid #1a1a1a;">
                <div class="ticker-thumb flex items-center justify-center text-[7px]" style="color:#2a2a2a;">IMG</div>
                <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] tracking-wider" style="color:#e8e8e8;">{{ $t['name'] }}</span>
                    <span class="text-[9px] tracking-wider" style="color:{{ $t['up'] ? '#F5B700' : '#DC2626' }};">{{ $t['change'] }}</span>
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
    </div>

</div>
