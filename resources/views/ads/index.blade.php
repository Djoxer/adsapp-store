<x-app-layout>
    @unless($isApproved)
        <div class="mb-4 px-4 py-3 text-[10px] tracking-[1.5px]" style="background:#1a0f0f;border:1px solid #DC2626;color:#DC2626;">
            ⚠ ACCOUNT WARTET AUF FREIGABE — ADS KÖNNEN ERST NACH ADMIN-BESTÄTIGUNG ERSTELLT WERDEN
        </div>
    @endunless
    <div class="p-6 space-y-5">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="text-[9px] tracking-[3px] text-copy-ticker mb-1">MERCHANT_CONSOLE // AD_MANAGEMENT</div>
                <div class="text-[18px] font-sans font-bold text-copy-soft tracking-wider">MEINE ADS</div>
            </div>
            <a href="{{ route('ads.create') }}"
               class="flex items-center gap-2 px-5 py-2.5 font-sans font-bold text-[11px] tracking-[2px] transition-colors"
               style="background:#DC2626;color:white;"
               onmouseover="this.style.background='#FF535B'"
               onmouseout="this.style.background='#DC2626'">
                <x-icons.create class="w-4 h-4" />
                AD ERSTELLEN
            </a>
        </div>

        {{-- STATS ROW --}}
        <div class="grid grid-cols-4 gap-3">
            @foreach([
                ['label'=>'GESAMT ADS', 'value'=>$stats['total'],  'sub'=>'ALLE EINTRÄGE',     'color'=>'#A1A1AA'],
                ['label'=>'AKTIV',      'value'=>$stats['active'], 'sub'=>'LIVE IM CATALOG',   'color'=>'#F5B700'],
                ['label'=>'PAUSIERT',   'value'=>$stats['paused'], 'sub'=>'NICHT SICHTBAR',    'color'=>'#999999'],
                ['label'=>'ENTWURF',    'value'=>$stats['draft'],  'sub'=>'NICHT VERÖFFENTL.', 'color'=>'#DC2626'],
            ] as $stat)
                <div class="p-4 relative overflow-hidden" style="background:#271717;border:1px solid #5B403F;">
                    <div class="text-[9px] tracking-[2px] text-copy-neutral mb-2">{{ $stat['label'] }}</div>
                    <div class="text-3xl font-sans font-bold" style="color:{{ $stat['color'] }};">{{ str_pad($stat['value'], 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-[9px] tracking-[1.5px] text-copy-ticker mt-1">{{ $stat['sub'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- FILTER BAR --}}
        <div class="flex items-center gap-3 flex-wrap">
            @foreach(['alle'=>'ALLE','active'=>'AKTIV','paused'=>'PAUSIERT','draft'=>'ENTWURF'] as $val => $label)
                <a href="{{ route('ads.index', array_merge(request()->query(), ['status'=>$val, 'page'=>1])) }}"
                   class="px-4 py-1.5 text-[10px] tracking-[1.5px] border transition-colors"
                   style="{{ request('status',$_default='alle') === $val
               ? 'border-color:#F5B700;color:#F5B700;'
               : 'border-color:#5B403F;color:#A1A1AA;' }}">
                    {{ $label }}
                </a>
            @endforeach

            {{-- Kategorie-Dropdown --}}
            <div class="relative">
                <select name="category"
                        onchange="window.location=this.value"
                        class="appearance-none pl-3 pr-7 py-1.5 text-[10px] tracking-[1.5px] focus:outline-none transition-colors cursor-pointer"
                        style="background:#1a0f0f;border:1px solid #5B403F;color:{{ request('category') ? '#F5B700' : '#A1A1AA' }};">
                    <option value="{{ route('ads.index', array_merge(request()->except('category'), ['page'=>1])) }}"
                        {{ !request('category') ? 'selected' : '' }}>
                        ALLE KAT.
                    </option>
                    @foreach($categories as $cat)
                        <option value="{{ route('ads.index', array_merge(request()->query(), ['category'=>$cat->id, 'page'=>1])) }}"
                            {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ strtoupper($cat->name) }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute right-2 inset-y-0 flex items-center pointer-events-none" style="color:#999999;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div class="flex-1"></div>

            {{-- Search --}}
            <form method="GET" action="{{ route('ads.index') }}" class="relative flex items-center gap-2">
                @if(request('status') && request('status') !== 'alle')
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative">
                    <div class="absolute left-2.5 inset-y-0 flex items-center pointer-events-none" style="color:#999999;">
                        <x-icons.search class="w-3.5 h-3.5" />
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="AD SUCHEN..."
                           class="pl-8 pr-4 py-1.5 text-[10px] tracking-wider focus:outline-none transition-colors"
                           style="background:#1a0f0f;border:1px solid {{ request('search') ? '#F5B700' : '#5B403F' }};color:#A1A1AA;width:200px;">
                </div>
                @if(request('search'))
                    <a href="{{ route('ads.index', array_merge(request()->except('search'), ['page'=>1])) }}"
                       class="text-[9px] tracking-wider transition-colors"
                       style="color:#999999;"
                       onmouseover="this.style.color='#DC2626'"
                       onmouseout="this.style.color='#999999'">✕</a>
                @endif
            </form>
        </div>

        {{-- ADS TABLE --}}
        <div style="background:#271717;border:1px solid #5B403F;">

            {{-- Table Header --}}
            <div class="grid text-[9px] tracking-[2px] text-copy-ticker px-4 py-3"
                 style="grid-template-columns: 48px 1fr 80px 80px 80px 100px 120px;border-bottom:1px solid #5B403F;">
                <div></div>
                <div>AD_TITEL</div>
                <div>PREIS</div>
                <div>SCORE</div>
                <div>CTR</div>
                <div>STATUS</div>
                <div class="text-right">AKTIONEN</div>
            </div>

            {{-- Rows --}}
            @forelse($ads as $ad)
                <div class="grid items-center px-4 py-3 transition-colors group"
                     style="grid-template-columns: 48px 1fr 80px 80px 80px 100px 120px;border-bottom:1px solid rgba(91,64,63,0.4);"
                     onmouseover="this.style.background='rgba(44,27,27,0.6)'"
                     onmouseout="this.style.background='transparent'">

                    <div class="w-9 h-9 flex items-center justify-center text-[7px] flex-shrink-0"
                         style="background:#1a0f0f;border:1px solid #5B403F;color:#999999;">
                        @if($ad->images->first())
                            <img src="{{ asset('storage/' . $ad->images->first()->cache_path) }}" class="w-full h-full object-cover">
                        @else
                            IMG
                        @endif
                    </div>

                    <div class="px-3 min-w-0">
                        <div class="text-[11px] font-sans font-semibold tracking-wider truncate" style="color:#e8e8e8;">{{ $ad->title }}</div>
                        <div class="text-[9px] tracking-wider mt-0.5" style="color:#999999;">ID_{{ str_pad($ad->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="text-[11px] tracking-wider font-sans font-bold" style="color:#F5B700;">{{ $ad->price_euro }}</div>

                    <div class="text-[11px] tracking-wider font-sans font-bold"
                         style="color:{{ ($ad->current_score ?? 0) >= 70 ? '#F5B700' : (($ad->current_score ?? 0) >= 50 ? '#A1A1AA' : '#DC2626') }};">
                        {{ $ad->current_score ?? '—' }}
                    </div>

                    <div class="text-[11px] tracking-wider" style="color:#A1A1AA;">—</div>

                    <div>
                        @php
                            $statusStyle = match($ad->status) {
                                'active'  => 'border-color:#F5B700;color:#F5B700;',
                                'paused'  => 'border-color:#999999;color:#999999;',
                                'draft'   => 'border-color:#DC2626;color:#DC2626;',
                                default   => 'border-color:#999999;color:#999999;',
                            };
                            $statusLabel = match($ad->status) {
                                'active' => 'AKTIV',
                                'paused' => 'PAUSIERT',
                                'draft'  => 'ENTWURF',
                                default  => strtoupper($ad->status),
                            };
                        @endphp
                        <span class="status-badge text-[9px] tracking-[1.5px] px-2 py-0.5 border font-sans font-bold"
                              style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('ads.edit', $ad->id) }}"
                           class="w-7 h-7 flex items-center justify-center transition-colors"
                           style="border:1px solid #5B403F;color:#A1A1AA;"
                           onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                           onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'">
                            <x-icons.edit class="w-3.5 h-3.5" />
                        </a>
                        <button class="w-7 h-7 flex items-center justify-center transition-colors toggle-status-btn"
                                data-id="{{ $ad->id }}"
                                data-status="{{ $ad->status }}"
                                style="border:1px solid #5B403F;color:#A1A1AA;"
                                onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                                onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'"
                                title="{{ $ad->status === 'active' ? 'Pausieren' : 'Aktivieren' }}">
                            <x-dynamic-component :component="'icons.' . ($ad->status === 'active' ? 'hidden' : 'show')" class="w-3.5 h-3.5" />
                        </button>
                        <button class="w-7 h-7 flex items-center justify-center transition-colors"
                                style="border:1px solid #5B403F;color:#A1A1AA;"
                                onmouseover="this.style.borderColor='#DC2626';this.style.color='#DC2626'"
                                onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'"
                                onclick="confirmDelete({{ $ad->id }}, '{{ addslashes($ad->title) }}')">
                            <x-icons.trash class="w-3.5 h-3.5" />
                        </button>
                    </div>

                </div>
            @empty
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-16 gap-4">
                    <x-icons.fav class="w-10 h-10" style="color:#2a1a1a;" />
                    <div class="text-[10px] tracking-[2px] text-copy-ticker">KEINE ADS GEFUNDEN</div>
                    <a href="{{ route('ads.create') }}"
                       class="mt-2 px-5 py-2 text-[10px] tracking-[2px] font-sans font-bold transition-colors"
                       style="border:1px solid #DC2626;color:#DC2626;"
                       onmouseover="this.style.background='#DC2626';this.style.color='white'"
                       onmouseout="this.style.background='transparent';this.style.color='#DC2626'">
                        ERSTE AD ERSTELLEN
                    </a>
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between text-[9px] tracking-[1.5px]" style="color:#999999;">
            <div>
                ZEIGE {{ $ads->firstItem() ?? 0 }}–{{ $ads->lastItem() ?? 0 }} VON {{ $ads->total() }} EINTRÄGEN
            </div>
            @if($ads->hasPages())
                <div class="flex gap-1">
                    {{-- Prev --}}
                    @if(!$ads->onFirstPage())
                        <a href="{{ $ads->previousPageUrl() }}"
                           class="w-7 h-7 flex items-center justify-center border transition-colors"
                           style="border-color:#5B403F;color:#A1A1AA;"
                           onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                           onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'">&larr;</a>
                    @endif

                    {{-- Seitenzahlen --}}
                    @foreach(range(1, $ads->lastPage()) as $p)
                        <a href="{{ $ads->url($p) }}"
                           class="w-7 h-7 flex items-center justify-center border transition-colors"
                           style="{{ $p === $ads->currentPage()
                   ? 'border-color:#F5B700;color:#F5B700;'
                   : 'border-color:#5B403F;color:#A1A1AA;' }}"
                           onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                           onmouseout="this.style.borderColor='{{ $p === $ads->currentPage() ? '#F5B700' : '#5B403F' }}';this.style.color='{{ $p === $ads->currentPage() ? '#F5B700' : '#A1A1AA' }}'">
                            {{ $p }}
                        </a>
                    @endforeach

                    {{-- Next --}}
                    @if($ads->hasMorePages())
                        <a href="{{ $ads->nextPageUrl() }}"
                           class="w-7 h-7 flex items-center justify-center border transition-colors"
                           style="border-color:#5B403F;color:#A1A1AA;"
                           onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                           onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'">&rarr;</a>
                    @endif
                </div>
            @endif
        </div>

    </div>

    {{-- Delete Confirm Overlay --}}
    <div id="delete-overlay" class="hidden fixed inset-0 z-[200] flex items-center justify-center"
         style="background:rgba(5,2,2,0.94);">
        <div class="w-full max-w-[400px] mx-4" style="background:#111111;border:1px solid #DC2626;">
            <div class="px-6 py-4" style="border-bottom:1px solid #2a2a2a;">
                <div class="text-[9px] tracking-[2px] mb-1" style="color:#DC2626;">⚠ WARNUNG // IRREVERSIBEL</div>
                <div class="text-[14px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">AD LÖSCHEN</div>
            </div>
            <div class="px-6 py-4">
                <div class="text-[11px] tracking-wider leading-relaxed mb-1" style="color:#A1A1AA;">
                    Folgende Ad wird permanent gelöscht:
                </div>
                <div id="delete-ad-title" class="text-[12px] font-sans font-bold tracking-wider my-3" style="color:#e8e8e8;"></div>
                <div class="text-[10px] tracking-wider" style="color:#999999;">Diese Aktion kann nicht rückgängig gemacht werden.</div>
            </div>
            <div class="px-6 py-4 flex gap-3 justify-end" style="border-top:1px solid #2a2a2a;">
                <button onclick="closeDelete()"
                        class="px-4 py-2 text-[10px] tracking-[1.5px] transition-colors"
                        style="border:1px solid #5B403F;color:#A1A1AA;"
                        onmouseover="this.style.borderColor='#A1A1AA'"
                        onmouseout="this.style.borderColor='#5B403F'">
                    ABBRECHEN
                </button>
                <form id="delete-form" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 text-[10px] tracking-[1.5px] font-sans font-bold transition-colors"
                            style="background:#DC2626;color:white;"
                            onmouseover="this.style.background='#FF535B'"
                            onmouseout="this.style.background='#DC2626'">
                        LÖSCHEN BESTÄTIGEN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, title) {
            document.getElementById('delete-ad-title').textContent = title;
            document.getElementById('delete-form').action = `{{ url('ads') }}/${id}`;
            document.getElementById('delete-overlay').classList.remove('hidden');
        }
        function closeDelete() {
            document.getElementById('delete-overlay').classList.add('hidden');
        }

        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.dataset.id;
                const row = this.closest('[data-ad-id]') ?? this.closest('.grid');

                try {
                    const res = await fetch(`/ads/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });

                    if (!res.ok) {
                        const err = await res.json();
                        alert(err.error ?? 'Fehler beim Status-Wechsel.');
                        return;
                    }

                    const data = await res.json();

                    // Status-Badge in der Zeile aktualisieren
                    const badge = this.closest('.grid').querySelector('.status-badge');
                    if (badge) {
                        badge.textContent = data.label;
                        badge.style.cssText = data.statusStyle;
                    }

                    // Icon tauschen
                    this.dataset.status = data.status;
                    const icon = this.querySelector('svg');
                    // Icon-Swap: einfachster Weg — ganzes Element neu laden würde SPA brauchen,
                    // daher nur opacity-Feedback + title update als visuelles Signal
                    this.title = data.status === 'active' ? 'Pausieren' : 'Aktivieren';
                    this.style.borderColor = data.status === 'active' ? '#F5B700' : '#999999';
                    this.style.color       = data.status === 'active' ? '#F5B700' : '#999999';
                    setTimeout(() => {
                        this.style.borderColor = '#5B403F';
                        this.style.color       = '#A1A1AA';
                    }, 800);

                } catch (e) {
                    console.error('Toggle failed:', e);
                }
            });
        });
    </script>

</x-app-layout>
