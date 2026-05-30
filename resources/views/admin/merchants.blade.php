<x-admin-layout>
    <x-slot name="header">HÄNDLER-FREIGABE</x-slot>

    {{-- Flash --}}
    @if(session('status'))
        @php
            $msg = match(session('status')) {
                'merchant-approved' => '✓ HÄNDLER FREIGEGEBEN',
                'merchant-rejected' => '✕ HÄNDLER ABGELEHNT',
                default => null,
            };
        @endphp
        @if($msg)
            <div class="mb-6 px-4 py-3 text-[10px] tracking-[1.5px]" style="background:#111a2b;border:1px solid #4fc3f7;color:#4fc3f7;">{{ $msg }}</div>
        @endif
    @endif

    {{-- ═══ WARTENDE HÄNDLER ═══ --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1 h-4" style="background:#f5a623;"></span>
            <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8f4ff;">WARTENDE FREIGABEN</span>
            <span class="text-[9px] tracking-[1.5px] px-2 py-0.5" style="background:rgba(245,166,35,0.1);color:#f5a623;">{{ $pending->count() }}</span>
        </div>

        @forelse($pending as $m)
            <div class="mb-3 p-4" style="background:#111a2b;border:1px solid #1e3050;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6 min-w-0">
                        {{-- Identität --}}
                        <div class="min-w-0">
                            <div class="text-[13px] font-sans font-bold tracking-wider truncate" style="color:#e8f4ff;">
                                {{ $m->company_name ?: ($m->user->name ?? '—') }}
                            </div>
                            <div class="text-[9px] tracking-[1.5px] mt-0.5" style="color:#5a7a9a;">
                                {{ $m->user->email ?? '—' }} · ID {{ $m->id }}
                            </div>
                        </div>
                        {{-- Details --}}
                        <div class="flex gap-6 flex-shrink-0">
                            <div>
                                <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">SHOP_URL</div>
                                <div class="text-[11px]" style="color:#c8d8e8;">
                                    @if($m->shop_url)
                                        <a href="{{ $m->shop_url }}" target="_blank" rel="noopener" style="color:#4fc3f7;" class="hover:underline">{{ \Illuminate\Support\Str::limit($m->shop_url, 32) }}</a>
                                    @else
                                        <span style="color:#5a7a9a;">— NICHT ANGEGEBEN</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">VAT_ID</div>
                                <div class="text-[11px]" style="color:#c8d8e8;">{{ $m->vat_id ?: '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">REGISTRIERT</div>
                                <div class="text-[11px]" style="color:#c8d8e8;">{{ $m->created_at?->format('d.m.Y') ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Aktionen --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('admin.merchants.approve', $m->id) }}">
                            @csrf
                            <button class="px-4 py-2 text-[10px] tracking-[1.5px] font-bold transition-colors"
                                    style="background:#43d685;color:#0a0f1a;"
                                    onmouseover="this.style.background='#5eeba0'" onmouseout="this.style.background='#43d685'">
                                FREIGEBEN
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.merchants.reject', $m->id) }}"
                              onsubmit="return confirm('Händler wirklich ablehnen?');">
                            @csrf
                            <button class="px-4 py-2 text-[10px] tracking-[1.5px] font-bold transition-colors"
                                    style="background:transparent;border:1px solid #dc2626;color:#dc2626;"
                                    onmouseover="this.style.background='#dc2626';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='#dc2626'">
                                ABLEHNEN
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-[9px] tracking-[2px]" style="background:#111a2b;border:1px solid #1e3050;color:#5a7a9a;">
                KEINE WARTENDEN HÄNDLER
            </div>
        @endforelse
    </div>

    {{-- ═══ ENTSCHIEDEN ═══ --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <span class="w-1 h-4" style="background:#43d685;"></span>
            <span class="text-[11px] font-sans font-bold tracking-[2px]" style="color:#e8f4ff;">ENTSCHIEDEN</span>
        </div>
        <div style="background:#111a2b;border:1px solid #1e3050;">
            <div class="grid text-[9px] tracking-[1.5px] px-4 py-3" style="grid-template-columns:1fr 180px 120px 140px;color:#5a7a9a;border-bottom:1px solid #1e3050;">
                <div>HÄNDLER</div><div>EMAIL</div><div>STATUS</div><div class="text-right">ENTSCHIEDEN VON</div>
            </div>
            @forelse($decided as $m)
                <div class="grid items-center px-4 py-3 text-[10px]" style="grid-template-columns:1fr 180px 120px 140px;border-bottom:1px solid #16243a;">
                    <div class="truncate" style="color:#c8d8e8;">{{ $m->company_name ?: ($m->user->name ?? '—') }}</div>
                    <div class="truncate" style="color:#5a7a9a;">{{ $m->user->email ?? '—' }}</div>
                    <div>
                        <span class="px-2 py-0.5 text-[8px] tracking-[1.5px]"
                              style="background:{{ $m->approval_status === 'approved' ? 'rgba(67,214,133,0.1)' : 'rgba(220,38,38,0.1)' }};color:{{ $m->approval_status === 'approved' ? '#43d685' : '#dc2626' }};">
                            {{ strtoupper($m->approval_status) }}
                        </span>
                    </div>
                    <div class="text-right truncate" style="color:#5a7a9a;">
                        {{ $m->reviewer->name ?? '—' }}
                        @if($m->approval_reviewed_at)
                            <span style="color:#3a5a70;"> · {{ $m->approval_reviewed_at->format('d.m.') }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-[9px] tracking-[2px]" style="color:#5a7a9a;">NOCH NICHTS ENTSCHIEDEN</div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
