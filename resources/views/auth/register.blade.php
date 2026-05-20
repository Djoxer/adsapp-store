<x-guest-layout title="REGISTER" subtitle="NEUEN ZUGANG ERSTELLEN">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4">

            <div>
                <x-input-label for="name" value="OPERATOR NAME" />
                <x-text-input id="name" type="text" name="name"
                              :value="old('name')" placeholder="MAX_MUSTERMANN"
                              required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-[10px] tracking-wider text-brand-red" />
            </div>

            <div>
                <x-input-label for="email" value="OPERATOR ID" />
                <x-text-input id="email" type="email" name="email"
                              :value="old('email')" placeholder="ID@EMAIL.COM"
                              required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] tracking-wider text-brand-red" />
            </div>

            <div>
                <x-input-label for="password" value="ZUGRIFFSCODE" />
                <x-text-input id="password" type="password" name="password"
                              placeholder="••••••••"
                              required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px] tracking-wider text-brand-red" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="BESTÄTIGEN" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                              placeholder="••••••••"
                              required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[10px] tracking-wider text-brand-red" />
            </div>

        </div>

        {{-- ROLE --}}
        <div class="mt-4">
            <x-input-label value="ZUGANGS-TYP" />
            <div class="grid grid-cols-3 gap-2 mt-2">
                @foreach(['buyer' => 'KÄUFER', 'merchant' => 'HÄNDLER', 'agency' => 'AGENTUR'] as $value => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="{{ $value }}"
                               {{ old('role', 'buyer') === $value ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="border border-line-warm text-center py-2.5 text-[10px] tracking-[1.5px] text-copy-neutral
                                peer-checked:border-brand-yellow peer-checked:text-brand-yellow peer-checked:bg-brand-yellow/5
                                hover:border-brand-yellow/50 hover:text-copy-soft transition-colors">
                            {{ $label }}
                        </div>
                    </label>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2 text-[10px] tracking-wider text-brand-red" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}"
               class="text-[10px] tracking-[1.5px] text-copy-ticker hover:text-brand-yellow transition-colors">
                BEREITS REGISTRIERT?
            </a>
            <x-primary-button>REGISTER</x-primary-button>
        </div>

    </form>
</x-guest-layout>
