<x-auth-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-display font-black tracking-tight text-dark dark:text-white">Lupa Kata Sandi?</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Jangan khawatir. Masukkan alamat email Anda dan kami akan mengirimkan tautan reset kata sandi.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm font-bold text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-form-label for="email" value="Alamat Email" />
            <x-form-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" :error="$errors->has('email')" />
            <x-form-error :messages="$errors->get('email')" />
        </div>

        <div class="pt-2">
            <x-button class="w-full" size="lg">
                Kirim Tautan Reset
            </x-button>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-500 hover:text-dark dark:hover:text-white transition-colors">
                &larr; Kembali ke halaman Masuk
            </a>
        </div>
    </form>
</x-auth-layout>
