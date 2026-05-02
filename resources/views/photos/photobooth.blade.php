@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8" x-data="photobooth()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
        <div>
            <h1 class="text-5xl md:text-6xl font-display font-black text-dark dark:text-white mb-2 tracking-tighter">STUDIO<span class="text-pinterest">.</span></h1>
            <p class="text-gray-400 font-bold uppercase tracking-[0.3em] text-[10px]">Bloxpin Interactive Photobooth</p>
        </div>
        <div class="flex items-center gap-4">
            <button @click="reset" x-show="capturedImages.length > 0" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-pinterest transition-colors">Reset Session</button>
        </div>
    </div>

    <div class="grid lg:grid-cols-[380px_1fr] gap-12 items-start">
        <!-- Left: Control Center -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-card p-8 rounded-[40px] border border-borderlight dark:border-borderdark shadow-sm space-y-6">

                <!-- Layout Selection -->
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center justify-between">
                        <span>Pilih Layout</span>
                        <span class="text-pinterest capitalize" x-text="activeLayout"></span>
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="activeLayout = 'strip'" class="group flex flex-col items-center gap-2">
                            <div class="w-full h-12 rounded-xl transition-all duration-300 flex flex-col items-center justify-center gap-0.5 border-2 p-1"
                                 :class="activeLayout === 'strip' ? 'border-pinterest bg-pinterest/5 scale-105 shadow-md' : 'border-gray-100 dark:border-white/10 hover:border-gray-200'">
                                <div class="w-full h-1.5 bg-gray-300 rounded-[1px]"></div>
                                <div class="w-full h-1.5 bg-gray-300 rounded-[1px]"></div>
                                <div class="w-full h-1.5 bg-gray-300 rounded-[1px]"></div>
                            </div>
                            <span class="text-[8px] font-bold uppercase tracking-tighter" :class="activeLayout === 'strip' ? 'text-dark dark:text-white' : 'text-gray-400'">Strip (1x4)</span>
                        </button>
                        <button @click="activeLayout = 'grid'" class="group flex flex-col items-center gap-2">
                            <div class="w-full h-12 rounded-xl transition-all duration-300 grid grid-cols-2 gap-0.5 border-2 p-1.5"
                                 :class="activeLayout === 'grid' ? 'border-pinterest bg-pinterest/5 scale-105 shadow-md' : 'border-gray-100 dark:border-white/10 hover:border-gray-200'">
                                <div class="bg-gray-300 rounded-[1px]"></div>
                                <div class="bg-gray-300 rounded-[1px]"></div>
                                <div class="bg-gray-300 rounded-[1px]"></div>
                                <div class="bg-gray-300 rounded-[1px]"></div>
                            </div>
                            <span class="text-[8px] font-bold uppercase tracking-tighter" :class="activeLayout === 'grid' ? 'text-dark dark:text-white' : 'text-gray-400'">Grid (2x2)</span>
                        </button>
                    </div>
                </div>

                <div class="h-px bg-borderlight dark:bg-borderdark"></div>

                <!-- Frame Selection -->
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center justify-between">
                        <span>Pilih Frame</span>
                        <span class="text-pinterest capitalize" x-text="frames.find(f=>f.id===activeFrame)?.name"></span>
                    </h3>
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="fr in frames" :key="fr.id">
                            <button @click="activeFrame = fr.id" class="group flex flex-col items-center gap-1.5">
                                <div class="w-full aspect-square rounded-xl transition-all duration-300 flex items-center justify-center overflow-hidden"
                                     :class="activeFrame === fr.id ? 'ring-2 ring-pinterest ring-offset-2 dark:ring-offset-dark scale-110 shadow-lg' : 'hover:scale-105'"
                                     :style="getFramePreviewStyle(fr.id)">
                                    <div x-html="getFramePreviewIcon(fr.id)" class="w-full h-full flex items-center justify-center"></div>
                                </div>
                                <span class="text-[7px] font-bold uppercase tracking-tighter leading-none" :class="activeFrame === fr.id ? 'text-dark dark:text-white' : 'text-gray-400'" x-text="fr.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="h-px bg-borderlight dark:bg-borderdark"></div>

                <!-- Filter Selection -->
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center justify-between">
                        <span>Pilih Filter</span>
                        <span class="text-pinterest" x-text="filters.find(f=>f.id===activeFilter)?.name"></span>
                    </h3>
                    <div class="grid grid-cols-3 gap-3">
                        <template x-for="f in filters" :key="f.id">
                            <button @click="activeFilter = f.id" class="group flex flex-col items-center gap-2">
                                <div class="w-full aspect-video rounded-2xl transition-all duration-300 overflow-hidden border-2"
                                     :class="activeFilter === f.id ? 'border-pinterest scale-105 shadow-md' : 'border-transparent hover:border-gray-200 dark:hover:border-white/10'">
                                    <div class="w-full h-full bg-gray-100 dark:bg-white/5" :style="getFilterPreviewStyle(f.id)"></div>
                                </div>
                                <span class="text-[8px] font-bold uppercase tracking-tighter" :class="activeFilter === f.id ? 'text-dark dark:text-white' : 'text-gray-400'" x-text="f.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-2 space-y-3" x-show="capturedImages.length === 4">
                    <button @click="saveToBloxpin" :disabled="isSaving" class="w-full btn-primary py-4 rounded-2xl font-bold flex items-center justify-center gap-3">
                        <template x-if="isSaving">
                            <div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                        </template>
                        <span x-text="isSaving ? 'Menyimpan...' : 'Lanjutkan ke Edit'"></span>
                    </button>
                    <button @click="downloadStrip" class="w-full bg-gray-100 dark:bg-white/10 text-dark dark:text-white py-4 rounded-2xl font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/20 transition-colors">
                        Download Hasil
                    </button>
                </div>
            </div>

            <!-- Tip -->
            <div class="p-6 bg-dark dark:bg-white rounded-[40px] text-white dark:text-dark">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-pinterest flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-xs uppercase tracking-widest mb-1">Studio Tip</h4>
                        <p class="text-[10px] leading-relaxed opacity-70">Coba frame <strong>Celestial</strong> atau <strong>Botanica</strong> untuk foto yang lebih unik dan berkarakter!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Studio Area -->
        <div class="space-y-8">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Camera View -->
                <div class="space-y-4">
                    <div class="relative aspect-[3/4] bg-black rounded-[40px] overflow-hidden shadow-2xl border-8 border-white dark:border-card ring-1 ring-black/5">
                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover -scale-x-100"></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                        <div x-show="countdown > 0" class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-md z-10">
                            <span class="text-[120px] font-black text-white" x-text="countdown"></span>
                        </div>
                        <div x-show="flash" x-transition:enter="transition opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-white z-20"></div>
                    </div>
                    <button @click="startCapture" :disabled="isStreaming === false || isProcessing" class="w-full btn-primary py-5 rounded-[24px] flex items-center justify-center gap-3 disabled:opacity-50 shadow-xl shadow-pinterest/20 transition-all hover:scale-[1.02] active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="font-black uppercase tracking-widest text-sm">Mulai Sesi Foto</span>
                    </button>
                </div>

                <!-- Strip Preview Area -->
                <div class="flex flex-col items-center justify-center bg-gray-50 dark:bg-white/5 rounded-[40px] p-8 border-2 border-dashed border-gray-200 dark:border-white/10">
                    <div id="strip-preview-wrapper" class="transition-all duration-700" :class="capturedImages.length > 0 ? 'scale-105 shadow-2xl' : 'opacity-20 grayscale'">
                        <div id="strip-container" :style="getFrameStyle()" class="relative overflow-hidden shadow-2xl transition-all duration-500" :style="activeLayout === 'strip' ? 'width:160px;' : 'width:280px;'" style="padding:15px; border-radius:12px;">
                            <!-- Frame Decoration Top -->
                            <div x-html="getFrameDecorationHTML('top')" class="mb-3 flex items-center justify-center"></div>

                            <!-- Dynamic Layout Container -->
                            <div :class="activeLayout === 'strip' ? 'flex flex-col gap-[6px]' : 'grid grid-cols-2 gap-[8px]'">
                                <template x-for="(img, index) in [0,1,2,3]">
                                    <div class="overflow-hidden rounded-sm" style="aspect-ratio:3/4; position:relative;">
                                        <template x-if="capturedImages[index]">
                                            <img :src="capturedImages[index]" class="w-full h-full object-cover" :style="getFilterStyle()">
                                        </template>
                                        <template x-if="!capturedImages[index]">
                                            <div class="w-full h-full flex items-center justify-center" :style="getEmptySlotStyle()">
                                                <span class="font-black opacity-30" :class="activeLayout === 'strip' ? 'text-xl' : 'text-2xl'" x-text="index + 1"></span>
                                            </div>
                                        </template>
                                        <!-- Corner decorations per frame -->
                                        <div x-html="getFrameCornerHTML()" class="absolute inset-0 pointer-events-none"></div>
                                    </div>
                                </template>
                            </div>

                            <!-- Frame Decoration Bottom / Footer -->
                            <div x-html="getFrameDecorationHTML('bottom')" class="mt-3 flex flex-col items-center gap-0.5"></div>
                        </div>
                    </div>
                    <p x-show="capturedImages.length === 0" class="mt-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hasil foto akan muncul di sini</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function photobooth() {
    return {
        isStreaming: false,
        capturedImages: [],
        countdown: 0,
        flash: false,
        activeFilter: 'none',
        activeFrame: 'classic',
        activeLayout: 'strip',
        isProcessing: false,
        isSaving: false,

        filters: [
            { id: 'none',    name: 'Normal' },
            { id: 'vintage', name: 'Vintage' },
            { id: 'bw',      name: 'B&W' },
            { id: 'y2k',     name: 'Y2K' },
            { id: 'sepia',   name: 'Sepia' },
            { id: 'dreamy',  name: 'Dreamy' },
        ],

        frames: [
            { id: 'classic',   name: 'Classic' },
            { id: 'modern',    name: 'Modern' },
            { id: 'bloxpin',   name: 'Bloxpin' },
            { id: 'vaporwave', name: 'Vapor' },
            { id: 'pink',      name: 'Lovely' },
            { id: 'celestial', name: 'Celestial' },
            { id: 'botanica',  name: 'Botanica' },
            { id: 'cinema',    name: 'Cinema' },
            { id: 'polaroid',  name: 'Polaroid' },
            { id: 'sakura',    name: 'Sakura' },
        ],

        async init() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', aspectRatio: 3/4 }, audio: false });
                this.$refs.video.srcObject = stream;
                this.isStreaming = true;
            } catch (err) {
                console.error('Camera error:', err);
                window.showToast && window.showToast('Gagal mengakses kamera. Pastikan izin sudah diberikan.', 'error');
            }
        },

        async startCapture() {
            this.capturedImages = [];
            this.isProcessing = true;
            for (let i = 0; i < 4; i++) {
                this.countdown = 3;
                while (this.countdown > 0) {
                    await new Promise(r => setTimeout(r, 1000));
                    this.countdown--;
                }
                this.takeSnap();
                await new Promise(r => setTimeout(r, 600));
            }
            this.isProcessing = false;
        },

        takeSnap() {
            this.flash = true;
            setTimeout(() => this.flash = false, 120);
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            
            // Calculate 3:4 crop
            let targetW = video.videoWidth;
            let targetH = video.videoWidth * (4/3);
            
            if (targetH > video.videoHeight) {
                targetH = video.videoHeight;
                targetW = video.videoHeight * (3/4);
            }

            canvas.width = targetW;
            canvas.height = targetH;
            const ctx = canvas.getContext('2d');
            
            // Mirror and Crop
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            
            const offsetX = (video.videoWidth - targetW) / 2;
            const offsetY = (video.videoHeight - targetH) / 2;
            
            ctx.drawImage(video, offsetX, offsetY, targetW, targetH, 0, 0, targetW, targetH);
            this.capturedImages.push(canvas.toDataURL('image/jpeg', 0.9));
        },

        reset() {
            this.capturedImages = [];
            this.activeFilter = 'none';
            this.activeFrame = 'classic';
        },

        // ─── Frame Styles ────────────────────────────────────────────────

        getFrameStyle() {
            const styles = {
                classic:   'background:#fff;',
                modern:    'background:#111;',
                bloxpin:   'background:#E60023;',
                vaporwave: 'background:linear-gradient(135deg,#ff00ff,#00ffff);',
                pink:      'background:#ffc0cb;',
                celestial: 'background:#0b0e27;',
                botanica:  'background:#e8f0e0;',
                cinema:    'background:#1a1209;',
                polaroid:  'background:#fafaf5; border:1px solid #e5e0d5;',
                sakura:    'background:linear-gradient(160deg,#fff0f5,#ffe4ef);',
            };
            return styles[this.activeFrame] || 'background:#fff;';
        },

        getFramePreviewStyle(id) {
            const styles = {
                classic:   'background:#fff; border:1px solid #eee;',
                modern:    'background:#111;',
                bloxpin:   'background:#E60023;',
                vaporwave: 'background:linear-gradient(135deg,#ff00ff,#00ffff);',
                pink:      'background:#ffc0cb;',
                celestial: 'background:#0b0e27;',
                botanica:  'background:#e8f0e0;',
                cinema:    'background:#1a1209;',
                polaroid:  'background:#fafaf5; border:1px solid #ddd;',
                sakura:    'background:linear-gradient(160deg,#fff0f5,#ffe4ef);',
            };
            return styles[id] || 'background:#fff;';
        },

        getEmptySlotStyle() {
            const dark = ['modern','bloxpin','celestial','cinema','vaporwave'];
            const bg = dark.includes(this.activeFrame) ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)';
            const color = dark.includes(this.activeFrame) ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.25)';
            return `background:${bg}; color:${color};`;
        },

        // ─── Frame SVG Decorations (Top / Bottom) ────────────────────────

        getFrameDecorationHTML(position) {
            const dark = ['modern','bloxpin','celestial','cinema','vaporwave'];
            const isDark = dark.includes(this.activeFrame);

            const decs = {

                classic: {
                    top: `<svg width="100" height="18" viewBox="0 0 100 18" fill="none">
                        <line x1="0" y1="9" x2="38" y2="9" stroke="#ddd" stroke-width="0.8"/>
                        <circle cx="50" cy="9" r="4" stroke="#ddd" stroke-width="0.8" fill="none"/>
                        <circle cx="50" cy="9" r="1.5" fill="#ddd"/>
                        <line x1="62" y1="9" x2="100" y2="9" stroke="#ddd" stroke-width="0.8"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <svg width="60" height="8" viewBox="0 0 60 8" fill="none">
                            <line x1="0" y1="4" x2="20" y2="4" stroke="#bbb" stroke-width="0.5"/>
                            <rect x="26" y="2" width="8" height="4" stroke="#bbb" stroke-width="0.5" fill="none"/>
                            <line x1="40" y1="4" x2="60" y2="4" stroke="#bbb" stroke-width="0.5"/>
                        </svg>
                        <span style="font-family:monospace; font-size:6px; color:#999; letter-spacing:0.25em; text-transform:uppercase;">BLOXPIN PHOTOBOOTH</span>
                        <span style="font-family:monospace; font-size:5px; color:#bbb;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                modern: {
                    top: `<svg width="120" height="14" viewBox="0 0 120 14" fill="none">
                        <line x1="0" y1="7" x2="120" y2="7" stroke="#333" stroke-width="0.5"/>
                        <rect x="50" y="3" width="20" height="8" fill="#E60023"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <svg width="100" height="6" viewBox="0 0 100 6" fill="none">
                            <line x1="0" y1="3" x2="100" y2="3" stroke="#333" stroke-width="0.5"/>
                        </svg>
                        <span style="font-family:monospace; font-size:6px; color:rgba(255,255,255,0.5); letter-spacing:0.25em; text-transform:uppercase;">BLOXPIN PHOTOBOOTH</span>
                        <span style="font-family:monospace; font-size:5px; color:#444;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                bloxpin: {
                    top: `<svg width="120" height="22" viewBox="0 0 120 22" fill="none">
                        <circle cx="60" cy="11" r="8" stroke="rgba(255,255,255,0.4)" stroke-width="0.8" fill="none"/>
                        <path d="M55 11 L60 7 L65 11 L60 15 Z" stroke="rgba(255,255,255,0.6)" stroke-width="0.8" fill="rgba(255,255,255,0.15)"/>
                        <circle cx="60" cy="11" r="2" fill="white" opacity="0.7"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <span style="font-family:monospace; font-size:7px; color:rgba(255,255,255,0.9); letter-spacing:0.3em; font-weight:700; text-transform:uppercase;">BLOXPIN</span>
                        <span style="font-family:monospace; font-size:5px; color:rgba(255,255,255,0.6);">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                vaporwave: {
                    top: `<svg width="120" height="24" viewBox="0 0 120 24" fill="none">
                        <text x="60" y="14" text-anchor="middle" font-size="10" font-family="monospace" fill="white" opacity="0.9" letter-spacing="4">V A P O R</text>
                        <line x1="10" y1="20" x2="110" y2="20" stroke="rgba(255,255,255,0.4)" stroke-width="0.5"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px; display:flex; flex-direction:column; align-items:center;">
                        <svg width="100" height="18" viewBox="0 0 100 18" fill="none">
                            <line x1="0" y1="3" x2="100" y2="3" stroke="rgba(255,255,255,0.3)" stroke-width="0.5"/>
                            <line x1="10" y1="8" x2="90" y2="8" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/>
                            <line x1="20" y1="13" x2="80" y2="13" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/>
                        </svg>
                        <span style="font-size:6px; color:rgba(255,255,255,0.85); font-family:monospace; letter-spacing:0.3em; text-transform:uppercase;">BLOXPIN</span>
                    </div>`,
                },

                pink: {
                    top: `<svg width="120" height="24" viewBox="0 0 120 24" fill="none">
                        <!-- hearts -->
                        <path d="M20 14 C20 10 14 8 14 13 C14 18 20 22 20 22 C20 22 26 18 26 13 C26 8 20 10 20 14Z" fill="#ff7ca3" opacity="0.5"/>
                        <path d="M60 14 C60 10 54 8 54 13 C54 18 60 22 60 22 C60 22 66 18 66 13 C66 8 60 10 60 14Z" fill="#ff7ca3" opacity="0.7"/>
                        <path d="M100 14 C100 10 94 8 94 13 C94 18 100 22 100 22 C100 22 106 18 106 13 C106 8 100 10 100 14Z" fill="#ff7ca3" opacity="0.5"/>
                        <line x1="30" y1="14" x2="50" y2="14" stroke="#ffaac0" stroke-width="0.5"/>
                        <line x1="70" y1="14" x2="90" y2="14" stroke="#ffaac0" stroke-width="0.5"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <svg width="80" height="12" viewBox="0 0 80 12" fill="none">
                            <path d="M10 7 C10 4 7 3 7 6 C7 9 10 11 10 11 C10 11 13 9 13 6 C13 3 10 4 10 7Z" fill="#ff9ab5"/>
                            <path d="M40 7 C40 4 37 3 37 6 C37 9 40 11 40 11 C40 11 43 9 43 6 C43 3 40 4 40 7Z" fill="#ff9ab5"/>
                            <path d="M70 7 C70 4 67 3 67 6 C67 9 70 11 70 11 C70 11 73 9 73 6 C73 3 70 4 70 7Z" fill="#ff9ab5"/>
                        </svg>
                        <span style="font-size:6px; color:#c06080; font-family:monospace; letter-spacing:0.25em; text-transform:uppercase;">BLOXPIN PHOTOBOOTH</span>
                    </div>`,
                },

                // ── CELESTIAL ─────────────────────────────────────────────
                celestial: {
                    top: `<svg width="140" height="40" viewBox="0 0 140 40" fill="none">
                        <!-- moon crescent -->
                        <path d="M70 8 A14 14 0 1 1 70 36 A8 8 0 1 0 70 8Z" fill="none" stroke="#a89bd4" stroke-width="0.8"/>
                        <!-- stars scattered -->
                        <polygon points="20,10 21.5,15 26,15 22.5,18 24,23 20,20 16,23 17.5,18 14,15 18.5,15" fill="#ffd700" opacity="0.7" transform="scale(0.4) translate(20,0)"/>
                        <circle cx="15" cy="20" r="1" fill="#fff" opacity="0.6"/>
                        <circle cx="35" cy="8"  r="1.2" fill="#fff" opacity="0.8"/>
                        <circle cx="105" cy="15" r="0.8" fill="#ffd700" opacity="0.7"/>
                        <circle cx="120" cy="6"  r="1.5" fill="#fff" opacity="0.5"/>
                        <circle cx="128" cy="25" r="0.8" fill="#a89bd4" opacity="0.8"/>
                        <!-- diamond star right -->
                        <path d="M118 18 L120 14 L122 18 L120 22 Z" fill="#ffd700" opacity="0.6"/>
                        <!-- small sparkle left -->
                        <path d="M26 18 L28 14 L30 18 L28 22 Z" fill="#a89bd4" opacity="0.5" transform="scale(0.7) translate(12,4)"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <svg width="120" height="16" viewBox="0 0 120 16" fill="none">
                            <line x1="0"  y1="8" x2="42" y2="8" stroke="#a89bd4" stroke-width="0.4" stroke-dasharray="2 3"/>
                            <path d="M54 8 L57 4 L60 8 L57 12 Z" fill="#ffd700" opacity="0.8"/>
                            <line x1="78" y1="8" x2="120" y2="8" stroke="#a89bd4" stroke-width="0.4" stroke-dasharray="2 3"/>
                        </svg>
                        <span style="font-size:6px; color:#a89bd4; font-family:monospace; letter-spacing:0.3em; text-transform:uppercase;">BLOXPIN · CELESTIAL</span>
                        <span style="font-size:5px; color:#4a4870; font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                // ── BOTANICA ──────────────────────────────────────────────
                botanica: {
                    top: `<svg width="140" height="42" viewBox="0 0 140 42" fill="none">
                        <!-- left leaves -->
                        <path d="M0 38 Q10 20 25 28 Q12 32 0 38Z" fill="#5a8a4a" opacity="0.7"/>
                        <path d="M5 42 Q20 25 30 35 Q16 38 5 42Z" fill="#7aaa5a" opacity="0.5"/>
                        <path d="M0 30 Q15 12 28 22 Q14 26 0 30Z" fill="#3a6a3a" opacity="0.5"/>
                        <!-- small flower center-left -->
                        <circle cx="38" cy="26" r="4" fill="#ffcc66" opacity="0.7"/>
                        <path d="M38 18 Q40 22 38 26 Q36 22 38 18Z" fill="#ff9966" opacity="0.6"/>
                        <path d="M30 26 Q34 24 38 26 Q34 28 30 26Z" fill="#ff9966" opacity="0.6"/>
                        <path d="M38 26 Q42 24 46 26 Q42 28 38 26Z" fill="#ff9966" opacity="0.6"/>
                        <path d="M38 26 Q40 30 38 34 Q36 30 38 26Z" fill="#ff9966" opacity="0.6"/>
                        <!-- right leaves -->
                        <path d="M140 38 Q130 20 115 28 Q128 32 140 38Z" fill="#5a8a4a" opacity="0.7"/>
                        <path d="M135 42 Q120 25 110 35 Q124 38 135 42Z" fill="#7aaa5a" opacity="0.5"/>
                        <path d="M140 30 Q125 12 112 22 Q126 26 140 30Z" fill="#3a6a3a" opacity="0.5"/>
                        <!-- small flower center-right -->
                        <circle cx="102" cy="26" r="4" fill="#ffcc66" opacity="0.7"/>
                        <path d="M102 18 Q104 22 102 26 Q100 22 102 18Z" fill="#ff9966" opacity="0.6"/>
                        <path d="M94 26 Q98 24 102 26 Q98 28 94 26Z" fill="#ff9966" opacity="0.6"/>
                        <path d="M102 26 Q106 24 110 26 Q106 28 102 26Z" fill="#ff9966" opacity="0.6"/>
                        <path d="M102 26 Q104 30 102 34 Q100 30 102 26Z" fill="#ff9966" opacity="0.6"/>
                        <!-- stem center -->
                        <line x1="70" y1="42" x2="70" y2="10" stroke="#5a8a4a" stroke-width="0.8"/>
                        <path d="M70 28 Q78 20 80 14" stroke="#5a8a4a" stroke-width="0.6" fill="none"/>
                        <path d="M70 22 Q62 14 64 8" stroke="#5a8a4a" stroke-width="0.6" fill="none"/>
                        <circle cx="70" cy="8" r="5" fill="#ff7070" opacity="0.7"/>
                        <circle cx="70" cy="8" r="2" fill="#fff" opacity="0.5"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <svg width="120" height="18" viewBox="0 0 120 18" fill="none">
                            <path d="M0 9 Q30 4 60 9 Q90 14 120 9" stroke="#7aaa5a" stroke-width="0.6" fill="none"/>
                            <circle cx="15" cy="7" r="2" fill="#ff9966" opacity="0.5"/>
                            <circle cx="60" cy="10" r="2" fill="#ff7070" opacity="0.5"/>
                            <circle cx="105" cy="7" r="2" fill="#ff9966" opacity="0.5"/>
                        </svg>
                        <span style="font-size:6px; color:#4a7a3a; font-family:Georgia,serif; letter-spacing:0.2em; font-style:italic;">Bloxpin Botanica</span>
                        <span style="font-size:5px; color:#8aaa7a; font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                // ── CINEMA ────────────────────────────────────────────────
                cinema: {
                    top: `<svg width="140" height="28" viewBox="0 0 140 28" fill="none">
                        <!-- film strip perforations top -->
                        ${Array.from({length:11}, (_,i) =>
                            `<rect x="${i*13+2}" y="4" width="8" height="8" rx="1" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.15)" stroke-width="0.5"/>`
                        ).join('')}
                        <!-- gold line -->
                        <line x1="0" y1="20" x2="140" y2="20" stroke="#c8a84b" stroke-width="0.6"/>
                        <!-- stars rating -->
                        <text x="70" y="28" text-anchor="middle" font-size="8" fill="#c8a84b" opacity="0.8">★ ★ ★ ★ ★</text>
                    </svg>`,
                    bottom: `<div style="padding-top:4px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <svg width="140" height="20" viewBox="0 0 140 20" fill="none">
                            <line x1="0" y1="6" x2="140" y2="6" stroke="#c8a84b" stroke-width="0.6"/>
                            ${Array.from({length:11}, (_,i) =>
                                `<rect x="${i*13+2}" y="10" width="8" height="8" rx="1" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.15)" stroke-width="0.5"/>`
                            ).join('')}
                        </svg>
                        <span style="font-size:7px; color:#c8a84b; font-family:Georgia,serif; letter-spacing:0.4em; text-transform:uppercase;">BLOXPIN CINEMA</span>
                        <span style="font-size:5px; color:rgba(200,168,75,0.5); font-family:monospace; letter-spacing:0.2em;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                // ── POLAROID ──────────────────────────────────────────────
                polaroid: {
                    top: `<svg width="120" height="20" viewBox="0 0 120 20" fill="none">
                        <rect x="48" y="4" width="24" height="14" rx="2" stroke="#ddd" stroke-width="0.8" fill="none"/>
                        <rect x="52" y="8" width="6" height="6" rx="1" fill="#ddd" opacity="0.5"/>
                        <circle cx="62" cy="10" r="4" stroke="#ddd" stroke-width="0.5" fill="none"/>
                        <circle cx="66" cy="7" r="1.5" fill="#ffd700" opacity="0.6"/>
                    </svg>`,
                    bottom: `<div style="padding-top:8px; display:flex; flex-direction:column; align-items:center; gap:3px;">
                        <span style="font-size:9px; color:#555; font-family:'Helvetica Neue',sans-serif; letter-spacing:-0.02em; font-weight:300;">memories</span>
                        <span style="font-size:5.5px; color:#aaa; font-family:monospace; letter-spacing:0.2em;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                // ── SAKURA ────────────────────────────────────────────────
                sakura: {
                    top: `<svg width="140" height="38" viewBox="0 0 140 38" fill="none">
                        <!-- sakura petals -->
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ffb7c5" opacity="0.8" transform="rotate(0,70,10)"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ffb7c5" opacity="0.7" transform="rotate(72,70,10)"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ff9ab0" opacity="0.7" transform="rotate(144,70,10)"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ffb7c5" opacity="0.7" transform="rotate(216,70,10)"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ff9ab0" opacity="0.6" transform="rotate(288,70,10)"/>
                        <circle cx="70" cy="10" r="3" fill="#ffdd99"/>

                        <!-- small falling petals -->
                        <ellipse cx="20" cy="20" rx="4" ry="6" fill="#ffb7c5" opacity="0.5" transform="rotate(-30,20,20)"/>
                        <circle cx="20" cy="20" r="1.5" fill="#ffcc66" opacity="0.6"/>
                        <ellipse cx="120" cy="15" rx="4" ry="6" fill="#ff9ab0" opacity="0.5" transform="rotate(20,120,15)"/>
                        <circle cx="120" cy="15" r="1.5" fill="#ffcc66" opacity="0.6"/>
                        <ellipse cx="35" cy="32" rx="3" ry="5" fill="#ffb7c5" opacity="0.4" transform="rotate(15,35,32)"/>
                        <ellipse cx="105" cy="30" rx="3" ry="5" fill="#ff9ab0" opacity="0.4" transform="rotate(-20,105,30)"/>

                        <!-- branch lines -->
                        <path d="M0 38 Q20 25 40 30 Q55 34 70 38" stroke="#c8a0a8" stroke-width="0.6" fill="none"/>
                        <path d="M140 38 Q120 25 100 30 Q85 34 70 38" stroke="#c8a0a8" stroke-width="0.6" fill="none"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px; display:flex; flex-direction:column; align-items:center; gap:2px;">
                        <svg width="100" height="14" viewBox="0 0 100 14" fill="none">
                            <ellipse cx="10" cy="7" rx="4" ry="6" fill="#ffb7c5" opacity="0.6" transform="rotate(-15,10,7)"/>
                            <circle cx="10" cy="7" r="1.5" fill="#ffcc66" opacity="0.5"/>
                            <ellipse cx="90" cy="7" rx="4" ry="6" fill="#ff9ab0" opacity="0.6" transform="rotate(15,90,7)"/>
                            <circle cx="90" cy="7" r="1.5" fill="#ffcc66" opacity="0.5"/>
                            <line x1="18" y1="7" x2="82" y2="7" stroke="#f0c0d0" stroke-width="0.5"/>
                        </svg>
                        <span style="font-size:6px; color:#c08090; font-family:Georgia,serif; font-style:italic; letter-spacing:0.15em;">Bloxpin · さくら</span>
                        <span style="font-size:5px; color:#daa0b0; font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },
            };

            const dec = decs[this.activeFrame];
            if (!dec) return '';
            return dec[position] || '';
        },

        // Corner overlays per photo slot
        getFrameCornerHTML() {
            const corners = {
                celestial: `
                    <svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                        <line x1="0" y1="0" x2="10" y2="0" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <line x1="0" y1="0" x2="0" y2="10" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <line x1="100" y1="0" x2="90" y2="0" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <line x1="100" y1="0" x2="100" y2="10" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <line x1="0" y1="75" x2="10" y2="75" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <line x1="0" y1="75" x2="0" y2="65" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <line x1="100" y1="75" x2="90" y2="75" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <line x1="100" y1="75" x2="100" y2="65" stroke="#a89bd4" stroke-width="0.8" opacity="0.6"/>
                        <circle cx="2" cy="2" r="1" fill="#ffd700" opacity="0.5"/>
                        <circle cx="98" cy="2" r="1" fill="#ffd700" opacity="0.5"/>
                    </svg>`,
                cinema: `
                    <svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                        <rect x="0" y="0" width="100" height="75" stroke="#c8a84b" stroke-width="1" fill="none"/>
                        <line x1="0" y1="0" x2="8" y2="0" stroke="#c8a84b" stroke-width="1.5"/>
                        <line x1="0" y1="0" x2="0" y2="8" stroke="#c8a84b" stroke-width="1.5"/>
                        <line x1="100" y1="0" x2="92" y2="0" stroke="#c8a84b" stroke-width="1.5"/>
                        <line x1="100" y1="0" x2="100" y2="8" stroke="#c8a84b" stroke-width="1.5"/>
                        <line x1="0" y1="75" x2="8" y2="75" stroke="#c8a84b" stroke-width="1.5"/>
                        <line x1="0" y1="75" x2="0" y2="67" stroke="#c8a84b" stroke-width="1.5"/>
                        <line x1="100" y1="75" x2="92" y2="75" stroke="#c8a84b" stroke-width="1.5"/>
                        <line x1="100" y1="75" x2="100" y2="67" stroke="#c8a84b" stroke-width="1.5"/>
                    </svg>`,
                polaroid: `
                    <svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                        <rect x="0.5" y="0.5" width="99" height="74" stroke="#e0ddd5" stroke-width="0.5" fill="none"/>
                    </svg>`,
                botanica: `
                    <svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                        <path d="M0 8 Q4 4 8 0" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                        <path d="M92 0 Q96 4 100 8" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                        <path d="M0 67 Q4 71 8 75" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                        <path d="M92 75 Q96 71 100 67" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                    </svg>`,
                sakura: `
                    <svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                        <ellipse cx="4" cy="4" rx="3" ry="4" fill="#ffb7c5" opacity="0.35" transform="rotate(-30,4,4)"/>
                        <ellipse cx="96" cy="4" rx="3" ry="4" fill="#ff9ab0" opacity="0.35" transform="rotate(30,96,4)"/>
                    </svg>`,
                default: '',
            };
            return corners[this.activeFrame] || corners.default;
        },

        // Preview icon inside frame selector button
        getFramePreviewIcon(id) {
            const icons = {
                classic: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="3" y="3" width="16" height="16" stroke="#ccc" stroke-width="0.8" fill="none"/>
                    <line x1="3" y1="11" x2="19" y2="11" stroke="#eee" stroke-width="0.5"/>
                </svg>`,
                modern: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="3" y="3" width="16" height="16" fill="#E60023" opacity="0.8"/>
                    <line x1="3" y1="11" x2="19" y2="11" stroke="#fff" stroke-width="0.5" opacity="0.4"/>
                </svg>`,
                bloxpin: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M11 4 L18 18 L4 18 Z" stroke="rgba(255,255,255,0.6)" stroke-width="0.8" fill="rgba(255,255,255,0.15)"/>
                    <circle cx="11" cy="11" r="2" fill="white" opacity="0.7"/>
                </svg>`,
                vaporwave: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <line x1="3" y1="8" x2="19" y2="8" stroke="rgba(255,255,255,0.6)" stroke-width="0.5"/>
                    <line x1="5" y1="12" x2="17" y2="12" stroke="rgba(255,255,255,0.4)" stroke-width="0.5"/>
                    <line x1="7" y1="16" x2="15" y2="16" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/>
                </svg>`,
                pink: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M11 14 C11 10 6 8 6 12 C6 16 11 19 11 19 C11 19 16 16 16 12 C16 8 11 10 11 14Z" fill="#ff7ca3" opacity="0.7"/>
                </svg>`,
                celestial: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M11 4 A7 7 0 1 1 11 18 A4 4 0 1 0 11 4Z" stroke="#a89bd4" stroke-width="0.8" fill="none"/>
                    <circle cx="16" cy="5" r="1.2" fill="#ffd700" opacity="0.8"/>
                    <circle cx="6" cy="8" r="0.8" fill="#fff" opacity="0.7"/>
                    <circle cx="17" cy="15" r="0.7" fill="#fff" opacity="0.5"/>
                </svg>`,
                botanica: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <line x1="11" y1="19" x2="11" y2="8" stroke="#5a8a4a" stroke-width="0.8"/>
                    <path d="M11 14 Q16 10 17 6" stroke="#5a8a4a" stroke-width="0.6" fill="none"/>
                    <path d="M11 11 Q6 7 7 3" stroke="#5a8a4a" stroke-width="0.6" fill="none"/>
                    <circle cx="11" cy="6" r="3" fill="#ff7070" opacity="0.7"/>
                    <circle cx="11" cy="6" r="1.2" fill="#fff" opacity="0.5"/>
                </svg>`,
                cinema: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="3" y="3" width="16" height="16" stroke="#c8a84b" stroke-width="0.8" fill="none"/>
                    ${[3,7,11,15].map(x => `<rect x="${x}" y="3" width="2" height="3" rx="0.5" fill="#c8a84b" opacity="0.5"/>`).join('')}
                    ${[3,7,11,15].map(x => `<rect x="${x}" y="16" width="2" height="3" rx="0.5" fill="#c8a84b" opacity="0.5"/>`).join('')}
                </svg>`,
                polaroid: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="4" y="4" width="14" height="14" rx="1" stroke="#ccc" stroke-width="0.8" fill="rgba(255,255,255,0.8)"/>
                    <rect x="4" y="15" width="14" height="3" fill="#f0ede8"/>
                    <circle cx="11" cy="10" r="3" stroke="#ddd" stroke-width="0.5" fill="none"/>
                </svg>`,
                sakura: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <ellipse cx="11" cy="11" rx="3" ry="5" fill="#ffb7c5" opacity="0.8"/>
                    <ellipse cx="11" cy="11" rx="3" ry="5" fill="#ff9ab0" opacity="0.7" transform="rotate(72,11,11)"/>
                    <ellipse cx="11" cy="11" rx="3" ry="5" fill="#ffb7c5" opacity="0.7" transform="rotate(144,11,11)"/>
                    <ellipse cx="11" cy="11" rx="3" ry="5" fill="#ff9ab0" opacity="0.7" transform="rotate(216,11,11)"/>
                    <ellipse cx="11" cy="11" rx="3" ry="5" fill="#ffb7c5" opacity="0.6" transform="rotate(288,11,11)"/>
                    <circle cx="11" cy="11" r="2" fill="#ffdd99"/>
                </svg>`,
            };
            return icons[id] || '';
        },

        // ─── Filter Styles ────────────────────────────────────────────────

        getFilterStyle() {
            const styles = {
                vintage: 'filter:sepia(0.3) contrast(1.1) brightness(0.9) saturate(0.8);',
                bw:      'filter:grayscale(1) contrast(1.2);',
                y2k:     'filter:hue-rotate(20deg) saturate(1.5) contrast(1.1);',
                sepia:   'filter:sepia(1);',
                dreamy:  'filter:brightness(1.1) saturate(1.2) blur(0.5px);',
            };
            return styles[this.activeFilter] || '';
        },

        getFilterPreviewStyle(id) {
            const styles = {
                vintage: 'background:#d2b48c; filter:sepia(0.3) contrast(1.1);',
                bw:      'background:#888; filter:grayscale(1);',
                y2k:     'background:#ff00ff; filter:hue-rotate(20deg) saturate(1.5);',
                sepia:   'background:#704214; filter:sepia(1);',
                dreamy:  'background:#fff; filter:brightness(1.1) saturate(1.2) blur(0.5px);',
                none:    'background:linear-gradient(135deg,#e0e0e0,#f5f5f5);',
            };
            return styles[id] || 'background:#ccc;';
        },

        // ─── Actions ──────────────────────────────────────────────────────

        async downloadStrip() {
            const blob = await this.generateStripBlob();
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `bloxpin-${this.activeFrame}-${Date.now()}.jpg`;
            a.click();
        },

        async saveToBloxpin() {
            if (!{{ Auth::check() ? 'true' : 'false' }}) {
                window.location.href = "{{ route('login') }}";
                return;
            }
            this.isSaving = true;
            try {
                const blob = await this.generateStripBlob();
                const formData = new FormData();
                formData.append('image[]', blob, 'photobooth.jpg');
                formData.append('title', 'Photobooth Moment');
                formData.append('description', 'Captured with Bloxpin Photobooth #photobooth');

                const response = await axios.post("{{ route('photos.store') }}", formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                window.showToast && window.showToast('Berhasil diproses!');
                const photoUid = response.data.photos[0].uid;
                window.location.href = `/photo/${photoUid}/edit`;
            } catch (err) {
                console.error(err);
                window.showToast && window.showToast('Gagal menyimpan foto.', 'error');
            } finally {
                this.isSaving = false;
            }
        },

        // ─── Canvas Export ────────────────────────────────────────────────

        async generateStripBlob() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            const imgW  = 450;
            const imgH  = 600;
            const pad   = 30;
            const topDec   = 60;
            const btmDec   = 100;

            if (this.activeLayout === 'strip') {
                canvas.width  = imgW + pad * 2;
                canvas.height = (imgH * 4) + (pad * 5) + topDec + btmDec;
            } else {
                canvas.width  = (imgW * 2) + (pad * 3);
                canvas.height = (imgH * 2) + (pad * 3) + topDec + btmDec;
            }

            // Background fill
            const bgMap = {
                classic:   () => { ctx.fillStyle = '#ffffff'; },
                modern:    () => { ctx.fillStyle = '#111111'; },
                bloxpin:   () => { ctx.fillStyle = '#E60023'; },
                vaporwave: () => {
                    const g = ctx.createLinearGradient(0,0,canvas.width,canvas.height);
                    g.addColorStop(0,'#ff00ff'); g.addColorStop(1,'#00ffff');
                    ctx.fillStyle = g;
                },
                pink:      () => { ctx.fillStyle = '#ffc0cb'; },
                celestial: () => { ctx.fillStyle = '#0b0e27'; },
                botanica:  () => { ctx.fillStyle = '#e8f0e0'; },
                cinema:    () => { ctx.fillStyle = '#1a1209'; },
                polaroid:  () => { ctx.fillStyle = '#fafaf5'; },
                sakura:    () => {
                    const g = ctx.createLinearGradient(0,0,0,canvas.height);
                    g.addColorStop(0,'#fff0f5'); g.addColorStop(1,'#ffe4ef');
                    ctx.fillStyle = g;
                },
            };
            (bgMap[this.activeFrame] || bgMap.classic)();
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Draw decorative top header
            await this._drawSvgDecoration(ctx, 'top', canvas.width, topDec, pad, 0);

            // Draw photos
            for (let i = 0; i < 4; i++) {
                const img = new Image();
                img.src = this.capturedImages[i];
                await new Promise(r => img.onload = r);
                
                let x, y;
                if (this.activeLayout === 'strip') {
                    x = pad;
                    y = topDec + pad + i * (imgH + pad);
                } else {
                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    x = pad + col * (imgW + pad);
                    y = topDec + pad + row * (imgH + pad);
                }

                ctx.save();
                const filterMap = {
                    vintage: 'sepia(0.3) contrast(1.1) brightness(0.9) saturate(0.8)',
                    bw:      'grayscale(1) contrast(1.2)',
                    y2k:     'hue-rotate(20deg) saturate(1.5) contrast(1.1)',
                    sepia:   'sepia(1)',
                    dreamy:  'brightness(1.1) saturate(1.2) blur(0.5px)',
                };
                if (filterMap[this.activeFilter]) ctx.filter = filterMap[this.activeFilter];
                ctx.drawImage(img, x, y, imgW, imgH);
                ctx.restore();
            }

            // Footer text
            const darkFrames = ['modern','bloxpin','celestial','cinema','vaporwave'];
            ctx.fillStyle = darkFrames.includes(this.activeFrame) ? 'rgba(255,255,255,0.7)' : '#999';
            ctx.font = 'bold 20px monospace';
            ctx.textAlign = 'center';
            ctx.fillText('BLOXPIN PHOTOBOOTH', canvas.width / 2, canvas.height - 50);
            ctx.font = '14px monospace';
            ctx.fillText(new Date().toLocaleDateString(), canvas.width / 2, canvas.height - 25);

            return new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.9));
        },

        async _drawSvgDecoration(ctx, position, canvasWidth, height, padX, offsetY) {
            // Simplified canvas decorations for export
            const frame = this.activeFrame;
            ctx.save();
            ctx.translate(0, offsetY);

            if (frame === 'cinema') {
                ctx.strokeStyle = '#c8a84b';
                ctx.lineWidth = 2;
                ctx.beginPath(); ctx.moveTo(0, height-10); ctx.lineTo(canvasWidth, height-10); ctx.stroke();
                ctx.fillStyle = '#c8a84b';
                for (let x = 10; x < canvasWidth - 30; x += 50) {
                    ctx.fillRect(x, 8, 30, 30);
                }
            } else if (frame === 'celestial') {
                ctx.fillStyle = '#a89bd4';
                ctx.font = '28px serif';
                ctx.textAlign = 'center';
                ctx.fillText('✦ ✧ ✦', canvasWidth/2, height - 10);
            } else if (frame === 'botanica') {
                ctx.strokeStyle = '#7aaa5a';
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.moveTo(0, height - 5);
                for (let x = 0; x <= canvasWidth; x += 20) {
                    ctx.quadraticCurveTo(x+10, height - 20, x+20, height - 5);
                }
                ctx.stroke();
            } else if (frame === 'sakura') {
                ctx.fillStyle = '#ffb7c5';
                ctx.font = '30px serif';
                ctx.textAlign = 'center';
                ctx.fillText('✿ ❀ ✿', canvasWidth/2, height - 8);
            } else if (frame === 'pink') {
                ctx.fillStyle = '#ff9ab0';
                ctx.font = '28px serif';
                ctx.textAlign = 'center';
                ctx.fillText('♥ ♡ ♥', canvasWidth/2, height - 8);
            }

            ctx.restore();
        }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection