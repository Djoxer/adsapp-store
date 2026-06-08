{{-- Hotspot Promo — kompakte Karte (16:9), eingestreut im Catalog-Raster
     Props: $hotspot = Hotspot Model
     Gleiche Größe wie ad-card (col-span-1) — hält das auto-fill-Raster lückenlos. --}}
@props(['hotspot'])

@php
    $collageImages = collect();
    if ($hotspot->relationLoaded('ads')) {
        $collageImages = $hotspot->ads->map(fn($a) => optional($a->images->first())->remote_url)->filter()->values();
    }
    $slug = $hotspot->slug;
    $gradient = 'linear-gradient(135deg,#1a1a1a,#2a2a2a)';
    if (str_contains($slug, 'sommer'))       $gradient = 'linear-gradient(135deg,#b45309,#d97706,#f59e0b)';
    elseif (str_contains($slug, 'winter'))   $gradient = 'linear-gradient(135deg,#1e3a5f,#2563eb,#93c5fd)';
    elseif (str_contains($slug, 'fruehling'))$gradient = 'linear-gradient(135deg,#166534,#16a34a,#86efac)';
    elseif (str_contains($slug, 'herbst'))   $gradient = 'linear-gradient(135deg,#7c2d12,#c2410c,#f97316)';
    elseif (str_contains($slug, 'morgen'))   $gradient = 'linear-gradient(135deg,#1e3a5f,#b45309,#f59e0b)';
    elseif (str_contains($slug, 'mittag'))   $gradient = 'linear-gradient(135deg,#1d4ed8,#0ea5e9,#bae6fd)';
    elseif (str_contains($slug, 'nachmittag'))$gradient= 'linear-gradient(135deg,#0369a1,#0891b2,#67e8f9)';
    elseif (str_contains($slug, 'abend'))    $gradient = 'linear-gradient(135deg,#3b0764,#7c3aed,#c084fc)';
    elseif (str_contains($slug, 'nacht'))    $gradient = 'linear-gradient(135deg,#020617,#0f172a,#1e293b)';
    elseif (str_contains($slug, 'weihnachten'))$gradient='linear-gradient(135deg,#7f1d1d,#dc2626,#166534)';
    elseif (str_contains($slug, 'halloween'))$gradient = 'linear-gradient(135deg,#431407,#c2410c,#1c1917)';
    elseif (str_contains($slug, 'valentinstag'))$gradient='linear-gradient(135deg,#881337,#e11d48,#fda4af)';
    elseif (str_contains($slug, 'ostern'))   $gradient = 'linear-gradient(135deg,#365314,#4ade80,#fde68a)';
    elseif (str_contains($slug, 'muttertag'))$gradient = 'linear-gradient(135deg,#701a75,#c026d3,#f0abfc)';
    elseif (str_contains($slug, 'vatertag')) $gradient = 'linear-gradient(135deg,#1e3a5f,#0369a1,#7dd3fc)';
    elseif ($hotspot->type === 'dynamisch')  $gradient = 'linear-gradient(135deg,#450a0a,#dc2626,#7f1d1d)';
@endphp

<a href="{{ route('catalog.hotspot.show', $hotspot->slug) }}"
   class="relative overflow-hidden group block"
   style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #DC2626;">

    {{-- Label --}}
    <div class="absolute top-2 left-2 z-10">
        <span class="text-[7px] tracking-[1.5px] px-1.5 py-0.5" style="background:rgba(220,38,38,0.85);color:white;">
            ZONE_04 // HOTSPOT
        </span>
    </div>

    {{-- Kollage > hero_image > Gradient, 16:9 --}}
    <div class="relative aspect-video w-full overflow-hidden">
        @if($collageImages->count() >= 2)
            <div class="absolute inset-0 grid gap-0.5"
                 style="grid-template-columns:repeat(2,1fr);grid-template-rows:repeat({{ $collageImages->count() >= 3 ? 2 : 1 }},1fr);">
                @foreach($collageImages->take(4) as $url)
                    <div class="overflow-hidden">
                        <img src="{{ $url }}" alt="" class="w-full h-full object-cover"
                             style="filter:brightness(0.65) saturate(0.8);">
                    </div>
                @endforeach
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-[28px]" style="filter:drop-shadow(0 2px 8px rgba(0,0,0,0.9));">{{ $hotspot->icon ?? '🔥' }}</span>
            </div>
        @elseif($hotspot->hero_image)
            <img src="{{ $hotspot->hero_image }}" alt="{{ $hotspot->name }}"
                 class="w-full h-full object-cover" style="filter:grayscale(0.4);opacity:0.8;">
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-[28px]">{{ $hotspot->icon ?? '🔥' }}</span>
            </div>
        @else
            <div class="absolute inset-0" style="background:{{ $gradient }};"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-[36px]" style="filter:drop-shadow(0 2px 8px rgba(0,0,0,0.5));">{{ $hotspot->icon ?? '🔥' }}</span>
            </div>
        @endif
        <div class="absolute inset-0 pointer-events-none" style="background:linear-gradient(to bottom,transparent 50%,rgba(0,0,0,0.5));"></div>
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
        <div class="text-[10px] tracking-[1.5px] mt-1.5" style="color:#DC2626;">ENTER →</div>
    </div>

    {{-- Hover Glow --}}
    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 24px rgba(220,38,38,0.08);"></div>
</a>
