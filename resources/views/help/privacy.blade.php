<x-buyer-app-layout>

    <div class="px-6 py-5 flex gap-5">

        {{-- ═══ MAIN ═══ --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- HEADER --}}
            <div style="background:#141414;border:1px solid #2a2a2a;border-left:3px solid #DC2626;" class="p-5">
                <div class="text-[9px] tracking-[3px] mb-1" style="color:#999999;">SYSTEM // DATENSCHUTZ & SICHERHEIT</div>
                <div class="text-[22px] font-sans font-bold tracking-wider" style="color:#e8e8e8;">PRIVACY & SECURITY</div>
                <div class="text-[11px] mt-1" style="color:#707070;">Datenschutzinformationen und Sicherheitshinweise</div>
            </div>

            {{-- DATENSCHUTZ --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                    <span class="w-1 h-4 flex-shrink-0" style="background:#DC2626;"></span>
                    <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#F5B700;">DATENSCHUTZ</span>
                </div>
                <div class="p-5 space-y-5 text-[11px] leading-relaxed" style="color:#A1A1AA;">
                    <div>
                        <div class="text-[9px] tracking-[2px] mb-2" style="color:#999999;">DATENERHEBUNG</div>
                        AdsApp.store erhebt nur die Daten die für den Betrieb der Plattform notwendig sind: E-Mail-Adresse, Benutzername und Interaktionsdaten (Views, Klicks, Bookmarks) zur Score-Berechnung.
                    </div>
                    <div style="border-top:1px solid #1e1e1e;padding-top:20px;">
                        <div class="text-[9px] tracking-[2px] mb-2" style="color:#999999;">COOKIES</div>
                        Wir verwenden ausschließlich technisch notwendige Session-Cookies. Keine Tracking-Cookies, keine Werbe-Cookies von Drittanbietern.
                    </div>
                    <div style="border-top:1px solid #1e1e1e;padding-top:20px;">
                        <div class="text-[9px] tracking-[2px] mb-2" style="color:#999999;">WEITERGABE AN DRITTE</div>
                        Deine Daten werden nicht an Dritte verkauft oder für Werbezwecke weitergegeben. Händler erhalten nur anonymisierte Interaktionsstatistiken.
                    </div>
                    <div style="border-top:1px solid #1e1e1e;padding-top:20px;">
                        <div class="text-[9px] tracking-[2px] mb-2" style="color:#999999;">DEINE RECHTE</div>
                        Du hast jederzeit das Recht auf Auskunft, Berichtigung und Löschung deiner Daten. Konto-Löschung ist direkt über Einstellungen möglich.
                    </div>
                </div>
            </div>

            {{-- SICHERHEIT --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid #2a2a2a;">
                    <span class="w-1 h-4 flex-shrink-0" style="background:#F5B700;"></span>
                    <span class="text-[13px] font-sans font-bold tracking-[2px]" style="color:#e8e8e8;">SICHERHEIT</span>
                </div>
                <div class="p-5 space-y-4">
                    @foreach([
                        ['label'=>'PASSWORT-VERSCHLÜSSELUNG', 'desc'=>'Passwörter werden mit bcrypt gehasht — niemals im Klartext gespeichert.', 'status'=>'AKTIV', 'color'=>'#43d685'],
                        ['label'=>'CSRF-SCHUTZ', 'desc'=>'Alle Formulare sind mit CSRF-Token gegen Cross-Site-Request-Forgery geschützt.', 'status'=>'AKTIV', 'color'=>'#43d685'],
                        ['label'=>'HTTPS / TLS', 'desc'=>'Die gesamte Kommunikation läuft verschlüsselt über TLS. Cloudflare SSL aktiv.', 'status'=>'AKTIV', 'color'=>'#43d685'],
                        ['label'=>'RATE LIMITING', 'desc'=>'Login-Versuche und API-Calls sind rate-limitiert gegen Brute-Force-Angriffe.', 'status'=>'AKTIV', 'color'=>'#43d685'],
                        ['label'=>'2FA', 'desc'=>'Zwei-Faktor-Authentifizierung per TOTP-App.', 'status'=>'COMING SOON', 'color'=>'#F5B700'],
                    ] as $item)
                        <div class="flex items-start gap-4 py-3" style="{{ !$loop->last ? 'border-bottom:1px solid #1e1e1e;' : '' }}">
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] font-sans font-bold tracking-[1.5px] mb-1" style="color:#e8e8e8;">{{ $item['label'] }}</div>
                                <div class="text-[10px] leading-relaxed" style="color:#707070;">{{ $item['desc'] }}</div>
                            </div>
                            <div class="flex-shrink-0 text-[8px] tracking-[1.5px] px-2 py-1 font-sans font-bold"
                                 style="border:1px solid {{ $item['color'] }};color:{{ $item['color'] }};">
                                {{ $item['status'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- KONTAKT --}}
            <div class="px-5 py-4 text-[10px] leading-relaxed" style="background:#111111;border:1px solid #1e1e1e;color:#555555;">
                Bei Datenschutzanfragen: <span style="color:#F5B700;">privacy@adsapp.store</span> —
                AdsApp.store ist ein Capstone-Projekt im Rahmen einer Fachinformatiker-Umschulung 2026–2028.
            </div>

        </div>

        {{-- ═══ RIGHT PANEL ═══ --}}
        <aside class="w-72 flex-shrink-0 space-y-4">

            {{-- SECURITY SCORE --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-4 py-3" style="border-bottom:1px solid #2a2a2a;">
                    <div class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8e8e8;">SECURITY CHECKLIST</div>
                </div>
                <div class="p-4 space-y-2">
                    @foreach([
                        ['label'=>'E-Mail bestätigt', 'done'=>true],
                        ['label'=>'Passwort gesetzt', 'done'=>true],
                        ['label'=>'2FA aktiviert', 'done'=>false],
                    ] as $check)
                        <div class="flex items-center gap-3 text-[10px] tracking-[1px]">
                            <span style="color:{{ $check['done'] ? '#43d685' : '#555555' }};">
                                {{ $check['done'] ? '✓' : '○' }}
                            </span>
                            <span style="color:{{ $check['done'] ? '#A1A1AA' : '#555555' }};">{{ $check['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- LINKS --}}
            <div style="background:#141414;border:1px solid #2a2a2a;">
                <div class="px-4 py-3" style="border-bottom:1px solid #2a2a2a;">
                    <div class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8e8e8;">VERWANDTE SEITEN</div>
                </div>
                <div class="divide-y" style="border-color:#1e1e1e;">
                    @foreach([
                        ['label'=>'HILFE & FAQ', 'route'=>'help'],
                        ['label'=>'EINSTELLUNGEN', 'route'=>'settings'],
                        ['label'=>'KONTO LÖSCHEN', 'route'=>'settings', 'query'=>'?tab=konto-loeschen', 'danger'=>true],
                    ] as $link)
                        <a href="{{ route($link['route']) }}{{ $link['query'] ?? '' }}"
                           class="flex items-center justify-between px-4 py-3 text-[10px] tracking-[1px] transition-colors"
                           style="color:{{ ($link['danger'] ?? false) ? '#DC2626' : '#A1A1AA' }};"
                           onmouseover="this.style.background='#1a1a1a'"
                           onmouseout="this.style.background='transparent'">
                            {{ $link['label'] }}
                            <span style="color:#555555;">→</span>
                        </a>
                    @endforeach
                </div>
            </div>

        </aside>
    </div>

</x-buyer-app-layout>
