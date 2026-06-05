<x-admin-layout>
    <x-slot name="header">USER_VERWALTUNG</x-slot>

    {{-- Feedback --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-2 text-[10px] tracking-[2px]" style="background:rgba(67,214,133,0.1);border:1px solid #43d685;color:#43d685;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-2 text-[10px] tracking-[2px]" style="background:rgba(220,38,38,0.1);border:1px solid #DC2626;color:#DC2626;">
            ✗ {{ session('error') }}
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-3 mb-6">
        <input type="text" name="q" value="{{ $search }}"
               placeholder="NAME / EMAIL SUCHEN..."
               class="px-3 py-2 text-[11px] tracking-wider focus:outline-none"
               style="background:#0d1526;border:1px solid #1e3050;color:#c8d8e8;width:280px;">
        <select name="role"
                class="px-3 py-2 text-[11px] tracking-wider focus:outline-none"
                style="background:#0d1526;border:1px solid #1e3050;color:#c8d8e8;">
            <option value="">ALLE ROLLEN</option>
            <option value="buyer"   {{ $role === 'buyer'   ? 'selected' : '' }}>BUYER</option>
            <option value="merchant"{{ $role === 'merchant'? 'selected' : '' }}>MERCHANT</option>
            <option value="agency"  {{ $role === 'agency'  ? 'selected' : '' }}>AGENCY</option>
            <option value="admin"   {{ $role === 'admin'   ? 'selected' : '' }}>ADMIN</option>
        </select>
        <button type="submit"
                class="px-4 py-2 text-[10px] tracking-[2px] font-sans font-bold"
                style="background:#4fc3f7;color:#0d1526;">
            FILTERN
        </button>
        @if($search || $role)
            <a href="{{ route('admin.users') }}"
               class="px-4 py-2 text-[10px] tracking-[2px]"
               style="border:1px solid #1e3050;color:#5a7a9a;">
                RESET
            </a>
        @endif
    </form>

    {{-- Tabelle --}}
    <div style="background:#111a2b;border:1px solid #1e3050;">
        <div class="px-5 py-3" style="border-bottom:1px solid #1e3050;">
            <span class="text-[11px] font-sans font-bold tracking-[1.5px]" style="color:#e8f4ff;">
                USER_LIST · {{ $users->total() }} EINTRÄGE
            </span>
        </div>

        <table class="w-full">
            <thead>
            <tr style="border-bottom:1px solid #1e3050;">
                <th class="px-5 py-3 text-left text-[9px] tracking-[2px]" style="color:#5a7a9a;">ID</th>
                <th class="px-5 py-3 text-left text-[9px] tracking-[2px]" style="color:#5a7a9a;">NAME / EMAIL</th>
                <th class="px-5 py-3 text-left text-[9px] tracking-[2px]" style="color:#5a7a9a;">ROLLE</th>
                <th class="px-5 py-3 text-left text-[9px] tracking-[2px]" style="color:#5a7a9a;">MERCHANT</th>
                <th class="px-5 py-3 text-left text-[9px] tracking-[2px]" style="color:#5a7a9a;">STATUS</th>
                <th class="px-5 py-3 text-left text-[9px] tracking-[2px]" style="color:#5a7a9a;">REGISTRIERT</th>
                <th class="px-5 py-3 text-left text-[9px] tracking-[2px]" style="color:#5a7a9a;">AKTIONEN</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $u)
                <tr style="border-bottom:1px solid #16243a;{{ $u->is_banned ? 'opacity:0.5;' : '' }}">
                    <td class="px-5 py-3 text-[9px]" style="color:#5a7a9a;">{{ $u->id }}</td>
                    <td class="px-5 py-3">
                        <div class="text-[11px] tracking-wider" style="color:#c8d8e8;">{{ $u->name }}</div>
                        <div class="text-[9px] mt-0.5" style="color:#5a7a9a;">{{ $u->email }}</div>
                    </td>
                    <td class="px-5 py-3">
                        {{-- Rollen-Dropdown --}}
                        <form method="POST" action="{{ route('admin.users.role', $u) }}">
                            @csrf @method('PATCH')
                            <div class="flex items-center gap-2">
                                <select name="role"
                                        onchange="this.form.submit()"
                                        class="text-[10px] tracking-wider px-2 py-1 focus:outline-none"
                                        style="background:#0d1526;border:1px solid #1e3050;color:
                                                {{ $u->role === 'admin' ? '#4fc3f7' :
                                                  ($u->role === 'merchant' ? '#f5a623' :
                                                  ($u->role === 'agency' ? '#a78bfa' : '#5a7a9a')) }};">
                                    <option value="buyer"    {{ $u->role === 'buyer'    ? 'selected' : '' }}>BUYER</option>
                                    <option value="merchant" {{ $u->role === 'merchant' ? 'selected' : '' }}>MERCHANT</option>
                                    <option value="agency"   {{ $u->role === 'agency'   ? 'selected' : '' }}>AGENCY</option>
                                    <option value="admin"    {{ $u->role === 'admin'    ? 'selected' : '' }}>ADMIN</option>
                                </select>
                            </div>
                        </form>
                    </td>
                    <td class="px-5 py-3">
                        @if($u->merchant)
                            @php $ms = $u->merchant->approval_status; @endphp
                            <form method="POST" action="{{ route('admin.users.merchant', $u) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[9px] tracking-[1.5px] px-2 py-1 transition-colors"
                                        style="background:{{ $ms === 'approved' ? 'rgba(67,214,133,0.1)' : 'rgba(220,38,38,0.1)' }};
                                                   border:1px solid {{ $ms === 'approved' ? '#43d685' : '#DC2626' }};
                                                   color:{{ $ms === 'approved' ? '#43d685' : '#DC2626' }};">
                                    {{ strtoupper($ms) }}
                                </button>
                            </form>
                        @else
                            <span class="text-[9px] tracking-[1.5px]" style="color:#2a3a4a;">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($u->is_banned)
                            <span class="text-[9px] tracking-[1.5px] px-2 py-1"
                                  style="background:rgba(220,38,38,0.1);border:1px solid #DC2626;color:#DC2626;">
                                    GESPERRT
                                </span>
                        @else
                            <span class="text-[9px] tracking-[1.5px]" style="color:#43d685;">AKTIV</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-[9px]" style="color:#5a7a9a;">
                        {{ $u->created_at->format('d.m.Y') }}
                    </td>
                    <td class="px-5 py-3">
                        @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.ban', $u) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-[9px] tracking-[1.5px] px-3 py-1.5 transition-colors"
                                        style="border:1px solid {{ $u->is_banned ? '#43d685' : '#DC2626' }};
                                                   color:{{ $u->is_banned ? '#43d685' : '#DC2626' }};
                                                   background:transparent;"
                                        onmouseover="this.style.background='{{ $u->is_banned ? 'rgba(67,214,133,0.1)' : 'rgba(220,38,38,0.1)' }}'"
                                        onmouseout="this.style.background='transparent'">
                                    {{ $u->is_banned ? 'ENTSPERREN' : 'SPERREN' }}
                                </button>
                            </form>
                        @else
                            <span class="text-[9px]" style="color:#2a3a4a;">—</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-5 py-3 flex items-center gap-2" style="border-top:1px solid #1e3050;">
                @if($users->onFirstPage())
                    <span class="text-[9px] tracking-[1.5px] px-3 py-1" style="color:#2a3a4a;border:1px solid #1e3050;">← PREV</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}"
                       class="text-[9px] tracking-[1.5px] px-3 py-1 transition-colors"
                       style="border:1px solid #1e3050;color:#4fc3f7;"
                       onmouseover="this.style.borderColor='#4fc3f7'"
                       onmouseout="this.style.borderColor='#1e3050'">← PREV</a>
                @endif
                <span class="text-[9px] tracking-[1.5px]" style="color:#5a7a9a;">
                    {{ $users->currentPage() }} / {{ $users->lastPage() }}
                </span>
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}"
                       class="text-[9px] tracking-[1.5px] px-3 py-1 transition-colors"
                       style="border:1px solid #1e3050;color:#4fc3f7;"
                       onmouseover="this.style.borderColor='#4fc3f7'"
                       onmouseout="this.style.borderColor='#1e3050'">NEXT →</a>
                @else
                    <span class="text-[9px] tracking-[1.5px] px-3 py-1" style="color:#2a3a4a;border:1px solid #1e3050;">NEXT →</span>
                @endif
            </div>
        @endif
    </div>
</x-admin-layout>
