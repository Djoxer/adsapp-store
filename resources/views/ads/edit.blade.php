<x-app-layout>
    <div class="p-6 space-y-5">

        {{-- HEADER --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('ads.index') }}"
               class="w-8 h-8 flex items-center justify-center transition-colors"
               style="border:1px solid #5B403F;color:#A1A1AA;"
               onmouseover="this.style.borderColor='#F5B700';this.style.color='#F5B700'"
               onmouseout="this.style.borderColor='#5B403F';this.style.color='#A1A1AA'">
                <x-icons.path class="w-4 h-4" style="transform:rotate(180deg)" />
            </a>
            <div>
                <div class="text-[9px] tracking-[3px] text-copy-ticker mb-1">MERCHANT_CONSOLE // AD_MANAGEMENT</div>
                <div class="text-[18px] font-sans font-bold text-copy-soft tracking-wider">AD BEARBEITEN</div>
            </div>
        </div>

        <form method="POST" action="{{ route('ads.update', $ad->id) }}" enctype="multipart/form-data">
            @csrf @method('PATCH')

            <div class="grid grid-cols-3 gap-5">

                {{-- LEFT — Hauptdaten --}}
                <div class="col-span-2 space-y-4">

                    {{-- Titel --}}
                    <div style="background:#271717;border:1px solid #5B403F;" class="p-5">
                        <div class="text-[9px] tracking-[3px] text-copy-ticker mb-4">AD_GRUNDDATEN</div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-[9px] tracking-[2px] text-copy-neutral mb-1.5">AD_TITEL *</label>
                                <input type="text" name="title" value="{{ old('title', $ad->title) }}"
                                       placeholder="Z.B. GAMING CHAIR PRO 2024"
                                       class="w-full px-3 py-2.5 text-[11px] tracking-wider focus:outline-none transition-colors"
                                       style="background:#1a0f0f;border:1px solid #5B403F;color:#e8e8e8;">
                                @error('title')
                                <div class="mt-1 text-[10px] tracking-wider" style="color:#DC2626;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[9px] tracking-[2px] text-copy-neutral mb-1.5">BESCHREIBUNG *</label>
                                <textarea name="description" rows="4"
                                          placeholder="PRODUKTBESCHREIBUNG — PRÄZISE UND INFORMATIV..."
                                          class="w-full px-3 py-2.5 text-[11px] tracking-wider focus:outline-none transition-colors resize-none"
                                          style="background:#1a0f0f;border:1px solid #5B403F;color:#e8e8e8;">{{ old('description', $ad->description) }}</textarea>
                                @error('description')
                                <div class="mt-1 text-[10px] tracking-wider" style="color:#DC2626;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[9px] tracking-[2px] text-copy-neutral mb-1.5">PREIS (CENT) *</label>
                                    <div class="relative">
                                        <span class="absolute left-3 inset-y-0 flex items-center text-[11px]" style="color:#F5B700;">€</span>
                                        <input type="number" name="price_cents" value="{{ old('price_cents', $ad->price_cents) }}"
                                               placeholder="24900"
                                               class="w-full pl-7 pr-3 py-2.5 text-[11px] tracking-wider focus:outline-none transition-colors"
                                               style="background:#1a0f0f;border:1px solid #5B403F;color:#e8e8e8;">
                                    </div>
                                    <div class="text-[9px] mt-1" style="color:#454745;">EINGABE IN CENT — 24900 = €249,00</div>
                                    @error('price_cents')
                                    <div class="mt-1 text-[10px] tracking-wider" style="color:#DC2626;">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[9px] tracking-[2px] text-copy-neutral mb-1.5">KATEGORIE *</label>
                                    <select name="category_id"
                                            class="w-full px-3 py-2.5 text-[11px] tracking-wider focus:outline-none transition-colors"
                                            style="background:#1a0f0f;border:1px solid #5B403F;color:#e8e8e8;">
                                        <option value="">— WÄHLEN —</option>
                                        @foreach($categories ?? [] as $cat)
                                            <option value="{{ $cat->id }}" {{ (string) old('category_id', $ad->category_id) === (string) $cat->id ? 'selected' : '' }}>
                                                {{ strtoupper($cat->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="mt-1 text-[10px] tracking-wider" style="color:#DC2626;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] tracking-[2px] text-copy-neutral mb-1.5">DEEPLINK_URL *</label>
                                <input type="url" name="deeplink_url" value="{{ old('deeplink_url', $ad->deeplink_url) }}"
                                       placeholder="HTTPS://DEIN-SHOP.DE/PRODUKT/..."
                                       class="w-full px-3 py-2.5 text-[11px] tracking-wider focus:outline-none transition-colors"
                                       style="background:#1a0f0f;border:1px solid #5B403F;color:#e8e8e8;">
                                @error('deeplink_url')
                                <div class="mt-1 text-[10px] tracking-wider" style="color:#DC2626;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div style="background:#271717;border:1px solid #5B403F;" class="p-5">
                        <div class="text-[9px] tracking-[3px] text-copy-ticker mb-4">TAGS // OPTIONAL</div>
                        <input type="text" name="tags" value="{{ old('tags', $ad->tags->pluck('name')->implode(', ')) }}"
                               placeholder="GAMING, CHAIR, ERGONOMIC, HOME_OFFICE..."
                               class="w-full px-3 py-2.5 text-[11px] tracking-wider focus:outline-none transition-colors"
                               style="background:#1a0f0f;border:1px solid #5B403F;color:#e8e8e8;">
                        <div class="text-[9px] mt-1.5" style="color:#454745;">KOMMA-GETRENNT — VERBESSERT AUFFINDBARKEIT IM CATALOG</div>
                    </div>

                </div>

                {{-- RIGHT — Bild + Status --}}
                <div class="space-y-4">

                    {{-- Image Upload --}}
                    <div style="background:#271717;border:1px solid #5B403F;" class="p-5">
                        <div class="text-[9px] tracking-[3px] text-copy-ticker mb-4">AD_BILD</div>

                        @php $currentImage = $ad->images->first()?->cache_path; @endphp

                        {{-- Drop-Zone (versteckt wenn schon ein Bild existiert) --}}
                        <div id="drop-zone"
                             class="aspect-square flex-col items-center justify-center gap-3 cursor-pointer transition-colors {{ $currentImage ? 'hidden' : 'flex' }}"
                             style="background:#1a0f0f;border:2px dashed #5B403F;"
                             onmouseover="this.style.borderColor='#F5B700'"
                             onmouseout="this.style.borderColor='#5B403F'"
                             onclick="document.getElementById('image-input').click()">
                            <x-icons.photo class="w-8 h-8" style="color:#5B403F;" />
                            <div class="text-[9px] tracking-[2px] text-center" style="color:#454745;">
                                KLICKEN ODER<br>DRAG & DROP
                            </div>
                            <div class="text-[8px] tracking-[1px]" style="color:#2a2a2a;">JPG / PNG / WEBP — MAX 2MB</div>
                        </div>

                        <input type="file" id="image-input" name="image" accept="image/*" class="hidden"
                               onchange="previewImage(this)">

                        {{-- Preview: bestehendes Bild oder neu gewähltes --}}
                        <div id="image-preview" class="{{ $currentImage ? '' : 'hidden' }} mt-0">
                            <img id="preview-img"
                                 src="{{ $currentImage ? asset('storage/' . $currentImage) : '' }}"
                                 class="w-full aspect-square object-cover" style="border:1px solid #5B403F;">
                            <button type="button" onclick="clearImage()"
                                    class="mt-2 w-full text-[9px] tracking-[1.5px] py-1.5 transition-colors"
                                    style="border:1px solid #5B403F;color:#A1A1AA;"
                                    onmouseover="this.style.color='#DC2626';this.style.borderColor='#DC2626'"
                                    onmouseout="this.style.color='#A1A1AA';this.style.borderColor='#5B403F'">
                                BILD ERSETZEN
                            </button>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div style="background:#271717;border:1px solid #5B403F;" class="p-5">
                        <div class="text-[9px] tracking-[3px] text-copy-ticker mb-4">STATUS</div>
                        <div class="space-y-2">
                            @php $currentStatus = old('status', $ad->status); @endphp
                            @foreach(['active'=>'AKTIV SCHALTEN','paused'=>'PAUSIERT','draft'=>'ALS ENTWURF'] as $val => $label)
                                <label class="flex items-center gap-3 cursor-pointer status-option" data-value="{{ $val }}">
                                    <input type="radio" name="status" value="{{ $val }}"
                                           {{ $currentStatus === $val ? 'checked' : '' }}
                                           class="sr-only" onchange="updateStatusRadios()">
                                    <div class="status-dot w-4 h-4 border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                         style="border-color:{{ $currentStatus === $val ? '#F5B700' : '#5B403F' }};">
                                        <div class="status-fill w-2 h-2 {{ $currentStatus === $val ? '' : 'hidden' }}" style="background:#F5B700;"></div>
                                    </div>
                                    <div class="status-label text-[10px] tracking-[1.5px] transition-colors"
                                         style="color:{{ $currentStatus === $val ? '#F5B700' : '#A1A1AA' }};">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-3 text-[12px] tracking-[2px] font-sans font-bold transition-colors"
                            style="background:#DC2626;color:white;"
                            onmouseover="this.style.background='#FF535B'"
                            onmouseout="this.style.background='#DC2626'">
                        AD SPEICHERN →
                    </button>

                    <a href="{{ route('ads.index') }}"
                       class="block w-full py-2.5 text-[10px] tracking-[2px] text-center transition-colors"
                       style="border:1px solid #5B403F;color:#A1A1AA;"
                       onmouseover="this.style.borderColor='#A1A1AA'"
                       onmouseout="this.style.borderColor='#5B403F'">
                        ABBRECHEN
                    </a>

                </div>
            </div>

        </form>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('drop-zone').classList.add('hidden');
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function clearImage() {
            // Bild-Auswahl zurücksetzen → wieder Drop-Zone zeigen.
            // Hinweis: das entfernt nur die NEUE Auswahl. Ein bereits gespeichertes
            // Bild bleibt in der DB bis ein neues hochgeladen wird.
            document.getElementById('image-input').value = '';
            document.getElementById('drop-zone').classList.remove('hidden');
            document.getElementById('image-preview').classList.add('hidden');
        }

        // Radio-Optik: gefüllter Punkt + Farbe für die gewählte Option
        function updateStatusRadios() {
            document.querySelectorAll('.status-option').forEach(opt => {
                const checked = opt.querySelector('input[type=radio]').checked;
                opt.querySelector('.status-dot').style.borderColor   = checked ? '#F5B700' : '#5B403F';
                opt.querySelector('.status-fill').classList.toggle('hidden', !checked);
                opt.querySelector('.status-label').style.color       = checked ? '#F5B700' : '#A1A1AA';
            });
        }
    </script>

</x-app-layout>
