<x-admin-layout>
    <x-slot name="header">SYSTEM_ÜBERSICHT</x-slot>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        @php
            $cards = [
                ['OFFENE SLOT-ANTRÄGE', $stats['pending_slots'], '#4fc3f7', $stats['pending_slots'] > 0],
                ['WARTENDE HÄNDLER', $stats['pending_merchants'], '#f5a623', $stats['pending_merchants'] > 0],
                ['LIVE SLOTS', $stats['live_slots'], '#43d685', false],
                ['AKTIVE ADS', $stats['active_ads'], '#c8d8e8', false],
                ['USER GESAMT', $stats['total_users'], '#c8d8e8', false],
                ['ORDERS GESAMT', $stats['total_orders'], '#c8d8e8', false],
            ];
        @endphp
        @foreach($cards as [$label, $value, $color, $alert])
            <div class="p-5 relative" style="background:#111a2b;border:1px solid {{ $alert ? $color : '#1e3050' }};">
                <div class="text-[9px] tracking-[2px] mb-2" style="color:#5a7a9a;">{{ $label }}</div>
                <div class="text-3xl font-sans font-bold" style="color:{{ $color }};">{{ $value }}</div>
                @if($alert)<div class="absolute top-3 right-3 w-2 h-2 rounded-full" style="background:{{ $color }};box-shadow:0 0 8px {{ $color }};"></div>@endif
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2 gap-6">
        {{-- Offene Slot-Anträge --}}
        <div style="background:#111a2b;border:1px solid #1e3050;">
            <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #1e3050;">
                <span class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8f4ff;">OFFENE SLOT-ANTRÄGE</span>
                <a href="{{ route('admin.slots') }}" class="text-[9px] tracking-[1.5px]" style="color:#4fc3f7;">ALLE →</a>
            </div>
            @forelse($recentBookings as $b)
                <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #16243a;">
                    <div class="min-w-0">
                        <div class="text-[11px] tracking-wider truncate" style="color:#c8d8e8;">{{ $b->ad->title ?? '—' }}</div>
                        <div class="text-[8px] tracking-[1.5px] mt-0.5" style="color:#5a7a9a;">
                            ZONE {{ $b->slot->zone ?? '?' }}-{{ $b->slot->position ?? '?' }} · {{ $b->duration_days }}T · {{ $b->merchant->user->name ?? 'UNBEKANNT' }}
                        </div>
                    </div>
                    <span class="text-[8px] tracking-[1.5px] px-2 py-0.5" style="background:rgba(79,195,247,0.1);color:#4fc3f7;">Q{{ $b->queue_position }}</span>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-[9px] tracking-[2px]" style="color:#5a7a9a;">KEINE OFFENEN ANTRÄGE</div>
            @endforelse
        </div>

        {{-- Wartende Händler --}}
        <div style="background:#111a2b;border:1px solid #1e3050;">
            <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #1e3050;">
                <span class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8f4ff;">WARTENDE HÄNDLER</span>
                <a href="{{ route('admin.merchants') }}" class="text-[9px] tracking-[1.5px]" style="color:#4fc3f7;">ALLE →</a>
            </div>
            @forelse($recentMerchants as $m)
                <div class="px-5 py-3 flex items-center justify-between" style="border-bottom:1px solid #16243a;">
                    <div class="min-w-0">
                        <div class="text-[11px] tracking-wider truncate" style="color:#c8d8e8;">{{ $m->company_name ?: $m->user->name ?? '—' }}</div>
                        <div class="text-[8px] tracking-[1.5px] mt-0.5" style="color:#5a7a9a;">{{ $m->user->email ?? '—' }}</div>
                    </div>
                    <span class="text-[8px] tracking-[1.5px] px-2 py-0.5" style="background:rgba(245,166,35,0.1);color:#f5a623;">PENDING</span>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-[9px] tracking-[2px]" style="color:#5a7a9a;">KEINE WARTENDEN HÄNDLER</div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
