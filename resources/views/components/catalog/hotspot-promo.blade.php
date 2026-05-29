{{-- Hotspot Promo — echter Event-Raum (Zone 04), eingestreut im Catalog
     Props: $hotspot = Hotspot Model --}}
@props(['hotspot'])

<a href="{{ route('catalog.hotspots') }}"
   class="col-span-2 relative overflow-hidden group block"
   style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #DC2626;">

    {{-- warmer Glow-Underlay (Hitze-Metapher, kein Fire-Icon) --}}
    <div class="absolute inset-0 pointer-events-none opacity-60"
         style="background:radial-gradient(circle at 85% 15%, rgba(220,38,38,0.12), transparent 60%);"></div>

    <div class="relative flex items-stretch min-h-[140px]">
        {{-- Icon/Bild-Seite --}}
        <div class="w-32 flex-shrink-0 flex items-center justify-center" style="background:#0a0a0a;border-right:1px solid #2a2a2a;">
            @if($hotspot->hero_image)
                <img src="{{ $hotspot->hero_image }}" class="w-full h-full object-cover" style="filter:grayscale(0.4);">
            @else
                <span class="text-[40px]">{{ $hotspot->icon ?? '🔥' }}</span>
            @endif
        </div>

        {{-- Text-Seite --}}
        <div class="flex-1 p-5 flex flex-col justify-center min-w-0">
            <div class="flex items-center gap-2 mb-1.5">
                <span class="text-[8px] tracking-[2px] px-2 py-0.5" style="background:rgba(220,38,38,0.85);color:white;">
                    ZONE_04 // HOTSPOT
                </span>
                @if($hotspot->days_left !== null)
                    <span class="text-[8px] tracking-[1.5px]" style="color:#F5B700;">NOCH {{ str_pad($hotspot->days_left,2,'0',STR_PAD_LEFT) }} TAGE</span>
                @else
                    <span class="text-[8px] tracking-[1.5px]" style="color:#43d685;">DAUERHAFT</span>
                @endif
            </div>

            <div class="text-[18px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ $hotspot->name }}</div>

            @if($hotspot->subtitle)
                <div class="text-[10px] tracking-wider mt-1 leading-relaxed line-clamp-2" style="color:#A1A1AA;">{{ $hotspot->subtitle }}</div>
            @endif

            <div class="flex items-center gap-3 mt-3">
                <span class="text-[9px] tracking-[1.5px]" style="color:#454745;">{{ $hotspot->ads_count }} ADS</span>
                <span class="text-[11px] tracking-[2px] font-sans font-semibold transition-colors"
                      style="color:#DC2626;"
                      onmouseover="this.style.color='#FF535B'"
                      onmouseout="this.style.color='#DC2626'">
                    ENTER →
                </span>
            </div>
        </div>
    </div>
</a>
