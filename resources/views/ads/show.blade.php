<x-buyer-app-layout>

    <div class="max-w-[900px] mx-auto p-6">

        {{-- Breadcrumb / Back --}}
        <a href="{{ route('catalog') }}"
           class="inline-flex items-center gap-2 text-[9px] tracking-[2px] mb-4 transition-colors"
           style="color:#454745;"
           onmouseover="this.style.color='#F5B700'"
           onmouseout="this.style.color='#454745'">
            &larr; ZURÜCK_ZUM_KATALOG
        </a>

        <div style="background:#111111;border:1px solid #2a2a2a;
                box-shadow:0 0 60px rgba(245,183,0,0.10);">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #2a2a2a;">
                <div>
                    <div class="text-[9px] tracking-[2px]" style="color:#454745;">
                        AD_DETAIL // {{ strtoupper($ad->category->name ?? 'UNCATEGORIZED') }}
                    </div>
                    <div class="text-2xl font-sans font-bold tracking-wider mt-1" style="color:#e8e8e8;">
                        {{ $ad->title }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[9px] tracking-[1.5px]" style="color:#454745;">SCORE</div>
                    <div class="text-3xl font-sans font-bold" style="color:#F5B700;">
                        {{ number_format((float) $ad->current_score, 1) }}
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="grid grid-cols-2 gap-6 p-6">

                {{-- Bild-Gallery --}}
                <div class="space-y-3">
                    <div class="aspect-square overflow-hidden flex items-center justify-center"
                         style="background:#1a1a1a;border:1px solid #2a2a2a;">
                        @if($ad->images->isNotEmpty())
                            <img src="{{ Storage::url($ad->images->first()->cache_path) }}"
                                 alt="{{ $ad->title }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[10px] tracking-[2px]" style="color:#454745;">NO_IMAGE</span>
                        @endif
                    </div>
                    {{-- Weitere Bilder als Thumbnails --}}
                    @if($ad->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($ad->images->skip(1) as $img)
                                <div class="aspect-square overflow-hidden" style="background:#1a1a1a;border:1px solid #2a2a2a;">
                                    <img src="{{ Storage::url($img->cache_path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex flex-col gap-5">
                    <div>
                        <div class="text-[9px] tracking-[2px] mb-1" style="color:#454745;">PREIS</div>
                        <div class="text-3xl font-sans font-bold" style="color:#F5B700;">{{ $ad->price_euro }}</div>
                    </div>

                    <div>
                        <div class="text-[9px] tracking-[2px] mb-1" style="color:#454745;">BESCHREIBUNG</div>
                        <div class="text-[12px] leading-relaxed" style="color:#A1A1AA;">{{ $ad->description }}</div>
                    </div>

                    <div>
                        <div class="text-[9px] tracking-[2px] mb-1" style="color:#454745;">HÄNDLER</div>
                        <div class="text-[12px]" style="color:#A1A1AA;">{{ $ad->merchant->company_name ?? '—' }}</div>
                    </div>

                    @if($ad->tags->isNotEmpty())
                        <div>
                            <div class="text-[9px] tracking-[2px] mb-2" style="color:#454745;">TAGS</div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($ad->tags as $tag)
                                    <span class="text-[9px] tracking-wider px-2 py-0.5"
                                          style="background:#1a1a1a;border:1px solid #2a2a2a;color:#777777;">
                                {{ $tag->name }}
                            </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-auto flex flex-col gap-2">
                        <a href="{{ route('ads.click', $ad->id) }}" target="_blank"
                           class="block text-center py-3 text-[12px] tracking-[2px] font-sans font-bold transition-colors no-underline"
                           style="background:#DC2626;color:white;"
                           onmouseover="this.style.background='#FF535B'"
                           onmouseout="this.style.background='#DC2626'">
                            ZUM HÄNDLER &rarr;
                        </a>
                        <button id="bookmark-btn-detail-{{ $ad->id }}"
                                onclick="toggleBookmarkDetail({{ $ad->id }})"
                                data-bm="{{ $bookmarked ? '1' : '0' }}"
                                class="py-2.5 text-[10px] tracking-[2px] transition-colors"
                                style="border:1px solid {{ $bookmarked ? '#F5B700' : '#2a2a2a' }};
                                   background:transparent;
                                   color:{{ $bookmarked ? '#F5B700' : '#A1A1AA' }};">
                            {{ $bookmarked ? '✦ GEMERKT' : '✦ ZUR MERKLISTE' }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Rechtliches / Footer-Info --}}
            <div class="px-6 py-4 text-[9px] tracking-wider leading-relaxed"
                 style="border-top:1px solid #2a2a2a;color:#454745;">
                HINWEIS: AdsApp ist eine Ad-Aggregations-Plattform. Der Klick auf "ZUM HÄNDLER"
                leitet zur externen Seite des Anbieters weiter. AdsApp ist nicht Vertragspartner
                des beworbenen Angebots.
            </div>
        </div>
    </div>

    <script>
        // Bookmark-Toggle für Detail-Page (eigene Button-ID)
        function toggleBookmarkDetail(adId) {
            fetch('/bookmarks/' + adId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
                .then(r => r.json())
                .then(res => {
                    const btn = document.getElementById('bookmark-btn-detail-' + adId);
                    if (btn) {
                        const active = res.bookmarked;
                        btn.dataset.bm = active ? '1' : '0';
                        btn.style.borderColor = active ? '#F5B700' : '#2a2a2a';
                        btn.style.color       = active ? '#F5B700' : '#A1A1AA';
                        btn.innerHTML = active ? '\u2726 GEMERKT' : '\u2726 ZUR MERKLISTE';
                    }
                    if (typeof showBookmarkToast === 'function') showBookmarkToast(res.bookmarked);
                })
                .catch(err => console.error('Bookmark error:', err));
        }
    </script>

</x-buyer-app-layout>
