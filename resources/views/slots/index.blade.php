<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="text-[9px] tracking-[3px] text-copy-ticker mb-1">MERCHANT_CONSOLE // PREMIUM_PLACEMENT</div>
                <div class="text-[18px] font-sans font-bold text-copy-soft tracking-wider">PREMIUM SLOTS</div>
            </div>
            <div class="text-[9px] tracking-[1.5px] text-copy-ticker">LAUFZEIT 1–7 TAGE // ADMIN-FREIGABE</div>
        </div>

        {{-- Flash --}}
        @if(session('status') === 'slot-booked')
            <div class="border border-brand-yellow bg-ink-panel px-4 py-3 text-[10px] tracking-[1.5px] text-brand-yellow">
                ✓ ANTRAG EINGEREICHT — DU STEHST IN DER WARTESCHLANGE. FREIGABE DURCH ADMIN.
            </div>
        @endif
        @error('slot')
        <div class="border border-brand-red bg-ink-panel px-4 py-3 text-[10px] tracking-[1.5px] text-brand-red">
            ⚠ {{ $message }}
        </div>
        @enderror

        {{-- ══ OBERE HÄLFTE: SLOT-ÜBERSICHT ══ --}}
        <div>
            <div class="text-[10px] tracking-[2px] text-copy-neutral mb-3">VERFÜGBARE SLOTS</div>

            {{-- ZONE A --}}
            <div class="mb-4">
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mb-2">ZONE A // TOP-STRIP // FIFO-QUEUE</div>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($slots->where('zone', 'A') as $slot)
                        @include('slots.partials.slot-card', ['slot' => $slot])
                    @endforeach
                </div>
            </div>

            {{-- ZONE B --}}
            <div>
                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mb-2">ZONE B // RIGHT-ASIDE // FIXED-PRICE</div>
                <div class="grid grid-cols-4 gap-3">
                    @foreach($slots->where('zone', 'B') as $slot)
                        @include('slots.partials.slot-card', ['slot' => $slot])
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══ UNTERE HÄLFTE: BUCHUNGSANTRAG ══ --}}
        <div class="grid grid-cols-3 gap-4">

            {{-- Formular --}}
            <div class="col-span-2 bg-ink-panel border border-line-warm p-5">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">BUCHUNGSANTRAG</div>

                @if($myAds->isEmpty())
                    <div class="text-[10px] tracking-wider text-copy-ticker py-6 text-center">
                        KEINE AKTIVEN ADS — ERST EINE AD ERSTELLEN ODER AKTIVIEREN.
                    </div>
                @else
                    <form method="POST" action="{{ route('slots.book') }}" class="space-y-4">
                        @csrf

                        <input type="hidden" name="premium_slot_id" id="selected-slot-id">

                        {{-- Gewählter Slot --}}
                        <div>
                            <label class="block text-[9px] tracking-[1.5px] text-copy-ticker mb-1.5">GEWÄHLTER SLOT</label>
                            <div id="slot-display" class="px-3 py-2.5 bg-ink-deep border border-line-warm text-[11px] tracking-wider text-copy-ticker">
                                ← SLOT OBEN AUSWÄHLEN
                            </div>
                        </div>

                        {{-- Ad-Auswahl --}}
                        <div>
                            <label class="block text-[9px] tracking-[1.5px] text-copy-ticker mb-1.5">AD AUSWÄHLEN</label>
                            <select name="ad_id" required
                                    class="w-full px-3 py-2.5 bg-ink-deep border border-line-warm text-[11px] tracking-wider text-copy-soft focus:outline-none focus:border-brand-yellow">
                                <option value="">— AD WÄHLEN —</option>
                                @foreach($myAds as $ad)
                                    <option value="{{ $ad->id }}">{{ $ad->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Laufzeit --}}
                        <div>
                            <label class="block text-[9px] tracking-[1.5px] text-copy-ticker mb-1.5">
                                LAUFZEIT: <span id="duration-label" class="text-brand-yellow">1 TAG</span>
                            </label>
                            <input type="range" name="duration_days" id="duration-slider"
                                   min="1" max="7" value="1"
                                   class="w-full accent-brand-yellow"
                                   oninput="updateCalc()">
                            <div class="flex justify-between text-[8px] text-copy-ticker mt-1">
                                @foreach(range(1,7) as $d)<span>{{ $d }}</span>@endforeach
                            </div>
                        </div>

                        {{-- Live-Preis + Queue-Vorschau --}}
                        <div class="grid grid-cols-2 gap-3 pt-2 border-t border-line-warm/40">
                            <div>
                                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mb-1">GESAMTPREIS</div>
                                <div id="total-price" class="text-2xl font-sans font-bold text-brand-yellow">—</div>
                                <div id="price-detail" class="text-[8px] tracking-wider text-copy-ticker mt-0.5"></div>
                            </div>
                            <div>
                                <div class="text-[9px] tracking-[1.5px] text-copy-ticker mb-1">QUEUE-POSITION</div>
                                <div id="queue-pos" class="text-2xl font-sans font-bold text-copy-soft">—</div>
                                <div class="text-[8px] tracking-wider text-copy-ticker mt-0.5">NACH FREIGABE</div>
                            </div>
                        </div>

                        <button type="submit" id="submit-btn" disabled
                                class="w-full py-3 text-[11px] tracking-[2px] font-sans font-bold transition-colors opacity-40 cursor-not-allowed"
                                style="background:#DC2626;color:white;">
                            ANTRAG EINREICHEN
                        </button>
                    </form>
                @endif
            </div>

            {{-- Meine Anträge --}}
            <div class="bg-ink-panel border border-line-warm p-5">
                <div class="text-[10px] tracking-[2px] text-copy-neutral mb-4">MEINE ANTRÄGE</div>
                @if($myBookings->isEmpty())
                    <div class="text-[9px] tracking-[1.5px] text-copy-ticker py-4">KEINE AKTIVEN ANTRÄGE</div>
                @else
                    <div class="space-y-2">
                        @foreach($myBookings as $b)
                            <div class="border-l-2 pl-3 py-1"
                                 style="border-color:{{ $b->status === 'live' ? '#F5B700' : ($b->status === 'approved' ? '#A1A1AA' : '#5B403F') }};">
                                <div class="text-[10px] tracking-wider text-copy-soft truncate">{{ $b->ad->title ?? '—' }}</div>
                                <div class="text-[8px] tracking-[1.5px] text-copy-ticker mt-0.5">
                                    ZONE {{ $b->slot->zone }}-{{ $b->slot->position }} ·
                                    {{ $b->duration_days }}T ·
                                    <span style="color:{{ $b->status === 'live' ? '#F5B700' : '#A1A1AA' }};">{{ strtoupper($b->status) }}</span>
                                    @if(in_array($b->status, ['pending','approved'])) · Q{{ $b->queue_position }}@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Slot-Daten + Interaktion --}}
    <script>
        const SLOTS = @json($slotsJs);
        let selectedSlot = null;

        function selectSlot(slotId) {
            selectedSlot = SLOTS.find(s => s.id === slotId);
            if (!selectedSlot) return;

            document.getElementById('selected-slot-id').value = selectedSlot.id;
            document.getElementById('slot-display').innerHTML =
                `ZONE ${selectedSlot.zone}-${selectedSlot.position} · ` +
                (selectedSlot.has_discount
                    ? `<span style="text-decoration:line-through;color:#5B403F;">${(selectedSlot.base_price/100).toFixed(2)}€</span> ` +
                    `<span style="color:#F5B700;">${(selectedSlot.price/100).toFixed(2)}€/TAG</span> (-${selectedSlot.discount_pct}%)`
                    : `<span style="color:#F5B700;">${(selectedSlot.price/100).toFixed(2)}€/TAG</span>`);
            document.getElementById('slot-display').style.color = '#e8e8e8';

            // Slot-Cards visuell markieren
            document.querySelectorAll('.slot-card').forEach(c => c.style.borderColor = '#5B403F');
            const card = document.querySelector(`[data-slot-card="${slotId}"]`);
            if (card) card.style.borderColor = '#F5B700';

            updateCalc();
        }

        function updateCalc() {
            const days = parseInt(document.getElementById('duration-slider').value);
            document.getElementById('duration-label').textContent = days + (days === 1 ? ' TAG' : ' TAGE');

            if (!selectedSlot) return;

            const total = selectedSlot.price * days;
            document.getElementById('total-price').textContent = (total / 100).toFixed(2) + '€';
            document.getElementById('price-detail').textContent =
                `${(selectedSlot.price/100).toFixed(2)}€ × ${days} TAGE`;

            // Queue-Position = aktuelle Länge + 1
            document.getElementById('queue-pos').textContent = '#' + (selectedSlot.queue_length + 1);

            // Submit aktivieren
            const btn = document.getElementById('submit-btn');
            btn.disabled = false;
            btn.classList.remove('opacity-40', 'cursor-not-allowed');
            btn.onmouseover = () => btn.style.background = '#FF535B';
            btn.onmouseout  = () => btn.style.background = '#DC2626';
        }
    </script>
</x-app-layout>
