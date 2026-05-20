@props(['item' => []])

<div class="relative overflow-hidden cursor-pointer group"
     style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #F5B700;"
     onclick="openAdOverlay({
         id:{{ $item['id'] }},
         title:'{{ addslashes($item['title']) }}',
         price:'{{ $item['price'] }}',
         rank:null, score:null,
         merchant:'SPONSOR_CORP',
         description:'Premium Slot — direkte Händler-Buchung.'
     })">

    <div class="flex items-center gap-3 p-3">
        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center text-[7px] tracking-wider"
             style="background:#1a1a1a;border:1px solid #2a2a2a;color:#2a2a2a;">IMG</div>
        <div class="min-w-0">
            <div class="text-[8px] tracking-[2px] mb-0.5" style="color:#F5B700;">{{ $item['label'] }}</div>
            <div class="text-[11px] tracking-wider truncate font-sans font-semibold" style="color:#e8e8e8;">{{ $item['title'] }}</div>
            <div class="text-[10px]" style="color:#F5B700;">{{ $item['price'] }}</div>
        </div>
    </div>

    <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"
         style="box-shadow:inset 0 0 20px rgba(245,183,0,0.07);"></div>
</div>
