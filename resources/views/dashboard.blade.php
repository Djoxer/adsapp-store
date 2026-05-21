<x-app-layout>

    <div class="p-6 space-y-6">

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-4 gap-4">

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">AKTIVE ADS</div>
                <div class="text-4xl font-sans font-bold text-copy-soft">34</div>
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-2">SYSTEM_LOAD: OPTIMAL</div>
                <div class="absolute top-4 right-4 text-brand-yellow">
                    <x-icons.fav class="w-5 h-5" />
                </div>
            </div>

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">VERKÄUFE HEUTE</div>
                <div class="text-4xl font-sans font-bold text-copy-soft">07</div>
                <div class="text-[9px] tracking-[1.5px] text-brand-yellow mt-2">+14.2% VS GESTERN</div>
                <div class="absolute top-4 right-4 text-brand-yellow">
                    <x-icons.trend class="w-5 h-5" />
                </div>
            </div>

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">GESAMT_UMSATZ</div>
                <div class="text-4xl font-sans font-bold text-copy-soft">€12.8K</div>
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-2">LIFETIME_VALUE_NETTO</div>
                <div class="absolute top-4 right-4 text-brand-yellow">
                    <x-icons.cash class="w-5 h-5" />
                </div>
            </div>

            <div class="bg-ink-panel border border-line-warm p-4 relative overflow-hidden">
                <div class="text-[9px] tracking-[2px] text-copy-neutral mb-3">SCORE_AVG</div>
                <div class="text-4xl font-sans font-bold text-brand-yellow">82.1</div>
                <div class="flex gap-1 mt-3">
                    @foreach([4,4,4,3,2] as $w)
                        <div class="h-1.5 flex-1 {{ $loop->index < 3 ? 'bg-brand-yellow' : 'bg-line-warm' }}"></div>
                    @endforeach
                </div>
                <div class="absolute top-4 right-4 text-brand-yellow">
                    <x-icons.stats class="w-5 h-5" />
                </div>
            </div>

        </div>

        {{-- CHART + TOP ADS --}}
        <div class="grid grid-cols-3 gap-4">

            {{-- Chart --}}
            <div class="col-span-2 bg-ink-panel border border-line-warm p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-[10px] tracking-[2px] text-copy-neutral">PERFORMANCE_LAST_30_DAYS</div>
                    <div class="flex gap-4 text-[9px] tracking-[1px]">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-brand-red inline-block"></span> IMPRESSIONS</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-brand-yellow inline-block"></span> CONVERSIONS</span>
                    </div>
                </div>
                {{-- Dummy Chart --}}
                <div class="h-48 flex items-end gap-1 border-b border-l border-line-warm px-2 pb-2">
                    @php
                        $impressions = [30,45,35,60,50,70,55,80,65,90,75,85,70,95,80,100,85,75,90,80,95,85,100,90,85,95,100,90,95,100];
                        $conversions = [10,15,12,20,18,25,20,30,25,35,28,32,27,38,30,40,33,28,35,30,38,33,40,35,32,38,40,35,38,40];
                    @endphp
                    @foreach($impressions as $i => $val)
                        <div class="flex-1 flex flex-col items-center gap-0.5 h-full justify-end">
                            <div class="w-full bg-brand-yellow/60" style="height: {{ $conversions[$i] }}%"></div>
                            <div class="w-full bg-brand-red/60" style="height: {{ $val/2 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Ads --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">DEINE TOP 5 ADS</div>
                <div class="space-y-2">
                    @foreach([
                        ['rank'=>1,'name'=>'Gaming Chair 2024','ctr'=>'4.8%','roi'=>'2.1x','active'=>true],
                        ['rank'=>2,'name'=>'Acoustic Pro X','ctr'=>'3.2%','roi'=>'1.8x','active'=>false],
                        ['rank'=>3,'name'=>'Vintage Tech Bundle','ctr'=>'2.9%','roi'=>'1.4x','active'=>false],
                        ['rank'=>4,'name'=>'RGB Strip 2m','ctr'=>'2.1%','roi'=>'1.2x','active'=>false],
                        ['rank'=>5,'name'=>'USB-C Hub Pro','ctr'=>'1.8%','roi'=>'1.1x','active'=>false],
                    ] as $ad)
                        <div class="flex items-center gap-3 p-2 {{ $ad['active'] ? 'bg-ink-surface border border-line-warm' : '' }}">
                            <div class="w-5 h-5 flex items-center justify-center text-[9px] font-bold
                            {{ $ad['active'] ? 'bg-brand-red text-white' : 'bg-line-warm text-copy-neutral' }}">
                                {{ $ad['rank'] }}
                            </div>
                            <div class="w-8 h-8 bg-ink-surface border border-line-warm flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] tracking-wider text-copy-soft truncate">{{ $ad['name'] }}</div>
                                <div class="text-[9px] tracking-wider text-copy-ticker">CTR: {{ $ad['ctr'] }} // ROI: {{ $ad['roi'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ORDERS + SYSTEM MESSAGES --}}
        <div class="grid grid-cols-3 gap-4">

            {{-- Recent Orders --}}
            <div class="col-span-2 bg-ink-panel border border-line-warm p-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-[10px] tracking-[2px] text-copy-neutral">RECENT ORDERS</div>
                    <a href="#" class="text-[9px] tracking-[1.5px] text-copy-ticker hover:text-brand-yellow transition-colors">VIEW_ALL_LOGS →</a>
                </div>
                <table class="w-full text-[10px] tracking-wider">
                    <thead>
                    <tr class="border-b border-line-warm">
                        <th class="text-left pb-2 text-copy-ticker font-normal">BESTELLNUMMER</th>
                        <th class="text-left pb-2 text-copy-ticker font-normal">PRODUKT</th>
                        <th class="text-left pb-2 text-copy-ticker font-normal">KÄUFER</th>
                        <th class="text-left pb-2 text-copy-ticker font-normal">BETRAG</th>
                        <th class="text-left pb-2 text-copy-ticker font-normal">STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach([
                        ['nr'=>'#ORD-9921','product'=>'Pro-Gamer Mousepad','buyer'=>'m.schmidt@web.de','amount'=>'€24.99','status'=>'BEZAHLT','color'=>'brand-yellow'],
                        ['nr'=>'#ORD-9920','product'=>'Neon Cable Kit','buyer'=>'tech_nick@gmail.com','amount'=>'€15.50','status'=>'VERSENDET','color'=>'copy-neutral'],
                        ['nr'=>'#ORD-9919','product'=>'RGB Strip 2m','buyer'=>'alex.jones@yahoo.com','amount'=>'€42.00','status'=>'BEZAHLT','color'=>'brand-yellow'],
                    ] as $order)
                        <tr class="border-b border-line-warm/40 hover:bg-ink-surface transition-colors">
                            <td class="py-3 text-copy-soft">{{ $order['nr'] }}</td>
                            <td class="py-3 text-copy-neutral">{{ $order['product'] }}</td>
                            <td class="py-3 text-copy-ticker">{{ $order['buyer'] }}</td>
                            <td class="py-3 text-copy-soft">{{ $order['amount'] }}</td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 border text-[9px] tracking-wider
                                    {{ $order['color'] === 'brand-yellow' ? 'border-brand-yellow text-brand-yellow' : 'border-copy-neutral text-copy-neutral' }}">
                                    {{ $order['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- System Messages --}}
            <div class="bg-ink-panel border border-line-warm p-4">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4 flex items-center gap-2">
                    <span class="text-brand-red">◉</span> SYSTEM_MELDUNGEN
                </div>
                <div class="space-y-3">
                    @foreach([
                        ['time'=>'12:44:11','msg'=>"KAMPAGNE 'SUMMER_SALE' ERREICHT BUDGET-LIMIT.",'urgent'=>true],
                        ['time'=>'12:10:05','msg'=>'NEUE ANALYTICS-BERICHTE VERFÜGBAR.','urgent'=>false],
                        ['time'=>'11:58:32','msg'=>'BACKUP-SYNCHRONISIERUNG ERFOLGREICH.','urgent'=>false],
                        ['time'=>'10:02:14','msg'=>'SECURITY_AUDIT: NO_THREATS_DETECTED.','urgent'=>false],
                    ] as $msg)
                        <div class="border-l-2 {{ $msg['urgent'] ? 'border-brand-red' : 'border-line-warm' }} pl-3">
                            <div class="text-[9px] text-copy-ticker">{{ $msg['time'] }}</div>
                            <div class="text-[10px] tracking-wider {{ $msg['urgent'] ? 'text-copy-soft' : 'text-copy-neutral' }} mt-0.5 leading-relaxed">
                                {{ $msg['msg'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
