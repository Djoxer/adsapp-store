{{--
    Ad Card Component — einheitliche Größe (16:9), responsives Grid
    Props:
      $ad         = Ad Model ODER legacy array [id, title, price, rank, score, merchant, description]
      $rank       = Rang-Nummer (Badge)
      $bookmarked = bool, ob bereits in Merkliste
--}}
@props([
    'ad'         => [],
    'rank'       => null,
    'bookmarked' => false,
])

@php
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
    $adImage  = $isModel ? $ad->images->first()?->cache_path : ($ad['image'] ?? null);
    $isTop    = $adRank === 1;
    $borderStyle = $isTop ? 'border:2px solid #DC2626;' : 'border:1px solid #1e1e1e;';
    $detailUrl = $adId ? route('ads.show', $adId) : '#';
@endphp

<div class="relative overflow-hidden cursor-pointer group"
     style="background:#141414;{{ $borderStyle }}"
     data-ad-id="{{ $adId }}"
     data-ad-title="{{ e($adTitle) }}"
     data-ad-price="{{ $adPrice }}"
     data-ad-rank="{{ $adRank ?? '' }}"
     data-ad-score="{{ $adScore ?? '' }}"
     data-ad-merchant="{{ e($adMerch) }}"
     data-ad-description="{{ e($adDesc) }}"
     data-ad-image="{{ $adImage ? asset('storage/' . $adImage) : '' }}"
     data-ad-bookmarked="{{ $bookmarked ? 'true' : 'false' }}"
     onclick="openAdOverlayFromCard(this)">

    {{-- Rank Badge --}}
    @if($adRank)
        <div class="absolute top-2 left-2 z-10 text-[8px] tracking-[2px] px-2 py-0.5 font-sans font-bold"
             style="{{ $isTop ? 'background:#DC2626;color:white;' : 'background:#111111;border:1px solid #2a2a2a;color:#454745;' }}">
            RANK #{{ $adRank }}{{ ($isTop && $adScore) ? ': '.number_format($adScore,1) : '' }}
        </div>
    @endif

    {{-- Action-Buttons --}}
    <div class="absolute top-2 right-2 z-20 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
        <button onclick="event.stopPropagation(); openAdOverlayFromCard(this.closest('[data-ad-id]'))"
                title="QUICK_VIEW"
                class="w-6 h-6 flex items-center justify-center text-[11px] transition-colors"
                style="background:#111111;border:1px solid #2a2a2a;color:#A1A1AA;"
                onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                onmouseout="this.style.borderColor='#2a2a2a';this.style.color='#A1A1AA'">⌕</button>
        <a href="{{ $detailUrl }}"
           onclick="event.stopPropagation()"
           title="VOLLANSICHT"
           class="w-6 h-6 flex items-center justify-center text-[11px] transition-colors no-underline"
           style="background:#111111;border:1px solid #2a2a2a;color:#A1A1AA;"
           onmouseover="this.style.borderColor='#DC2626';this.style.color='#DC2626'"
           onmouseout="this.style.borderColor='#2a2a2a';this.style.color='#A1A1AA'">→</a>
    </div>

    {{-- Bild --}}
    <div class="aspect-video w-full overflow-hidden flex items-center justify-center text-[7px]"
         style="background:#1a1a1a;color:#2a2a2a;">
        @if($adImage)
            <img src="{{ asset('storage/' . $adImage) }}" alt="{{ e($adTitle) }}"
                 class="w-full h-full object-cover opacity-80">
        @else
            IMG
        @endif
    </div>

    {{-- Text --}}
    <div class="p-3">
        <div class="text-[11px] font-sans font-semibold tracking-wider truncate" style="color:#e8e8e8;">{{ $adTitle }}</div>
        <div class="text-[10px] mt-0.5" style="color:#F5B700;">{{ $adPrice }}</div>
        @if($adScore)
            <div class="text-[8px] tracking-wider mt-1" style="color:#454745;">SCORE {{ number_format($adScore,1) }}</div>
        @endif
    </div>

    {{-- Hover Glow --}}
    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 24px rgba({{ $isTop ? '220,38,38' : '245,183,0' }},0.07);"></div>
</div>
