<x-app-layout>
    <div class="p-6 space-y-5">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="text-[9px] tracking-[3px] text-copy-ticker mb-1">MERCHANT_CONSOLE // LEADS_&_ORDERS</div>
                <div class="text-[18px] font-sans font-bold text-copy-soft tracking-wider">BESTELLUNGEN & LEADS</div>
            </div>
        </div>

        {{-- STATS ROW --}}
        <div class="grid grid-cols-4 gap-3">
            @foreach([
                ['label'=>'LEADS GESAMT',  'value'=>$totalLeads,   'sub'=>'ALLE DWELL-EVENTS',   'color'=>'#A1A1AA'],
                ['label'=>'LEADS HEUTE',   'value'=>$leadsToday,   'sub'=>'SEIT MITTERNACHT',     'color'=>'#F5B700'],
                ['label'=>'LEADS WOCHE',   'value'=>$leadsWeek,    'sub'=>'LETZTE 7 TAGE',        'color'=>'#F5B700'],
                ['label'=>'CONVERSION',    'value'=>$convRate.'%', 'sub'=>'DWELL → SALE (EST.)',  'color'=>'#DC2626'],
            ] as $stat)
                <div class="p-4 relative overflow-hidden" style="background:#271717;border:1px solid #5B403F;">
                    <div class="text-[9px] tracking-[2px] text-copy-neutral mb-2">{{ $stat['label'] }}</div>
                    <div class="text-3xl font-sans font-bold" style="color:{{ $stat['color'] }};">{{ $stat['value'] }}</div>
                    <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-1">{{ $stat['sub'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- TABS --}}
        <div class="flex gap-0" style="border-bottom:1px solid #5B403F;">
            @foreach(['LEADS' => 'leads', 'BESTELLUNGEN' => 'orders'] as $label => $tab)
                <button onclick="switchTab('{{ $tab }}')" id="tab-{{ $tab }}"
                        class="px-5 py-2.5 text-[10px] tracking-[2px] font-sans font-bold transition-colors tab-btn"
                        style="{{ $tab === 'leads' ? 'border-bottom:2px solid #F5B700;color:#F5B700;margin-bottom:-1px;' : 'color:#454745;border-bottom:2px solid transparent;margin-bottom:-1px;' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- LEADS TAB --}}
        <div id="panel-leads">
            <div style="background:#271717;border:1px solid #5B403F;">

                {{-- Header --}}
                <div class="grid text-[9px] tracking-[2px] text-copy-ticker px-4 py-3"
                     style="grid-template-columns:60px 1fr 180px 140px 100px;border-bottom:1px solid #5B403F;">
                    <div>ID</div>
                    <div>AD_TITEL</div>
                    <div>KÄUFER</div>
                    <div>ZEITPUNKT</div>
                    <div class="text-right">AD ANZEIGEN</div>
                </div>

                @forelse($leads as $lead)
                    <div class="grid items-center px-4 py-3 transition-colors"
                         style="grid-template-columns:60px 1fr 180px 140px 100px;border-bottom:1px solid rgba(91,64,63,0.4);"
                         onmouseover="this.style.background='rgba(44,27,27,0.6)'"
                         onmouseout="this.style.background='transparent'">

                        <div class="text-[9px] tracking-wider font-sans font-bold" style="color:#454745;">
                            #{{ str_pad($lead->id, 5, '0', STR_PAD_LEFT) }}
                        </div>

                        <div class="px-3 min-w-0">
                            <div class="text-[11px] font-sans font-semibold tracking-wider truncate" style="color:#e8e8e8;">
                                {{ $lead->ad_title }}
                            </div>
                            <div class="text-[9px] tracking-wider mt-0.5" style="color:#454745;">
                                AD_{{ str_pad($lead->ad_id, 4, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>

                        <div class="text-[10px] tracking-wider" style="color:#A1A1AA;">
                            {{ $lead->buyer_email ?? '—&nbsp;ANONYM' }}
                        </div>

                        <div>
                            <div class="text-[10px] tracking-wider" style="color:#A1A1AA;">
                                {{ \Carbon\Carbon::parse($lead->created_at)->format('d.m.Y') }}
                            </div>
                            <div class="text-[9px] tracking-wider mt-0.5" style="color:#454745;">
                                {{ \Carbon\Carbon::parse($lead->created_at)->format('H:i') }} UHR
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('ads.show', $lead->ad_id) }}"
                               class="w-7 h-7 flex items-center justify-center transition-colors"
                               style="border:1px solid #5B403F;color:#A1A1AA;"
                               onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                               onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'">
                                <x-icons.show class="w-3.5 h-3.5" />
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 gap-4">
                        <x-icons.signal class="w-10 h-10" style="color:#2a1a1a;" />
                        <div class="text-[10px] tracking-[2px] text-copy-ticker">KEINE LEADS VORHANDEN</div>
                        <div class="text-[9px] tracking-wider" style="color:#454745;">Leads entstehen wenn Käufer auf deine Ads klicken.</div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination Leads --}}
            @if($leads->hasPages())
                <div class="flex items-center justify-between text-[9px] tracking-[1.5px] mt-3" style="color:#454745;">
                    <div>ZEIGE {{ $leads->firstItem() }}–{{ $leads->lastItem() }} VON {{ $leads->total() }} LEADS</div>
                    <div class="flex gap-1">
                        @if(!$leads->onFirstPage())
                            <a href="{{ $leads->previousPageUrl() }}"
                               class="w-7 h-7 flex items-center justify-center border transition-colors"
                               style="border-color:#5B403F;color:#A1A1AA;"
                               onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                               onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'">&larr;</a>
                        @endif
                        @foreach(range(1, min($leads->lastPage(), 7)) as $p)
                            <a href="{{ $leads->url($p) }}"
                               class="w-7 h-7 flex items-center justify-center border transition-colors"
                               style="{{ $p === $leads->currentPage() ? 'border-color:#F5B700;color:#F5B700;' : 'border-color:#5B403F;color:#A1A1AA;' }}"
                               onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                               onmouseout="this.style.borderColor='{{ $p===$leads->currentPage()?'#F5B700':'#5B403F' }}';this.style.color='{{ $p===$leads->currentPage()?'#F5B700':'#A1A1AA' }}'">
                                {{ $p }}
                            </a>
                        @endforeach
                        @if($leads->hasMorePages())
                            <a href="{{ $leads->nextPageUrl() }}"
                               class="w-7 h-7 flex items-center justify-center border transition-colors"
                               style="border-color:#5B403F;color:#A1A1AA;"
                               onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                               onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'">&rarr;</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ORDERS TAB (Dummy/Placeholder) --}}
        <div id="panel-orders" class="hidden">
            <div style="background:#271717;border:1px solid #5B403F;">
                <div class="flex flex-col items-center justify-center py-16 gap-4">
                    <x-icons.cash class="w-10 h-10" style="color:#2a1a1a;" />
                    <div class="text-[10px] tracking-[2px] text-copy-ticker">BESTELLUNGEN // COMING SOON</div>
                    <div class="text-[9px] tracking-wider" style="color:#454745;">In-App-Checkout ab v2. Aktuell leitet AdsApp direkt zum Händler-Shop.</div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function switchTab(tab) {
            ['leads','orders'].forEach(t => {
                document.getElementById('panel-' + t).classList.toggle('hidden', t !== tab);
                const btn = document.getElementById('tab-' + t);
                if (t === tab) {
                    btn.style.borderBottom = '2px solid #F5B700';
                    btn.style.color = '#F5B700';
                } else {
                    btn.style.borderBottom = '2px solid transparent';
                    btn.style.color = '#454745';
                }
            });
        }
    </script>

</x-app-layout>
