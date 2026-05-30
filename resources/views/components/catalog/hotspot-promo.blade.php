{{-- Hotspot Promo — kompakte Karte (16:9), eingestreut im Catalog-Raster
     Props: $hotspot = Hotspot Model
     Gleiche Größe wie ad-card (col-span-1) — hält das auto-fill-Raster lückenlos
     und landet durch die variable Spaltenzahl automatisch "wechselseitig". --}}
@props(['hotspot'])

<a href="{{ route('catalog.hotspots') }}"
   class="relative overflow-hidden group block"
   style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #DC2626;">

    {{-- Label --}}
    <div class="absolute top-2 left-2 z-10">
        <span class="text-[7px] tracking-[1.5px] px-1.5 py-0.5" style="background:rgba(220,38,38,0.85);color:white;">
            ZONE_04 // HOTSPOT
        </span>
    </div>

    {{-- Icon/Bild — 16:9 wie die Ad-Karten --}}
    <div class="aspect-video w-full overflow-hidden flex items-center justify-center"
         style="background:#1a1a1a;">
        @if($hotspot->hero_image)
            <img src="{{ $hotspot->hero_image }}" alt="{{ $hotspot->name }}"
                 class="w-full h-full object-cover" style="filter:grayscale(0.4);opacity:0.8;">
        @else
            <span class="text-[40px]">{{ $hotspot->icon ?? '🔥' }}</span>
        @endif
    </div>

    {{-- Text --}}
    <div class="p-3">
        <div class="text-[11px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ $hotspot->name }}</div>
        <div class="flex items-center justify-between mt-1">
            <span class="text-[8px] tracking-[1.5px]" style="color:#454745;">{{ $hotspot->ads_count }} ADS</span>
            @if($hotspot->days_left !== null)
                <span class="text-[8px] tracking-[1.5px]" style="color:#F5B700;">NOCH {{ $hotspot->days_left }}T</span>
            @else
                <span class="text-[8px] tracking-[1.5px]" style="color:#43d685;">DAUERHAFT</span>
            @endif
        </div>
        <div class="text-[10px] tracking-[1.5px] mt-1.5 transition-colors" style="color:#DC2626;">ENTER →</div>
    </div>

    {{-- Hover Glow --}}
    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 24px rgba(220,38,38,0.08);"></div>
</a>
