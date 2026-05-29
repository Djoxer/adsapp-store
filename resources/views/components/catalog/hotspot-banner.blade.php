{{-- Hotspot Banner — großer Featured-Hotspot (Zone 04)
     Props: $hotspot = Hotspot Model --}}
@props(['hotspot'])

<a href="{{ route('catalog.hotspots') }}"
   class="relative overflow-hidden cursor-pointer group block"
   style="border:2px solid rgba(220,38,38,0.7);background:#141414;">

    {{-- Label --}}
    <div class="absolute top-3 left-3 z-10 flex items-center gap-2">
        <span class="text-[8px] tracking-[2px] px-2 py-0.5" style="background:rgba(220,38,38,0.85);color:white;">
            ZONE_04 // HOTSPOT
        </span>
        @if($hotspot->days_left !== null)
            <span class="text-[8px] tracking-[1.5px] px-2 py-0.5" style="background:#F5B700;color:#0a0a0a;">
                NOCH {{ str_pad($hotspot->days_left,2,'0',STR_PAD_LEFT) }} TAGE
            </span>
        @else
            <span class="text-[8px] tracking-[1.5px] px-2 py-0.5" style="background:#43d685;color:#0a0a0a;">
                DAUERHAFT
            </span>
        @endif
    </div>

    {{-- warmer Glow --}}
    <div class="absolute inset-0 pointer-events-none opacity-60"
         style="background:radial-gradient(circle at 88% 12%, rgba(220,38,38,0.12), transparent 55%);"></div>

    <div class="grid grid-cols-2 min-h-[180px]">
        {{-- Bild/Icon --}}
        <div class="flex items-center justify-center overflow-hidden"
             style="background:#1a1a1a;border-right:1px solid #2a2a2a;">
            @if($hotspot->hero_image)
                <img src="{{ $hotspot->hero_image }}" alt="{{ $hotspot->name }}"
                     class="w-full h-full object-cover opacity-80" style="filter:grayscale(0.3);">
            @else
                <span class="text-[56px]">{{ $hotspot->icon ?? '🔥' }}</span>
            @endif
        </div>

        {{-- Text --}}
        <div class="relative p-6 flex flex-col justify-center">
            <div class="text-2xl font-sans font-bold tracking-wider leading-tight" style="color:#e8e8e8;">
                {{ $hotspot->name }}
            </div>
            @if($hotspot->subtitle)
                <div class="text-[11px] tracking-wider mt-2 leading-relaxed" style="color:#A1A1AA;">
                    {{ $hotspot->subtitle }}
                </div>
            @endif
            <div class="flex items-center gap-3 mt-2">
                <span class="text-[9px] tracking-[1.5px]" style="color:#454745;">{{ $hotspot->ads_count }} ADS</span>
                <span class="text-[8px] tracking-[2px]" style="color:#454745;">TYPE: {{ strtoupper($hotspot->type) }}</span>
            </div>
            <span class="mt-4 self-start text-[11px] tracking-[2px] px-5 py-2.5 font-sans font-semibold transition-colors"
                  style="border:1px solid #e8e8e8;color:#e8e8e8;background:transparent;"
                  onmouseover="this.style.background='#e8e8e8';this.style.color='#0a0a0a'"
                  onmouseout="this.style.background='transparent';this.style.color='#e8e8e8'">
                JETZT_ERLEBEN →
            </span>
        </div>
    </div>

    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 40px rgba(220,38,38,0.08);"></div>
</a>
