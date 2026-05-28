{{-- SECTION: SECURITY --}}
<div id="section-sicherheit" class="settings-section hidden">
    <div class="text-[11px] tracking-[3px] text-copy-neutral mb-6">SICHERHEIT_SETTINGS</div>

    <form method="POST" action="{{ route('settings.password') }}">
        @csrf @method('PATCH')

        <div class="space-y-4 max-w-md">
            <div>
                <x-input-label value="AKTUELLES PASSWORT" />
                <x-text-input name="current_password" type="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required />
                <x-input-error :messages="$errors->get('current_password')" class="mt-1 text-[10px] text-brand-red" />
            </div>
            <div>
                <x-input-label value="NEUES PASSWORT" />
                <x-text-input name="password" type="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] text-brand-red" />
            </div>
            <div>
                <x-input-label value="BESTÄTIGEN" />
                <x-text-input name="password_confirmation" type="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required />
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>PASSWORT_AKTUALISIEREN</x-primary-button>
        </div>
    </form>
</div>
