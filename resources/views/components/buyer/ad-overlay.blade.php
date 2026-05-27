{{-- Ad Overlay — vollständig JS-generiert, kein vorgerendertes HTML --}}
<script>
    (function() {
        'use strict';

        let overlayEl = null;

        function buildOverlay() {
            if (overlayEl) return overlayEl;

            overlayEl = document.createElement('div');
            overlayEl.id = 'ad-overlay-dynamic';
            Object.assign(overlayEl.style, {
                position:       'fixed',
                top:            '0',
                left:           '0',
                right:          '0',
                bottom:         '0',
                zIndex:         '9000',        // Toast (100000) liegt drüber
                display:        'none',
                alignItems:     'center',
                justifyContent: 'center',
                background:     'transparent', // kein Backdrop
                pointerEvents:  'none',        // Klicks gehen durch
            });

            document.body.appendChild(overlayEl);
            return overlayEl;
        }

        window.openAdOverlay = function(data) {
            const overlay = buildOverlay();

            overlay.innerHTML = `
            <div style="width:100%;max-width:680px;position:relative;margin:0 16px;
                        background:#111111;border:1px solid #2a2a2a;
                        pointer-events:all;
                        box-shadow:0 0 60px rgba(245,183,0,0.25), 0 0 120px rgba(245,183,0,0.12);">
                <button onclick="closeAdOverlay()"
                        style="position:absolute;top:16px;right:16px;background:none;border:none;font-size:18px;cursor:pointer;color:#454745;z-index:10;"
                        onmouseover="this.style.color='#DC2626'" onmouseout="this.style.color='#454745'">✕</button>

                <div style="border-bottom:1px solid #2a2a2a;padding:16px 24px;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <div style="font-size:9px;letter-spacing:2px;color:#454745;font-family:'Share Tech Mono',monospace;">AD_DETAIL // RANK #${data.rank ?? '—'}</div>
                        <div style="font-size:20px;font-family:'Rajdhani',sans-serif;font-weight:700;color:#e8e8e8;letter-spacing:2px;margin-top:4px;">${data.title}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:9px;letter-spacing:1.5px;color:#454745;font-family:'Share Tech Mono',monospace;">SCORE</div>
                        <div style="font-size:24px;font-family:'Rajdhani',sans-serif;font-weight:700;color:#F5B700;">${data.score ?? '—'}</div>
                    </div>
                </div>

                <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                    <div style="aspect-ratio:1;background:#1a1a1a;border:1px solid #2a2a2a;display:flex;align-items:center;justify-content:center;">
                        ${data.image
                ? `<img src="${data.image}" style="width:100%;height:100%;object-fit:cover;">`
                : `<span style="font-size:10px;letter-spacing:2px;color:#454745;font-family:'Share Tech Mono',monospace;">NO_IMAGE</span>`
            }
                    </div>
                    <div style="display:flex;flex-direction:column;gap:16px;">
                        <div>
                            <div style="font-size:9px;letter-spacing:2px;color:#454745;margin-bottom:4px;font-family:'Share Tech Mono',monospace;">PREIS</div>
                            <div style="font-size:28px;font-family:'Rajdhani',sans-serif;font-weight:700;color:#F5B700;">${data.price}</div>
                        </div>
                        <div>
                            <div style="font-size:9px;letter-spacing:2px;color:#454745;margin-bottom:4px;font-family:'Share Tech Mono',monospace;">BESCHREIBUNG</div>
                            <div style="font-size:11px;letter-spacing:1px;color:#A1A1AA;line-height:1.6;font-family:'Share Tech Mono',monospace;">${data.description ?? '—'}</div>
                        </div>
                        <div>
                            <div style="font-size:9px;letter-spacing:2px;color:#454745;margin-bottom:4px;font-family:'Share Tech Mono',monospace;">HÄNDLER</div>
                            <div style="font-size:11px;letter-spacing:1px;color:#A1A1AA;font-family:'Share Tech Mono',monospace;">${data.merchant ?? '—'}</div>
                        </div>
                        <div style="margin-top:auto;display:flex;gap:12px;">
                            <a href="${data.deeplink ?? '#'}" target="_blank"
                               style="flex:1;display:block;text-align:center;padding:12px;font-size:11px;letter-spacing:2px;font-family:'Rajdhani',sans-serif;font-weight:700;text-decoration:none;background:#DC2626;color:white;"
                               onmouseover="this.style.background='#FF535B'" onmouseout="this.style.background='#DC2626'">
                                JETZT KAUFEN →
                            </a>
                            <button id="bookmark-btn-${data.id}"
                                    onclick="toggleBookmark(${data.id})"
                                    style="width:48px;border:1px solid ${data.bookmarked ? '#F5B700' : '#2a2a2a'};background:transparent;color:${data.bookmarked ? '#F5B700' : '#454745'};font-size:16px;cursor:pointer;"
                                    onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
                                    onmouseout="this.dataset.bm==='1'?(this.style.borderColor='#F5B700',this.style.color='#F5B700'):(this.style.borderColor='#2a2a2a',this.style.color='#454745')">✦</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        window.closeAdOverlay = function() {
            if (overlayEl) overlayEl.style.display = 'none';
            document.body.style.overflow = '';
        };

        window.toggleBookmark = function(adId) {
            fetch('/bookmarks/' + adId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    const btn = document.getElementById('bookmark-btn-' + adId);
                    if (btn) {
                        const isActive = res.bookmarked;
                        btn.dataset.bm        = isActive ? '1' : '0';
                        btn.style.color       = isActive ? '#F5B700' : '#454745';
                        btn.style.borderColor = isActive ? '#F5B700' : '#2a2a2a';
                        btn.title = isActive ? 'AUS MERKLISTE ENTFERNEN' : 'ZUR MERKLISTE HINZUFÜGEN';
                    }
                    showBookmarkToast(res.bookmarked);
                })
                .catch(function(err) { console.error('Bookmark error:', err); });
        };

        window.showBookmarkToast = function(added) {
            let toast = document.getElementById('bookmark-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'bookmark-toast';
                Object.assign(toast.style, {
                    position:   'fixed',
                    bottom:     '80px',
                    right:      '24px',
                    zIndex:     '100000', // über allem
                    padding:    '8px 16px',
                    fontSize:   '10px',
                    letterSpacing: '2px',
                    fontFamily: 'monospace',
                    border:     '1px solid',
                    transition: 'opacity 0.2s ease',
                    pointerEvents: 'none', // Toast selbst nicht klickbar
                });
                document.body.appendChild(toast);
            }
            toast.textContent    = added ? '✦ MERKLISTE +1' : '✦ ENTFERNT';
            toast.style.background  = added ? '#1a1200' : '#141414';
            toast.style.borderColor = added ? '#F5B700' : '#454745';
            toast.style.color       = added ? '#F5B700' : '#A1A1AA';
            toast.style.opacity     = '1';
            clearTimeout(window._toastTimer);
            window._toastTimer = setTimeout(() => { toast.style.opacity = '0'; }, 2000);
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAdOverlay();
        });
    })();
</script>
