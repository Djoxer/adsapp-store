<x-buyer-app-layout>
    <x-catalog.filter-bar />

    <div class="flex gap-5 px-6 py-5">

        {{-- ═══════ MAIN — LEADERBOARD ═══════ --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- ZEITRAUM-FILTER --}}
            <div class="flex items-center gap-6">
                <div>
                    <div class="text-[9px] tracking-[3px] mb-2" style="color:#454745;">ZEITRAUM_FILTER</div>
                    <div class="flex gap-2">
                        @foreach(['today'=>'HEUTE','7d'=>'7_TAGE','30d'=>'30_TAGE','all'=>'ALL_TIME'] as $val => $label)
                            <a href="{{ route('catalog.ranking', ['period'=>$val]) }}"
                               class="px-4 py-2 text-[10px] tracking-[1.5px] font-sans font-bold transition-colors"
                               style="{{ $period === $val
                                   ? 'background:#DC2626;color:white;'
                                   : 'border:1px solid #2a2a2a;color:#A1A1AA;' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- LEADERBOARD --}}
            <div class="space-y-3">
                @forelse($ads as $i => $ad)
                    @php
                        $rank = $i + 1;
                        $isTop3 = $rank <= 3;
                        $delta = round(((($ad->id * 37) % 50) - 25) / 10, 1);
                        $deltaUp = $delta >= 0;
                    @endphp

                    @if($isTop3)
                        {{-- ── TOP 3 — PROMINENT ── --}}
                        <div class="relative flex items-center gap-5 px-5 py-5 transition-colors"
                             style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #F5B700;"
                             onmouseover="this.style.background='#1a1a1a'"
                             onmouseout="this.style.background='#141414'">

                            <div class="flex flex-col items-center justify-center w-12 flex-shrink-0">
                                <div class="text-[20px] font-sans font-bold tracking-wider" style="color:#F5B700;">#{{ str_pad($rank,2,'0',STR_PAD_LEFT) }}</div>
                                <svg class="w-5 h-5 mt-1" fill="#F5B700" viewBox="0 0 24 24"><path d="M12 2l2.4 4.8 5.3.8-3.8 3.7.9 5.3L12 14.8 7.2 17.4l.9-5.3L4.3 7.6l5.3-.8z"/></svg>
                            </div>

                            <div class="w-20 h-20 flex items-center justify-center flex-shrink-0"
                                 style="background:#0a0a0a;border:1px solid #2a2a2a;">
                                @if($ad->images->first())
                                    <img src="{{ asset('storage/' . $ad->images->first()->cache_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[7px]" style="color:#454745;">IMG</span>
                                @endif
                            </div>

                            <div class="min-w-0 w-64">
                                <div class="text-[18px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ $ad->title }}</div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-[9px] tracking-[1.5px] px-2 py-0.5" style="border:1px solid #2a2a2a;color:#A1A1AA;">{{ strtoupper($ad->category->name ?? '—') }}</span>
                                    <span class="text-[9px] tracking-[1.5px]" style="color:#454745;">MERC: {{ strtoupper($ad->merchant->company_name ?? '—') }}</span>
                                </div>
                            </div>

                            <div class="w-48 flex-shrink-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-[13px] font-sans font-bold tracking-wider" style="color:#F5B700;">SCORE: {{ $ad->current_score }}</span>
                                    <span class="text-[10px] font-sans font-bold" style="color:{{ $deltaUp ? '#43d685' : '#DC2626' }};">
                                        {{ $deltaUp ? '▲' : '▼' }}{{ abs($delta) }}
                                    </span>
                                </div>
                                <div class="mt-2 h-1 w-full" style="background:#1e1e1e;">
                                    <div class="h-full" style="width:{{ min($ad->current_score, 100) }}%;background:linear-gradient(90deg,#DC2626,#F5B700);"></div>
                                </div>
                            </div>

                            <div class="flex-1 text-[10px] tracking-[1.5px] space-y-1" style="color:#A1A1AA;">
                                <div>{{ $ad->sales_count }} VERKÄUFE</div>
                                <div>{{ number_format($ad->bookmarks_count, 0, ',', '.') }} MERKLISTEN</div>
                                <div>{{ $ad->dwell_count }} INTERAKTIONEN</div>
                            </div>

                            <div class="w-24 flex-shrink-0">
                                <svg viewBox="0 0 100 30" class="w-full">
                                    <polyline fill="none" stroke="{{ $deltaUp ? '#43d685' : '#DC2626' }}" stroke-width="1.5"
                                              points="0,{{ 20+($ad->id%8) }} 20,{{ 15+($ad->id%6) }} 40,{{ 18+($ad->id%5) }} 60,{{ 10+($ad->id%7) }} 80,{{ 12+($ad->id%4) }} 100,{{ 6+($ad->id%5) }}"/>
                                </svg>
                            </div>
                        </div>

                    @else
                        {{-- ── RANK 4+ — KOMPAKT ── --}}
                        <div class="flex items-center gap-4 px-5 py-3 transition-colors"
                             style="background:#111111;border:1px solid #2a2a2a;"
                             onmouseover="this.style.background='#1a1a1a'"
                             onmouseout="this.style.background='#111111'">

                            <div class="text-[13px] font-sans font-bold tracking-wider w-10 flex-shrink-0" style="color:#A1A1AA;">#{{ str_pad($rank,2,'0',STR_PAD_LEFT) }}</div>

                            <div class="w-12 h-12 flex items-center justify-center flex-shrink-0" style="background:#0a0a0a;border:1px solid #2a2a2a;">
                                @if($ad->images->first())
                                    <img src="{{ asset('storage/' . $ad->images->first()->cache_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[6px]" style="color:#454745;">IMG</span>
                                @endif
                            </div>

                            <div class="w-56 min-w-0">
                                <div class="text-[13px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ $ad->title }}</div>
                                <div class="text-[9px] tracking-[1.5px] mt-0.5" style="color:#454745;">{{ strtoupper($ad->category->name ?? '—') }} / {{ strtoupper($ad->merchant->company_name ?? '—') }}</div>
                            </div>

                            <div class="w-44 flex-shrink-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-sans font-bold tracking-wider" style="color:#A1A1AA;">SCORE: {{ $ad->current_score }}</span>
                                    <span class="text-[9px] font-sans font-bold" style="color:{{ $deltaUp ? '#43d685' : '#DC2626' }};">{{ $deltaUp ? '▲' : '▼' }}{{ abs($delta) }}</span>
                                </div>
                                <div class="mt-1.5 h-0.5 w-full" style="background:#1e1e1e;">
                                    <div class="h-full" style="width:{{ min($ad->current_score,100) }}%;background:#DC2626;"></div>
                                </div>
                            </div>

                            <div class="flex-1 flex gap-5 text-[9px] tracking-[1.5px]" style="color:#A1A1AA;">
                                <span>{{ $ad->sales_count }} VERK.</span>
                                <span>{{ number_format($ad->bookmarks_count,0,',','.') }} MERK.</span>
                                <span>{{ $ad->dwell_count }} INT.</span>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="flex flex-col items-center justify-center py-24 gap-3">
                        <div class="text-[10px] tracking-[2px]" style="color:#454745;">KEINE AKTIVEN ADS IM RANKING</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ═══════ RIGHT ASIDE ═══════ --}}
        <aside class="w-72 flex-shrink-0 space-y-6">

            {{-- TRENDING NOW (Dummy) --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span style="color:#43d685;">↗</span>
                    <span class="text-[12px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">TRENDING NOW</span>
                </div>
                <div class="space-y-2">
                    @foreach($ads->take(2) as $ad)
                        @php $tdelta = 5 + ($ad->id % 15); @endphp
                        <div class="flex items-center gap-3 px-3 py-2.5" style="background:#141414;border:1px solid #2a2a2a;">
                            <span class="text-[10px] font-sans font-bold px-1.5 py-0.5" style="background:#1e1e1e;color:#43d685;">+{{ $tdelta }}%</span>
                            <div class="min-w-0">
                                <div class="text-[11px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ strtoupper(str_replace(' ','_',$ad->title)) }}</div>
                                <div class="text-[8px] tracking-[1.5px]" style="color:#454745;">MERC: {{ strtoupper($ad->merchant->company_name ?? '—') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- MARKET SPLIT (echt: Kategorie-Verteilung) --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span style="color:#F5B700;">◐</span>
                    <span class="text-[12px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">MARKET_SPLIT</span>
                </div>
                <div class="px-4 py-5 flex flex-col items-center" style="background:#141414;border:1px solid #2a2a2a;">
                    @php
                        $colors = ['#DC2626','#F5B700','#43d685','#4fc3f7','#9d4edd','#f5a623'];
                        $circumference = 2 * pi() * 40;
                        $offset = 0;
                    @endphp
                    <svg viewBox="0 0 100 100" class="w-32 h-32 -rotate-90">
                        @foreach($marketSplit as $idx => $seg)
                            @php
                                $pct = $totalActive > 0 ? $seg->cnt / $totalActive : 0;
                                $dash = $pct * $circumference;
                                $color = $colors[$idx % count($colors)];
                            @endphp
                            <circle cx="50" cy="50" r="40" fill="none" stroke="{{ $color }}" stroke-width="12"
                                    stroke-dasharray="{{ $dash }} {{ $circumference }}"
                                    stroke-dashoffset="-{{ $offset }}"/>
                            @php $offset += $dash; @endphp
                        @endforeach
                    </svg>
                    <div class="-mt-20 mb-12 text-center">
                        <div class="text-[8px] tracking-[2px]" style="color:#454745;">TOTAL</div>
                        <div class="text-[20px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">{{ number_format($totalActive,0,',','.') }}</div>
                    </div>
                    <div class="w-full space-y-1.5 mt-2">
                        @foreach($marketSplit->take(4) as $idx => $seg)
                            <div class="flex items-center justify-between text-[9px] tracking-[1px]">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2" style="background:{{ $colors[$idx % count($colors)] }};"></span>
                                    <span style="color:#A1A1AA;">{{ strtoupper($seg->name) }}</span>
                                </div>
                                <span style="color:#454745;">{{ $totalActive > 0 ? round($seg->cnt/$totalActive*100) : 0 }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- NEWCOMERS (echt: neueste Ads) --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span style="color:#F5B700;">⚡</span>
                    <span class="text-[12px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">NEWCOMERS</span>
                </div>
                <div class="space-y-2">
                    @foreach($newcomers as $ad)
                        <div class="px-3 py-3" style="background:#141414;border:1px solid #2a2a2a;">
                            <div class="text-[8px] tracking-[1.5px] mb-1.5" style="color:#DC2626;">ENTRY: {{ $ad->created_at->format('H:i:s') }}</div>
                            <div class="text-[12px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ strtoupper(str_replace(' ','_',$ad->title)) }}</div>
                            <div class="text-[8px] tracking-[1.5px] mt-1" style="color:#454745;">{{ strtoupper($ad->category->name ?? '—') }} // MERC: {{ strtoupper($ad->merchant->company_name ?? '—') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</x-buyer-app-layout>
