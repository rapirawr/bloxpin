<x-auth-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-display font-black tracking-tight text-dark dark:text-white">Atur Ulang Kata Sandi</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Masukkan kata sandi baru Anda di bawah ini.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-form-label for="email" value="Alamat Email" />
            <x-form-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus placeholder="nama@email.com" :error="$errors->has('email')" />
            <x-form-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-form-label for="password" value="Kata Sandi Baru" />
            <x-form-input id="password" class="block mt-1 w-full" type="password" name="password" required placeholder="Minimal 8 karakter" :error="$errors->has('password')" />
            <x-form-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-form-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-form-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required placeholder="Ulangi kata sandi" :error="$errors->has('password_confirmation')" />
            <x-form-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="pt-2">
            <x-button class="w-full" size="lg">
                Perbarui Kata Sandi
            </x-button>
        </div>
    </form>
</x-auth-layout>
