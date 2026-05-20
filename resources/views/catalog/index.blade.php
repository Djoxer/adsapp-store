<x-buyer-app-layout>

<x-catalog.filter-bar />

<div class="flex">

    {{-- ═══ MAIN FEED ═══ --}}
    <div class="flex-1 p-4 space-y-4 min-w-0">

        {{-- PREMIUM SLOTS --}}
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                ['id'=>1,'title'=>'NEURAL-LINK CORE V1', 'price'=>'€245,00','label'=>'SPONSORED_AD_01'],
                ['id'=>2,'title'=>'ATMOS ZENITH HUB',    'price'=>'€189,00','label'=>'SPONSORED_AD_02'],
                ['id'=>3,'title'=>'TERRA-FORM PRO ELITE','price'=>'€399,00','label'=>'SPONSORED_AD_03'],
            ] as $slot)
                <x-catalog.premium-slot :item="$slot" />
            @endforeach
        </div>

        {{-- ORGANIC AD GRID --}}
        <div class="grid grid-cols-3 gap-3">
            <x-catalog.ad-card size="featured" :ad="[
                'id'=>101,'title'=>'HYPERION GT-X','price'=>'€245.000,00',
                'rank'=>1,'score'=>'99.9','merchant'=>'HYPERION_CORP',
                'description'=>'Das ultimative Gaming-System. KI-gestütztes Display, 8K, 360Hz.'
            ]"/>
            <x-catalog.ad-card size="medium" :ad="[
                'id'=>102,'title'=>'VISION-CORE CURVED','price'=>'€1.249,00 + €80,00 EXP',
                'rank'=>2,'score'=>'87.4','merchant'=>'VISION_TECH',
                'description'=>'34-Zoll Curved OLED. 0.1ms Response-Time.'
            ]"/>
            <x-catalog.ad-card size="medium" :ad="[
                'id'=>103,'title'=>'TERRA-FORM PRO','price'=>'€245,00',
                'rank'=>3,'score'=>'82.1','merchant'=>'TERRA_CORP',
                'description'=>'Ergonomischer Schreibtisch, automatische Höhenverstellung.'
            ]"/>
            <x-catalog.ad-card size="medium" :ad="[
                'id'=>104,'title'=>'SYNTH PROTEIN','price'=>'€89,00',
                'rank'=>4,'score'=>'71.3','merchant'=>'BIO_LABS',
                'description'=>'40g Protein pro Serving.'
            ]"/>
            <x-catalog.ad-card size="small" :ad="[
                'id'=>105,'title'=>'SHADOW-FLYER Mk.II','price'=>'€850,00',
                'rank'=>5,'score'=>'68.9','merchant'=>'DRONE_TECH',
                'description'=>'4K-Kamera, 45min Flugzeit.'
            ]"/>
        </div>

        {{-- HOTSPOT --}}
        <x-catalog.hotspot-banner :hotspot="[
            'id'=>200,'title'=>'ATMOS CONNECTIVITY SUITE','price'=>'€299,00',
            'score'=>'95.0','merchant'=>'ATMOS_CORP',
            'headline'=>'EXPLORE THE <span style=\'color:#DC2626;\'>CORE</span> NETWORK',
            'description'=>'Der Zenith Atmos Hub verbindet alle deine Implantate in Echtzeit. 24H Signal-Boost Garantie.',
            'label'=>'HEUTE_HEISS // HOTSPOT'
        ]"/>

        {{-- MORE ADS --}}
        <div class="grid grid-cols-4 gap-3">
            @foreach([
                ['id'=>106,'title'=>'NIGHT-PROWL X',  'price'=>'€850,00','rank'=>6,'score'=>'64.2','merchant'=>'PROWL_INC','description'=>''],
                ['id'=>107,'title'=>'NEXUS CTRL V2',  'price'=>'€129,00','rank'=>7,'score'=>'61.8','merchant'=>'NEXUS_TECH','description'=>''],
                ['id'=>108,'title'=>'VOID HEADSET',   'price'=>'€199,00','rank'=>8,'score'=>'58.3','merchant'=>'VOID_AUDIO','description'=>''],
                ['id'=>109,'title'=>'CORE_SWITCH PRO','price'=>'€449,00','rank'=>9,'score'=>'55.1','merchant'=>'CORE_SYS','description'=>''],
            ] as $ad)
                <x-catalog.ad-card size="mini" :ad="$ad" />
            @endforeach
        </div>

        {{-- INFINITE SCROLL TRIGGER --}}
        <div id="infinite-scroll-trigger" class="py-10 flex items-center justify-center">
            <div class="flex items-center gap-2 text-[9px] tracking-[2px]" style="color:#454745;">
                <span class="live-dot w-1.5 h-1.5 rounded-full inline-block" style="background:#454745;"></span>
                LOADING_MORE_DATA...
            </div>
        </div>

    </div>

    {{-- RIGHT PANEL --}}
    <x-catalog.right-panel :picks="[
        ['id'=>301,'title'=>'NEXUS CONTROLLER S','desc'=>'Ultimative Kontrolle für Drohnenschwärme.', 'price'=>'€499,00','exp'=>'+ €5,00 EXP'],
        ['id'=>302,'title'=>'BIO-GEL PACK',      'desc'=>'Regeneration in Rekordzeit.',              'price'=>'€89,00', 'exp'=>'+ €0,00 EXP'],
        ['id'=>303,'title'=>'GHOST SHELL PARKA', 'desc'=>'Unsichtbarkeit im urbanen Dschungel.',     'price'=>'€1.250,00','exp'=>'+ FREE_EXP'],
        ['id'=>304,'title'=>'PLACEHOLDER_AD_03', 'desc'=>'',                                          'price'=>'—',      'exp'=>''],
    ]"/>

</div>

</x-buyer-app-layout>
