<x-buyer-app-layout>
    <x-catalog.filter-bar :categories="collect()" :activeCategory="null" :sort="'score'" />

    <div class="px-6 py-5 flex gap-5">

        {{-- ═══ MAIN ═══ --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- HOTSPOT HEADER --}}
            <div class="relative overflow-hidden" style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #DC2626;">
                <div class="relative h-48 flex items-center justify-center overflow-hidden" style="background:#0a0a0a;">
                    @if($hotspot->hero_image)
                        <img src="{{ $hotspot->hero_image }}" class="w-full h-full object-cover" style="filter:grayscale(0.4);">
                    @else
                        <span class="text-[56px]">{{ $hotspot->icon ?? '🔥' }}</span>
                    @endif

                    @if($hotspot->days_left !== null)
                        <div class="absolute top-3 left-3 px-3 py-1 text-[10px] font-sans font-bold tracking-[1.5px]"
                             style="background:#F5B700;color:#0a0a0a;">
                            NOCH {{ str_pad($hotspot->days_left, 2, '0', STR_PAD_LEFT) }} TAGE
                        </div>
                    @else
                        <div class="absolute top-3 left-3 px-3 py-1 text-[10px] font-sans font-bold tracking-[1.5px]"
                             style="background:#43d685;color:#0a0a0a;">
                            DAUERHAFT
                        </div>
                    @endif

                    @if($hotspot->ends_soon)
                        <div class="absolute top-3 right-3 px-3 py-1 text-[10px] font-sans font-bold tracking-[1.5px] animate-pulse"
                             style="background:#DC2626;color:white;">
                            ENDET BALD
                        </div>
                    @endif

                    {{-- Typ Badge --}}
                    <div class="absolute bottom-3 left-3 px-2 py-1 text-[9px] tracking-[1.5px]"
                         style="background:rgba(0,0,0,0.7);border:1px solid #2a2a2a;color:#A1A1AA;">
                        ZONE_04 // HOTSPOT &nbsp;·&nbsp; TYPE: {{ strtoupper($hotspot->type) }}
                    </div>
                </div>

                <div class="p-5 flex items-start justify-between gap-4">
                    <div>
                        <div class="text-[20px] font-sans font-bold tracking-[1px] mb-1" style="color:#e8e8e8;">
                            {{ $hotspot->name }}
                        </div>
                        @if($hotspot->subtitle)
                            <div class="text-[12px] tracking-[1px] mb-2" style="color:#A1A1AA;">{{ $hotspot->subtitle }}</div>
                        @endif
                        @if($hotspot->description)
                            <div class="text-[11px] leading-relaxed" style="color:#454745;">{{ $hotspot->description }}</div>
                        @endif
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <div class="text-[9px] tracking-[1.5px] mb-1" style="color:#454745;">ID: HS_{{ str_pad($hotspot->id,4,'0',STR_PAD_LEFT) }}</div>
                        <div class="text-[22px] font-sans font-bold" style="color:#DC2626;">{{ $hotspot->ads_count }}</div>
                        <div class="text-[9px] tracking-[1.5px]" style="color:#454745;">ADS</div>
                    </div>
                </div>
            </div>

            {{-- ADS GRID --}}
            @if($ads->isNotEmpty())
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-1 h-4" style="background:#DC2626;"></span>
                    <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#F5B700;">
                        ADS IN DIESEM HOTSPOT
                    </span>
                    <span class="text-[10px] tracking-[1px] ml-2" style="color:#454745;">
                        {{ $ads->count() }} EINTRÄGE // SORTIERT NACH SCORE
                    </span>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:0.75rem;">
                    @foreach($ads as $i => $ad)
                        <x-catalog.ad-card
                            :ad="$ad"
                            :rank="$i + 1"
                            :bookmarked="in_array($ad->id, $bookmarkedIds)"
                        />
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-24 gap-3"
                     style="background:#141414;border:1px solid #2a2a2a;">
                    <span class="text-[32px]">📭</span>
                    <div class="text-[10px] tracking-[2px]" style="color:#454745;">KEINE AKTIVEN ADS IN DIESEM HOTSPOT</div>
                </div>
            @endif
        </div>

        {{-- ═══ RIGHT PANEL ═══ --}}
        <aside class="w-72 flex-shrink-0 space-y-4">

            {{-- BACK --}}
            <a href="{{ route('catalog.hotspots') }}"
               class="flex items-center gap-2 px-4 py-3 text-[10px] tracking-[1.5px] transition-colors"
               style="background:#141414;border:1px solid #2a2a2a;color:#A1A1AA;"
               onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
               onmouseout="this.style.borderColor='#2a2a2a';this.style.color='#A1A1AA'">
                ← ALLE HOTSPOTS
            </a>

            {{-- HOTSPOT INFO --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-4 py-3" style="border-bottom:1px solid #2a2a2a;">
                    <div class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8e8e8;">HOTSPOT INFO</div>
                </div>
                <div class="p-4 space-y-3 text-[10px] tracking-[1px]">
                    <div class="flex justify-between">
                        <span style="color:#454745;">STATUS</span>
                        <span style="color:#43d685;">● AKTIV</span>
                    </div>
                    <div class="flex justify-between" style="border-top:1px solid #1e1e1e;padding-top:12px;">
                        <span style="color:#454745;">TYP</span>
                        <span style="color:#A1A1AA;">{{ strtoupper($hotspot->type) }}</span>
                    </div>
                    @if($hotspot->opens_at)
                        <div class="flex justify-between" style="border-top:1px solid #1e1e1e;padding-top:12px;">
                            <span style="color:#454745;">GEÖFFNET</span>
                            <span style="color:#A1A1AA;">{{ $hotspot->opens_at->format('d.m.Y') }}</span>
                        </div>
                    @endif
                    @if($hotspot->closes_at)
                        <div class="flex justify-between" style="border-top:1px solid #1e1e1e;padding-top:12px;">
                            <span style="color:#454745;">ENDET</span>
                            <span style="color:{{ $hotspot->ends_soon ? '#DC2626' : '#A1A1AA' }};">
                            {{ $hotspot->closes_at->format('d.m.Y') }}
                        </span>
                        </div>
                    @endif
                    <div class="flex justify-between" style="border-top:1px solid #1e1e1e;padding-top:12px;">
                        <span style="color:#454745;">ADS</span>
                        <span class="font-sans font-bold text-[13px]" style="color:#e8e8e8;">{{ $hotspot->ads_count }}</span>
                    </div>
                </div>
            </div>

            {{-- ANDERE AKTIVE HOTSPOTS --}}
            @php
                $otherHotspots = \App\Models\Hotspot::active()
                    ->where('id', '!=', $hotspot->id)
                    ->withCount('ads')
                    ->take(4)
                    ->get();
            @endphp
            @if($otherHotspots->isNotEmpty())
                <div style="background:#141414;border:1px solid #2a2a2a;">
                    <div class="px-4 py-3" style="border-bottom:1px solid #2a2a2a;">
                        <div class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8e8e8;">WEITERE HOTSPOTS</div>
                    </div>
                    <div class="divide-y" style="border-color:#1e1e1e;">
                        @foreach($otherHotspots as $h)
                            <a href="{{ route('catalog.hotspot.show', $h->slug) }}"
                               class="flex items-center gap-3 px-4 py-3 transition-colors"
                               style="color:#A1A1AA;"
                               onmouseover="this.style.background='#1a1a1a';this.style.color='#F5B700'"
                               onmouseout="this.style.background='transparent';this.style.color='#A1A1AA'">
                                <span class="text-[20px]">{{ $h->icon ?? '🔥' }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[10px] font-sans font-bold tracking-[1px] truncate">
                                        {{ strtoupper(str_replace(' ','_',$h->name)) }}
                                    </div>
                                    <div class="text-[9px] tracking-[1px] mt-0.5" style="color:#454745;">
                                        {{ $h->ads_count }} ADS
                                        @if($h->days_left !== null)
                                            · NOCH {{ $h->days_left }}T
                                        @else
                                            · DAUERHAFT
                                        @endif
                                    </div>
                                </div>
                                <span style="font-size:10px;color:#454745;">→</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</x-buyer-app-layout>
