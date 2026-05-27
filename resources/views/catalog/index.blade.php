<x-buyer-app-layout>

    <x-catalog.filter-bar />

    <div class="flex">

        {{-- ═══ MAIN FEED ═══ --}}
        <div class="flex-1 p-4 space-y-4 min-w-0">

            {{-- PREMIUM STRIP — Top 3 nach Score --}}
            @if($premiumAds->isNotEmpty())
                <div class="grid grid-cols-3 gap-3">
                    @foreach($premiumAds as $i => $ad)
                        <x-catalog.premium-slot :ad="$ad" :rank="$i + 1" />
                    @endforeach
                </div>
            @endif

            {{-- ORGANIC GRID — Rang 4+ --}}
            @if($organicAds->isNotEmpty())
                <div class="grid grid-cols-3 gap-3">
                    @foreach($organicAds->take(5) as $i => $ad)
                        @php
                            $pos  = $i + 4; // Rang beginnt bei 4
                            $size = match($i) {
                                0       => 'featured',
                                1, 2    => 'medium',
                                default => 'small',
                            };
                        @endphp
                        <x-catalog.ad-card
                            :ad="$ad"
                            :size="$size"
                            :rank="$pos"
                            :bookmarked="in_array($ad->id, $bookmarkedIds)"
                        />
                    @endforeach
                </div>
            @endif

            {{-- HOTSPOT — Highest Score --}}
            @if($hotspot)
                <x-catalog.hotspot-banner :ad="$hotspot" />
            @endif

            {{-- MORE ADS — Rest ab Rang 9 --}}
            @php $moreAds = $organicAds->skip(5); @endphp
            @if($moreAds->isNotEmpty())
                <div class="grid grid-cols-4 gap-3">
                    @foreach($moreAds as $i => $ad)
                        <x-catalog.ad-card
                            :ad="$ad"
                            size="mini"
                            :rank="$i + 9"
                            :bookmarked="in_array($ad->id, $bookmarkedIds)"
                        />
                    @endforeach
                </div>
            @endif

            {{-- EMPTY STATE --}}
            @if($premiumAds->isEmpty() && $organicAds->isEmpty())
                <div class="flex flex-col items-center justify-center py-32 space-y-3">
                    <div class="text-[9px] tracking-[3px]" style="color:#454745;">NO_ACTIVE_ADS_FOUND</div>
                    <div class="text-[8px] tracking-[2px]" style="color:#2a2a2a;">SIGNAL_EMPTY // CATALOG_VOID</div>
                </div>
            @endif

            {{-- INFINITE SCROLL TRIGGER (Placeholder für später) --}}
            <div id="infinite-scroll-trigger" class="py-10 flex items-center justify-center">
                <div class="flex items-center gap-2 text-[9px] tracking-[2px]" style="color:#454745;">
                    <span class="live-dot w-1.5 h-1.5 rounded-full inline-block" style="background:#454745;"></span>
                    LOADING_MORE_DATA...
                </div>
            </div>

        </div>

        {{-- RIGHT PANEL --}}
        <x-catalog.right-panel :ads="$rightPanelAds" />

    </div>

</x-buyer-app-layout>
