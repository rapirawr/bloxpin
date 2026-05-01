<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-light dark:bg-dark p-4">
        <div class="w-full sm:max-w-md bg-white dark:bg-card p-10 rounded-[3rem] shadow-minimal dark:shadow-minimal-dark border border-borderlight dark:border-borderdark">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-10">
                <div class="w-16 h-16 bg-dark dark:bg-white text-white dark:text-dark rounded-2xl flex items-center justify-center mb-6 shadow-xl rotate-12">
                    <span class="text-3xl font-black italic">B</span>
                </div>
                <h2 class="text-3xl font-black tracking-tighter text-dark dark:text-white uppercase">Reset Link</h2>
                <p class="text-gray-500 text-xs font-black uppercase tracking-[0.2em] mt-2 text-center leading-relaxed">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-center text-xs font-black uppercase tracking-widest text-green-500" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-8">
                    <label for="email" class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-2">Email Address</label>
                    <input id="email" class="block w-full bg-light dark:bg-white/5 border-none rounded-2xl px-6 py-4 text-dark dark:text-white focus:ring-2 focus:ring-dark dark:focus:ring-white transition-all outline-none text-sm font-medium" 
                           type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 ml-2" />
                </div>

                <div class="flex flex-col gap-4">
                    <button type="submit" class="w-full bg-dark dark:bg-white text-white dark:text-dark font-black text-xs uppercase tracking-[0.2em] py-5 rounded-2xl hover:scale-[1.02] active:scale-95 transition-all shadow-xl">
                        {{ __('Email Password Reset Link') }}
                    </button>
                    
                    <a href="{{ route('login') }}" class="text-center text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] hover:text-dark dark:hover:text-white transition-colors py-2">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
