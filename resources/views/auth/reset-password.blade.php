<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-light dark:bg-dark p-4">
        <div class="w-full sm:max-w-md bg-white dark:bg-card p-10 rounded-[3rem] shadow-minimal dark:shadow-minimal-dark border border-borderlight dark:border-borderdark">
            
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-10">
                <div class="w-16 h-16 bg-dark dark:bg-white text-white dark:text-dark rounded-2xl flex items-center justify-center mb-6 shadow-xl -rotate-6">
                    <span class="text-3xl font-black italic">B</span>
                </div>
                <h2 class="text-3xl font-black tracking-tighter text-dark dark:text-white uppercase text-center">New Password</h2>
                <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] mt-2 text-center">
                    Secure your inspiration.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-2">Email Address</label>
                    <input id="email" class="block w-full bg-light dark:bg-white/5 border-none rounded-2xl px-6 py-4 text-dark dark:text-white focus:ring-2 focus:ring-dark dark:focus:ring-white transition-all outline-none text-sm font-medium" 
                           type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 ml-2" />
                </div>

                <!-- Password -->
                <div class="mb-6" x-data="{ show: false }">
                    <label for="password" class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-2">New Password</label>
                    <div class="relative">
                        <input id="password" class="block w-full bg-light dark:bg-white/5 border-none rounded-2xl px-6 py-4 text-dark dark:text-white focus:ring-2 focus:ring-dark dark:focus:ring-white transition-all outline-none text-sm font-medium pr-14" 
                               :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" />
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-dark dark:hover:text-white p-2">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 ml-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-10">
                    <label for="password_confirmation" class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-2">Confirm Password</label>
                    <input id="password_confirmation" class="block w-full bg-light dark:bg-white/5 border-none rounded-2xl px-6 py-4 text-dark dark:text-white focus:ring-2 focus:ring-dark dark:focus:ring-white transition-all outline-none text-sm font-medium" 
                           type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 ml-2" />
                </div>

                <div class="flex flex-col gap-4">
                    <button type="submit" class="w-full bg-dark dark:bg-white text-white dark:text-dark font-black text-xs uppercase tracking-[0.2em] py-5 rounded-2xl hover:scale-[1.02] active:scale-95 transition-all shadow-xl">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
