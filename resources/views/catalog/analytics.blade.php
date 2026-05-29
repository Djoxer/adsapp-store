<x-buyer-app-layout>
    <x-catalog.filter-bar />

    <div class="p-4 space-y-4">

        {{-- ═══ KPI ROW ═══ --}}
        <div class="grid grid-cols-5 gap-3">
            @php
                $kpis = [
                    ['GESEHENE_ADS', number_format($viewsCount,0,',','.'), '📊', true],
                    ['GEMERKTE_ADS', number_format($bookmarksCount,0,',','.'), '🔖', true],
                    ['KÄUFE', number_format($purchasesCount,0,',','.'), '', true],
                    ['DWELL_TIME_AVG', '6.2s', '', false],      // Dummy
                    ['SIGNAL_BEITRAG', '342', '', false],        // Dummy
                ];
            @endphp
            @foreach($kpis as [$label, $value, $icon, $isReal])
                <div class="p-4 relative" style="background:#141414;border:1px solid {{ $loop->index === 2 ? '#DC2626' : '#2a2a2a' }};">
                    <div class="text-[9px] tracking-[2px] mb-2" style="color:#F5B700;">{{ $label }}</div>
                    <div class="flex items-end justify-between">
                        <div class="text-2xl font-sans font-bold" style="color:#e8e8e8;">{{ $value }}</div>
                        @if($icon)<span class="text-[16px] opacity-50">{{ $icon }}</span>@endif
                    </div>
                    @unless($isReal)
                        <div class="absolute top-2 right-2 text-[7px] tracking-[1px]" style="color:#454745;">DEMO</div>
                    @endunless
                </div>
            @endforeach
        </div>

        <div class="flex gap-4">

            {{-- ═══ LEFT COLUMN ═══ --}}
            <div class="flex-1 min-w-0 space-y-4">

                {{-- MEINE AKTIVITÄT --}}
                <div style="background:#141414;border:1px solid #2a2a2a;">
                    <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid #2a2a2a;">
                        <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">MEINE AKTIVITÄT</span>
                        <span class="text-[8px] tracking-[2px]" style="color:#DC2626;">LIVE_FEED</span>
                    </div>

                    @forelse($activityFeed as $item)
                        <div class="flex items-center gap-4 px-4 py-3" style="border-bottom:1px solid #1a1a1a;">
                            {{-- Marker --}}
                            <div class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                 style="background:{{ $item->type === 'order' ? '#DC2626' : ($item->type === 'bookmark' ? '#F5B700' : '#454745') }};"></div>

                            {{-- Thumb --}}
                            <div class="w-12 h-12 flex items-center justify-center flex-shrink-0" style="background:#0a0a0a;border:1px solid #2a2a2a;">
                                @if($item->ad && $item->ad->images->first())
                                    <img src="{{ asset('storage/' . $item->ad->images->first()->cache_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[6px]" style="color:#454745;">IMG</span>
                                @endif
                            </div>

                            {{-- Text --}}
                            <div class="flex-1 min-w-0">
                                @if($item->type === 'order')
                                    <div class="text-[12px] tracking-wider" style="color:#e8e8e8;">
                                        KAUF: <span style="color:#F5B700;">{{ $item->ad->title ?? '—' }}</span> — €{{ number_format($item->amount/100,2,',','.') }}
                                    </div>
                                    <span class="inline-block mt-1 text-[8px] tracking-[1.5px] px-2 py-0.5" style="background:#2a1a1a;color:#A1A1AA;">🧾 BELEG</span>
                                @elseif($item->type === 'bookmark')
                                    <div class="text-[12px] tracking-wider" style="color:#e8e8e8;">
                                        DU HAST <span style="color:#F5B700;">{{ $item->ad->title ?? '—' }}</span> GEMERKT
                                    </div>
                                @else
                                    <div class="text-[12px] tracking-wider" style="color:#777777;">
                                        ANZEIGE GESEHEN: {{ $item->ad->title ?? '—' }}
                                    </div>
                                @endif
                            </div>

                            {{-- Zeit --}}
                            <div class="text-[9px] tracking-[1.5px] flex-shrink-0" style="color:#454745;">
                                {{ \Carbon\Carbon::parse($item->timestamp)->isToday()
                                    ? \Carbon\Carbon::parse($item->timestamp)->format('H:i:s')
                                    : \Carbon\Carbon::parse($item->timestamp)->diffForHumans(null, true) }}
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-12 text-center text-[9px] tracking-[2px]" style="color:#454745;">
                            NOCH KEINE AKTIVITÄT // BROWSE_THE_CATALOG
                        </div>
                    @endforelse
                </div>

                {{-- KATEGORIE-VERTEILUNG --}}
                <div style="background:#141414;border:1px solid #2a2a2a;">
                    <div class="px-4 py-3" style="border-bottom:1px solid #2a2a2a;">
                        <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">KATEGORIE-VERTEILUNG</span>
                    </div>
                    <div class="p-6">
                        @if($catSplit->isNotEmpty())
                            <div class="flex items-center gap-8">
                                {{-- Donut --}}
                                <div class="relative flex-shrink-0">
                                    @php
                                        $colors = ['#DC2626','#F5B700','#A1A1AA','#454745','#43d685','#4fc3f7'];
                                        $circ = 2 * pi() * 40;
                                        $offset = 0;
                                    @endphp
                                    <svg viewBox="0 0 100 100" class="w-40 h-40 -rotate-90">
                                        @foreach($catSplit as $idx => $seg)
                                            @php
                                                $pct = $totalSpent > 0 ? $seg->total / $totalSpent : 0;
                                                $dash = $pct * $circ;
                                            @endphp
                                            <circle cx="50" cy="50" r="40" fill="none"
                                                    stroke="{{ $colors[$idx % count($colors)] }}" stroke-width="10"
                                                    stroke-dasharray="{{ $dash }} {{ $circ }}"
                                                    stroke-dashoffset="-{{ $offset }}"/>
                                            @php $offset += $dash; @endphp
                                        @endforeach
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <div class="text-[8px] tracking-[2px]" style="color:#454745;">TOTAL_EXP</div>
                                        <div class="text-[18px] font-sans font-bold" style="color:#e8e8e8;">€{{ $totalSpent > 999 ? round($totalSpent/100000,1).'K' : number_format($totalSpent/100,0,',','.') }}</div>
                                    </div>
                                </div>

                                {{-- Legende --}}
                                <div class="flex-1 grid grid-cols-2 gap-3">
                                    @foreach($catSplit as $idx => $seg)
                                        <div class="flex items-center gap-2 text-[10px] tracking-[1px]">
                                            <span class="w-2.5 h-2.5 flex-shrink-0" style="background:{{ $colors[$idx % count($colors)] }};"></span>
                                            <span style="color:#A1A1AA;">{{ strtoupper($seg->name) }}</span>
                                            <span style="color:#454745;">{{ $totalSpent > 0 ? round($seg->total/$totalSpent*100) : 0 }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="py-8 text-center text-[9px] tracking-[2px]" style="color:#454745;">
                                NOCH KEINE KÄUFE // KEINE VERTEILUNG
                            </div>
                        @endif
                    </div>
                </div>

                {{-- PLATFORM_PULSE (Dummy) --}}
                <div style="background:#141414;border:1px solid #2a2a2a;">
                    <div class="px-4 py-3 flex items-center justify-between" style="border-bottom:1px solid #2a2a2a;">
                        <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#454745;">PLATFORM_PULSE</span>
                        <span class="text-[7px] tracking-[1px]" style="color:#454745;">DEMO</span>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-4">
                        @foreach([
                            ['AKTIVE_ADS','42,891','#e8e8e8'],
                            ['USER_ONLINE','1,402','#e8e8e8'],
                            ['TPS_SIGNAL','88.4','#e8e8e8'],
                            ['LOAD_STATUS','NOMINAL','#F5B700'],
                        ] as $row)
                            <div>
                                <div class="text-[8px] tracking-[1.5px] mb-1" style="color:#454745;">{{ $row[0] }}</div>
                                <div class="text-[16px] font-sans font-bold" style="color:{{ $row[2] }};">{{ $row[1] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ═══ RIGHT COLUMN ═══ --}}
            <div class="w-80 flex-shrink-0 space-y-4">

                {{-- TRENDING IN DEINEN KATEGORIEN (Dummy) --}}
                <div style="background:#141414;border:1px solid #2a2a2a;">
                    <div class="px-4 py-3 flex items-center justify-between" style="border-bottom:1px solid #2a2a2a;">
                        <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">TRENDING IN DEINEN KATEGORIEN</span>
                    </div>
                    @foreach([
                        ['TITAN-SHIELD PRO','9.8','HIGH_INTENT','+14.2%','#43d685'],
                        ['ORBIT-LENS V2','8.4','STABLE','+3.1%','#F5B700'],
                        ['FLUX-DRIVE 1TB','7.9','VOLUME_UP','+22.5%','#DC2626'],
                    ] as $t)
                        <div class="flex items-center gap-3 px-4 py-3" style="border-bottom:1px solid #1a1a1a;">
                            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0" style="background:#0a0a0a;border:1px solid #2a2a2a;">
                                <span class="text-[6px]" style="color:#454745;">IMG</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[11px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ $t[0] }}</div>
                                <div class="text-[8px] tracking-[1px]" style="color:#454745;">SCORE: {{ $t[1] }} / {{ $t[2] }}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="text-[11px] font-sans font-bold" style="color:{{ $t[4] }};">{{ $t[3] }}</div>
                                <div class="text-[7px] tracking-[1px]" style="color:#454745;">24H_Δ</div>
                            </div>
                        </div>
                    @endforeach
                    <a href="{{ route('catalog.ranking') }}" class="block px-4 py-3 text-center text-[10px] tracking-[2px] transition-colors"
                       style="color:#A1A1AA;" onmouseover="this.style.color='#F5B700'" onmouseout="this.style.color='#A1A1AA'">
                        ALLE_ANZEIGEN
                    </a>
                </div>

                {{-- PREISALARM (Dummy) --}}
                <div style="background:#141414;border:1px solid #F5B700;">
                    <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                        <span style="color:#F5B700;">🔔</span>
                        <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">PREISALARM</span>
                        <span class="ml-auto text-[7px] tracking-[1px]" style="color:#454745;">DEMO</span>
                    </div>
                    <div class="p-4" style="border-left:3px solid #F5B700;">
                        <div class="text-[12px] font-sans font-bold tracking-wider mb-2" style="color:#e8e8e8;">SHADOW-FLYER X1</div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[11px] line-through" style="color:#454745;">€399</span>
                            <span style="color:#A1A1AA;">→</span>
                            <span class="text-[14px] font-sans font-bold" style="color:#F5B700;">€349</span>
                            <span class="ml-auto text-[9px] px-1.5 py-0.5" style="background:#DC2626;color:white;">-12.5%</span>
                        </div>
                        <button class="w-full py-2.5 text-[10px] tracking-[2px] font-sans font-bold transition-colors"
                                style="background:#F5B700;color:#0a0a0a;"
                                onmouseover="this.style.background='#FFD889'"
                                onmouseout="this.style.background='#F5B700'">
                            JETZT_KAUFEN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-buyer-app-layout>
