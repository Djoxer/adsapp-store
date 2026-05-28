{{-- SECTION: NOTIFICATIONS --}}
<div id="section-benachricht" class="settings-section hidden">
    <div class="flex items-center justify-between mb-6">
        <div class="text-[11px] tracking-[3px] text-copy-neutral">LEAD_FEED // BENACHRICHTIGUNGEN</div>
        <div class="text-[9px] tracking-[1.5px] text-copy-ticker">KLICKS ZUM HÄNDLER // LETZTE 20</div>
    </div>

    @if($leads->isEmpty())
        <div class="text-[11px] tracking-wider text-copy-ticker py-8 text-center"
             style="border:1px dashed #2a1a1a;">
            NO_LEADS_YET // SOBALD KÄUFER AUF "ZUM HÄNDLER" KLICKEN, ERSCHEINEN SIE HIER
        </div>
    @else
        <div class="space-y-2 max-w-2xl">
            @php $seenAt = Auth::user()->notifications_seen_at ?? Auth::user()->created_at; @endphp
            @foreach($leads as $lead)
                @php $isNew = \Carbon\Carbon::parse($lead->created_at)->gt($seenAt); @endphp
                <div class="flex items-center gap-4 px-4 py-3 transition-colors"
                     style="background:{{ $isNew ? '#1a1200' : '#0f0a0a' }};
                            border:1px solid {{ $isNew ? 'rgba(245,183,0,0.3)' : '#2a1a1a' }};
                            border-left:2px solid {{ $isNew ? '#F5B700' : '#2a1a1a' }};">
                    <div class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                         style="background:{{ $isNew ? '#F5B700' : '#2a1a1a' }};"></div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[11px] tracking-wider text-copy-soft">
                            NEUER LEAD: <a href="{{ route('ads.show', $lead->ad_id) }}"
                                           class="text-brand-yellow hover:underline">{{ $lead->ad_title }}</a>
                        </div>
                        <div class="text-[9px] tracking-wider text-copy-ticker mt-0.5">
                            {{ $lead->buyer_email ?? 'ANONYMER_BESUCHER' }}
                        </div>
                    </div>
                    <div class="text-[9px] tracking-wider text-copy-ticker flex-shrink-0">
                        {{ \Carbon\Carbon::parse($lead->created_at)->format('d.m. H:i') }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
