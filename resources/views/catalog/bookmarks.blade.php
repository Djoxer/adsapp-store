<x-buyer-app-layout>

    {{-- HEADER --}}
    <div class="sticky top-0 z-30 flex items-center justify-between gap-4 px-5 py-3"
         style="background:rgba(34,34,34,0.97);border-bottom:1px solid #1e1e1e;backdrop-filter:blur(8px);">
        <div class="flex items-center gap-3">
            <x-icons.bookmark class="w-5 h-5" style="color:#F5B700;" />
            <div>
                <div class="text-[9px] tracking-[3px]" style="color:#454745;">BUYER_CONSOLE // GESPEICHERT</div>
                <div class="text-[14px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">MERKLISTE</div>
            </div>
        </div>
        <div id="bookmark-count" class="text-[10px] tracking-[1.5px]" style="color:#454745;">
            {{ $bookmarks->count() }} EINTRÄGE
        </div>
    </div>

    <div class="p-4">

        @forelse($bookmarks->chunk(4) as $row)
            <div class="grid grid-cols-4 gap-3 mb-3">
                @foreach($row as $ad)
                    <div id="bookmark-card-{{ $ad->id }}"
                         class="relative overflow-hidden cursor-pointer group"
                         style="background:#141414;border:1px solid #1e1e1e;border-left:3px solid #F5B700;"
                         data-ad-id="{{ $ad->id }}"
                         data-ad-title="{{ e($ad->title) }}"
                         data-ad-price="{{ $ad->price_euro }}"
                         data-ad-rank=""
                         data-ad-score="{{ $ad->current_score ?? '' }}"
                         data-ad-merchant="{{ e($ad->merchant->company_name ?? '') }}"
                         data-ad-description="{{ e(Str::limit($ad->description, 120)) }}"
                         data-ad-image="{{ $ad->images->first() ? asset('storage/' . $ad->images->first()->cache_path) : '' }}"
                         data-ad-bookmarked="true"
                         onclick="openAdOverlayFromCard(this)">

                        {{-- Image --}}
                        <div class="aspect-video w-full flex items-center justify-center text-[7px]"
                             style="background:#1a1a1a;color:#2a2a2a;">
                            @if($ad->images->first())
                                <img src="{{ asset('storage/' . $ad->images->first()->cache_path) }}" class="w-full h-full object-cover">
                            @else
                                IMG
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-3">
                            <div class="text-[11px] font-sans font-semibold tracking-wider truncate" style="color:#e8e8e8;">{{ $ad->title }}</div>
                            <div class="text-[10px] mt-0.5" style="color:#F5B700;">{{ $ad->price_euro }}</div>
                            @if($ad->category)
                                <div class="text-[8px] tracking-wider mt-1" style="color:#454745;">{{ strtoupper($ad->category->name) }}</div>
                            @endif
                        </div>

                        {{-- Bookmark indicator --}}
                        <div class="absolute top-2 right-2">
                            <x-icons.bookmark class="w-3.5 h-3.5" style="color:#F5B700;" />
                        </div>

                    </div>
                @endforeach
            </div>
        @empty
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center gap-4" style="padding:80px 0;">
                <x-icons.bookmark class="w-12 h-12" style="color:#2a2a2a;" />
                <div class="text-[11px] tracking-[2px]" style="color:#454745;">MERKLISTE IST LEER</div>
                <div class="text-[10px] tracking-wider text-center max-w-[280px] leading-relaxed" style="color:#3a3a3a;">
                    Speichere Ads aus dem Katalog über das ✦ Symbol — sie erscheinen dann hier.
                </div>
                <a href="{{ route('catalog') }}"
                   class="mt-2 px-5 py-2 text-[10px] tracking-[2px] font-sans font-bold transition-colors"
                   style="border:1px solid #F5B700;color:#F5B700;"
                   onmouseover="this.style.background='#F5B700';this.style.color='#0a0a0a'"
                   onmouseout="this.style.background='transparent';this.style.color='#F5B700'">
                    ZUM KATALOG
                </a>
            </div>
        @endforelse

    </div>

    <script>
    window.addEventListener('load', function() {
        // Läuft garantiert NACH dem Overlay-Script
        window.toggleBookmark = function(adId) {
            fetch('/bookmarks/' + adId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(r => r.json())
            .then(res => {
                if (!res.bookmarked) {
                    const card = document.getElementById('bookmark-card-' + adId);
                    if (card) {
                        card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.92)';
                        setTimeout(() => {
                            card.remove();
                            updateBookmarkCount();
                            checkEmptyState();
                        }, 250);
                    }
                    if (typeof closeAdOverlay === 'function') closeAdOverlay();
                }
                if (typeof showBookmarkToast === 'function') showBookmarkToast(res.bookmarked);
            })
            .catch(err => console.error('Bookmark error:', err));
        };

        function updateBookmarkCount() {
            const remaining = document.querySelectorAll('[id^="bookmark-card-"]').length;
            const counter = document.getElementById('bookmark-count');
            if (counter) counter.textContent = remaining + ' EINTRÄGE';
        }

        function checkEmptyState() {
            const remaining = document.querySelectorAll('[id^="bookmark-card-"]').length;
            if (remaining === 0) location.reload();
        }
    });
    </script>

</x-buyer-app-layout>
