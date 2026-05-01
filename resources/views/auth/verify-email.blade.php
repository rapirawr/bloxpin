<x-auth-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-display font-black tracking-tight text-dark dark:text-white">Verifikasi Email</h1>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm font-bold text-green-600 dark:text-green-400">
            Tautan verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="mt-8 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-button class="w-full" size="lg">
                Kirim Ulang Email Verifikasi
            </x-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-bold text-gray-500 hover:text-red-500 transition-colors">
                Keluar dari Akun
            </button>
        </form>
    </div>
</x-auth-layout>
