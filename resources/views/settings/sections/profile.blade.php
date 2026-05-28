{{-- SECTION: PROFILE --}}
<div id="section-profil" class="settings-section">
    <div class="text-[11px] tracking-[3px] text-copy-neutral mb-6">PROFIL_DATEN</div>

    <form method="POST" action="{{ route('settings.profile') }}">
        @csrf @method('PATCH')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <x-input-label value="USERNAME" />
                <x-text-input name="name" type="text" :value="old('name', Auth::user()->name)"
                              placeholder="OPERATOR_01" required />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-[10px] text-brand-red" />
            </div>
            <div>
                <x-input-label value="EMAIL_ADDRESS" />
                <x-text-input name="email" type="email" :value="old('email', Auth::user()->email)"
                              placeholder="operator@adsapp.store" required />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] text-brand-red" />
            </div>
            <div>
                <x-input-label value="REGION_ZONE" />
                <select name="region"
                        class="w-full bg-ink-surface border border-line-warm text-copy-soft text-[11px] tracking-wider px-3 py-2 focus:outline-none focus:border-brand-yellow">
                    @foreach(['DACH_REGION (DE/AT/CH)','EU_WEST','EU_EAST','GLOBAL'] as $r)
                        <option value="{{ $r }}" {{ Auth::user()->region === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label value="ZIP_CODE_IO" />
                <x-text-input name="zip_code" type="text" :value="old('zip_code', Auth::user()->zip_code)"
                              placeholder="XXXXX" />
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>SPEICHERN</x-primary-button>
        </div>
    </form>
</div>
