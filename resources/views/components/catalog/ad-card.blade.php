{{--
    Ad Card Component — rank-aware sizing
    Props:
      $ad = [id, title, price, rank, score, merchant, description, image?]
      $size = 'featured' | 'medium' | 'small' | 'mini'
--}}
@props([
    'ad'   => [],
    'size' => 'medium',
])

@php
$borderStyle = match(true) {
    ($ad['rank'] ?? 99) === 1 => 'border:2px solid #DC2626;',
    default                   => 'border:1px solid #1e1e1e;',
};
$colSpan = match($size) {
    'featured' => 'col-span-1 row-span-2',
    'medium'   => 'col-span-1',
    'small'    => 'col-span-1',
    'mini'     => 'col-span-1',
    default    => 'col-span-1',
};
$minH = $size === 'featured' ? 'min-height:320px;' : '';
@endphp

<div class="{{ $colSpan }} relative overflow-hidden cursor-pointer group"
     style="background:#141414;{{ $borderStyle }}{{ $minH }}"
     onclick="openAdOverlay({
         id:{{ $ad['id'] }},
         title:'{{ addslashes($ad['title']) }}',
         price:'{{ $ad['price'] }}',
         rank:{{ $ad['rank'] ?? 'null' }},
         score:'{{ $ad['score'] ?? '' }}',
         merchant:'{{ $ad['merchant'] ?? '' }}',
         description:'{{ addslashes($ad['description'] ?? '') }}'
     })">

    @if(isset($ad['rank']))
    <div class="absolute top-2 left-2 z-10 text-[8px] tracking-[2px] px-2 py-0.5 font-sans font-bold"
         style="{{ ($ad['rank'] === 1) ? 'background:#DC2626;color:white;' : 'background:#111111;border:1px solid #2a2a2a;color:#454745;' }}">
        RANK #{{ $ad['rank'] }}{{ ($ad['rank'] === 1 && isset($ad['score'])) ? ': '.$ad['score'] : '' }}
    </div>
    @endif

    @if($size === 'featured')
        {{-- Full-bleed image with bottom gradient overlay --}}
        <div class="absolute inset-0" style="background:linear-gradient(180deg,#1a1a1a 0%,#0f0f0f 100%);"></div>
        <div class="absolute bottom-0 left-0 right-0 p-4"
             style="background:linear-gradient(0deg,rgba(10,5,5,0.95) 0%,transparent 100%);">
            <div class="text-xl font-sans font-bold tracking-wider" style="color:#e8e8e8;">{{ $ad['title'] }}</div>
            <div class="text-[11px] tracking-wider mt-1" style="color:#F5B700;">{{ $ad['price'] }}</div>
            <button class="mt-3 text-[10px] tracking-[2px] px-4 py-2 font-sans font-semibold"
                    style="background:#DC2626;color:white;"
                    onmouseover="this.style.background='#FF535B'"
                    onmouseout="this.style.background='#DC2626'">DATA_FETCH</button>
        </div>
    @else
        <div class="{{ $size === 'small' ? 'aspect-square' : 'aspect-video' }} w-full flex items-center justify-center text-[7px]"
             style="background:#1a1a1a;color:#2a2a2a;">
            {{ isset($ad['image']) ? '' : 'IMG' }}
        </div>
        <div class="p-3">
            <div class="text-[11px] font-sans font-semibold tracking-wider truncate" style="color:#e8e8e8;">{{ $ad['title'] }}</div>
            <div class="text-[10px] mt-0.5" style="color:#F5B700;">{{ $ad['price'] }}</div>
            @if(isset($ad['score']))
            <div class="text-[8px] tracking-wider mt-1" style="color:#454745;">SCORE {{ $ad['score'] }}</div>
            @endif
        </div>
    @endif

    {{-- Hover glow --}}
    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 24px rgba({{ ($ad['rank'] ?? 99) === 1 ? '220,38,38' : '245,183,0' }},0.07);"></div>
</div>
