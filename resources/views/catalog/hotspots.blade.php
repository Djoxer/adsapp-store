<x-buyer-app-layout>
    <x-catalog.filter-bar />

    <div class="px-6 py-5 flex gap-5">

        {{-- ═══════ MAIN ═══════ --}}
        <div class="flex-1 min-w-0 space-y-8">

            {{-- ACTIVE DEPLOYMENTS --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1 h-4" style="background:#DC2626;"></span>
                    <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#F5B700;">ACTIVE DEPLOYMENTS</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @forelse($active as $h)
                        <div class="relative overflow-hidden transition-colors"
                             style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #DC2626;">

                            {{-- Hero --}}
                            <div class="relative h-44 flex items-center justify-center overflow-hidden" style="background:#0a0a0a;">
                                @if($h->hero_image)
                                    <img src="{{ $h->hero_image }}" class="w-full h-full object-cover" style="filter:grayscale(0.4);">
                                @else
                                    <span class="text-[40px]">{{ $h->icon ?? '🔥' }}</span>
                                @endif

                                {{-- Countdown Badge --}}
                                @if($h->days_left !== null)
                                    <div class="absolute top-3 left-3 px-3 py-1 text-[10px] font-sans font-bold tracking-[1.5px]"
                                         style="background:#F5B700;color:#0a0a0a;">
                                        NOCH {{ str_pad($h->days_left, 2, '0', STR_PAD_LEFT) }} TAGE
                                    </div>
                                @else
                                    <div class="absolute top-3 left-3 px-3 py-1 text-[10px] font-sans font-bold tracking-[1.5px]"
                                         style="background:#43d685;color:#0a0a0a;">
                                        DAUERHAFT
                                    </div>
                                @endif

                                {{-- Endet bald --}}
                                @if($h->ends_soon)
                                    <div class="absolute top-3 right-3 px-3 py-1 text-[10px] font-sans font-bold tracking-[1.5px] animate-pulse"
                                         style="background:#DC2626;color:white;">
                                        ENDET BALD
                                    </div>
                                @endif
                            </div>

                            {{-- Body --}}
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-[15px] font-sans font-bold tracking-[1px]" style="color:#DC2626;">
                                        {{ strtoupper(str_replace(' ','_',$h->name)) }} // HOTSPOT
                                    </span>
                                    <span class="text-[9px] tracking-[1.5px]" style="color:#454745;">ID: HS_{{ str_pad($h->id,4,'0',STR_PAD_LEFT) }}</span>
                                </div>

                                {{-- Stats --}}
                                <div class="grid grid-cols-3 gap-2 py-4" style="border-top:1px solid #2a2a2a;border-bottom:1px solid #2a2a2a;">
                                    <div class="text-center">
                                        <div class="text-[9px] tracking-[1.5px] mb-1" style="color:#454745;">ADS</div>
                                        <div class="text-[18px] font-sans font-bold" style="color:#e8e8e8;">{{ number_format($h->ads_count,0,',','.') }}</div>
                                    </div>
                                    <div class="text-center" style="border-left:1px solid #2a2a2a;border-right:1px solid #2a2a2a;">
                                        <div class="text-[9px] tracking-[1.5px] mb-1" style="color:#454745;">VIEWS</div>
                                        <div class="text-[18px] font-sans font-bold" style="color:#e8e8e8;">{{ $h->total_views > 999 ? round($h->total_views/1000,1).'K' : $h->total_views }}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[9px] tracking-[1.5px] mb-1" style="color:#454745;">BOOKMARKS</div>
                                        <div class="text-[18px] font-sans font-bold" style="color:#e8e8e8;">{{ number_format($h->total_bookmarks,0,',','.') }}</div>
                                    </div>
                                </div>

                                {{-- CTA --}}
                                <a href="#"
                                   class="block mt-4 text-center py-3 text-[11px] font-sans font-bold tracking-[2px] transition-colors"
                                   style="background:#DC2626;color:white;"
                                   onmouseover="this.style.background='#FF535B'"
                                   onmouseout="this.style.background='#DC2626'">
                                    ENTER HOTSPOT →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 flex flex-col items-center justify-center py-16 gap-3" style="background:#141414;border:1px solid #2a2a2a;">
                            <span class="text-[32px]">🔥</span>
                            <div class="text-[10px] tracking-[2px]" style="color:#454745;">KEINE AKTIVEN HOTSPOTS</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- COMING SOON --}}
            @if($upcoming->isNotEmpty())
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-1 h-4" style="background:#F5B700;"></span>
                        <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">COMING SOON</span>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        @foreach($upcoming as $h)
                            <div class="relative overflow-hidden" style="background:#141414;border:1px solid #2a2a2a;">
                                <div class="relative h-36 flex items-center justify-center overflow-hidden" style="background:#0a0a0a;">
                                    @if($h->hero_image)
                                        <img src="{{ $h->hero_image }}" class="w-full h-full object-cover" style="filter:grayscale(1) blur(2px);opacity:0.5;">
                                    @else
                                        <span class="text-[32px]" style="opacity:0.4;">{{ $h->icon ?? '🔒' }}</span>
                                    @endif
                                    {{-- Lock Overlay --}}
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-7 h-7" fill="none" stroke="#454745" stroke-width="1.5" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="1"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="text-[10px] tracking-[1.5px] mb-1" style="color:#F5B700;">{{ $h->opens_at->format('d.m.Y') }}</div>
                                    <div class="text-[12px] font-sans font-bold tracking-[1px]" style="color:#e8e8e8;">{{ strtoupper(str_replace(' ','_',$h->name)) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ARCHIV --}}
            @if($archived->isNotEmpty())
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-1 h-4" style="background:#454745;"></span>
                        <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#454745;">ARCHIV</span>
                    </div>

                    <div class="grid grid-cols-4 gap-3">
                        @foreach($archived as $h)
                            <div class="px-4 py-3" style="background:#111111;border:1px solid #1e1e1e;opacity:0.6;">
                                <div class="text-[8px] tracking-[1.5px] mb-1" style="color:#454745;">ENDED {{ $h->closes_at->format('d.m.Y') }}</div>
                                <div class="text-[11px] font-sans font-bold tracking-[1px]" style="color:#A1A1AA;">{{ strtoupper(str_replace(' ','_',$h->name)) }} // {{ str_pad($h->id,2,'0',STR_PAD_LEFT) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ═══════ RIGHT PANEL ═══════ --}}
        <aside class="w-72 flex-shrink-0 space-y-4">

            {{-- HOTSPOT STATS --}}
            <div style="background:#141414;border:1px solid #F5B700;">
                <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                    <svg class="w-4 h-4" fill="#F5B700" viewBox="0 0 24 24"><path d="M3 13h2v8H3zm4-6h2v14H7zm4-4h2v18h-2zm4 8h2v10h-2zm4-4h2v14h-2z"/></svg>
                    <span class="text-[12px] font-sans font-bold tracking-[1.5px]" style="color:#e8e8e8;">HOTSPOT STATS</span>
                </div>
                <div class="p-4 space-y-3">
                    @foreach([
                        ['ACTIVE_NODES', str_pad($stats['active_nodes'],2,'0',STR_PAD_LEFT), '#e8e8e8'],
                        ['PENDING_QUEUE', str_pad($stats['pending_queue'],2,'0',STR_PAD_LEFT), '#e8e8e8'],
                        ['TOTAL_AD_VOLUME', number_format($stats['total_volume'],0,',','.'), '#e8e8e8'],
                        ['UPTIME', $stats['uptime'].'%', '#F5B700'],
                    ] as $row)
                        <div class="flex items-center justify-between text-[10px] tracking-[1px]" style="{{ !$loop->last ? 'border-bottom:1px solid #1e1e1e;padding-bottom:12px;' : '' }}">
                            <span style="color:#A1A1AA;">{{ $row[0] }}</span>
                            <span class="font-sans font-bold text-[13px]" style="color:{{ $row[2] }};">{{ $row[1] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- CREATE HOTSPOT (Merchant/Agency) --}}
            @if(in_array(auth()->user()->role, ['merchant','agency','admin']))
                <a href="#"
                   class="flex items-center justify-center gap-2 py-3 text-[11px] font-sans font-bold tracking-[2px] transition-colors"
                   style="background:#DC2626;color:white;"
                   onmouseover="this.style.background='#FF535B'"
                   onmouseout="this.style.background='#DC2626'">
                    <span class="text-[14px]">⊕</span> CREATE HOTSPOT
                </a>
            @endif

            {{-- SYSTEM BROADCAST (Dummy) --}}
            <div class="px-4 py-3" style="background:#141414;border:1px dashed #DC2626;">
                <div class="text-[9px] tracking-[2px] mb-2" style="color:#DC2626;">SYSTEM BROADCAST</div>
                <div class="text-[10px] leading-relaxed italic" style="color:#A1A1AA;">
                    "Hotspot-Aktivität wird in Echtzeit überwacht. Dynamische Auto-Hotspots folgen in v2 — Activity-Detection-Engine in Entwicklung."
                </div>
            </div>
        </aside>
    </div>
</x-buyer-app-layout>
