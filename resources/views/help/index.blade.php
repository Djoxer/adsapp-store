<x-buyer-app-layout>

    <div class="px-6 py-5 flex gap-5">

        {{-- ═══ MAIN ═══ --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- HEADER --}}
            <div style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #F5B700;" class="p-5">
                <div class="text-[9px] tracking-[3px] mb-1" style="color:#999999;">SYSTEM // SUPPORT_CENTER</div>
                <div class="text-[22px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">HILFE & INFO</div>
                <div class="text-[11px] mt-1" style="color:#707070;">FAQ, Plattform-Info und Support-Kontakt</div>
            </div>

            {{-- FAQ --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                    <span class="w-1 h-4 flex-shrink-0" style="background:#F5B700;"></span>
                    <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#F5B700;">FAQ // HÄUFIGE FRAGEN</span>
                </div>
                <div class="divide-y" style="border-color:#1e1e1e;">
                    @php
                        $faqs = [
                            ['q'=>'Was ist AdsApp.store?', 'a'=>'AdsApp ist eine Pull-basierte Werbeplattform — du entscheidest aktiv, welche Ads du siehst. Keine Push-Werbung, kein Tracking ohne Zustimmung.'],
                            ['q'=>'Wie funktionieren Hotspots?', 'a'=>'Hotspots sind kuratierte Themenbereiche die Ads nach Kategorie oder Saison bündeln. Aktive Hotspots haben eine Laufzeit — dauerhaft oder zeitlich begrenzt.'],
                            ['q'=>'Was bedeutet der Score einer Ad?', 'a'=>'Der Score (0–99.99) berechnet sich aus Views, Dwell-Time, Klicks und Bookmarks. Je höher der Score, desto relevanter die Ad laut Community-Feedback.'],
                            ['q'=>'Wie funktioniert die Merkliste?', 'a'=>'Über das ✦ Symbol kannst du Ads speichern. Die Merkliste ist unter MERKLISTE in der Sidebar erreichbar.'],
                            ['q'=>'Was passiert beim Klick auf "ZUM HÄNDLER"?', 'a'=>'Du wirst direkt zum Händler weitergeleitet. AdsApp ist kein Shop — wir vermitteln nur den Kontakt. Der Kauf läuft ausschließlich über den Händler.'],
                            ['q'=>'Wie werde ich Merchant?', 'a'=>'Registriere dich als Merchant und warte auf die Admin-Freigabe. Nach Freigabe kannst du Ads erstellen und Premium-Slots buchen.'],
                        ];
                    @endphp
                    @foreach($faqs as $i => $faq)
                        <div x-data="{ open: false }" class="px-5 py-4 cursor-pointer" @click="open = !open">
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-[11px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">
                                    <span class="mr-3 text-[9px]" style="color:#F5B700;">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                                    {{ $faq['q'] }}
                                </div>
                                <span class="flex-shrink-0 text-[12px] transition-transform"
                                      :style="open ? 'color:#F5B700;transform:rotate(45deg)' : 'color:#555555;'"
                                      style="transition:transform 0.2s,color 0.2s;">+</span>
                            </div>
                            <div x-show="open" x-transition class="mt-3 text-[11px] leading-relaxed pl-8" style="color:#A1A1AA;">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ÜBER DIE PLATTFORM --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                    <span class="w-1 h-4 flex-shrink-0" style="background:#DC2626;"></span>
                    <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">PLATTFORM INFO</span>
                </div>
                <div class="p-5 grid grid-cols-2 gap-5 text-[11px] leading-relaxed" style="color:#A1A1AA;">
                    <div class="space-y-3">
                        <div>
                            <div class="text-[9px] tracking-[2px] mb-1" style="color:#999999;">KONZEPT</div>
                            AdsApp.store ist eine Pull-basierte Aggregationsplattform für Werbeanzeigen. Im Gegensatz zu klassischer Push-Werbung entscheiden Nutzer selbst, welche Inhalte sie sehen.
                        </div>
                        <div>
                            <div class="text-[9px] tracking-[2px] mb-1" style="color:#999999;">SCORING</div>
                            Ads werden durch einen Community-getriebenen Score-Algorithmus bewertet. Premium-Slots sind davon getrennt und zeitlich gebucht.
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="text-[9px] tracking-[2px] mb-1" style="color:#999999;">VERSION</div>
                            AdsApp MVP v1.0 — deployed {{ now()->format('Y') }}
                        </div>
                        <div>
                            <div class="text-[9px] tracking-[2px] mb-1" style="color:#999999;">TECH_STACK</div>
                            Laravel 11 · PHP 8.3 · MySQL 8 · Tailwind CSS · Alpine.js · Hetzner CAX21
                        </div>
                        <div>
                            <div class="text-[9px] tracking-[2px] mb-1" style="color:#999999;">DATENSCHUTZ</div>
                            <a href="{{ route('privacy') }}" style="color:#F5B700;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Datenschutz & Sicherheit →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUPPORT TICKET (Dummy) --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                    <span class="w-1 h-4 flex-shrink-0" style="background:#DC2626;"></span>
                    <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">SUPPORT KONTAKT</span>
                    <span class="ml-2 text-[8px] tracking-[1.5px] px-2 py-0.5" style="background:#1a1a1a;border:1px solid #2a2a2a;color:#555555;">COMING SOON</span>
                </div>
                <div class="p-5 space-y-4">
                    <div class="text-[11px] leading-relaxed" style="color:#707070;">
                        Das integrierte Ticket-System ist in Entwicklung. Bis dahin erreichst du uns per E-Mail:
                    </div>
                    <div class="flex items-center gap-3 px-4 py-3" style="background:#1a1a1a;border:1px solid #2a2a2a;">
                        <span class="text-[9px] tracking-[2px]" style="color:#999999;">SUPPORT_MAIL</span>
                        <span class="text-[11px] font-sans font-bold" style="color:#F5B700;">support@adsapp.store</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-3 opacity-50 cursor-not-allowed" style="background:#111111;border:1px dashed #2a2a2a;">
                        <span class="text-[10px] tracking-[2px] font-sans font-bold" style="color:#555555;">⊕ TICKET ERSTELLEN — COMING SOON</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══ RIGHT PANEL ═══ --}}
        <aside class="w-72 flex-shrink-0 space-y-4">

            {{-- SYSTEM STATUS --}}
            <div style="background:#141414;border:1px solid #F5B700;">
                <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:#43d685;"></span>
                    <span class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8e8e8;">SYSTEM STATUS</span>
                </div>
                <div class="p-4 space-y-3 text-[10px] tracking-[1px]">
                    @foreach([
                        ['PLATFORM', 'ONLINE', '#43d685'],
                        ['API', 'OPERATIONAL', '#43d685'],
                        ['SEARCH', 'OPERATIONAL', '#43d685'],
                        ['UPTIME_30D', '99.98%', '#F5B700'],
                    ] as $row)
                        <div class="flex justify-between" style="{{ !$loop->last ? 'border-bottom:1px solid #1e1e1e;padding-bottom:12px;' : '' }}">
                            <span style="color:#999999;">{{ $row[0] }}</span>
                            <span class="font-sans font-bold" style="color:{{ $row[2] }};">{{ $row[1] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- QUICK LINKS --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-4 py-3" style="border-bottom:1px solid #2a2a2a;">
                    <div class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8e8e8;">QUICK LINKS</div>
                </div>
                <div class="divide-y" style="border-color:#1e1e1e;">
                    @foreach([
                        ['label'=>'KATALOG', 'route'=>'catalog'],
                        ['label'=>'MERKLISTE', 'route'=>'bookmarks.index'],
                        ['label'=>'HOTSPOTS', 'route'=>'catalog.hotspots'],
                        ['label'=>'EINSTELLUNGEN', 'route'=>'settings'],
                        ['label'=>'DATENSCHUTZ', 'route'=>'privacy'],
                    ] as $link)
                        <a href="{{ route($link['route']) }}"
                           class="flex items-center justify-between px-4 py-3 text-[10px] tracking-[1px] transition-colors"
                           style="color:#A1A1AA;"
                           onmouseover="this.style.background='#1a1a1a';this.style.color='#F5B700'"
                           onmouseout="this.style.background='transparent';this.style.color='#A1A1AA'">
                            {{ $link['label'] }}
                            <span style="color:#555555;">→</span>
                        </a>
                    @endforeach
                </div>
            </div>

        </aside>
    </div>

</x-buyer-app-layout>
