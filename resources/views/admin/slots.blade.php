<x-admin-layout>
    <x-slot name="header">SLOT-ANTRÄGE // FREIGABE</x-slot>

    {{-- Flash --}}
    @if(session('status'))
        @php
            $msg = match(session('status')) {
                'slot-approved' => '✓ ANTRAG GENEHMIGT — SLOT GEHT LIVE / WARTET AUF STARTZEIT',
                'slot-rejected' => '✕ ANTRAG ABGELEHNT',
                default => null,
            };
        @endphp
        @if($msg)
            <div class="mb-6 px-4 py-3 text-[10px] tracking-[1.5px]" style="background:#111a2b;border:1px solid #4fc3f7;color:#4fc3f7;">{{ $msg }}</div>
        @endif
    @endif

    {{-- PENDING --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1 h-4" style="background:#4fc3f7;"></span>
            <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8f4ff;">OFFENE ANTRÄGE</span>
            <span class="text-[9px] tracking-[1.5px] px-2 py-0.5" style="background:rgba(79,195,247,0.1);color:#4fc3f7;">{{ $pending->count() }}</span>
        </div>

        @forelse($pending as $b)
            <div class="mb-3 p-4" style="background:#111a2b;border:1px solid #1e3050;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6 min-w-0">
                        {{-- Slot --}}
                        <div class="flex-shrink-0">
                            <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">SLOT</div>
                            <div class="text-[14px] font-sans font-bold" style="color:#4fc3f7;">{{ $b->slot->zone }}-{{ $b->slot->position }}</div>
                        </div>
                        {{-- Ad + Merchant --}}
                        <div class="min-w-0">
                            <div class="text-[12px] tracking-wider truncate" style="color:#e8f4ff;">{{ $b->ad->title ?? '—' }}</div>
                            <div class="text-[9px] tracking-[1.5px] mt-0.5" style="color:#5a7a9a;">
                                {{ $b->merchant->user->name ?? 'UNBEKANNT' }} · {{ $b->merchant->user->email ?? '' }}
                            </div>
                        </div>
                        {{-- Details --}}
                        <div class="flex gap-6 flex-shrink-0">
                            <div>
                                <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">LAUFZEIT</div>
                                <div class="text-[12px]" style="color:#c8d8e8;">{{ $b->duration_days }} TAGE</div>
                            </div>
                            <div>
                                <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">PREIS</div>
                                <div class="text-[12px]" style="color:#43d685;">{{ number_format($b->total_cents/100,2,',','.') }}€</div>
                            </div>
                            <div>
                                <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">QUEUE</div>
                                <div class="text-[12px]" style="color:#c8d8e8;">#{{ $b->queue_position }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Aktionen --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('admin.slots.approve', $b->id) }}">
                            @csrf
                            <button class="px-4 py-2 text-[10px] tracking-[1.5px] font-bold transition-colors"
                                    style="background:#43d685;color:#0a0f1a;"
                                    onmouseover="this.style.background='#5eeba0'" onmouseout="this.style.background='#43d685'">
                                GENEHMIGEN
                            </button>
                        </form>
                        <button onclick="document.getElementById('reject-{{ $b->id }}').classList.toggle('hidden')"
                                class="px-4 py-2 text-[10px] tracking-[1.5px] font-bold transition-colors"
                                style="background:transparent;border:1px solid #dc2626;color:#dc2626;"
                                onmouseover="this.style.background='#dc2626';this.style.color='white'"
                                onmouseout="this.style.background='transparent';this.style.color='#dc2626'">
                            ABLEHNEN
                        </button>
                    </div>
                </div>

                {{-- Reject-Form (toggle) --}}
                <div id="reject-{{ $b->id }}" class="hidden mt-3 pt-3" style="border-top:1px solid #1e3050;">
                    <form method="POST" action="{{ route('admin.slots.reject', $b->id) }}" class="flex gap-2">
                        @csrf
                        <input type="text" name="reason" placeholder="GRUND (OPTIONAL)"
                               class="flex-1 px-3 py-2 text-[10px] tracking-wider focus:outline-none"
                               style="background:#0a0f1a;border:1px solid #1e3050;color:#c8d8e8;">
                        <button class="px-4 py-2 text-[10px] tracking-[1.5px] font-bold" style="background:#dc2626;color:white;">
                            ABLEHNUNG BESTÄTIGEN
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-[9px] tracking-[2px]" style="background:#111a2b;border:1px solid #1e3050;color:#5a7a9a;">
                KEINE OFFENEN ANTRÄGE
            </div>
        @endforelse
    </div>

    {{-- AKTIVE / GENEHMIGTE --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1 h-4" style="background:#43d685;"></span>
            <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8f4ff;">GENEHMIGT / LIVE</span>
        </div>
        <div style="background:#111a2b;border:1px solid #1e3050;">
            <div class="grid text-[9px] tracking-[1.5px] px-4 py-3" style="grid-template-columns:80px 1fr 140px 100px 100px;color:#5a7a9a;border-bottom:1px solid #1e3050;">
                <div>SLOT</div><div>AD</div><div>ZEITRAUM</div><div>STATUS</div><div class="text-right">PREIS</div>
            </div>
            @forelse($active as $b)
                <div class="grid items-center px-4 py-3 text-[10px]" style="grid-template-columns:80px 1fr 140px 100px 100px;border-bottom:1px solid #16243a;">
                    <div style="color:#4fc3f7;">{{ $b->slot->zone }}-{{ $b->slot->position }}</div>
                    <div class="truncate" style="color:#c8d8e8;">{{ $b->ad->title ?? '—' }}</div>
                    <div style="color:#5a7a9a;">
                        {{ $b->starts_at?->format('d.m.') }} – {{ $b->ends_at?->format('d.m.') }}
                    </div>
                    <div>
                        <span class="px-2 py-0.5 text-[8px] tracking-[1.5px]"
                              style="background:{{ $b->status === 'live' ? 'rgba(67,214,133,0.1)' : 'rgba(245,166,35,0.1)' }};color:{{ $b->status === 'live' ? '#43d685' : '#f5a623' }};">
                            {{ strtoupper($b->status) }}
                        </span>
                    </div>
                    <div class="text-right" style="color:#43d685;">{{ number_format($b->total_cents/100,2,',','.') }}€</div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-[9px] tracking-[2px]" style="color:#5a7a9a;">NOCH NICHTS GENEHMIGT</div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
