<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="text-[9px] tracking-[3px] text-copy-ticker mb-1">MERCHANT_CONSOLE // DEEP_ANALYTICS</div>
                <div class="text-[18px] font-sans font-bold text-copy-soft tracking-wider">ANALYTICS</div>
            </div>
            <div class="text-[9px] tracking-[1.5px] text-copy-ticker">ZEITRAUM: LETZTE 30 TAGE</div>
        </div>

        {{-- ── KPI ROW ──────────────────────────────────────────── --}}
        <div class="grid grid-cols-5 gap-4">
            @php
                $kpiCards = [
                    ['VIEWS_30T',    $kpis['views_30'] >= 1000 ? number_format($kpis['views_30']/1000,1).'K' : $kpis['views_30'], 'IMPRESSIONS'],
                    ['LEADS_30T',    $kpis['leads_30'], 'KLICKS ZUM SHOP'],
                    ['SALES_30T',    $kpis['sales_30'], 'VERKÄUFE'],
                    ['CONVERSION',   $kpis['conversion'].'%', 'LEAD → SALE'],
                    ['VIEWS_TOTAL',  $kpis['views_total'] >= 1000 ? number_format($kpis['views_total']/1000,1).'K' : $kpis['views_total'], 'ALL-TIME'],
                ];
            @endphp
            @foreach($kpiCards as $i => $card)
                <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                    <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">{{ $card[0] }}</div>
                    <div class="text-3xl font-sans font-bold {{ $i === 3 ? 'text-brand-yellow' : 'text-copy-soft' }}">{{ $card[1] }}</div>
                    <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-2">{{ $card[2] }}</div>
                </div>
            @endforeach
        </div>

        {{-- ── TIMELINE CHARTS ─────────────────────────────────── --}}
        <div class="grid grid-cols-2 gap-4">

            {{-- Views Verlauf --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-[10px] tracking-[2px] text-copy-neutral">VIEWS_VERLAUF // 30T</div>
                    <span class="flex items-center gap-1.5 text-[9px]"><span class="w-2 h-2 bg-brand-red inline-block"></span> VIEWS</span>
                </div>
                <div class="h-40 flex items-end gap-0.5 border-b border-l border-line-warm px-2 pb-2">
                    @foreach($chartData as $day)
                        <div class="flex-1 h-full flex items-end group relative">
                            <div class="w-full bg-brand-red/60 hover:bg-brand-red transition-colors" style="height:{{ $day['views_pct'] }}%"></div>
                            <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block text-[8px] bg-ink-deep border border-line-warm px-1.5 py-0.5 text-copy-soft whitespace-nowrap z-10">
                                {{ $day['views'] }} VIEWS
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($chartData->every(fn($d) => $d['views'] === 0))
                    <div class="text-center text-[9px] tracking-[2px] text-copy-ticker mt-2">NO_DATA_YET</div>
                @endif
            </div>

            {{-- Leads Verlauf --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-[10px] tracking-[2px] text-copy-neutral">LEADS_VERLAUF // 30T</div>
                    <span class="flex items-center gap-1.5 text-[9px]"><span class="w-2 h-2 bg-brand-yellow inline-block"></span> LEADS</span>
                </div>
                <div class="h-40 flex items-end gap-0.5 border-b border-l border-line-warm px-2 pb-2">
                    @foreach($chartData as $day)
                        <div class="flex-1 h-full flex items-end group relative">
                            <div class="w-full bg-brand-yellow/60 hover:bg-brand-yellow transition-colors" style="height:{{ $day['leads_pct'] }}%"></div>
                            <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block text-[8px] bg-ink-deep border border-line-warm px-1.5 py-0.5 text-copy-soft whitespace-nowrap z-10">
                                {{ $day['leads'] }} LEADS
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($chartData->every(fn($d) => $d['leads'] === 0))
                    <div class="text-center text-[9px] tracking-[2px] text-copy-ticker mt-2">NO_DATA_YET</div>
                @endif
            </div>
        </div>

        {{-- ── MARKT-POSITION ──────────────────────────────────── --}}
        <div class="grid grid-cols-3 gap-4">

            {{-- Markt-Rang --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">MARKT_POSITION</div>
                @if($marketPosition)
                    <div class="space-y-3">
                        <div>
                            <div class="text-[9px] tracking-[1.5px] text-copy-ticker mb-1">BESTE AD GLOBALER RANG</div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-sans font-bold text-brand-yellow">#{{ $marketPosition['rank'] }}</span>
                                <span class="text-[10px] text-copy-neutral">/ {{ $marketPosition['total'] }} AKTIVE ADS</span>
                            </div>
                        </div>
                        <div>
                            <div class="text-[9px] tracking-[1.5px] text-copy-ticker mb-1">PERZENTIL</div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-line-warm relative overflow-hidden">
                                    <div class="h-full bg-brand-yellow" style="width:{{ $marketPosition['percentile'] }}%"></div>
                                </div>
                                <span class="text-[11px] font-bold text-copy-soft">TOP {{ 100 - $marketPosition['percentile'] === 0 ? 1 : (100 - $marketPosition['percentile']) }}%</span>
                            </div>
                        </div>
                        <div class="text-[9px] tracking-wider text-copy-ticker pt-2 border-t border-line-warm/40">
                            "{{ Str::limit($marketPosition['best_ad'], 30) }}" — SCORE {{ number_format($marketPosition['best_score'],1) }}
                        </div>
                    </div>
                @else
                    <div class="text-[9px] tracking-[2px] text-copy-ticker py-4">KEINE AKTIVEN ADS</div>
                @endif
            </div>

            {{-- Score-Vergleich --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">SCORE: DU VS. MARKT</div>
                <div class="space-y-4 mt-6">
                    <div>
                        <div class="flex justify-between text-[9px] tracking-[1.5px] mb-1">
                            <span class="text-brand-yellow">DEINE ADS Ø</span>
                            <span class="text-copy-soft font-bold">{{ number_format($myAvgScore,1) }}</span>
                        </div>
                        <div class="h-2 bg-line-warm relative overflow-hidden">
                            <div class="h-full bg-brand-yellow" style="width:{{ min($myAvgScore,100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[9px] tracking-[1.5px] mb-1">
                            <span class="text-copy-neutral">MARKT Ø</span>
                            <span class="text-copy-soft font-bold">{{ number_format($marketAvgScore,1) }}</span>
                        </div>
                        <div class="h-2 bg-line-warm relative overflow-hidden">
                            <div class="h-full bg-copy-neutral" style="width:{{ min($marketAvgScore,100) }}%"></div>
                        </div>
                    </div>
                    <div class="text-[9px] tracking-wider pt-3 border-t border-line-warm/40 {{ $myAvgScore >= $marketAvgScore ? 'text-brand-yellow' : 'text-brand-red' }}">
                        @if($myAvgScore >= $marketAvgScore)
                            ▲ {{ number_format($myAvgScore - $marketAvgScore,1) }} ÜBER MARKTSCHNITT
                        @else
                            ▼ {{ number_format($marketAvgScore - $myAvgScore,1) }} UNTER MARKTSCHNITT
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sales-Conversion-Funnel (echt) --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">FUNNEL // 30T</div>
                @php
                    $fViews = $kpis['views_30'];
                    $fLeads = $kpis['leads_30'];
                    $fSales = $kpis['sales_30'];
                    $leadPct = $fViews > 0 ? round($fLeads/$fViews*100) : 0;
                    $salePct = $fViews > 0 ? round($fSales/$fViews*100) : 0;
                @endphp
                <div class="space-y-2 mt-4">
                    <div>
                        <div class="flex justify-between text-[9px] tracking-[1px] mb-1"><span class="text-copy-neutral">VIEWS</span><span class="text-copy-soft">{{ $fViews }}</span></div>
                        <div class="h-6 bg-brand-red/60" style="width:100%"></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[9px] tracking-[1px] mb-1"><span class="text-copy-neutral">LEADS</span><span class="text-copy-soft">{{ $fLeads }} ({{ $leadPct }}%)</span></div>
                        <div class="h-6 bg-brand-yellow/60" style="width:{{ max($leadPct,2) }}%"></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[9px] tracking-[1px] mb-1"><span class="text-copy-neutral">SALES</span><span class="text-copy-soft">{{ $fSales }} ({{ $salePct }}%)</span></div>
                        <div class="h-6 bg-brand-yellow" style="width:{{ max($salePct,2) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── PRO-AD BREAKDOWN ────────────────────────────────── --}}
        <div class="bg-ink-panel border border-line-warm p-4">
            <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">PERFORMANCE PRO AD // 30T</div>
            @if($adBreakdown->isEmpty())
                <div class="text-[9px] tracking-[2px] text-copy-ticker py-4">KEINE AKTIVEN ADS</div>
            @else
                <table class="w-full text-[10px] tracking-wider">
                    <thead>
                    <tr class="border-b border-line-warm text-copy-ticker">
                        <th class="text-left pb-2 font-normal">AD</th>
                        <th class="text-right pb-2 font-normal">VIEWS</th>
                        <th class="text-right pb-2 font-normal">LEADS</th>
                        <th class="text-right pb-2 font-normal">SALES</th>
                        <th class="text-right pb-2 font-normal">CTR</th>
                        <th class="text-right pb-2 font-normal">SCORE</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($adBreakdown as $ad)
                        <tr class="border-b border-line-warm/40 hover:bg-ink-surface transition-colors">
                            <td class="py-3 text-copy-soft truncate max-w-[240px]">{{ $ad->title }}</td>
                            <td class="py-3 text-right text-copy-neutral">{{ $ad->v_views }}</td>
                            <td class="py-3 text-right text-copy-neutral">{{ $ad->v_leads }}</td>
                            <td class="py-3 text-right text-copy-neutral">{{ $ad->v_sales }}</td>
                            <td class="py-3 text-right text-copy-neutral">{{ $ad->v_ctr }}%</td>
                            <td class="py-3 text-right font-bold {{ $ad->current_score >= 70 ? 'text-brand-yellow' : 'text-copy-neutral' }}">{{ number_format($ad->current_score,1) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-app-layout>
