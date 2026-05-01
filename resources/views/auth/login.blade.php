<x-auth-layout>
    <div>
        <h1 class="text-3xl font-display font-black tracking-tight text-dark dark:text-white">Selamat Datang Kembali</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-bold text-dark dark:text-white hover:underline decoration-2 underline-offset-4">Daftar sekarang gratis</a>
        </p>
    </div>

    <div class="mt-8">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-form-label for="email" value="Alamat Email" />
                <x-form-input id="email" name="email" type="email" autocomplete="email" required 
                    :value="old('email')"
                    placeholder="nama@email.com"
                    :error="$errors->has('email')" />
                <x-form-error :messages="$errors->get('email')" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <div class="flex items-center justify-between">
                    <x-form-label for="password" value="Kata Sandi" />
                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-bold text-gray-500 hover:text-dark dark:hover:text-white transition-colors">Lupa kata sandi?</a>
                        </div>
                    @endif
                </div>
                <div class="mt-2 relative">
                    <x-form-input id="password" name="password" ::type="show ? 'text' : 'password'" autocomplete="current-password" required 
                        class="pr-12"
                        placeholder="••••••••"
                        :error="$errors->has('password')" />
                    
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-dark dark:hover:text-white transition-colors">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    </button>
                </div>
                <x-form-error :messages="$errors->get('password')" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" 
                    class="h-4 w-4 rounded border-gray-300 dark:border-borderdark text-dark dark:text-white focus:ring-dark dark:focus:ring-white bg-light dark:bg-card">
                <label for="remember_me" class="ml-3 block text-sm font-bold leading-6 text-gray-500 dark:text-gray-400">Ingat saya</label>
            </div>

            <div class="pt-2">
                <x-button class="w-full" size="lg">
                    Masuk ke Bloxpin
                </x-button>
            </div>
        </form>

    </div>
</x-auth-layout>
