{{-- PLACEHOLDER SECTIONS — darstellung / datenschutz / hilfe --}}
@foreach(['darstellung'=>'DARSTELLUNG', 'datenschutz'=>'DATENSCHUTZ', 'hilfe'=>'INFO / HILFE'] as $id => $title)
    <div id="section-{{ $id }}" class="settings-section hidden">
        <div class="text-[11px] tracking-[3px] text-copy-neutral mb-6">{{ $title }}</div>
        <div class="text-[11px] tracking-wider text-copy-ticker">COMING_SOON // V2</div>
    </div>
@endforeach
