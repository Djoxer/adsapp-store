{{-- Right Panel — Top Picks
     Props: $ads = Collection<Ad> (Model) ODER legacy $picks = array
--}}
@props(['ads' => null, 'picks' => []])

@php
    // Rückwärtskompatibilität: wenn noch altes $picks-Array übergeben wird
    $items = $ads ?? collect($picks);
@endphp

<div class="w-[220px] flex-shrink-0 overflow-y-auto" style="border-left:1px solid #1e1e1e;background:#0d0d0d;">

    <div class="px-4 py-3" style="border-bottom:1px solid #1e1e1e;">
        <div class="text-[9px] tracking-[2px]" style="color:#454745;">SPONSORED // TOP PICKS</div>
    </div>

    @foreach($items as $item)
        @php
            $isModel = $item instanceof \App\Models\Ad;
            $pId    = $isModel ? $item->id    : ($item['id']    ?? 0);
            $pTitle = $isModel ? $item->title : ($item['title'] ?? '');
            $pDesc  = $isModel ? $item->description : ($item['desc'] ?? '');
            $pPrice = $isModel
                ? number_format($item->price_cents / 100, 2, ',', '.') . ' €'
                : ($item['price'] ?? '');
            $pImage = $isModel ? $item->images->first()?->path : null;
            $pMerch = $isModel ? ($item->merchant->company_name ?? 'SPONSOR') : 'SPONSOR';
        @endphp
        <div style="border-bottom:1px solid #1a1a1a;border-left:3px solid #F5B700;"
             class="cursor-pointer group"
             onclick="openAdOverlay({
             id:{{ $pId }},
             title:'{{ addslashes(e($pTitle)) }}',
             price:'{{ $pPrice }}',
             rank:null, score:null,
             merchant:'{{ addslashes(e($pMerch)) }}',
             description:'{{ addslashes(e($pDesc)) }}'
         })">
            <div class="aspect-[4/3] flex items-center justify-center text-[7px] tracking-wider transition-colors overflow-hidden"
                 style="background:#141414;border-bottom:1px solid #1a1a1a;color:#2a2a2a;">
                @if($pImage)
                    <img src="{{ Storage::url($pImage) }}" alt="{{ $pTitle }}"
                         class="w-full h-full object-cover opacity-80">
                @else
                    IMG
                @endif
            </div>
            @if($pTitle)
                <div class="p-3">
                    <div class="text-[11px] font-sans font-semibold tracking-wider" style="color:#e8e8e8;">{{ $pTitle }}</div>
                    @if($pDesc)
                        <div class="text-[9px] mt-1 leading-relaxed line-clamp-2" style="color:#777777;">{{ $pDesc }}</div>
                    @endif
                    <div class="text-[11px] font-sans font-bold mt-2" style="color:#F5B700;">{{ $pPrice }}</div>
                </div>
            @endif
        </div>
    @endforeach

    @if($items->isEmpty())
        <div class="p-4 text-[8px] tracking-[2px]" style="color:#2a2a2a;">NO_PICKS_AVAILABLE</div>
    @endif

</div>
