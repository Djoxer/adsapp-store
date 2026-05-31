{{-- Premium Slot — Top-3-Strip
     Props: $ad = Ad Model ODER legacy $item = array
            $rank = int (1-3)
--}}
@props(['ad' => null, 'item' => [], 'rank' => null])

@php
    $src = $ad ?? $item;
    $isModel = $src instanceof \App\Models\Ad;

    $sId    = $isModel ? $src->id    : ($src['id']    ?? 0);
    $sTitle = $isModel ? $src->title : ($src['title'] ?? '');
    $sPrice = $isModel
        ? number_format($src->price_cents / 100, 2, ',', '.') . ' €'
        : ($src['price'] ?? '');
    $sImage = $isModel ? $src->images->first()?->cache_path : null;
    $sMerch = $isModel ? ($src->merchant->company_name ?? 'SPONSOR_CORP') : 'SPONSOR_CORP';
    $sDesc  = $isModel ? $src->description : '';
    $sLabel = $src['label'] ?? ('SPONSORED_AD_0' . ($rank ?? '?'));
@endphp

<div class="relative overflow-hidden cursor-pointer group"
     style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #F5B700;"
     onclick="openAdOverlay({
         id:{{ $sId }},
         title:'{{ addslashes(e($sTitle)) }}',
         price:'{{ $sPrice }}',
         rank:null, score:null,
         merchant:'{{ addslashes(e($sMerch)) }}',
         description:'{{ addslashes(e($sDesc)) }}'
     })">

    <div class="flex items-center gap-3 p-3">
        <div class="w-10 h-10 flex-shrink-0 overflow-hidden flex items-center justify-center text-[7px] tracking-wider"
             style="background:#1a1a1a;border:1px solid #2a2a2a;color:#2a2a2a;">
            @if($sImage)
                <img src="{{ Storage::url($sImage) }}" alt="{{ $sTitle }}"
                     class="w-full h-full object-cover">
            @else
                IMG
            @endif
        </div>
        <div class="min-w-0">
            <div class="text-[8px] tracking-[2px] mb-0.5" style="color:#F5B700;">{{ $sLabel }}</div>
            <div class="text-[11px] tracking-wider truncate font-sans font-semibold" style="color:#e8e8e8;">{{ $sTitle }}</div>
            <div class="text-[10px]" style="color:#F5B700;">{{ $sPrice }}</div>
        </div>
    </div>

    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 20px rgba(245,183,0,0.07);"></div>
</div>
