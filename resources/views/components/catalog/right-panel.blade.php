{{-- Right Panel — Sponsored / Top Picks
     Props: $picks = array of [id, title, desc, price, exp]
--}}
@props(['picks' => []])

<div class="w-[220px] flex-shrink-0 overflow-y-auto" style="border-left:1px solid #1e1e1e;background:#0d0d0d;">

    <div class="px-4 py-3" style="border-bottom:1px solid #1e1e1e;">
        <div class="text-[9px] tracking-[2px]" style="color:#454745;">SPONSORED // TOP PICKS</div>
    </div>

    @foreach($picks as $pick)
    <div style="border-bottom:1px solid #1a1a1a;border-left:3px solid #F5B700;"
         class="cursor-pointer group"
         onclick="openAdOverlay({
             id:{{ $pick['id'] }},
             title:'{{ addslashes($pick['title']) }}',
             price:'{{ $pick['price'] }}',
             rank:null, score:null,
             merchant:'SPONSOR',
             description:'{{ addslashes($pick['desc'] ?? '') }}'
         })">
        <div class="aspect-[4/3] flex items-center justify-center text-[7px] tracking-wider transition-colors"
             style="background:#141414;border-bottom:1px solid #1a1a1a;color:#2a2a2a;"
             onmouseover="this.style.background='#1a1a1a'"
             onmouseout="this.style.background='#141414'">IMG</div>
        @if(!empty($pick['title']))
        <div class="p-3">
            <div class="text-[11px] font-sans font-semibold tracking-wider" style="color:#e8e8e8;">{{ $pick['title'] }}</div>
            @if(!empty($pick['desc']))
            <div class="text-[9px] mt-1 leading-relaxed" style="color:#777777;">{{ $pick['desc'] }}</div>
            @endif
            <div class="text-[11px] font-sans font-bold mt-2" style="color:#F5B700;">
                {{ $pick['price'] }}
                @if(!empty($pick['exp']))
                <span class="text-[9px] font-normal" style="color:#454745;">{{ $pick['exp'] }}</span>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endforeach

</div>
