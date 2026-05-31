<style>
    @keyframes tickerScroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.8)} }
    .ticker-track { animation: tickerScroll 60s linear infinite; }
    .live-dot     { animation: pulse-dot 1.5s ease-in-out infinite; }
    .ticker-thumb { width:32px; height:32px; background:#1e1e1e; border:1px solid #2a2a2a; flex-shrink:0; overflow:hidden; }
</style>

@php
    // Neueste 12 aktive Ads — direkt hier laden, kein Controller nötig
    $tickerAds = \App\Models\Ad::with('images')
        ->where('status', 'active')
        ->orderByDesc('created_at')
        ->limit(12)
        ->get();
@endphp

<div class="fixed bottom-0 left-0 right-0 z-[150] flex items-center overflow-hidden"
     style="height:50px; background:#141414; border-top:1px solid rgba(245,183,0,0.55);">

    {{-- Label --}}
    <div class="flex-shrink-0 h-full flex flex-col items-center justify-center px-5 gap-1"
         style="background:#F5B700; border-right:1px solid #c49500; min-width:115px;">
        <div class="flex items-center gap-1.5">
            <span class="live-dot w-1.5 h-1.5 rounded-full inline-block" style="background:#DC2626;"></span>
            <span class="text-[9px] tracking-[2px] font-bold" style="color:#0a0505; font-family:'Rajdhani',sans-serif;">LIVE_FEED</span>
        </div>
        <span class="text-[8px] tracking-[1px]" style="color:#7a5500; font-family:'Share Tech Mono',monospace;">// NEUE_ADS</span>
    </div>

    {{-- Scroll --}}
    <div class="overflow-hidden flex-1 h-full flex items-center">
        <div class="ticker-track flex whitespace-nowrap items-center">
            {{-- Doppelt für nahtloses Loop --}}
            @foreach([1,2] as $r)
                @foreach($tickerAds as $ad)
                    @php
                        $score  = (float) $ad->current_score;
                        $isUp   = $score >= 50;
                        $image  = $ad->images->first()?->cache_path;
                        // Titel kürzen für Ticker
                        $label  = strtoupper(Str::limit($ad->title, 18, ''));
                    @endphp
                    <div class="flex items-center gap-3 px-4 h-full cursor-pointer group"
                         style="border-right:1px solid #1a1a1a;"
                         data-ad-id="{{ $ad->id }}"
                         data-ad-title="{{ e($ad->title) }}"
                         data-ad-price="{{ number_format($ad->price_cents/100,2,',','.') }} €"
                         data-ad-rank=""
                         data-ad-score="{{ number_format($score,1) }}"
                         data-ad-merchant="{{ e($ad->merchant->company_name ?? '') }}"
                         data-ad-description="{{ e($ad->description) }}"
                         data-ad-image="{{ $image ? asset('storage/' . $image) : '' }}"
                         data-ad-bookmarked="false"
                         onclick="openAdOverlayFromCard(this)">
                        <div class="ticker-thumb flex items-center justify-center text-[7px]" style="color:#2a2a2a;">
                            @if($image)
                                <img src="{{ Storage::url($image) }}" class="w-full h-full object-cover">
                            @else
                                IMG
                            @endif
                        </div>
                        <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] tracking-wider transition-colors group-hover:text-brand-yellow"
                          style="color:#e8e8e8;">{{ $label }}</span>
                            <span class="text-[9px] tracking-wider"
                                  style="color:{{ $isUp ? '#F5B700' : '#DC2626' }};">
                        SCORE {{ number_format($score,1) }}
                    </span>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

</div>
