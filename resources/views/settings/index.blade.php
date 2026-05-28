<x-app-layout>
    <div class="p-6">

        {{-- HEADER --}}
        <div class="flex items-end gap-6 mb-6 pb-6" style="border-bottom:1px solid #5B403F;">
            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                <div class="w-[120px] h-[120px] border-2 border-line-warm flex items-center justify-center overflow-hidden"
                     style="background:#1a0f0f;">
                    @if(Auth::user()->avatar_path)
                        <img src="{{ asset(Auth::user()->avatar_path) }}" class="w-full h-full object-cover">
                    @else
                        <x-icons.profile class="w-12 h-12 text-copy-ticker" />
                    @endif
                </div>
                <div class="absolute -bottom-2 left-0 right-0 text-center">
                    <span class="text-[8px] tracking-[2px] px-2 py-0.5 font-sans font-bold"
                          style="background:#DC2626;color:white;">
                        OPERATOR_ID: {{ Auth::user()->id }}
                    </span>
                </div>
            </div>

            {{-- Meta --}}
            <div class="flex-1 grid grid-cols-4 gap-6 pb-2">
                <div>
                    <div class="text-[9px] tracking-[2px] text-copy-ticker mb-1">USERNAME</div>
                    <div class="text-[15px] font-sans font-bold text-copy-soft tracking-wider">{{ strtoupper(Auth::user()->name) }}</div>
                </div>
                <div>
                    <div class="text-[9px] tracking-[2px] text-copy-ticker mb-1">EMAIL_ADDRESS</div>
                    <div class="text-[13px] font-sans text-copy-neutral tracking-wider">{{ Auth::user()->email }}</div>
                </div>
                <div>
                    <div class="text-[9px] tracking-[2px] text-copy-ticker mb-1">JOIN_DATE</div>
                    <div class="text-[13px] font-sans text-copy-neutral tracking-wider">
                        {{ Auth::user()->created_at->format('Y-m-d\_H:i') }}_UTC
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-[10px] tracking-[2px] px-3 py-1 border font-sans font-bold"
                          style="border-color:#DC2626;color:#DC2626;">
                        TIER: {{ strtoupper(Auth::user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="flex gap-6">

            {{-- LEFT SUB-NAV --}}
            <div class="w-[200px] flex-shrink-0 flex flex-col justify-between" style="min-height:500px;">
                <div class="space-y-0">
                    @php
                        $subNav = [
                            ['id'=>'profil',         'label'=>'PROFIL',             'icon'=>'profile', 'active'=>true,  'danger'=>false],
                            ['id'=>'sicherheit',     'label'=>'SICHERHEIT',         'icon'=>'lock',    'active'=>false, 'danger'=>false],
                            ['id'=>'benachricht',    'label'=>'BENACHRICHTIGUNGEN', 'icon'=>'bell',    'active'=>false, 'danger'=>false],
                            ['id'=>'darstellung',    'label'=>'DARSTELLUNG',        'icon'=>'color',   'active'=>false, 'danger'=>false],
                            ['id'=>'datenschutz',    'label'=>'DATENSCHUTZ',        'icon'=>'hidden',  'active'=>false, 'danger'=>false],
                            ['id'=>'hilfe',          'label'=>'INFO / HILFE',       'icon'=>'info',    'active'=>false, 'danger'=>false],
                            ['id'=>'konto-loeschen', 'label'=>'KONTO LÖSCHEN',      'icon'=>'delete',  'active'=>false, 'danger'=>true],
                        ];
                    @endphp
                    @foreach($subNav as $nav)
                        <button
                            onclick="showSection('{{ $nav['id'] }}')"
                            class="w-full flex items-center justify-between px-4 py-3 text-[10px] tracking-[1.5px] transition-colors text-left settings-nav-btn"
                            data-section="{{ $nav['id'] }}"
                            style="border-left:2px solid {{ $nav['active'] ? '#F5B700' : 'transparent' }};
                                   background:{{ $nav['active'] ? '#1a1200' : 'transparent' }};
                                   color:{{ $nav['danger'] ? '#DC2626' : ($nav['active'] ? '#F5B700' : '#A1A1AA') }};
                                   border-bottom:1px solid #2a1a1a;">
                            <div class="flex items-center gap-3">
                                <x-dynamic-component :component="'icons.' . $nav['icon']" class="w-4 h-4 flex-shrink-0" />
                                {{ $nav['label'] }}
                            </div>
                            <x-icons.pointer class="w-3 h-3 opacity-40" />
                        </button>
                    @endforeach
                </div>

                {{-- Security info box --}}
                <div class="mt-4 p-3 text-[9px] leading-relaxed tracking-wider"
                     style="background:#0f0a0a;border:1px solid #2a1a1a;border-left:2px solid #F5B700;">
                    <div class="flex items-center gap-1.5 mb-1.5">
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#F5B700;"></span>
                        <span class="text-brand-yellow font-bold">SYS_ENCRYPTION_ACTIVE</span>
                    </div>
                    <div class="text-copy-ticker leading-relaxed">
                        Your account data is secured using military-grade RSA-4096 protocols.
                        Last audit: <span class="text-copy-neutral">48m ago.</span>
                    </div>
                </div>
            </div>

            {{-- MAIN PANEL --}}
            <div class="flex-1 min-w-0">

                {{-- SESSION STATUS --}}
                @if(session('status') === 'profile-updated')
                    <div class="mb-4 px-4 py-2 text-[10px] tracking-[1.5px] text-brand-yellow"
                         style="background:#1a1200;border:1px solid #F5B700;">
                        &check; PROFIL_AKTUALISIERT
                    </div>
                @endif
                @if(session('status') === 'password-updated')
                    <div class="mb-4 px-4 py-2 text-[10px] tracking-[1.5px] text-brand-yellow"
                         style="background:#1a1200;border:1px solid #F5B700;">
                        &check; PASSWORT_AKTUALISIERT
                    </div>
                @endif

                @include('settings.sections.profile')
                @include('settings.sections.security')
                @include('settings.sections.notifications')
                @include('settings.sections.delete-account')
                @include('settings.sections.placeholder')

            </div>
        </div>
    </div>

    <script>
        function showSection(id) {
            // Hide all
            document.querySelectorAll('.settings-section').forEach(s => s.classList.add('hidden'));
            // Show target
            const target = document.getElementById('section-' + id);
            if (target) target.classList.remove('hidden');
            // Update nav styles
            document.querySelectorAll('.settings-nav-btn').forEach(btn => {
                const active = btn.dataset.section === id;
                const isDanger = btn.dataset.section === 'konto-loeschen';
                btn.style.borderLeft = active ? '2px solid #F5B700' : '2px solid transparent';
                btn.style.background = active ? '#1a1200' : 'transparent';
                btn.style.color = isDanger ? '#DC2626' : (active ? '#F5B700' : '#A1A1AA');
            });

            // Benachrichtigungen als gesehen markieren
            if (id === 'benachricht') {
                fetch('{{ route('settings.notifications.seen') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                }).then(() => {
                    const badge = document.getElementById('notif-badge');
                    if (badge) badge.style.display = 'none';
                });
            }
        }

        // Tab aus URL-Parameter öffnen (nach Funktionsdefinition!)
        const urlTab = new URLSearchParams(window.location.search).get('tab');
        if (urlTab) showSection(urlTab);
    </script>
</x-app-layout>
