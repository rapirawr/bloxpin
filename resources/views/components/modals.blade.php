<div x-data="appModals" 
     @app-confirm.window="openConfirm($event.detail)"
     @app-prompt.window="openPrompt($event.detail)"
     @keydown.escape.window="closeModal()">
    <!-- Confirm Modal -->
    <div x-show="confirmData.show" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <!-- Backdrop -->
        <div x-show="confirmData.show" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>
        <!-- Modal Content -->
        <div x-show="confirmData.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white dark:bg-dark border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl p-6 w-[90%] max-w-sm mx-auto z-10 text-center">
            
            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-red-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            
            <h3 class="text-xl font-bold text-dark dark:text-white mb-2" x-text="confirmData.title">Konfirmasi</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm" x-text="confirmData.message"></p>
            
            <div class="flex gap-3">
                <button @click="closeModal()" class="flex-1 py-3 px-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-dark dark:text-white font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button @click="confirmAction()" class="flex-1 py-3 px-4 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors shadow-lg shadow-red-500/30">
                    <span x-text="confirmData.confirmText || 'Ya, Lanjutkan'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Prompt Modal -->
    <div x-show="promptData.show" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <!-- Backdrop -->
        <div x-show="promptData.show" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>
        <!-- Modal Content -->
        <div x-show="promptData.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white dark:bg-dark border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl p-6 w-[90%] max-w-sm mx-auto z-10 text-center">
            
            <h3 class="text-xl font-bold text-dark dark:text-white mb-2" x-text="promptData.title">Masukkan Data</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4 text-sm" x-text="promptData.message"></p>
            
            <input type="text" x-model="promptData.input" x-ref="promptInput" @keydown.enter="promptAction()" class="w-full bg-gray-50 dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 mb-6 text-dark dark:text-white focus:outline-none focus:border-dark dark:focus:border-white transition-colors" :placeholder="promptData.placeholder">
            
            <div class="flex gap-3">
                <button @click="closeModal()" class="flex-1 py-3 px-4 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-dark dark:text-white font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button @click="promptAction()" class="flex-1 py-3 px-4 bg-dark dark:bg-white hover:bg-black dark:hover:bg-gray-200 text-white dark:text-dark font-bold rounded-xl transition-colors shadow-lg">
                    <span x-text="promptData.confirmText || 'Simpan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
