<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="OPERATOR ID" />
            <x-text-input id="email" type="email" name="email"
                          :value="old('email')"
                          placeholder="OPERATOR_ID@EMAIL.COM"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] tracking-wider text-brand-red" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="ZUGRIFFSCODE" />
            <x-text-input id="password" type="password" name="password"
                          placeholder="••••••••"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px] tracking-wider text-brand-red" />
        </div>

        <div class="flex items-center justify-between mt-5 mb-6">
            <label class="flex items-center gap-2.5 text-[10px] tracking-[1.5px] text-copy-neutral cursor-pointer">
                <input type="checkbox" name="remember"
                       class="w-3.5 h-3.5 border border-line-warm bg-transparent checked:bg-brand-red hover:border-brand-yellow rounded-none cursor-pointer">
                ANGEMELDET BLEIBEN
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-[10px] tracking-[1.5px] text-brand-yellow-dim hover:text-brand-yellow transition-colors">
                    PASSWORT VERGESSEN?
                </a>
            @endif
        </div>

        <x-primary-button>INITIALIZE SESSION</x-primary-button>

        <div class="text-center mt-4 text-[10px] tracking-[1.5px] text-copy-neutral">
            KEIN ACCOUNT?
            <a href="{{ route('register') }}" class="text-brand-yellow-dim hover:text-brand-yellow transition-colors">
                ZUGANG BEANTRAGEN
            </a>
        </div>

    </form>
</x-guest-layout>
