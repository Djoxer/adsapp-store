<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- ── KPI CARDS ────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-4 gap-4">

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">AKTIVE ADS</div>
                <div class="text-4xl font-sans font-bold text-copy-soft">{{ str_pad($activeAdsCount, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-2">SYSTEM_LOAD: OPTIMAL</div>
                <div class="absolute top-4 right-4 text-brand-yellow"><x-icons.fav class="w-5 h-5" /></div>
            </div>

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">LEADS HEUTE</div>
                <div class="text-4xl font-sans font-bold text-copy-soft">{{ str_pad($leadsToday, 2, '0', STR_PAD_LEFT) }}</div>
                @if($leadsDelta !== null)
                    <div class="text-[9px] tracking-[1.5px] mt-2 {{ $leadsDelta >= 0 ? 'text-brand-yellow' : 'text-brand-red' }}">
                        {{ $leadsDelta >= 0 ? '+' : '' }}{{ $leadsDelta }}% VS GESTERN
                    </div>
                @else
                    <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-2">KEIN_VERGLEICH_VERFÜGBAR</div>
                @endif
                <div class="absolute top-4 right-4 text-brand-yellow"><x-icons.trend class="w-5 h-5" /></div>
            </div>

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">VIEWS_30_TAGE</div>
                <div class="text-4xl font-sans font-bold text-copy-soft">
                    {{ $viewsTotal >= 1000 ? number_format($viewsTotal / 1000, 1) . 'K' : $viewsTotal }}
                </div>
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-2">AD_IMPRESSIONS_TOTAL</div>
                <div class="absolute top-4 right-4 text-brand-yellow"><x-icons.cash class="w-5 h-5" /></div>
            </div>

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">SCORE_AVG</div>
                <div class="text-4xl font-sans font-bold text-brand-yellow">{{ number_format($scoreAvg, 1) }}</div>
                {{-- Score-Bar: 5 Segmente, gefüllt proportional zum Score --}}
                @php $filled = round($scoreAvg / 20); @endphp
                <div class="flex gap-1 mt-3">
                    @for($i = 0; $i < 5; $i++)
                        <div class="h-1.5 flex-1 {{ $i < $filled ? 'bg-brand-yellow' : 'bg-line-warm' }}"></div>
                    @endfor
                </div>
                <div class="absolute top-4 right-4 text-brand-yellow"><x-icons.stats class="w-5 h-5" /></div>
            </div>

        </div>

        {{-- ── CHART + TOP ADS ──────────────────────────────────────────── --}}
        <div class="grid grid-cols-3 gap-4">

            {{-- Performance Chart --}}
            <div class="col-span-2 bg-ink-panel border border-line-warm p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-[10px] tracking-[2px] text-copy-neutral">PERFORMANCE_LAST_30_DAYS</div>
                    <div class="flex gap-4 text-[9px] tracking-[1px]">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-brand-red inline-block"></span> VIEWS</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-brand-yellow inline-block"></span> CLICKS</span>
                    </div>
                </div>
                <div class="h-48 flex items-end gap-1 border-b border-l border-line-warm px-2 pb-2">
                    @foreach($chartData as $day)
                        <div class="flex-1 flex flex-col items-center gap-0.5 h-full justify-end">
                            <div class="w-full bg-brand-yellow/60" style="height:{{ $day['clicks_pct'] }}%"></div>
                            <div class="w-full bg-brand-red/60"    style="height:{{ $day['views_pct']  }}%"></div>
                        </div>
                    @endforeach
                </div>
                @if($chartData->every(fn($d) => $d['views_pct'] === 0))
                    <div class="text-center text-[9px] tracking-[2px] text-copy-ticker mt-2">NO_DATA_YET // EVENTS_WERDEN_GETRACKT</div>
                @endif
            </div>

            {{-- Top 5 Ads --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">DEINE TOP 5 ADS</div>
                @if($topAds->isEmpty())
                    <div class="text-[9px] tracking-[2px] text-copy-ticker">KEINE_AKTIVEN_ADS</div>
                @else
                    <div class="space-y-2">
                        @foreach($topAds as $ad)
                            <div class="flex items-center gap-3 p-2 {{ $ad->rank === 1 ? 'bg-ink-surface border border-line-warm' : '' }}">
                                <div class="w-5 h-5 flex items-center justify-center text-[9px] font-bold {{ $ad->rank === 1 ? 'bg-brand-red text-white' : 'bg-line-warm text-copy-neutral' }}">
                                    {{ $ad->rank }}
                                </div>
                                <div class="w-8 h-8 bg-ink-surface border border-line-warm flex-shrink-0 overflow-hidden">
                                    @if($ad->images->isNotEmpty())
                                        <img src="{{ Storage::url($ad->images->first()->cache_path) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[10px] tracking-wider text-copy-soft truncate">{{ $ad->title }}</div>
                                    <div class="text-[9px] tracking-wider text-copy-ticker">
                                        CTR: {{ $ad->ctr }} // SCORE: {{ number_format($ad->current_score, 1) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- ── RECENT LEADS + SYSTEM MESSAGES ──────────────────────────── --}}
        <div class="grid grid-cols-3 gap-4">

            {{-- Recent Leads --}}
            <div class="col-span-2 bg-ink-panel border border-line-warm p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-[10px] tracking-[2px] text-copy-neutral">RECENT LEADS</div>
                    <span class="text-[9px] tracking-[1.5px] text-copy-ticker">KLICKS ZUM HÄNDLER // LETZTE 5</span>
                </div>
                @if($recentLeads->isEmpty())
                    <div class="text-[9px] tracking-[2px] text-copy-ticker py-4">NO_LEADS_YET // CATALOG_TRAFFIC_PENDING</div>
                @else
                    <table class="w-full text-[10px] tracking-wider">
                        <thead>
                        <tr class="border-b border-line-warm">
                            <th class="text-left pb-2 text-copy-ticker font-normal">AD</th>
                            <th class="text-left pb-2 text-copy-ticker font-normal">BUYER</th>
                            <th class="text-left pb-2 text-copy-ticker font-normal">ZEITPUNKT</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentLeads as $lead)
                            <tr class="border-b border-line-warm/40 hover:bg-ink-surface transition-colors">
                                <td class="py-3 text-copy-soft truncate max-w-[200px]">{{ $lead->ad_title }}</td>
                                <td class="py-3 text-copy-ticker">{{ $lead->buyer_email ?? 'ANONYM' }}</td>
                                <td class="py-3 text-copy-ticker">{{ \Carbon\Carbon::parse($lead->created_at)->format('d.m. H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- System Messages — bleibt statisch, ist OK --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4 flex items-center gap-2">
                    <span class="text-brand-red">◉</span> SYSTEM_MELDUNGEN
                </div>
                <div class="space-y-3">
                    @if($activeAdsCount === 0)
                        <div class="border-l-2 border-brand-red pl-3">
                            <div class="text-[9px] text-copy-ticker">{{ now()->format('H:i:s') }}</div>
                            <div class="text-[10px] tracking-wider text-copy-soft mt-0.5 leading-relaxed">KEINE AKTIVEN ADS — CATALOG LEER.</div>
                        </div>
                    @endif
                    <div class="border-l-2 border-line-warm pl-3">
                        <div class="text-[9px] text-copy-ticker">SYSTEM</div>
                        <div class="text-[10px] tracking-wider text-copy-neutral mt-0.5 leading-relaxed">SCORE-RECALC LÄUFT ALLE 5 MIN.</div>
                    </div>
                    <div class="border-l-2 border-line-warm pl-3">
                        <div class="text-[9px] text-copy-ticker">SYSTEM</div>
                        <div class="text-[10px] tracking-wider text-copy-neutral mt-0.5 leading-relaxed">BACKUP-SYNCHRONISIERUNG ERFOLGREICH.</div>
                    </div>
                    <div class="border-l-2 border-line-warm pl-3">
                        <div class="text-[9px] text-copy-ticker">SYSTEM</div>
                        <div class="text-[10px] tracking-wider text-copy-neutral mt-0.5 leading-relaxed">SECURITY_AUDIT: NO_THREATS_DETECTED.</div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
