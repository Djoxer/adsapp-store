{{-- Einzelne Slot-Card — Props: $slot (mit current_booking, queue_length, days_remaining) --}}
<div class="slot-card bg-ink-panel border p-4 cursor-pointer transition-colors"
     style="border-color:#5B403F;"
     data-slot-card="{{ $slot->id }}"
     onclick="selectSlot({{ $slot->id }})">

    {{-- Kopf: Zone-Position + Preis --}}
    <div class="flex items-center justify-between mb-3">
        <span class="text-[11px] font-sans font-bold tracking-wider text-copy-soft">{{ $slot->zone }}-{{ $slot->position }}</span>
        <div class="text-right">
            @if($slot->has_discount)
                <span class="text-[8px] line-through text-copy-ticker">{{ number_format($slot->base_price_cents/100,2,',','.') }}€</span>
                <span class="text-[10px] font-bold text-brand-yellow">{{ number_format($slot->effective_price_cents/100,2,',','.') }}€</span>
            @else
                <span class="text-[10px] font-bold text-brand-yellow">{{ number_format($slot->base_price_cents/100,2,',','.') }}€</span>
            @endif
            <span class="text-[8px] text-copy-ticker">/TAG</span>
        </div>
    </div>

    {{-- Status --}}
    @if($slot->current_booking)
        <div class="text-[9px] tracking-[1.5px] text-brand-red mb-2">● BELEGT</div>
        {{-- Fortschrittsbalken: verbleibende Laufzeit --}}
        @php
            $total = $slot->current_booking->duration_days;
            $remaining = $slot->days_remaining ?? 0;
            $pct = $total > 0 ? round((($total - $remaining) / $total) * 100) : 0;
        @endphp
        <div class="h-1 bg-line-warm mb-2">
            <div class="h-full bg-brand-yellow" style="width:{{ $pct }}%"></div>
        </div>
        <div class="text-[8px] tracking-wider text-copy-ticker">NOCH {{ $remaining }} TAG{{ $remaining === 1 ? '' : 'E' }}</div>
    @else
        <div class="text-[9px] tracking-[1.5px] text-brand-yellow mb-2">○ FREI</div>
        @if($slot->has_discount)
            <div class="text-[8px] tracking-wider px-1.5 py-0.5 inline-block" style="background:#DC2626;color:white;">-{{ $slot->discount_pct }}% RABATT</div>
        @else
            <div class="text-[8px] tracking-wider text-copy-ticker">SOFORT VERFÜGBAR</div>
        @endif
    @endif

    {{-- Queue --}}
    <div class="text-[8px] tracking-[1.5px] text-copy-ticker mt-2 pt-2 border-t border-line-warm/40">
        WARTESCHLANGE: {{ $slot->queue_length }}
    </div>
</div>
