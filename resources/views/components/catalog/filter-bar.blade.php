@props(['categories' => collect(), 'activeCategory' => null, 'sort' => 'score'])

<div class="sticky top-0 z-30 flex items-center justify-between gap-4 px-5 py-2.5"
     style="background:rgba(17,17,17,0.97);border-bottom:1px solid #1e1e1e;backdrop-filter:blur(8px);">

    {{-- Kategorie-Buttons — aus DB --}}
    <div class="flex items-center gap-2 overflow-x-auto" style="scrollbar-width:none;">

        {{-- ALLE --}}
        <a href="{{ route('catalog', array_filter(['q' => request('q'), 'sort' => $sort])) }}"
           class="flex-shrink-0 px-4 py-1.5 text-[10px] tracking-[1.5px] border transition-colors"
           style="{{ $activeCategory === null
               ? 'border-color:#F5B700;color:#F5B700;'
               : 'border-color:#2a2a2a;color:#A1A1AA;' }}">
            ALLE
        </a>

        @foreach($categories as $cat)
            <a href="{{ route('catalog', array_filter(['q' => request('q'), 'category' => $cat->slug, 'sort' => $sort])) }}"
               class="flex-shrink-0 px-4 py-1.5 text-[10px] tracking-[1.5px] border transition-colors"
               style="{{ $activeCategory?->slug === $cat->slug
               ? 'border-color:#F5B700;color:#F5B700;'
               : 'border-color:#2a2a2a;color:#A1A1AA;' }}"
               onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
               onmouseout="this.style.borderColor='{{ $activeCategory?->slug === $cat->slug ? '#F5B700' : '#2a2a2a' }}';this.style.color='{{ $activeCategory?->slug === $cat->slug ? '#F5B700' : '#A1A1AA' }}'">
                {{ strtoupper($cat->name) }}
            </a>
        @endforeach

    </div>

    {{-- Sortierung --}}
    <div class="flex items-center gap-3 flex-shrink-0">
        <span style="font-size:9px;letter-spacing:1.5px;color:#454745;">SORTIEREN:</span>
        @foreach(['score'=>'RELEVANZ','newest'=>'NEUESTE','price_asc'=>'PREIS ↑','price_desc'=>'PREIS ↓'] as $val => $label)
            <a href="{{ route('catalog', array_filter(['q' => request('q'), 'category' => request('category'), 'sort' => $val])) }}"
               class="text-[10px] tracking-[1.5px] transition-colors"
               style="{{ $sort === $val
               ? 'color:#F5B700;border-bottom:1px solid rgba(245,183,0,0.4);'
               : 'color:#454745;' }}"
               onmouseover="this.style.color='#F5B700'"
               onmouseout="this.style.color='{{ $sort === $val ? '#F5B700' : '#454745' }}'">
                {{ $label }}
            </a>
        @endforeach
    </div>

</div>
