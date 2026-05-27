{{--
    Ad Card Component — rank-aware sizing
    Props:
      $ad   = Ad Model ODER legacy array [id, title, price, rank, score, merchant, description]
      $size = 'featured' | 'medium' | 'small' | 'mini'
      $rank = optionaler Override (für grid-position-basiertes Ranking)
      $bookmarked = bool, ob bereits in Merkliste
--}}
@props([
    'ad'         => [],
    'size'       => 'medium',
    'rank'       => null,
    'bookmarked' => false,
])

@php
    // Model oder Array — einheitliche Variablen
    $isModel  = $ad instanceof \App\Models\Ad;
    $adId     = $isModel ? $ad->id          : ($ad['id']          ?? 0);
    $adTitle  = $isModel ? $ad->title       : ($ad['title']       ?? '');
    $adPrice  = $isModel
        ? number_format($ad->price_cents / 100, 2, ',', '.') . ' €'
        : ($ad['price'] ?? '');
    $adScore  = $isModel ? (float) $ad->current_score : ($ad['score'] ?? null);
    $adMerch  = $isModel ? ($ad->merchant->company_name ?? '') : ($ad['merchant'] ?? '');
    $adDesc   = $isModel ? $ad->description : ($ad['description'] ?? '');
    $adRank   = $rank ?? ($ad['rank'] ?? null);

    // Bild: erstes AdImage oder Placeholder
    $adImage  = $isModel ? $ad->images->first()?->path : ($ad['image'] ?? null);

    $isTop    = $adRank === 1;
    $borderStyle = $isTop
        ? 'border:2px solid #DC2626;'
        : 'border:1px solid #1e1e1e;';
    $minH = $size === 'featured' ? 'min-height:320px;' : '';

    // JS-sicheres escaping für onclick
    $jsTitle = addslashes(e($adTitle));
    $jsDesc  = addslashes(e($adDesc));
    $jsMerch = addslashes(e($adMerch));
@endphp

<div class="relative overflow-hidden cursor-pointer group"
     style="background:#141414;{{ $borderStyle }}{{ $minH }}"
     onclick="openAdOverlay({
         id:{{ $adId }},
         title:'{{ $jsTitle }}',
         price:'{{ $adPrice }}',
         rank:{{ $adRank ?? 'null' }},
         score:'{{ $adScore ?? '' }}',
         merchant:'{{ $jsMerch }}',
         description:'{{ $jsDesc }}',
         bookmarked:{{ json_encode($bookmarked) }}
     })">

    {{-- Rank Badge --}}
    @if($adRank)
        <div class="absolute top-2 left-2 z-10 text-[8px] tracking-[2px] px-2 py-0.5 font-sans font-bold"
             style="{{ $isTop ? 'background:#DC2626;color:white;' : 'background:#111111;border:1px solid #2a2a2a;color:#454745;' }}">
            RANK #{{ $adRank }}{{ ($isTop && $adScore) ? ': '.number_format($adScore,1) : '' }}
        </div>
    @endif

    @if($size === 'featured')
        {{-- Featured: Gradient-Overlay mit Bottom-Text --}}
        @if($adImage)
            <img src="{{ Storage::url($adImage) }}" alt="{{ $adTitle }}"
                 class="absolute inset-0 w-full h-full object-cover opacity-60">
        @else
            <div class="absolute inset-0" style="background:linear-gradient(180deg,#1a1a1a 0%,#0f0f0f 100%);"></div>
        @endif
        <div class="absolute bottom-0 left-0 right-0 p-4"
             style="background:linear-gradient(0deg,rgba(10,5,5,0.95) 0%,transparent 100%);">
            <div class="text-xl font-sans font-bold tracking-wider" style="color:#e8e8e8;">{{ $adTitle }}</div>
            <div class="text-[11px] tracking-wider mt-1" style="color:#F5B700;">{{ $adPrice }}</div>
            <button class="mt-3 text-[10px] tracking-[2px] px-4 py-2 font-sans font-semibold"
                    style="background:#DC2626;color:white;"
                    onmouseover="this.style.background='#FF535B'"
                    onmouseout="this.style.background='#DC2626'">DATA_FETCH</button>
        </div>
    @else
        {{-- Standard-Karten --}}
        <div class="{{ $size === 'small' ? 'aspect-square' : 'aspect-video' }} w-full overflow-hidden flex items-center justify-center text-[7px]"
             style="background:#1a1a1a;color:#2a2a2a;">
            @if($adImage)
                <img src="{{ Storage::url($adImage) }}" alt="{{ $adTitle }}"
                     class="w-full h-full object-cover opacity-80">
            @else
                IMG
            @endif
        </div>
        <div class="p-3">
            <div class="text-[11px] font-sans font-semibold tracking-wider truncate" style="color:#e8e8e8;">{{ $adTitle }}</div>
            <div class="text-[10px] mt-0.5" style="color:#F5B700;">{{ $adPrice }}</div>
            @if($adScore)
                <div class="text-[8px] tracking-wider mt-1" style="color:#454745;">SCORE {{ number_format($adScore,1) }}</div>
            @endif
        </div>
    @endif

    {{-- Hover Glow --}}
    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 24px rgba({{ $isTop ? '220,38,38' : '245,183,0' }},0.07);"></div>
</div>
