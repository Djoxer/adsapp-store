@props(['activeCategory' => 'ALLE'])

<div class="sticky top-0 z-30 flex items-center justify-between gap-4 px-5 py-2.5"
     style="background:rgba(17,17,17,0.97);border-bottom:1px solid #1e1e1e;backdrop-filter:blur(8px);">

    <div class="flex items-center gap-2 overflow-x-auto" style="scrollbar-width:none;">
        @php $cats = ['ALLE','TECHNIK','LIFESTYLE','OUTDOOR','AUTOMOBIL','GAMING','FASHION','HOME']; @endphp
        @foreach($cats as $cat)
        <button
            onclick="filterByCategory('{{ $cat }}')"
            class="flex-shrink-0 px-4 py-1.5 text-[10px] tracking-[1.5px] border transition-colors cat-btn"
            data-cat="{{ $cat }}"
            style="{{ $cat === $activeCategory
                ? 'border-color:#F5B700;color:#F5B700;'
                : 'border-color:#2a2a2a;color:#A1A1AA;' }}">
            {{ $cat }}
        </button>
        @endforeach
    </div>

    <div class="flex items-center gap-3 flex-shrink-0">
        <span style="font-size:9px;letter-spacing:1.5px;color:#454745;">SORTIEREN:</span>
        <button class="text-[10px] tracking-[1.5px]"
                style="color:#F5B700;border-bottom:1px solid rgba(245,183,0,0.4);">
            RELEVANZ_ALPHA
        </button>
    </div>
</div>

<script>
function filterByCategory(cat) {
    document.querySelectorAll('.cat-btn').forEach(btn => {
        const active = btn.dataset.cat === cat;
        btn.style.borderColor = active ? '#F5B700' : '#2a2a2a';
        btn.style.color       = active ? '#F5B700' : '#A1A1AA';
    });
    // später: AJAX-Reload des Ad-Grids
}
</script>
