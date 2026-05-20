{{-- Hotspot Banner
     Props: $hotspot = [id, title, price, score, merchant, description, label?]
--}}
@props(['hotspot' => []])

<div class="relative overflow-hidden cursor-pointer group"
     style="border:2px solid rgba(220,38,38,0.7);background:#141414;"
     onclick="openAdOverlay({
         id:{{ $hotspot['id'] }},
         title:'{{ addslashes($hotspot['title']) }}',
         price:'{{ $hotspot['price'] }}',
         rank:null,
         score:'{{ $hotspot['score'] ?? '' }}',
         merchant:'{{ $hotspot['merchant'] ?? '' }}',
         description:'{{ addslashes($hotspot['description'] ?? '') }}'
     })">

    <div class="absolute top-3 left-3 z-10 text-[8px] tracking-[2px] px-2 py-0.5"
         style="background:rgba(220,38,38,0.85);color:white;">
        {{ $hotspot['label'] ?? 'HEUTE_HEISS // HOTSPOT' }}
    </div>

    <div class="grid grid-cols-2 min-h-[180px]">
        <div class="flex items-center justify-center text-[8px] tracking-wider"
             style="background:#1a1a1a;border-right:1px solid #2a2a2a;color:#2a2a2a;">HOTSPOT_IMAGE</div>
        <div class="p-6 flex flex-col justify-center">
            <div class="text-2xl font-sans font-bold tracking-wider leading-tight" style="color:#e8e8e8;">
                {{ $hotspot['headline'] ?? $hotspot['title'] }}
            </div>
            <div class="text-[11px] tracking-wider mt-2 leading-relaxed" style="color:#A1A1AA;">
                {{ $hotspot['description'] ?? '' }}
            </div>
            <button class="mt-4 self-start text-[11px] tracking-[2px] px-5 py-2.5 font-sans font-semibold transition-colors"
                    style="border:1px solid #e8e8e8;color:#e8e8e8;background:transparent;"
                    onmouseover="this.style.background='#e8e8e8';this.style.color='#0a0a0a'"
                    onmouseout="this.style.background='transparent';this.style.color='#e8e8e8'">
                JETZT_ERLEBEN
            </button>
        </div>
    </div>

    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 40px rgba(220,38,38,0.08);"></div>
</div>
