{{-- Hotspot Banner
     Props: $ad = Ad Model ODER legacy $hotspot = array
--}}
@props(['ad' => null, 'hotspot' => []])

@php
    $h = $ad ?? $hotspot; // Model oder Array
    $isModel = $h instanceof \App\Models\Ad;

    $hId    = $isModel ? $h->id          : ($h['id']          ?? 0);
    $hTitle = $isModel ? $h->title       : ($h['title']       ?? '');
    $hPrice = $isModel
        ? number_format($h->price_cents / 100, 2, ',', '.') . ' €'
        : ($h['price'] ?? '');
    $hScore = $isModel ? (float) $h->current_score : ($h['score'] ?? '');
    $hMerch = $isModel ? ($h->merchant->company_name ?? '') : ($h['merchant'] ?? '');
    $hDesc  = $isModel ? $h->description : ($h['description'] ?? '');
    $hImage = $isModel ? $h->images->first()?->path : null;
    $hLabel = $h['label'] ?? 'HEUTE_HEISS // HOTSPOT';
@endphp

<div class="relative overflow-hidden cursor-pointer group"
     style="border:2px solid rgba(220,38,38,0.7);background:#141414;"
     onclick="openAdOverlay({
         id:{{ $hId }},
         title:'{{ addslashes(e($hTitle)) }}',
         price:'{{ $hPrice }}',
         rank:null,
         score:'{{ $hScore }}',
         merchant:'{{ addslashes(e($hMerch)) }}',
         description:'{{ addslashes(e($hDesc)) }}'
     })">

    <div class="absolute top-3 left-3 z-10 text-[8px] tracking-[2px] px-2 py-0.5"
         style="background:rgba(220,38,38,0.85);color:white;">
        {{ $hLabel }}
    </div>

    <div class="grid grid-cols-2 min-h-[180px]">
        <div class="flex items-center justify-center text-[8px] tracking-wider overflow-hidden"
             style="background:#1a1a1a;border-right:1px solid #2a2a2a;color:#2a2a2a;">
            @if($hImage)
                <img src="{{ Storage::url($hImage) }}" alt="{{ $hTitle }}"
                     class="w-full h-full object-cover opacity-70">
            @else
                HOTSPOT_IMAGE
            @endif
        </div>
        <div class="p-6 flex flex-col justify-center">
            <div class="text-2xl font-sans font-bold tracking-wider leading-tight" style="color:#e8e8e8;">
                {{ $hTitle }}
            </div>
            @if($hDesc)
                <div class="text-[11px] tracking-wider mt-2 leading-relaxed" style="color:#A1A1AA;">
                    {{ $hDesc }}
                </div>
            @endif
            <div class="flex items-center gap-3 mt-2">
                <span class="text-[12px] font-bold" style="color:#F5B700;">{{ $hPrice }}</span>
                @if($hScore)
                    <span class="text-[8px] tracking-[2px]" style="color:#454745;">SCORE {{ number_format((float)$hScore,1) }}</span>
                @endif
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
