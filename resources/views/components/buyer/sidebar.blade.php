<style>
    #buyer-sidebar {
        width: var(--sidebar-collapsed);
        transition: width 0.28s cubic-bezier(0.4,0,0.2,1);
        position: fixed;
        top: var(--topbar-h); left: 0; bottom: var(--ticker-h);
        z-index: 100;
        background: #0a0a0a;
        border-right: 1px solid #1e1e1e;
        display: flex; flex-direction: column; overflow: hidden;
    }
    #buyer-sidebar.expanded { width: var(--sidebar-expanded); }

    .nav-label {
        opacity: 0; width: 0; overflow: hidden; white-space: nowrap;
        transition: opacity 0.18s ease 0.05s, width 0.28s cubic-bezier(0.4,0,0.2,1);
        font-size: 10px; letter-spacing: 1.5px;
    }
    #buyer-sidebar.expanded .nav-label { opacity: 1; width: auto; }

    .nav-item {
        display: flex; align-items: center;
        padding: 10px 0; justify-content: center;
        transition: background 0.15s, color 0.15s;
        border-left: 2px solid transparent;
        cursor: pointer; text-decoration: none; color: #555555;
    }
    .nav-item:hover  { color: #F5B700 !important; background: #141414; }
    .nav-item.active { color: #F5B700 !important; background: #1a1200; border-left-color: #F5B700; }
    #buyer-sidebar.expanded .nav-item { padding-left: 18px; justify-content: flex-start; gap: 12px; }
    .nav-item .nav-icon { width: 24px; text-align: center; flex-shrink: 0; font-size: 15px; line-height: 1; }
</style>

<aside id="buyer-sidebar">

    {{-- Toggle --}}
    <button id="sidebar-toggle"
            class="h-[44px] flex items-center justify-center border-b w-full transition-colors flex-shrink-0"
            style="border-color:#1e1e1e; color:#555555;"
            onmouseover="this.style.color='#DC2626'" onmouseout="this.style.color='#555555'">
        <span style="font-size:15px;">☰</span>
    </button>

    {{-- Nav --}}
    <nav class="flex-1 py-2 overflow-y-auto overflow-x-hidden">
        @php
            $buyerNav = [
                ['icon'=>'raster',   'label'=>'KATALOG',   'route'=>'catalog'],
                ['icon'=>'rank',     'label'=>'RANKING',   'route'=>'catalog.ranking'],
                ['icon'=>'hot',      'label'=>'HOTSPOTS',  'route'=>'catalog.hotspots'],
                ['icon'=>'analyze',  'label'=>'ANALYTICS', 'route'=>'catalog.analytics'],
                ['icon'=>'bookmark', 'label'=>'MERKLISTE', 'route'=>'bookmarks.index'],
            ];
        @endphp
        @foreach($buyerNav as $item)
            <a href="{{ route($item['route']) }}"
               class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
        <span class="nav-icon">
            <x-dynamic-component :component="'icons.' . $item['icon']" class="w-5 h-5" />
        </span>
                <span class="nav-label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

</aside>

<script>
    (function() {
        const sidebar = document.getElementById('buyer-sidebar');
        const wrap    = document.getElementById('catalog-wrap');
        const toggle  = document.getElementById('sidebar-toggle');

        if (toggle && sidebar) {
            toggle.addEventListener('click', () => {
                const open = sidebar.classList.toggle('expanded');
                if (wrap) {
                    wrap.style.left = open ? 'var(--sidebar-expanded)' : 'var(--sidebar-collapsed)';
                }
            });
        }
    })();
</script>
