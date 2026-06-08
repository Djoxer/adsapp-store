{{-- Hotspot Banner — großer Featured-Hotspot (Zone 04) --}}
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
   class="relative overflow-hidden cursor-pointer group block"
   style="border:2px solid rgba(220,38,38,0.7);background:#141414;">

    {{-- Labels --}}
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

    <div class="grid grid-cols-2 min-h-[180px]">

        {{-- Bild-Seite: Kollage > hero_image > Gradient --}}
        <div class="relative overflow-hidden" style="border-right:1px solid #2a2a2a;">
            @if($collageImages->count() >= 2)
                <div class="absolute inset-0 grid gap-0.5"
                     style="grid-template-columns:repeat(2,1fr);grid-template-rows:repeat({{ $collageImages->count() >= 3 ? 2 : 1 }},1fr);">
                    @foreach($collageImages->take(4) as $url)
                        <div class="overflow-hidden">
                            <img src="{{ $url }}" alt="" class="w-full h-full object-cover"
                                 style="filter:brightness(0.7) saturate(0.85);">
                        </div>
                    @endforeach
                </div>
                <div class="absolute inset-0" style="background:linear-gradient(to right,rgba(0,0,0,0.3),rgba(0,0,0,0.05));"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-[44px]" style="filter:drop-shadow(0 2px 10px rgba(0,0,0,0.9));">{{ $hotspot->icon ?? '🔥' }}</span>
                </div>
            @elseif($hotspot->hero_image)
                <img src="{{ $hotspot->hero_image }}" alt="{{ $hotspot->name }}"
                     class="w-full h-full object-cover opacity-80" style="filter:grayscale(0.3);">
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-[44px]" style="filter:drop-shadow(0 2px 10px rgba(0,0,0,0.9));">{{ $hotspot->icon ?? '🔥' }}</span>
                </div>
            @else
                <div class="absolute inset-0" style="background:{{ $gradient }};"></div>
                <div class="absolute inset-0" style="background:linear-gradient(to right,rgba(0,0,0,0.2),rgba(0,0,0,0.05));"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-[56px]" style="filter:drop-shadow(0 2px 12px rgba(0,0,0,0.6));">{{ $hotspot->icon ?? '🔥' }}</span>
                </div>
            @endif
        </div>

        {{-- Text-Seite --}}
        <div class="relative p-6 flex flex-col justify-center">
            <div class="absolute inset-0 pointer-events-none opacity-60"
                 style="background:radial-gradient(circle at 88% 12%, rgba(220,38,38,0.12), transparent 55%);"></div>
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
