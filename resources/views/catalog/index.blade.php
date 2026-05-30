<x-buyer-app-layout>

    <x-catalog.filter-bar :categories="$categories" :activeCategory="$activeCategory" :sort="$sort" />

    <div class="flex">

        {{-- ═══ MAIN FEED ═══ --}}
        <div class="flex-1 p-4 space-y-4 min-w-0">

            {{-- PREMIUM STRIP — Zone A (FIFO Top-Strip, max 3, feste 3 Spalten, ohne Rang) --}}
            @if($premiumZoneA->isNotEmpty())
                <div class="grid grid-cols-3 gap-3">
                    @foreach($premiumZoneA as $i => $ad)
                        <x-catalog.premium-slot :ad="$ad" :rank="$i + 1" />
                    @endforeach
                </div>
            @endif

            {{-- FEATURED HOTSPOT — kuratierter Aufmacher, voller Streifen über dem Raster --}}
            @if($featuredHotspot)
                <x-catalog.hotspot-banner :hotspot="$featuredHotspot" />
            @endif

            {{-- ORGANIC GRID — ein responsives Raster, Karten gleich groß, Rang ab #1.
                 auto-fill: mehr Spalten bei mehr Breite statt größerer Karten. --}}
            @if($organicAds->isNotEmpty())
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:0.75rem;">
                    @foreach($organicAds as $i => $ad)
                        <x-catalog.ad-card
                            :ad="$ad"
                            :rank="$i + 1"
                            :bookmarked="in_array($ad->id, $bookmarkedIds)"
                        />

                        {{-- Nach jeder 8. Ad eine kompakte Hotspot-Karte einstreuen (zyklisch) --}}
                        @if(($i + 1) % 8 === 0 && $catalogHotspots->isNotEmpty())
                            @php
                                $hsIndex = intdiv($i + 1, 8) - 1;
                                $promoHotspot = $catalogHotspots[$hsIndex % $catalogHotspots->count()];
                            @endphp
                            <x-catalog.hotspot-promo :hotspot="$promoHotspot" />
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- EMPTY STATE --}}
            @if($premiumZoneA->isEmpty() && $organicAds->isEmpty())
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

        {{-- RIGHT PANEL — Hotspots + Premium Zone B --}}
        <x-catalog.right-panel :premiumSlots="$premiumZoneB" :hotspots="$catalogHotspots" />

    </div>

</x-buyer-app-layout>
