{{-- Right Panel — Aktive Hotspots + Premium Zone B
     Props:
       $premiumSlots = Collection<SlotBooking> (live Zone-B-Buchungen, sortiert nach Position)
       $hotspots     = Collection<Hotspot> (aktive Hotspots)
--}}
@props(['premiumSlots' => null, 'hotspots' => null])

<div class="w-[220px] flex-shrink-0 overflow-y-auto" style="border-left:1px solid #1e1e1e;background:#0d0d0d;">

    {{-- ═══ AKTIVE HOTSPOTS ═══ --}}
    @if($hotspots && $hotspots->isNotEmpty())
        <div class="px-4 py-3" style="border-bottom:1px solid #1e1e1e;">
            <div class="flex items-center gap-2 text-[9px] tracking-[2px]" style="color:#DC2626;">
                <span class="w-1.5 h-1.5 rounded-full inline-block live-dot" style="background:#DC2626;"></span>
                AKTIVE HOTSPOTS
            </div>
        </div>
        @foreach($hotspots->sortBy(fn($h) => $h->days_left ?? 9999)->take(3) as $hs)
            <a href="{{ route('catalog.hotspot.show', $hs->slug) }}"
               class="flex items-center gap-3 px-4 py-3 cursor-pointer transition-colors"
               style="border-bottom:1px solid #1a1a1a;border-left:3px solid #DC2626;"
               onmouseover="this.style.background='#141414'"
               onmouseout="this.style.background='transparent'">
                <span class="text-[20px] flex-shrink-0">{{ $hs->icon ?? '🔥' }}</span>
                <div class="min-w-0">
                    <div class="text-[11px] font-sans font-bold tracking-wider truncate" style="color:#e8e8e8;">{{ $hs->name }}</div>
                    <div class="text-[8px] tracking-[1.5px] mt-0.5" style="color:#454745;">
                        {{ $hs->ads_count }} ADS ·
                        @if($hs->days_left !== null) NOCH {{ $hs->days_left }}T @else DAUERHAFT @endif
                    </div>
                </div>
            </a>
        @endforeach
    @endif

    {{-- ═══ PREMIUM // ZONE B ═══ --}}
    <div class="px-4 py-3" style="border-bottom:1px solid #1e1e1e;">
        <div class="text-[9px] tracking-[2px]" style="color:#454745;">PREMIUM // ZONE B</div>
    </div>

    @forelse($premiumSlots ?? collect() as $booking)
        @php
            $ad     = $booking->ad;
            $pImage = $ad?->images->first()?->cache_path;
        @endphp
        @if($ad)
            <div style="border-bottom:1px solid #1a1a1a;border-left:3px solid #F5B700;"
                 class="cursor-pointer group relative"
                 data-ad-id="{{ $ad->id }}"
                 data-ad-title="{{ e($ad->title) }}"
                 data-ad-price="{{ number_format($ad->price_cents / 100, 2, ',', '.') }} €"
                 data-ad-rank=""
                 data-ad-score=""
                 data-ad-merchant="{{ e($ad->merchant->company_name ?? 'SPONSOR') }}"
                 data-ad-description="{{ e($ad->description) }}"
                 data-ad-image="{{ $pImage ? asset('storage/' . $pImage) : '' }}"
                 data-ad-bookmarked="false"
                 onclick="openAdOverlayFromCard(this)">

                {{-- Position-Badge B-1 .. B-4 --}}
                <div class="absolute top-2 left-2 z-10 text-[7px] tracking-[1.5px] px-1.5 py-0.5"
                     style="background:#F5B700;color:#0a0a0a;">
                    B-{{ $booking->slot->position }}
                </div>

                <div class="aspect-[4/3] flex items-center justify-center text-[7px] tracking-wider overflow-hidden"
                     style="background:#141414;border-bottom:1px solid #1a1a1a;color:#2a2a2a;">
                    @if($pImage)
                        <img src="{{ asset('storage/' . $pImage) }}" alt="{{ $ad->title }}"
                             class="w-full h-full object-cover opacity-80 pointer-events-none">
                    @else
                        IMG
                    @endif
                </div>

                <div class="p-3">
                    <div class="text-[11px] font-sans font-semibold tracking-wider" style="color:#e8e8e8;">{{ $ad->title }}</div>
                    @if($ad->description)
                        <div class="text-[9px] mt-1 leading-relaxed line-clamp-2" style="color:#777777;">{{ $ad->description }}</div>
                    @endif
                    <div class="text-[11px] font-sans font-bold mt-2" style="color:#F5B700;">{{ number_format($ad->price_cents / 100, 2, ',', '.') }} €</div>
                </div>
            </div>
        @endif
    @empty
        <div class="p-4 text-[8px] tracking-[2px]" style="color:#2a2a2a;">KEINE PREMIUM-PLATZIERUNGEN</div>
    @endforelse

</div>
