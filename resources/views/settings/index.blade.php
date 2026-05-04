@extends('layouts.app')

@section('title', 'Settings — Bloxpin')

@section('content')
<div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 md:py-12">
    <div class="flex flex-col md:flex-row gap-8 lg:gap-12" x-data="{ 
        tab: 'profile', 
        isCompact: document.documentElement.classList.contains('compact-mode') 
    }">
        
        {{-- ── Sidebar Navigation ── --}}
        <aside class="flex-none w-full md:w-64 lg:w-72">
            <h1 class="text-2xl font-display font-bold mb-8 px-2">Settings</h1>
            
            <nav class="flex flex-row md:flex-col gap-1 overflow-x-auto md:overflow-visible pb-4 md:pb-0 scrollbar-hide">
                <button @click="tab = 'profile'" 
                        :class="tab === 'profile' ? 'bg-dark/5 dark:bg-white/5 text-dark dark:text-white font-semibold' : 'text-gray-500 hover:text-dark dark:hover:text-white hover:bg-dark/5 dark:hover:bg-white/5'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Public Profile
                </button>
                
                <button @click="tab = 'account'" 
                        :class="tab === 'account' ? 'bg-dark/5 dark:bg-white/5 text-dark dark:text-white font-semibold' : 'text-gray-500 hover:text-dark dark:hover:text-white hover:bg-dark/5 dark:hover:bg-white/5'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Account
                </button>
                
                <button @click="tab = 'security'" 
                        :class="tab === 'security' ? 'bg-dark/5 dark:bg-white/5 text-dark dark:text-white font-semibold' : 'text-gray-500 hover:text-dark dark:hover:text-white hover:bg-dark/5 dark:hover:bg-white/5'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Security
                </button>

                <button @click="tab = 'app'" 
                        :class="tab === 'app' ? 'bg-dark/5 dark:bg-white/5 text-dark dark:text-white font-semibold' : 'text-gray-500 hover:text-dark dark:hover:text-white hover:bg-dark/5 dark:hover:bg-white/5'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    App
                </button>

                <button @click="tab = 'display'" 
                        :class="tab === 'display' ? 'bg-dark/5 dark:bg-white/5 text-dark dark:text-white font-semibold' : 'text-gray-500 hover:text-dark dark:hover:text-white hover:bg-dark/5 dark:hover:bg-white/5'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Display
                </button>

                <button @click="tab = 'privacy'" 
                        :class="tab === 'privacy' ? 'bg-dark/5 dark:bg-white/5 text-dark dark:text-white font-semibold' : 'text-gray-500 hover:text-dark dark:hover:text-white hover:bg-dark/5 dark:hover:bg-white/5'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Privacy
                </button>
                
                <div class="h-px bg-dark/5 dark:bg-white/5 my-4 mx-4"></div>
                
                <button @click="tab = 'danger'" 
                        :class="tab === 'danger' ? 'bg-red-500/10 text-red-500 font-semibold' : 'text-gray-500 hover:text-red-500 hover:bg-red-500/5'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Danger Zone
                </button>
            </nav>
        </aside>

        {{-- ── Main Content ── --}}
        <main class="flex-1 w-full overflow-y-auto overflow-x-auto bg-white dark:bg-white/5 border border-dark/5 dark:border-white/10 rounded-3xl p-6 md:p-8 shadow-sm">
            
            {{-- Profile Tab --}}
            <section x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <header class="mb-8">
                    <h2 class="text-xl font-bold mb-1">Public Profile</h2>
                    <p class="text-gray-500 text-sm">Informasi ini akan terlihat oleh semua orang di Bloxpin.</p>
                </header>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')

                    {{-- Avatar & Cover --}}
                    <div class="flex flex-col gap-6">
                        <div class="relative group">
                            <label class="block text-sm font-semibold mb-3">Avatar</label>
                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <img id="avatar-preview" src="{{ $user->avatar ? Storage::disk('s3')->url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=E60023&color=fff' }}" 
                                          alt="{{ $user->name }}" 
                                          class="w-24 h-24 rounded-full object-cover border-4 border-dark/5 dark:border-white/10 shadow-lg">
                                    <label for="avatar_input" class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    </label>
                                    <input type="file" id="avatar_input" name="avatar" class="hidden" 
                                           onchange="const file = this.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { document.getElementById('avatar-preview').src = e.target.result; }; reader.readAsDataURL(file); }">
                                </div>
                                <div class="text-xs text-gray-500 leading-relaxed max-w-[200px]">
                                    Pilih foto profil terbaikmu. Disarankan minimal 400x400px.
                                </div>
                            </div>
                        </div>

                        <div class="relative group">
                            <label class="block text-sm font-semibold mb-3">Cover Photo</label>
                            <div class="relative w-full h-32 rounded-2xl overflow-hidden border-2 border-dashed border-dark/10 dark:border-white/10 group-hover:border-primary/50 transition-all">
                                <img id="cover-preview" src="{{ $user->cover_photo ? Storage::disk('s3')->url($user->cover_photo) : '' }}" 
                                     class="w-full h-full object-cover {{ $user->cover_photo ? '' : 'hidden' }}">
                                <div id="cover-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 {{ $user->cover_photo ? 'hidden' : '' }}">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-medium">Klik untuk upload cover</span>
                                </div>
                                <label for="cover_input" class="absolute inset-0 cursor-pointer"></label>
                                <input type="file" id="cover_input" name="cover_photo" class="hidden" 
                                       onchange="const file = this.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { document.getElementById('cover-preview').src = e.target.result; document.getElementById('cover-preview').classList.remove('hidden'); document.getElementById('cover-placeholder').classList.add('hidden'); }; reader.readAsDataURL(file); }">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-3 bg-dark/5 dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold">Username</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">@</span>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                                       class="w-full pl-8 pr-4 py-3 bg-dark/5 dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all">
                            </div>
                            @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold">Bio</label>
                            <textarea name="bio" rows="4" 
                                      class="w-full px-4 py-3 bg-dark/5 dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all resize-none"
                                      placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-dark/5 dark:border-white/10 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-full transition-all shadow-lg shadow-primary/25">
                            Save Changes
                        </button>
                    </div>
                </form>
            </section>

            {{-- Account Tab --}}
            <section x-show="tab === 'account'" style="display:none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <header class="mb-8">
                    <h2 class="text-xl font-bold mb-1">Account Settings</h2>
                    <p class="text-gray-500 text-sm">Kelola informasi dasar akun dan verifikasi email.</p>
                </header>
                
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="username" value="{{ $user->username }}">

                    <div class="space-y-3">
                        <label class="block text-sm font-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-3 bg-dark/5 dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all">
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2 text-sm">
                                <p class="text-amber-500 font-medium">Email belum diverifikasi.</p>
                                <button form="send-verification" class="text-primary hover:underline font-semibold">Kirim ulang link verifikasi</button>
                            </div>
                        @endif
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-6 border-t border-dark/5 dark:border-white/10 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-full transition-all shadow-lg shadow-primary/25">
                            Update Email
                        </button>
                    </div>
                </form>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
            </section>

            {{-- Security Tab --}}
            <section x-show="tab === 'security'" style="display:none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <header class="mb-8">
                    <h2 class="text-xl font-bold mb-1">Security</h2>
                    <p class="text-gray-500 text-sm">Pastikan akunmu tetap aman dengan password yang kuat.</p>
                </header>
                
                <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('put')

                    <div class="space-y-3">
                        <label class="block text-sm font-semibold">Current Password</label>
                        <input type="password" name="current_password" 
                               class="w-full px-4 py-3 bg-dark/5 dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all">
                        @error('current_password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-semibold">New Password</label>
                        <input type="password" name="password" 
                               class="w-full px-4 py-3 bg-dark/5 dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all">
                        @error('password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full px-4 py-3 bg-dark/5 dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary transition-all">
                    </div>

                    <div class="pt-6 border-t border-dark/5 dark:border-white/10 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-full transition-all shadow-lg shadow-primary/25">
                            Update Password
                        </button>
                    </div>
                </form>
            </section>

            {{-- App Tab --}}
            <section x-show="tab === 'app'" style="display:none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <header class="mb-8">
                    <h2 class="text-xl font-bold mb-1">App & Installation</h2>
                    <p class="text-gray-500 text-sm">Gunakan Bloxpin sebagai aplikasi di perangkatmu.</p>
                </header>
                
                <div class="space-y-6">
                    <div id="install-section" class="hidden flex flex-col md:flex-row items-center justify-between p-6 bg-primary/5 border border-primary/10 rounded-[28px] gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-white dark:bg-white/10 rounded-2xl flex items-center justify-center shadow-sm">
                                <img src="/images/icon-512.png" class="w-10 h-10 rounded-lg">
                            </div>
                            <div>
                                <div class="font-bold text-lg">Instal Bloxpin App</div>
                                <div class="text-sm text-gray-500">Dapatkan akses cepat dan pengalaman layar penuh.</div>
                            </div>
                        </div>
                        <button id="install-btn" class="w-full md:w-auto px-8 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-full transition-all shadow-lg shadow-primary/25">
                            Instal Sekarang
                        </button>
                    </div>

                    <div class="p-6 bg-dark/5 dark:bg-white/5 rounded-3xl space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold">Tentang Aplikasi</h3>
                            <div class="px-2 py-0.5 bg-green-500/10 text-green-500 text-[10px] font-bold rounded-full uppercase tracking-wider">Up to date</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="text-gray-500">Versi</div>
                            <div class="text-right font-mono">1.2.0-stable</div>
                            <div class="text-gray-500">Build</div>
                            <div class="text-right font-mono">2026.05.04.14</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 border border-dark/10 dark:border-white/10 rounded-2xl">
                        <div>
                            <div class="font-semibold text-sm">Hapus Cache Aplikasi</div>
                            <div class="text-xs text-gray-500 mt-0.5">Bersihkan data tersimpan untuk memperbaiki masalah tampilan.</div>
                        </div>
                        <button @click="localStorage.clear(); sessionStorage.clear(); window.location.reload();" 
                                class="px-4 py-2 bg-dark/5 dark:bg-white/5 hover:bg-dark/10 dark:hover:bg-white/10 text-xs font-bold rounded-lg transition-all">
                            Clear Cache
                        </button>
                    </div>

                    {{-- iOS Instructions (Safari doesn't support beforeinstallprompt) --}}
                    <div class="md:hidden p-5 bg-blue-500/5 border border-blue-500/10 rounded-2xl">
                        <h4 class="text-sm font-bold text-blue-500 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pengguna iPhone/Safari?
                        </h4>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Jika tombol instal tidak muncul, tap ikon <strong class="text-dark dark:text-white">Share</strong> (kotak panah atas) lalu pilih <strong class="text-dark dark:text-white">"Add to Home Screen"</strong>.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Display Tab --}}
            <section x-show="tab === 'display'" style="display:none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <header class="mb-8">
                    <h2 class="text-xl font-bold mb-1">Display Preferences</h2>
                    <p class="text-gray-500 text-sm">Sesuaikan tampilan Bloxpin agar nyaman di matamu.</p>
                </header>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-dark/5 dark:bg-white/5 rounded-2xl">
                        <div>
                            <div class="font-semibold">Dark Mode</div>
                            <div class="text-xs text-gray-500 mt-0.5">Aktifkan tema gelap untuk kenyamanan mata.</div>
                        </div>
                        <button @click="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')" 
                                class="w-12 h-6 rounded-full bg-gray-300 dark:bg-primary relative transition-all">
                            <div class="absolute top-1 left-1 dark:left-7 w-4 h-4 bg-white rounded-full transition-all"></div>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-dark/5 dark:bg-white/5 rounded-2xl">
                        <div>
                            <div class="font-semibold">Compact View</div>
                            <div class="text-xs text-gray-500 mt-0.5">Tampilkan lebih banyak konten dengan margin yang lebih kecil.</div>
                        </div>
                        <button @click="isCompact = !isCompact; document.documentElement.classList.toggle('compact-mode', isCompact); localStorage.setItem('compact-mode', isCompact)" 
                                class="w-12 h-6 rounded-full relative transition-all"
                                :class="isCompact ? 'bg-primary' : 'bg-gray-300 dark:bg-white/10'">
                            <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all"
                                 :style="isCompact ? 'left: 28px' : 'left: 4px'"></div>
                        </button>
                    </div>

                    <div class="p-4 bg-dark/5 dark:bg-white/5 rounded-2xl space-y-4">
                        <div class="font-semibold">Grid Density</div>
                        <div class="grid grid-cols-3 gap-2">
                            <button class="p-2 text-xs font-bold bg-primary text-white rounded-lg">Comfortable</button>
                            <button class="p-2 text-xs font-bold bg-dark/5 dark:bg-white/5 text-gray-500 rounded-lg opacity-50 cursor-not-allowed">Standard</button>
                            <button class="p-2 text-xs font-bold bg-dark/5 dark:bg-white/5 text-gray-500 rounded-lg opacity-50 cursor-not-allowed">Dense</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-dark/5 dark:bg-white/5 rounded-2xl">
                        <div>
                            <div class="font-semibold">Language</div>
                            <div class="text-xs text-gray-500 mt-0.5">Pilih bahasa antarmuka aplikasi.</div>
                        </div>
                        <select class="bg-transparent border-none text-sm font-bold focus:ring-0 cursor-pointer">
                            <option>Bahasa Indonesia</option>
                            <option>English</option>
                        </select>
                    </div>
                </div>
            </section>

            {{-- Privacy Tab --}}
            <section x-show="tab === 'privacy'" style="display:none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <header class="mb-8">
                    <h2 class="text-xl font-bold mb-1">Privacy & Visibility</h2>
                    <p class="text-gray-500 text-sm">Kontrol siapa yang bisa melihat profil dan aktivitasmu.</p>
                </header>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-dark/5 dark:bg-white/5 rounded-2xl">
                        <div>
                            <div class="font-semibold">Private Profile</div>
                            <div class="text-xs text-gray-500 mt-0.5">Hanya pengikut yang disetujui yang bisa melihat board kamu.</div>
                        </div>
                        <button class="w-12 h-6 rounded-full bg-gray-300 relative transition-all opacity-50 cursor-not-allowed">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-all"></div>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-dark/5 dark:bg-white/5 rounded-2xl">
                        <div>
                            <div class="font-semibold">Tampilkan di Hasil Pencarian</div>
                            <div class="text-xs text-gray-500 mt-0.5">Izinkan mesin pencari seperti Google mengindeks profilmu.</div>
                        </div>
                        <button class="w-12 h-6 rounded-full bg-primary relative transition-all">
                            <div class="absolute top-1 right-1 w-4 h-4 bg-white rounded-full transition-all"></div>
                        </button>
                    </div>

                    <div class="p-4 bg-amber-500/5 border border-amber-500/10 rounded-2xl flex gap-4">
                        <div class="text-amber-500 flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="text-xs text-amber-600 leading-relaxed">
                            Beberapa fitur privasi masih dalam pengembangan. Saat ini, semua foto yang diunggah ke Bloxpin bersifat publik kecuali disimpan di Board rahasia.
                        </div>
                    </div>
                </div>
            </section>

            {{-- Danger Tab --}}
            <section x-show="tab === 'danger'" style="display:none" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <header class="mb-8 text-red-500">
                    <h2 class="text-xl font-bold mb-1">Danger Zone</h2>
                    <p class="text-red-500/70 text-sm">Hati-hati! Tindakan di sini bersifat permanen.</p>
                </header>
                
                <div class="bg-red-500/5 border border-red-500/20 p-6 rounded-2xl space-y-4">
                    <h3 class="font-bold text-red-500">Hapus Akun</h3>
                    <p class="text-sm text-red-500/80 leading-relaxed">
                        Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.
                    </p>
                    
                    <button @click="$dispatch('open-modal', 'confirm-user-deletion')" 
                            class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-red-500/20">
                        Hapus Akun Saya
                    </button>
                </div>
            </section>

        </main>
    </div>
</div>

{{-- Deletion Modal --}}
<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
        </p>

        <div class="mt-6">
            <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}" />
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {{ __('Hapus Akun') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>

@endsection

@push('scripts')
<script>
    // Handle status message from session
    @if (session('status') === 'profile-updated')
        window.showToast('Profil berhasil diperbarui!');
    @endif
    @if (session('status') === 'password-updated')
        window.showToast('Password berhasil diperbarui!');
    @endif

    // PWA Install Logic
    let deferredPrompt;
    const installSection = document.getElementById('install-section');
    const installBtn = document.getElementById('install-btn');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent the mini-infobar from appearing on mobile
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        // Update UI notify the user they can install the PWA
        if (installSection) {
            installSection.classList.remove('hidden');
        }
    });

    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            // Show the install prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            const { outcome } = await deferredPrompt.userChoice;
            // Optionally, send analytics event with outcome of user choice
            console.log(`User response to the install prompt: ${outcome}`);
            // We've used the prompt, and can't use it again, throw it away
            deferredPrompt = null;
            // Hide the install button
            installSection.classList.add('hidden');
        });
    }

    window.addEventListener('appinstalled', (event) => {
        console.log('👍', 'appinstalled', event);
        // Clear the deferredPrompt so it can be garbage collected
        deferredPrompt = null;
        if (installSection) {
            installSection.classList.add('hidden');
        }
        window.showToast('Bloxpin berhasil diinstal!');
    });
</script>
@endpush
