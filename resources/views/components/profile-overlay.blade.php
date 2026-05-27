{{-- Profile Overlay — für alle Rollen
     Einbinden in: layouts/app.blade.php + layouts/buyer-app.blade.php
     Trigger: openProfileOverlay() via JS
--}}

{{-- Zeile 4: das äußere div --}}
<div id="profile-overlay" class="hidden fixed inset-0 z-[200] flex items-center justify-center"
     style="background:transparent;pointer-events:none;"
     onclick="if(event.target===this) closeProfileOverlay()">

    <div class="w-full max-w-[520px] relative mx-4 font-mono"
         style="background:#111111;border:1px solid #2a2a2a;pointer-events:all;
                box-shadow:0 0 60px rgba(245,183,0,0.25), 0 0 120px rgba(245,183,0,0.12);">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #2a2a2a;">
            <div>
                <div class="text-[9px] tracking-[2px]" style="color:#454745;">OPERATOR_PROFILE // ACCOUNT</div>
                <div class="text-[16px] font-sans font-bold tracking-wider mt-0.5" style="color:#e8e8e8;">
                    {{ strtoupper(Auth::user()->name) }}
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[9px] tracking-[2px] px-2 py-1 border font-sans font-bold"
                      style="border-color:#DC2626;color:#DC2626;">
                    {{ strtoupper(Auth::user()->role) }}
                </span>
                <button onclick="closeProfileOverlay()"
                        class="w-7 h-7 flex items-center justify-center transition-colors"
                        style="color:#454745;"
                        onmouseover="this.style.color='#DC2626'"
                        onmouseout="this.style.color='#454745'">
                    <x-icons.close class="w-4 h-4" />
                </button>
            </div>
        </div>

        {{-- Avatar + Meta --}}
        <div class="flex items-center gap-4 px-6 py-4" style="border-bottom:1px solid #2a2a2a;">
            <div class="w-[64px] h-[64px] flex-shrink-0 flex items-center justify-center relative"
                 style="background:#1a1a1a;border:1px solid #2a2a2a;">
                @if(Auth::user()->avatar_path)
                    <img src="{{ asset(Auth::user()->avatar_path) }}" class="w-full h-full object-cover">
                @else
                    <x-icons.profile class="w-8 h-8" style="color:#454745;" />
                @endif
                <div class="absolute -bottom-2 left-0 right-0 text-center">
                    <span class="text-[7px] tracking-[1.5px] px-1 py-0.5"
                          style="background:#DC2626;color:white;">
                        ID:{{ Auth::user()->id }}
                    </span>
                </div>
            </div>
            <div class="flex-1 grid grid-cols-2 gap-x-4 gap-y-1">
                <div>
                    <div class="text-[8px] tracking-[1.5px] mb-0.5" style="color:#454745;">EMAIL_ADDRESS</div>
                    <div class="text-[11px] tracking-wider" style="color:#A1A1AA;">{{ Auth::user()->email }}</div>
                </div>
                <div>
                    <div class="text-[8px] tracking-[1.5px] mb-0.5" style="color:#454745;">JOIN_DATE</div>
                    <div class="text-[11px] tracking-wider" style="color:#A1A1AA;">
                        {{ Auth::user()->created_at->format('d.m.Y') }}
                    </div>
                </div>
                @if(Auth::user()->region)
                    <div>
                        <div class="text-[8px] tracking-[1.5px] mb-0.5" style="color:#454745;">REGION</div>
                        <div class="text-[11px] tracking-wider" style="color:#A1A1AA;">{{ Auth::user()->region }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Edit Form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="px-6 py-4">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <div class="text-[9px] tracking-[1.5px] mb-1.5" style="color:#454745;">USERNAME</div>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                           class="w-full px-3 py-2 text-[11px] tracking-wider focus:outline-none transition-colors"
                           style="background:#1a1a1a;border:1px solid #333333;color:#e8e8e8;">
                </div>
                <div>
                    <div class="text-[9px] tracking-[1.5px] mb-1.5" style="color:#454745;">EMAIL_ADDRESS</div>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                           class="w-full px-3 py-2 text-[11px] tracking-wider focus:outline-none transition-colors"
                           style="background:#1a1a1a;border:1px solid #333333;color:#e8e8e8;">
                </div>
            </div>

            @if($errors->any())
                <div class="mb-3 text-[10px] tracking-wider" style="color:#DC2626;">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('status') === 'profile-updated')
                <div class="mb-3 text-[10px] tracking-wider" style="color:#F5B700;">
                    ✓ PROFIL_AKTUALISIERT
                </div>
            @endif

            <div class="flex items-center justify-between mt-2">
                {{-- Settings Link — nur für Merchant --}}
                @if(in_array(Auth::user()->role, ['merchant','agency','admin']))
                    <a href="{{ route('settings') }}"
                       class="flex items-center gap-2 text-[10px] tracking-[1.5px] transition-colors"
                       style="color:#A1A1AA;"
                       onmouseover="this.style.color='#F5B700'"
                       onmouseout="this.style.color='#A1A1AA'"
                       onclick="closeProfileOverlay()">
                        <x-icons.controls class="w-3.5 h-3.5" />
                        EINSTELLUNGEN
                    </a>
                @else
                    <div></div>
                @endif

                <div class="flex items-center gap-2">
                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 text-[10px] tracking-[1.5px] transition-colors"
                                style="border:1px solid #2a2a2a;color:#A1A1AA;background:transparent;"
                                onmouseover="this.style.borderColor='#DC2626';this.style.color='#DC2626'"
                                onmouseout="this.style.borderColor='#2a2a2a';this.style.color='#A1A1AA'">
                            <x-icons.logout class="w-3.5 h-3.5" />
                            LOGOUT
                        </button>
                    </form>
                    {{-- Save --}}
                    <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 text-[10px] tracking-[1.5px] font-sans font-bold transition-colors"
                            style="background:#DC2626;color:white;"
                            onmouseover="this.style.background='#FF535B'"
                            onmouseout="this.style.background='#DC2626'">
                        <x-icons.checked class="w-3.5 h-3.5" />
                        SPEICHERN
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    function openProfileOverlay() {
        document.getElementById('profile-overlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeProfileOverlay() {
        document.getElementById('profile-overlay').classList.add('hidden');
        document.body.style.overflow = '';
    }
    // ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeProfileOverlay();
    });
    // Auto-open if profile was just updated (zeigt Feedback)
    @if(session('status') === 'profile-updated')
    document.addEventListener('DOMContentLoaded', () => openProfileOverlay());
    @endif
</script>
