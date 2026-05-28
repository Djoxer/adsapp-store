{{-- SECTION: DELETE ACCOUNT --}}
<div id="section-konto-loeschen" class="settings-section hidden">
    <div class="text-[11px] tracking-[3px] text-brand-red mb-6">KONTO_LÖSCHEN</div>
    <div class="text-[11px] tracking-wider text-copy-neutral mb-6 leading-relaxed max-w-md">
        Once your account is deleted, all resources and data will be permanently removed.
        This action cannot be undone.
    </div>
    <form method="POST" action="{{ route('settings.account.delete') }}">
        @csrf @method('DELETE')
        <button type="submit"
                onclick="return confirm('CONFIRM_DELETE: Diese Aktion kann nicht rückgängig gemacht werden.')"
                class="px-6 py-3 text-[11px] tracking-[2px] font-sans font-bold transition-colors"
                style="border:2px solid #DC2626;color:#DC2626;background:transparent;"
                onmouseover="this.style.background='#DC2626';this.style.color='white'"
                onmouseout="this.style.background='transparent';this.style.color='#DC2626'">
            KONTO_LÖSCHEN
        </button>
    </form>
</div>
