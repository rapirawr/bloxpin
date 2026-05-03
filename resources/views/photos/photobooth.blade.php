@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8" x-data="photobooth()">

    <div class="flex flex-col lg:grid lg:grid-cols-[380px_1fr] gap-6 lg:gap-12 items-start pb-[200px] lg:pb-0 relative">
        <!-- Left: Control Center (Desktop Only) -->
        <div class="space-y-6 hidden lg:block">
            <div class="bg-white dark:bg-card p-8 rounded-[40px] border border-borderlight dark:border-borderdark shadow-sm space-y-6">

                <!-- Layout Selection -->
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center justify-between">
                        <span>Pilih Layout</span>
                        <span class="text-pinterest capitalize" x-text="activeLayout"></span>
                    </h3>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="activeLayout = 'single'" class="group flex flex-col items-center gap-2">
                            <div class="w-full h-10 rounded-lg transition-all duration-300 flex items-center justify-center border-2 p-1"
                                 :class="activeLayout === 'single' ? 'border-pinterest bg-pinterest/5 scale-105 shadow-md' : 'border-gray-100 dark:border-white/10 hover:border-gray-200'">
                                <div class="w-full h-full bg-gray-300 rounded-[1px]"></div>
                            </div>
                            <span class="text-[7px] font-bold uppercase tracking-tighter" :class="activeLayout === 'single' ? 'text-dark dark:text-white' : 'text-gray-400'">Single</span>
                        </button>
                        <button @click="activeLayout = 'double'" class="group flex flex-col items-center gap-2">
                            <div class="w-full h-10 rounded-lg transition-all duration-300 flex flex-col items-center justify-center gap-0.5 border-2 p-1"
                                 :class="activeLayout === 'double' ? 'border-pinterest bg-pinterest/5 scale-105 shadow-md' : 'border-gray-100 dark:border-white/10 hover:border-gray-200'">
                                <div class="w-full h-full bg-gray-300 rounded-[1px]"></div>
                                <div class="w-full h-full bg-gray-300 rounded-[1px]"></div>
                            </div>
                            <span class="text-[7px] font-bold uppercase tracking-tighter" :class="activeLayout === 'double' ? 'text-dark dark:text-white' : 'text-gray-400'">Double</span>
                        </button>
                        <button @click="activeLayout = 'trio'" class="group flex flex-col items-center gap-2">
                            <div class="w-full h-10 rounded-lg transition-all duration-300 flex flex-col items-center justify-center gap-0.5 border-2 p-1"
                                 :class="activeLayout === 'trio' ? 'border-pinterest bg-pinterest/5 scale-105 shadow-md' : 'border-gray-100 dark:border-white/10 hover:border-gray-200'">
                                <div class="w-full h-full bg-gray-300 rounded-[1px]"></div>
                                <div class="w-full h-full bg-gray-300 rounded-[1px]"></div>
                                <div class="w-full h-full bg-gray-300 rounded-[1px]"></div>
                            </div>
                            <span class="text-[7px] font-bold uppercase tracking-tighter" :class="activeLayout === 'trio' ? 'text-dark dark:text-white' : 'text-gray-400'">Trio</span>
                        </button>
                        <button @click="activeLayout = 'strip'" class="group flex flex-col items-center gap-2">
                            <div class="w-full h-10 rounded-lg transition-all duration-300 flex flex-col items-center justify-center gap-0.5 border-2 p-1"
                                 :class="activeLayout === 'strip' ? 'border-pinterest bg-pinterest/5 scale-105 shadow-md' : 'border-gray-100 dark:border-white/10 hover:border-gray-200'">
                                <div class="w-2/3 h-full bg-gray-300 rounded-[1px] flex flex-col gap-0.5">
                                    <div class="bg-gray-400/50 w-full h-full"></div>
                                    <div class="bg-gray-400/50 w-full h-full"></div>
                                    <div class="bg-gray-400/50 w-full h-full"></div>
                                    <div class="bg-gray-400/50 w-full h-full"></div>
                                </div>
                            </div>
                            <span class="text-[7px] font-bold uppercase tracking-tighter" :class="activeLayout === 'strip' ? 'text-dark dark:text-white' : 'text-gray-400'">Strip</span>
                        </button>
                        <button @click="activeLayout = 'grid'" class="group flex flex-col items-center gap-2">
                            <div class="w-full h-10 rounded-lg transition-all duration-300 grid grid-cols-2 gap-0.5 border-2 p-1"
                                 :class="activeLayout === 'grid' ? 'border-pinterest bg-pinterest/5 scale-105 shadow-md' : 'border-gray-100 dark:border-white/10 hover:border-gray-200'">
                                <div class="bg-gray-300 rounded-[1px]"></div>
                                <div class="bg-gray-300 rounded-[1px]"></div>
                                <div class="bg-gray-300 rounded-[1px]"></div>
                                <div class="bg-gray-300 rounded-[1px]"></div>
                            </div>
                            <span class="text-[7px] font-bold uppercase tracking-tighter" :class="activeLayout === 'grid' ? 'text-dark dark:text-white' : 'text-gray-400'">Grid</span>
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
                <div class="pt-2 space-y-3" x-show="capturedImages.length === maxCaptures">
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

        </div>

        <!-- Right: Studio Area -->
        <div class="space-y-4 w-full order-first lg:order-none">
            <div class="flex flex-col lg:grid lg:md:grid-cols-2 gap-4 lg:gap-6">

                <!-- ── CAMERA VIEW ── -->
                <div class="relative flex flex-col items-center w-full" x-ref="cameraWrap">
                    <div class="relative bg-black rounded-[24px] lg:rounded-[40px] overflow-hidden shadow-2xl border-4 lg:border-8 border-white dark:border-card ring-1 ring-black/5 mx-auto w-full mobile-camera-box"
                         style="aspect-ratio:3/4;"
                         :class="isProcessing ? 'camera-shake-active' : ''"
                         x-ref="cameraBox">
                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover transition-transform duration-300" :class="facingMode === 'user' ? '-scale-x-100' : ''"></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>

                        <!-- Camera Error Overlay -->
                        <div x-show="cameraError" style="display: none;" class="absolute inset-0 bg-black/80 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center z-40">
                            <div class="w-16 h-16 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">Akses Kamera Ditolak</h3>
                            <p class="text-gray-300 text-sm mb-6">Bloxpin membutuhkan izin akses kamera untuk menggunakan fitur photobooth. Silakan izinkan kamera di browser Anda.</p>
                            <button @click="startStream()" class="px-6 py-2 bg-white text-black font-bold rounded-full hover:scale-105 active:scale-95 transition-transform">
                                Coba Lagi / Minta Izin
                            </button>
                        </div>

                        <!-- Flip Camera Button -->
                        <button @click="switchCamera"
                                :disabled="isStreaming === false || isProcessing"
                                class="absolute top-4 right-4 w-10 h-10 bg-black/40 backdrop-blur-md border border-white/20 rounded-full flex items-center justify-center text-white shadow-lg transition-transform active:scale-90 disabled:opacity-50 z-30 hover:bg-black/60">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>

                        <!-- Countdown Dramatis -->
                        <div x-show="countdown > 0"
                             class="absolute inset-0 flex items-center justify-center bg-black/65 backdrop-blur-sm z-10">
                            <!-- Ring Progress SVG -->
                            <svg class="absolute w-40 h-40 lg:w-48 lg:h-48" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="35" stroke="rgba(255,255,255,0.08)" stroke-width="2" fill="none"/>
                                <circle cx="50" cy="50" r="35"
                                    :stroke="countdown === 3 ? '#ff4444' : countdown === 2 ? '#ffcc00' : '#44ff88'"
                                    stroke-width="2.5" fill="none"
                                    stroke-linecap="round"
                                    stroke-dasharray="220"
                                    :style="`transform:rotate(-90deg);transform-origin:50% 50%;stroke-dashoffset:${220 - (220/3)*(4-countdown)};transition:stroke-dashoffset 0.85s linear, stroke 0.25s ease;`">
                                </circle>
                            </svg>
                            <!-- Number -->
                            <span
                                class="font-black leading-none select-none countdown-num"
                                :class="{
                                    'countdown-3': countdown === 3,
                                    'countdown-2': countdown === 2,
                                    'countdown-1': countdown === 1,
                                }"
                                x-text="countdown">
                            </span>
                            <!-- Shot progress dots -->
                            <div class="absolute bottom-5 left-0 right-0 flex justify-center gap-2">
                                <template x-for="i in maxCaptures">
                                    <div class="rounded-full transition-all duration-400"
                                         :class="capturedImages.length >= i
                                             ? 'w-3 h-3 bg-white shadow-glow scale-110'
                                             : 'w-2 h-2 bg-white/30'">
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Flash overlay -->
                        <div x-show="flash"
                             class="absolute inset-0 bg-white z-20 pointer-events-none shutter-flash"></div>

                        <!-- Mobile shutter button (overlaid on camera) -->
                        <button @click="startCapture"
                                :disabled="isStreaming === false || isProcessing"
                                class="lg:hidden absolute bottom-4 left-1/2 -translate-x-1/2 w-16 h-16 bg-white/20 backdrop-blur-md border-2 border-white rounded-full flex items-center justify-center shadow-lg transition-transform active:scale-90 disabled:opacity-50 z-30">
                            <div class="w-12 h-12 bg-white rounded-full shadow-inner flex items-center justify-center">
                                <svg class="w-6 h-6 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <circle cx="12" cy="13" r="3" stroke-width="2"/>
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Desktop shutter button -->
                    <button @click="startCapture"
                            :disabled="isStreaming === false || isProcessing"
                            class="hidden lg:flex w-full btn-primary py-5 rounded-[24px] items-center justify-center gap-3 disabled:opacity-50 shadow-xl shadow-pinterest/20 transition-all hover:scale-[1.02] active:scale-95 mt-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-black uppercase tracking-widest text-sm">Ambil Foto</span>
                    </button>
                </div>

                <!-- ── STRIP PREVIEW ── -->
                <div class="flex flex-col items-center justify-start bg-gray-50 dark:bg-white/5 rounded-[24px] lg:rounded-[40px] p-4 lg:p-8 border-2 border-dashed border-gray-200 dark:border-white/10 mobile-strip-area"
                     :class="capturedImages.length > 0 ? 'flex' : 'hidden lg:flex'"
                     style="min-height: 200px;">
                    <div id="strip-preview-wrapper"
                         class="transition-all duration-700 w-full flex justify-center"
                         :class="capturedImages.length > 0 ? 'opacity-100' : 'opacity-20 grayscale'">
                        <div id="strip-container"
                             :style="getFrameStyle()"
                             class="relative overflow-hidden shadow-2xl transition-all duration-500"
                             :style="(activeLayout === 'grid' ? 'width:min(280px,80vw);' : 'width:min(160px,42vw);') + getFrameStyle()">
                            <!-- Frame Decoration Top -->
                            <div x-html="getFrameDecorationHTML('top')" class="flex items-center justify-center" style="padding:10px 10px 0;"></div>

                            <!-- Photos Grid/Stack -->
                            <div :class="activeLayout === 'grid' ? 'grid grid-cols-2 gap-[6px] p-[10px]' : 'flex flex-col gap-[6px] p-[10px]'">
                                <template x-for="(img, index) in Array.from({length: maxCaptures})">
                                    <div class="overflow-hidden rounded-sm photo-slot" style="aspect-ratio:3/4; position:relative;">
                                        <template x-if="capturedImages[index]">
                                            <img :src="capturedImages[index]"
                                                 class="w-full h-full object-cover photo-enter"
                                                 :style="getFilterStyle()">
                                        </template>
                                        <template x-if="!capturedImages[index]">
                                            <div class="w-full h-full flex items-center justify-center" :style="getEmptySlotStyle()">
                                                <span class="font-black opacity-30 text-xl" x-text="index + 1"></span>
                                            </div>
                                        </template>
                                        <div x-html="getFrameCornerHTML()" class="absolute inset-0 pointer-events-none"></div>
                                    </div>
                                </template>
                            </div>

                            <!-- Frame Decoration Bottom -->
                            <div x-html="getFrameDecorationHTML('bottom')" class="flex flex-col items-center gap-0.5" style="padding:0 10px 10px;"></div>
                        </div>
                    </div>

                    <p x-show="capturedImages.length === 0" class="mt-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Hasil foto akan muncul di sini</p>

                    <div x-show="capturedImages.length > 0" class="mt-4">
                        <button @click="capturedImages = []" class="text-[10px] font-bold text-pinterest uppercase tracking-widest hover:underline">Ulangi Sesi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
         MOBILE CONTROL CENTER — Fixed Bottom Panel
         ══════════════════════════════════════════════ -->
    <div class="lg:hidden fixed bottom-0 inset-x-0 bg-dark/95 backdrop-blur-2xl border-t border-white/10 z-40 rounded-t-[28px] shadow-[0_-20px_60px_rgba(0,0,0,0.6)]" style="padding-bottom: env(safe-area-inset-bottom);">
        <!-- Drag handle -->
        <div class="flex justify-center pt-2.5 pb-1">
            <div class="w-10 h-1 rounded-full bg-white/20"></div>
        </div>

        <!-- Tab Selectors -->
        <div class="flex px-6 mb-3 relative border-b border-white/5 pb-2">
            <button class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-[0.2em] transition-colors relative"
                    :class="editTab === 'layout' ? 'text-white' : 'text-white/40 hover:text-white/70'"
                    @click="editTab = 'layout'">
                Layout
                <div x-show="editTab === 'layout'" class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-0.5 rounded-full bg-pinterest"></div>
            </button>
            <button class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-[0.2em] transition-colors relative"
                    :class="editTab === 'frame' ? 'text-white' : 'text-white/40 hover:text-white/70'"
                    @click="editTab = 'frame'">
                Frame
                <div x-show="editTab === 'frame'" class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-0.5 rounded-full bg-pinterest"></div>
            </button>
            <button class="flex-1 py-1.5 text-[10px] font-black uppercase tracking-[0.2em] transition-colors relative"
                    :class="editTab === 'filter' ? 'text-white' : 'text-white/40 hover:text-white/70'"
                    @click="editTab = 'filter'">
                Filter
                <div x-show="editTab === 'filter'" class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-0.5 rounded-full bg-pinterest"></div>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="px-4 pb-4 overflow-x-auto hide-scrollbar touch-pan-x">
            <div class="flex items-center min-h-[100px]">

                <!-- Layouts -->
                <div x-show="editTab === 'layout'"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-x-3"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="flex gap-3 min-w-max px-1 py-2">
                    <button @click="activeLayout = 'single'" class="flex flex-col items-center gap-2 w-14">
                        <div class="w-12 h-14 rounded-xl flex items-center justify-center p-1.5 border transition-all duration-300"
                             :class="activeLayout === 'single' ? 'bg-white/15 border-white/60 scale-105 shadow-lg shadow-white/10' : 'bg-white/5 border-white/10'">
                            <div class="w-full h-full bg-white/80 rounded-sm"></div>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-widest" :class="activeLayout === 'single' ? 'text-white' : 'text-white/40'">1×1</span>
                    </button>
                    <button @click="activeLayout = 'double'" class="flex flex-col items-center gap-2 w-14">
                        <div class="w-12 h-14 rounded-xl flex flex-col gap-0.5 items-center justify-center p-1.5 border transition-all duration-300"
                             :class="activeLayout === 'double' ? 'bg-white/15 border-white/60 scale-105 shadow-lg shadow-white/10' : 'bg-white/5 border-white/10'">
                            <div class="w-full h-full bg-white/80 rounded-sm"></div>
                            <div class="w-full h-full bg-white/80 rounded-sm"></div>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-widest" :class="activeLayout === 'double' ? 'text-white' : 'text-white/40'">2×1</span>
                    </button>
                    <button @click="activeLayout = 'trio'" class="flex flex-col items-center gap-2 w-14">
                        <div class="w-12 h-14 rounded-xl flex flex-col gap-0.5 items-center justify-center p-1.5 border transition-all duration-300"
                             :class="activeLayout === 'trio' ? 'bg-white/15 border-white/60 scale-105 shadow-lg shadow-white/10' : 'bg-white/5 border-white/10'">
                            <div class="w-full h-full bg-white/80 rounded-sm"></div>
                            <div class="w-full h-full bg-white/80 rounded-sm"></div>
                            <div class="w-full h-full bg-white/80 rounded-sm"></div>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-widest" :class="activeLayout === 'trio' ? 'text-white' : 'text-white/40'">3×1</span>
                    </button>
                    <button @click="activeLayout = 'strip'" class="flex flex-col items-center gap-2 w-14">
                        <div class="w-12 h-14 rounded-xl flex flex-col gap-[1px] items-center justify-center p-1.5 border transition-all duration-300"
                             :class="activeLayout === 'strip' ? 'bg-white/15 border-white/60 scale-105 shadow-lg shadow-white/10' : 'bg-white/5 border-white/10'">
                            <div class="w-2/3 h-full bg-white/80 rounded-sm flex flex-col gap-[1px]">
                                <div class="bg-dark/40 w-full h-full"></div>
                                <div class="bg-dark/40 w-full h-full"></div>
                                <div class="bg-dark/40 w-full h-full"></div>
                                <div class="bg-dark/40 w-full h-full"></div>
                            </div>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-widest" :class="activeLayout === 'strip' ? 'text-white' : 'text-white/40'">Strip</span>
                    </button>
                    <button @click="activeLayout = 'grid'" class="flex flex-col items-center gap-2 w-14">
                        <div class="w-12 h-14 rounded-xl grid grid-cols-2 gap-0.5 p-1.5 border transition-all duration-300"
                             :class="activeLayout === 'grid' ? 'bg-white/15 border-white/60 scale-105 shadow-lg shadow-white/10' : 'bg-white/5 border-white/10'">
                            <div class="bg-white/80 rounded-sm"></div>
                            <div class="bg-white/80 rounded-sm"></div>
                            <div class="bg-white/80 rounded-sm"></div>
                            <div class="bg-white/80 rounded-sm"></div>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-widest" :class="activeLayout === 'grid' ? 'text-white' : 'text-white/40'">Grid</span>
                    </button>
                </div>

                <!-- Frames -->
                <div x-show="editTab === 'frame'"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-x-3"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     style="display:none;"
                     class="flex gap-3 min-w-max px-1 py-2">
                    <template x-for="fr in frames" :key="fr.id">
                        <button @click="activeFrame = fr.id" class="flex flex-col items-center gap-2 w-[58px]">
                            <div class="w-[58px] h-[58px] rounded-[16px] transition-all duration-300 flex items-center justify-center overflow-hidden border border-white/10"
                                 :class="activeFrame === fr.id ? 'ring-2 ring-white scale-110 shadow-xl shadow-white/10' : 'opacity-75 hover:opacity-100'"
                                 :style="getFramePreviewStyle(fr.id)">
                                <div x-html="getFramePreviewIcon(fr.id)" class="w-full h-full flex items-center justify-center scale-110"></div>
                            </div>
                            <span class="text-[8px] font-bold uppercase tracking-widest truncate w-full text-center"
                                  :class="activeFrame === fr.id ? 'text-white' : 'text-white/40'"
                                  x-text="fr.name"></span>
                        </button>
                    </template>
                </div>

                <!-- Filters -->
                <div x-show="editTab === 'filter'"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-x-3"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     style="display:none;"
                     class="flex gap-3 min-w-max px-1 py-2">
                    <template x-for="f in filters" :key="f.id">
                        <button @click="activeFilter = f.id" class="flex flex-col items-center gap-2 w-[68px]">
                            <div class="w-[68px] h-[90px] rounded-[18px] transition-all duration-300 overflow-hidden border border-white/10 relative"
                                 :class="activeFilter === f.id ? 'ring-2 ring-white scale-105 shadow-xl' : 'opacity-75 hover:opacity-100'">
                                <div class="w-full h-full bg-white/10" :style="getFilterPreviewStyle(f.id)"></div>
                                <!-- Active checkmark -->
                                <div x-show="activeFilter === f.id"
                                     class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-white flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-[8px] font-bold uppercase tracking-widest"
                                  :class="activeFilter === f.id ? 'text-white' : 'text-white/40'"
                                  x-text="f.name"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Mobile Submit Actions (shows when all photos taken) -->
        <div x-show="capturedImages.length === maxCaptures"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="flex gap-3 px-5 pb-4 mt-1 border-t border-white/10 pt-4">
            <button @click="saveToBloxpin" :disabled="isSaving"
                    class="flex-1 h-12 bg-white text-dark rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center justify-center gap-2 active:scale-95 transition-all shadow-lg">
                <template x-if="isSaving">
                    <div class="w-3 h-3 border-2 border-dark border-t-transparent rounded-full animate-spin"></div>
                </template>
                <span x-text="isSaving ? 'Mempublish...' : 'Publish Foto'"></span>
            </button>
            <button @click="downloadStrip"
                    class="flex-1 h-12 bg-white/10 border border-white/10 text-white rounded-2xl font-bold text-[9px] uppercase tracking-widest active:scale-95 transition-all">
                Download
            </button>
            <button @click="capturedImages = []"
                    class="w-12 h-12 bg-white/5 border border-white/10 text-white/60 rounded-2xl flex items-center justify-center active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
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
        editTab: 'layout',
        isProcessing: false,
        isSaving: false,

        get maxCaptures() {
            const map = { single: 1, double: 2, trio: 3, strip: 4, grid: 4 };
            return map[this.activeLayout] || 4;
        },

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
            { id: 'y2k',       name: 'Y2K' },
            { id: 'korean',    name: 'Korean' },
            { id: 'halloween', name: 'Hallown' },
        ],

        maxCaptures: 4,
        facingMode: 'user',
        cameraError: false,

        async init() {
            await this.startStream();
        },

        async startStream() {
            if (this.$refs.video && this.$refs.video.srcObject) {
                this.$refs.video.srcObject.getTracks().forEach(t => t.stop());
            }
            try {
                this.cameraError = false;
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: this.facingMode, aspectRatio: 3/4 },
                    audio: false
                });
                this.$refs.video.srcObject = stream;
                this.isStreaming = true;
            } catch (err) {
                console.error('Camera error:', err);
                this.cameraError = true;
                window.showToast && window.showToast('Izin kamera ditolak atau tidak tersedia.', 'error');
            }
        },

        async switchCamera() {
            this.facingMode = this.facingMode === 'user' ? 'environment' : 'user';
            await this.startStream();
        },

        async startCapture() {
            this.capturedImages = [];
            this.isProcessing = true;

            for (let i = 0; i < this.maxCaptures; i++) {
                // Countdown 3..1
                for (let c = 3; c >= 1; c--) {
                    this.countdown = c;
                    // Trigger re-animation by briefly removing class
                    await this.$nextTick();
                    const el = document.querySelector('.countdown-num');
                    if (el) {
                        el.classList.remove('countdown-anim');
                        void el.offsetWidth; // reflow
                        el.classList.add('countdown-anim');
                    }
                    await new Promise(r => setTimeout(r, 1000));
                }
                this.countdown = 0;
                this.takeSnap(i);
                await new Promise(r => setTimeout(r, 700));
            }

            this.isProcessing = false;
        },

        takeSnap(index) {
            // Flash
            this.flash = true;
            setTimeout(() => this.flash = false, 150);

            // Camera shake on the box
            const box = this.$refs.cameraBox;
            if (box) {
                box.classList.add('camera-shake');
                setTimeout(() => box.classList.remove('camera-shake'), 400);
            }

            const video = this.$refs.video;
            const canvas = this.$refs.canvas;

            let targetW = video.videoWidth;
            let targetH = video.videoWidth * (4/3);
            if (targetH > video.videoHeight) {
                targetH = video.videoHeight;
                targetW = video.videoHeight * (3/4);
            }

            canvas.width  = targetW;
            canvas.height = targetH;
            const ctx = canvas.getContext('2d');

            if (this.facingMode === 'user') {
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
            }

            const offsetX = (video.videoWidth  - targetW) / 2;
            const offsetY = (video.videoHeight - targetH) / 2;
            ctx.drawImage(video, offsetX, offsetY, targetW, targetH, 0, 0, targetW, targetH);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            this.capturedImages.push(dataUrl);

            // Smooth slide-in on the newly added photo slot
            this.$nextTick(() => {
                const slots = document.querySelectorAll('#strip-container .photo-slot');
                const slot  = slots[index];
                if (slot) {
                    slot.classList.add('photo-slide-in');
                    setTimeout(() => slot.classList.remove('photo-slide-in'), 650);
                }
            });
        },

        reset() {
            this.capturedImages = [];
            this.activeFilter   = 'none';
            this.activeFrame    = 'classic';
        },

        // ─── Frame Styles ─────────────────────────────────────────────────

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
                y2k:       'background:linear-gradient(135deg,#ff6ef7,#a78bfa,#67e8f9,#fde68a);',
                korean:    'background:#fafafa; border:1px solid #f0e6e6;',
                halloween: 'background:#0d0d0d;',
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
                y2k:       'background:linear-gradient(135deg,#ff6ef7,#a78bfa,#67e8f9);',
                korean:    'background:#fafafa; border:1px solid #f0e6e6;',
                halloween: 'background:#0d0d0d;',
            };
            return styles[id] || 'background:#fff;';
        },

        getEmptySlotStyle() {
            const dark = ['modern','bloxpin','celestial','cinema','vaporwave','halloween','y2k'];
            const bg    = dark.includes(this.activeFrame) ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)';
            const color = dark.includes(this.activeFrame) ? 'rgba(255,255,255,0.3)'  : 'rgba(0,0,0,0.25)';
            return `background:${bg}; color:${color};`;
        },

        // ─── Frame SVG Decorations ────────────────────────────────────────

        getFrameDecorationHTML(position) {
            const decs = {

                classic: {
                    top: `<svg width="100" height="18" viewBox="0 0 100 18" fill="none">
                        <line x1="0" y1="9" x2="38" y2="9" stroke="#ddd" stroke-width="0.8"/>
                        <circle cx="50" cy="9" r="4" stroke="#ddd" stroke-width="0.8" fill="none"/>
                        <circle cx="50" cy="9" r="1.5" fill="#ddd"/>
                        <line x1="62" y1="9" x2="100" y2="9" stroke="#ddd" stroke-width="0.8"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="60" height="8" viewBox="0 0 60 8" fill="none">
                            <line x1="0" y1="4" x2="20" y2="4" stroke="#bbb" stroke-width="0.5"/>
                            <rect x="26" y="2" width="8" height="4" stroke="#bbb" stroke-width="0.5" fill="none"/>
                            <line x1="40" y1="4" x2="60" y2="4" stroke="#bbb" stroke-width="0.5"/>
                        </svg>
                        <span style="font-family:monospace;font-size:6px;color:#999;letter-spacing:0.25em;text-transform:uppercase;">BLOXPIN PHOTOBOOTH</span>
                        <span style="font-family:monospace;font-size:5px;color:#bbb;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                modern: {
                    top: `<svg width="120" height="14" viewBox="0 0 120 14" fill="none">
                        <line x1="0" y1="7" x2="120" y2="7" stroke="#333" stroke-width="0.5"/>
                        <rect x="50" y="3" width="20" height="8" fill="#E60023"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="100" height="6" viewBox="0 0 100 6" fill="none">
                            <line x1="0" y1="3" x2="100" y2="3" stroke="#333" stroke-width="0.5"/>
                        </svg>
                        <span style="font-family:monospace;font-size:6px;color:rgba(255,255,255,0.5);letter-spacing:0.25em;text-transform:uppercase;">BLOXPIN PHOTOBOOTH</span>
                        <span style="font-family:monospace;font-size:5px;color:#444;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                bloxpin: {
                    top: `<svg width="120" height="22" viewBox="0 0 120 22" fill="none">
                        <circle cx="60" cy="11" r="8" stroke="rgba(255,255,255,0.4)" stroke-width="0.8" fill="none"/>
                        <path d="M55 11 L60 7 L65 11 L60 15 Z" stroke="rgba(255,255,255,0.6)" stroke-width="0.8" fill="rgba(255,255,255,0.15)"/>
                        <circle cx="60" cy="11" r="2" fill="white" opacity="0.7"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <span style="font-family:monospace;font-size:7px;color:rgba(255,255,255,0.9);letter-spacing:0.3em;font-weight:700;text-transform:uppercase;">BLOXPIN</span>
                        <span style="font-family:monospace;font-size:5px;color:rgba(255,255,255,0.6);">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                vaporwave: {
                    top: `<svg width="120" height="24" viewBox="0 0 120 24" fill="none">
                        <text x="60" y="14" text-anchor="middle" font-size="10" font-family="monospace" fill="white" opacity="0.9" letter-spacing="4">V A P O R</text>
                        <line x1="10" y1="20" x2="110" y2="20" stroke="rgba(255,255,255,0.4)" stroke-width="0.5"/>
                    </svg>`,
                    bottom: `<div style="padding-top:6px;display:flex;flex-direction:column;align-items:center;">
                        <svg width="100" height="18" viewBox="0 0 100 18" fill="none">
                            <line x1="0" y1="3" x2="100" y2="3" stroke="rgba(255,255,255,0.3)" stroke-width="0.5"/>
                            <line x1="10" y1="8" x2="90" y2="8" stroke="rgba(255,255,255,0.2)" stroke-width="0.5"/>
                            <line x1="20" y1="13" x2="80" y2="13" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/>
                        </svg>
                        <span style="font-size:6px;color:rgba(255,255,255,0.85);font-family:monospace;letter-spacing:0.3em;text-transform:uppercase;">BLOXPIN</span>
                    </div>`,
                },

                pink: {
                    top: `<svg width="120" height="24" viewBox="0 0 120 24" fill="none">
                        <path d="M20 14 C20 10 14 8 14 13 C14 18 20 22 20 22 C20 22 26 18 26 13 C26 8 20 10 20 14Z" fill="#ff7ca3" opacity="0.5"/>
                        <path d="M60 14 C60 10 54 8 54 13 C54 18 60 22 60 22 C60 22 66 18 66 13 C66 8 60 10 60 14Z" fill="#ff7ca3" opacity="0.7"/>
                        <path d="M100 14 C100 10 94 8 94 13 C94 18 100 22 100 22 C100 22 106 18 106 13 C106 8 100 10 100 14Z" fill="#ff7ca3" opacity="0.5"/>
                        <line x1="30" y1="14" x2="50" y2="14" stroke="#ffaac0" stroke-width="0.5"/>
                        <line x1="70" y1="14" x2="90" y2="14" stroke="#ffaac0" stroke-width="0.5"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="80" height="12" viewBox="0 0 80 12" fill="none">
                            <path d="M10 7 C10 4 7 3 7 6 C7 9 10 11 10 11 C10 11 13 9 13 6 C13 3 10 4 10 7Z" fill="#ff9ab5"/>
                            <path d="M40 7 C40 4 37 3 37 6 C37 9 40 11 40 11 C40 11 43 9 43 6 C43 3 40 4 40 7Z" fill="#ff9ab5"/>
                            <path d="M70 7 C70 4 67 3 67 6 C67 9 70 11 70 11 C70 11 73 9 73 6 C73 3 70 4 70 7Z" fill="#ff9ab5"/>
                        </svg>
                        <span style="font-size:6px;color:#c06080;font-family:monospace;letter-spacing:0.25em;text-transform:uppercase;">BLOXPIN PHOTOBOOTH</span>
                    </div>`,
                },

                celestial: {
                    top: `<svg width="140" height="40" viewBox="0 0 140 40" fill="none">
                        <path d="M70 8 A14 14 0 1 1 70 36 A8 8 0 1 0 70 8Z" fill="none" stroke="#a89bd4" stroke-width="0.8"/>
                        <circle cx="15" cy="20" r="1" fill="#fff" opacity="0.6"/>
                        <circle cx="35" cy="8"  r="1.2" fill="#fff" opacity="0.8"/>
                        <circle cx="105" cy="15" r="0.8" fill="#ffd700" opacity="0.7"/>
                        <circle cx="120" cy="6"  r="1.5" fill="#fff" opacity="0.5"/>
                        <circle cx="128" cy="25" r="0.8" fill="#a89bd4" opacity="0.8"/>
                        <path d="M118 18 L120 14 L122 18 L120 22 Z" fill="#ffd700" opacity="0.6"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="120" height="16" viewBox="0 0 120 16" fill="none">
                            <line x1="0" y1="8" x2="42" y2="8" stroke="#a89bd4" stroke-width="0.4" stroke-dasharray="2 3"/>
                            <path d="M54 8 L57 4 L60 8 L57 12 Z" fill="#ffd700" opacity="0.8"/>
                            <line x1="78" y1="8" x2="120" y2="8" stroke="#a89bd4" stroke-width="0.4" stroke-dasharray="2 3"/>
                        </svg>
                        <span style="font-size:6px;color:#a89bd4;font-family:monospace;letter-spacing:0.3em;text-transform:uppercase;">BLOXPIN · CELESTIAL</span>
                        <span style="font-size:5px;color:#4a4870;font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                botanica: {
                    top: `<svg width="140" height="42" viewBox="0 0 140 42" fill="none">
                        <path d="M0 38 Q10 20 25 28 Q12 32 0 38Z" fill="#5a8a4a" opacity="0.7"/>
                        <path d="M5 42 Q20 25 30 35 Q16 38 5 42Z" fill="#7aaa5a" opacity="0.5"/>
                        <path d="M140 38 Q130 20 115 28 Q128 32 140 38Z" fill="#5a8a4a" opacity="0.7"/>
                        <path d="M135 42 Q120 25 110 35 Q124 38 135 42Z" fill="#7aaa5a" opacity="0.5"/>
                        <circle cx="70" cy="8" r="5" fill="#ff7070" opacity="0.7"/>
                        <circle cx="70" cy="8" r="2" fill="#fff" opacity="0.5"/>
                        <line x1="70" y1="13" x2="70" y2="42" stroke="#5a8a4a" stroke-width="0.8"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="120" height="18" viewBox="0 0 120 18" fill="none">
                            <path d="M0 9 Q30 4 60 9 Q90 14 120 9" stroke="#7aaa5a" stroke-width="0.6" fill="none"/>
                        </svg>
                        <span style="font-size:6px;color:#4a7a3a;font-family:Georgia,serif;letter-spacing:0.2em;font-style:italic;">Bloxpin Botanica</span>
                        <span style="font-size:5px;color:#8aaa7a;font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                cinema: {
                    top: `<svg width="140" height="28" viewBox="0 0 140 28" fill="none">
                        ${Array.from({length:11}, (_,i) =>
                            `<rect x="${i*13+2}" y="4" width="8" height="8" rx="1" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.15)" stroke-width="0.5"/>`
                        ).join('')}
                        <line x1="0" y1="20" x2="140" y2="20" stroke="#c8a84b" stroke-width="0.6"/>
                        <text x="70" y="28" text-anchor="middle" font-size="8" fill="#c8a84b" opacity="0.8">★ ★ ★ ★ ★</text>
                    </svg>`,
                    bottom: `<div style="padding-top:4px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="140" height="20" viewBox="0 0 140 20" fill="none">
                            <line x1="0" y1="6" x2="140" y2="6" stroke="#c8a84b" stroke-width="0.6"/>
                            ${Array.from({length:11}, (_,i) =>
                                `<rect x="${i*13+2}" y="10" width="8" height="8" rx="1" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.15)" stroke-width="0.5"/>`
                            ).join('')}
                        </svg>
                        <span style="font-size:7px;color:#c8a84b;font-family:Georgia,serif;letter-spacing:0.4em;text-transform:uppercase;">BLOXPIN CINEMA</span>
                        <span style="font-size:5px;color:rgba(200,168,75,0.5);font-family:monospace;letter-spacing:0.2em;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                polaroid: {
                    top: `<svg width="120" height="20" viewBox="0 0 120 20" fill="none">
                        <rect x="48" y="4" width="24" height="14" rx="2" stroke="#ddd" stroke-width="0.8" fill="none"/>
                        <circle cx="62" cy="10" r="4" stroke="#ddd" stroke-width="0.5" fill="none"/>
                        <circle cx="66" cy="7" r="1.5" fill="#ffd700" opacity="0.6"/>
                    </svg>`,
                    bottom: `<div style="padding-top:8px;display:flex;flex-direction:column;align-items:center;gap:3px;">
                        <span style="font-size:9px;color:#555;font-family:'Helvetica Neue',sans-serif;letter-spacing:-0.02em;font-weight:300;">memories</span>
                        <span style="font-size:5.5px;color:#aaa;font-family:monospace;letter-spacing:0.2em;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                sakura: {
                    top: `<svg width="140" height="38" viewBox="0 0 140 38" fill="none">
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ffb7c5" opacity="0.8"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ffb7c5" opacity="0.7" transform="rotate(72,70,10)"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ff9ab0" opacity="0.7" transform="rotate(144,70,10)"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ffb7c5" opacity="0.7" transform="rotate(216,70,10)"/>
                        <ellipse cx="70" cy="10" rx="5" ry="8" fill="#ff9ab0" opacity="0.6" transform="rotate(288,70,10)"/>
                        <circle cx="70" cy="10" r="3" fill="#ffdd99"/>
                        <path d="M0 38 Q20 25 40 30 Q55 34 70 38" stroke="#c8a0a8" stroke-width="0.6" fill="none"/>
                        <path d="M140 38 Q120 25 100 30 Q85 34 70 38" stroke="#c8a0a8" stroke-width="0.6" fill="none"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="100" height="14" viewBox="0 0 100 14" fill="none">
                            <ellipse cx="10" cy="7" rx="4" ry="6" fill="#ffb7c5" opacity="0.6" transform="rotate(-15,10,7)"/>
                            <ellipse cx="90" cy="7" rx="4" ry="6" fill="#ff9ab0" opacity="0.6" transform="rotate(15,90,7)"/>
                            <line x1="18" y1="7" x2="82" y2="7" stroke="#f0c0d0" stroke-width="0.5"/>
                        </svg>
                        <span style="font-size:6px;color:#c08090;font-family:Georgia,serif;font-style:italic;letter-spacing:0.15em;">Bloxpin · さくら</span>
                        <span style="font-size:5px;color:#daa0b0;font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                // ── Y2K ───────────────────────────────────────────────────
                y2k: {
                    top: `<svg width="140" height="30" viewBox="0 0 140 30" fill="none">
                        <defs>
                            <linearGradient id="y2kg" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#ff6ef7"/>
                                <stop offset="33%" stop-color="#a78bfa"/>
                                <stop offset="66%" stop-color="#67e8f9"/>
                                <stop offset="100%" stop-color="#fde68a"/>
                            </linearGradient>
                        </defs>
                        <line x1="0" y1="15" x2="140" y2="15" stroke="url(#y2kg)" stroke-width="1.5"/>
                        <circle cx="30" cy="15" r="5" fill="none" stroke="url(#y2kg)" stroke-width="1.5"/>
                        <circle cx="30" cy="15" r="2" fill="#ff6ef7" opacity="0.8"/>
                        <circle cx="70" cy="15" r="7" fill="none" stroke="url(#y2kg)" stroke-width="1.5"/>
                        <circle cx="70" cy="15" r="2.5" fill="#a78bfa"/>
                        <circle cx="110" cy="15" r="5" fill="none" stroke="url(#y2kg)" stroke-width="1.5"/>
                        <circle cx="110" cy="15" r="2" fill="#67e8f9" opacity="0.8"/>
                        <text x="70" y="7" text-anchor="middle" font-size="5" fill="rgba(255,255,255,0.8)" font-family="monospace" letter-spacing="3">Y 2 K</text>
                    </svg>`,
                    bottom: `<div style="padding-top:5px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="120" height="10" viewBox="0 0 120 10" fill="none">
                            <defs><linearGradient id="y2kb" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#ff6ef7"/>
                                <stop offset="50%" stop-color="#a78bfa"/>
                                <stop offset="100%" stop-color="#67e8f9"/>
                            </linearGradient></defs>
                            <line x1="0" y1="5" x2="120" y2="5" stroke="url(#y2kb)" stroke-width="1.5"/>
                        </svg>
                        <span style="font-size:7px;background:linear-gradient(90deg,#ff6ef7,#a78bfa,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-family:monospace;letter-spacing:0.3em;font-weight:900;text-transform:uppercase;">BLOXPIN Y2K</span>
                        <span style="font-size:5px;color:rgba(255,255,255,0.4);font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                // ── KOREAN MINIMALIST ──────────────────────────────────────
                korean: {
                    top: `<svg width="140" height="28" viewBox="0 0 140 28" fill="none">
                        <line x1="15" y1="14" x2="52" y2="14" stroke="#e8d5d5" stroke-width="0.6"/>
                        <circle cx="60" cy="14" r="4" stroke="#e8c4c4" stroke-width="0.8" fill="none"/>
                        <circle cx="70" cy="14" r="6" stroke="#d4a0a0" stroke-width="0.8" fill="none"/>
                        <circle cx="80" cy="14" r="4" stroke="#e8c4c4" stroke-width="0.8" fill="none"/>
                        <line x1="88" y1="14" x2="125" y2="14" stroke="#e8d5d5" stroke-width="0.6"/>
                        <circle cx="70" cy="14" r="2" fill="#d4a0a0" opacity="0.6"/>
                        <text x="70" y="6" text-anchor="middle" font-size="5" fill="#c9a0a0" font-family="serif" letter-spacing="2">✦ STUDIO ✦</text>
                    </svg>`,
                    bottom: `<div style="padding-top:6px;display:flex;flex-direction:column;align-items:center;gap:3px;">
                        <svg width="80" height="8" viewBox="0 0 80 8" fill="none">
                            <line x1="0" y1="4" x2="30" y2="4" stroke="#e8d5d5" stroke-width="0.5"/>
                            <circle cx="40" cy="4" r="3" stroke="#d4a0a0" stroke-width="0.5" fill="none"/>
                            <line x1="50" y1="4" x2="80" y2="4" stroke="#e8d5d5" stroke-width="0.5"/>
                        </svg>
                        <span style="font-size:7px;color:#c09090;font-family:Georgia,serif;font-style:italic;letter-spacing:0.15em;">bloxpin studio</span>
                        <span style="font-size:5px;color:#d4b0b0;font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },

                // ── HALLOWEEN ─────────────────────────────────────────────
                halloween: {
                    top: `<svg width="140" height="36" viewBox="0 0 140 36" fill="none">
                        <path d="M70 6 A12 12 0 1 1 70 30 A7 7 0 1 0 70 6Z" fill="none" stroke="#ff6600" stroke-width="0.8" opacity="0.8"/>
                        <path d="M15 20 Q12 14 8 16 Q10 20 15 20Z" fill="#ff6600" opacity="0.6"/>
                        <path d="M15 20 Q18 14 22 16 Q20 20 15 20Z" fill="#ff6600" opacity="0.6"/>
                        <circle cx="15" cy="20" r="2" fill="#1a0a00"/>
                        <path d="M125 20 Q122 14 118 16 Q120 20 125 20Z" fill="#ff6600" opacity="0.6"/>
                        <path d="M125 20 Q128 14 132 16 Q130 20 125 20Z" fill="#ff6600" opacity="0.6"/>
                        <circle cx="125" cy="20" r="2" fill="#1a0a00"/>
                        <ellipse cx="46" cy="26" rx="6" ry="5" fill="#ff6600" opacity="0.7"/>
                        <rect x="44" y="20" width="4" height="2" rx="1" fill="#3d8a3d" opacity="0.7"/>
                        <path d="M43 25 L45 28 L47 25" stroke="#1a0a00" stroke-width="0.8" fill="none"/>
                        <circle cx="44" cy="27" r="0.8" fill="#1a0a00"/>
                        <circle cx="48" cy="27" r="0.8" fill="#1a0a00"/>
                        <ellipse cx="94" cy="26" rx="6" ry="5" fill="#ff6600" opacity="0.7"/>
                        <rect x="92" y="20" width="4" height="2" rx="1" fill="#3d8a3d" opacity="0.7"/>
                        <path d="M91 25 L93 28 L95 25" stroke="#1a0a00" stroke-width="0.8" fill="none"/>
                        <circle cx="92" cy="27" r="0.8" fill="#1a0a00"/>
                        <circle cx="96" cy="27" r="0.8" fill="#1a0a00"/>
                    </svg>`,
                    bottom: `<div style="padding-top:4px;display:flex;flex-direction:column;align-items:center;gap:2px;">
                        <svg width="120" height="16" viewBox="0 0 120 16" fill="none">
                            <line x1="0" y1="8" x2="40" y2="8" stroke="#ff6600" stroke-width="0.4" stroke-dasharray="3 3"/>
                            <path d="M54 8 L56 5 L58 2 L60 5 L62 8 L60 11 L58 14 L56 11 Z" fill="#ff6600" opacity="0.7"/>
                            <line x1="80" y1="8" x2="120" y2="8" stroke="#ff6600" stroke-width="0.4" stroke-dasharray="3 3"/>
                        </svg>
                        <span style="font-size:7px;color:#ff6600;font-family:monospace;letter-spacing:0.3em;text-transform:uppercase;">BLOXPIN · 👻</span>
                        <span style="font-size:5px;color:rgba(255,102,0,0.5);font-family:monospace;">${new Date().toLocaleDateString()}</span>
                    </div>`,
                },
            };

            const dec = decs[this.activeFrame];
            if (!dec) return '';
            return dec[position] || '';
        },

        getFrameCornerHTML() {
            const corners = {
                celestial: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
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
                cinema: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
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
                polaroid: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                    <rect x="0.5" y="0.5" width="99" height="74" stroke="#e0ddd5" stroke-width="0.5" fill="none"/>
                </svg>`,
                botanica: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                    <path d="M0 8 Q4 4 8 0" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                    <path d="M92 0 Q96 4 100 8" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                    <path d="M0 67 Q4 71 8 75" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                    <path d="M92 75 Q96 71 100 67" stroke="#7aaa5a" stroke-width="0.8" fill="none" opacity="0.5"/>
                </svg>`,
                sakura: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                    <ellipse cx="4" cy="4" rx="3" ry="4" fill="#ffb7c5" opacity="0.35" transform="rotate(-30,4,4)"/>
                    <ellipse cx="96" cy="4" rx="3" ry="4" fill="#ff9ab0" opacity="0.35" transform="rotate(30,96,4)"/>
                </svg>`,
                y2k: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                    <defs><linearGradient id="y2kc" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#ff6ef7"/>
                        <stop offset="100%" stop-color="#67e8f9"/>
                    </linearGradient></defs>
                    <line x1="0" y1="0" x2="14" y2="0" stroke="url(#y2kc)" stroke-width="2"/>
                    <line x1="0" y1="0" x2="0" y2="14" stroke="url(#y2kc)" stroke-width="2"/>
                    <line x1="100" y1="0" x2="86" y2="0" stroke="url(#y2kc)" stroke-width="2"/>
                    <line x1="100" y1="0" x2="100" y2="14" stroke="url(#y2kc)" stroke-width="2"/>
                    <line x1="0" y1="75" x2="14" y2="75" stroke="url(#y2kc)" stroke-width="2"/>
                    <line x1="0" y1="75" x2="0" y2="61" stroke="url(#y2kc)" stroke-width="2"/>
                    <line x1="100" y1="75" x2="86" y2="75" stroke="url(#y2kc)" stroke-width="2"/>
                    <line x1="100" y1="75" x2="100" y2="61" stroke="url(#y2kc)" stroke-width="2"/>
                    <circle cx="2" cy="2" r="2.5" fill="#ff6ef7" opacity="0.6"/>
                    <circle cx="98" cy="2" r="2.5" fill="#67e8f9" opacity="0.6"/>
                    <circle cx="2" cy="73" r="2.5" fill="#a78bfa" opacity="0.6"/>
                    <circle cx="98" cy="73" r="2.5" fill="#fde68a" opacity="0.6"/>
                </svg>`,
                korean: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                    <rect x="0.5" y="0.5" width="99" height="74" stroke="#e8d5d5" stroke-width="0.6" fill="none"/>
                    <path d="M0 8 Q4 4 8 0" stroke="#d4a0a0" stroke-width="0.5" fill="none"/>
                    <path d="M92 0 Q96 4 100 8" stroke="#d4a0a0" stroke-width="0.5" fill="none"/>
                    <path d="M0 67 Q4 71 8 75" stroke="#d4a0a0" stroke-width="0.5" fill="none"/>
                    <path d="M92 75 Q96 71 100 67" stroke="#d4a0a0" stroke-width="0.5" fill="none"/>
                    <circle cx="3" cy="3" r="1.5" fill="#d4a0a0" opacity="0.4"/>
                    <circle cx="97" cy="3" r="1.5" fill="#d4a0a0" opacity="0.4"/>
                    <circle cx="3" cy="72" r="1.5" fill="#d4a0a0" opacity="0.4"/>
                    <circle cx="97" cy="72" r="1.5" fill="#d4a0a0" opacity="0.4"/>
                </svg>`,
                halloween: `<svg width="100%" height="100%" viewBox="0 0 100 75" fill="none" preserveAspectRatio="none" style="position:absolute;inset:0;">
                    <rect x="0.5" y="0.5" width="99" height="74" stroke="#ff6600" stroke-width="0.6" fill="none" stroke-dasharray="4 3" opacity="0.5"/>
                    <path d="M0 0 L10 0 L10 2 L2 2 L2 10 L0 10 Z" fill="#ff6600" opacity="0.6"/>
                    <path d="M100 0 L90 0 L90 2 L98 2 L98 10 L100 10 Z" fill="#ff6600" opacity="0.6"/>
                    <path d="M0 75 L10 75 L10 73 L2 73 L2 65 L0 65 Z" fill="#ff6600" opacity="0.6"/>
                    <path d="M100 75 L90 75 L90 73 L98 73 L98 65 L100 65 Z" fill="#ff6600" opacity="0.6"/>
                </svg>`,
                default: '',
            };
            return corners[this.activeFrame] || corners.default;
        },

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
                </svg>`,
                botanica: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <line x1="11" y1="19" x2="11" y2="8" stroke="#5a8a4a" stroke-width="0.8"/>
                    <circle cx="11" cy="6" r="3" fill="#ff7070" opacity="0.7"/>
                    <circle cx="11" cy="6" r="1.2" fill="#fff" opacity="0.5"/>
                </svg>`,
                cinema: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="3" y="3" width="16" height="16" stroke="#c8a84b" stroke-width="0.8" fill="none"/>
                    ${[3,7,11,15].map(x=>`<rect x="${x}" y="3" width="2" height="3" rx="0.5" fill="#c8a84b" opacity="0.5"/>`).join('')}
                    ${[3,7,11,15].map(x=>`<rect x="${x}" y="16" width="2" height="3" rx="0.5" fill="#c8a84b" opacity="0.5"/>`).join('')}
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
                y2k: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <defs><linearGradient id="y2ki" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#ff6ef7"/>
                        <stop offset="50%" stop-color="#a78bfa"/>
                        <stop offset="100%" stop-color="#67e8f9"/>
                    </linearGradient></defs>
                    <circle cx="11" cy="11" r="7" stroke="url(#y2ki)" stroke-width="1.5" fill="none"/>
                    <circle cx="11" cy="11" r="3" fill="url(#y2ki)" opacity="0.7"/>
                    <line x1="4" y1="11" x2="8" y2="11" stroke="url(#y2ki)" stroke-width="1"/>
                    <line x1="14" y1="11" x2="18" y2="11" stroke="url(#y2ki)" stroke-width="1"/>
                    <line x1="11" y1="4" x2="11" y2="8" stroke="url(#y2ki)" stroke-width="1"/>
                    <line x1="11" y1="14" x2="11" y2="18" stroke="url(#y2ki)" stroke-width="1"/>
                </svg>`,
                korean: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <rect x="3" y="3" width="16" height="16" stroke="#d4a0a0" stroke-width="0.8" fill="#fafafa"/>
                    <circle cx="11" cy="11" r="4" stroke="#d4a0a0" stroke-width="0.6" fill="none"/>
                    <circle cx="11" cy="11" r="1.5" fill="#d4a0a0" opacity="0.6"/>
                    <line x1="3" y1="11" x2="7" y2="11" stroke="#e8d5d5" stroke-width="0.5"/>
                    <line x1="15" y1="11" x2="19" y2="11" stroke="#e8d5d5" stroke-width="0.5"/>
                </svg>`,
                halloween: `<svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <ellipse cx="11" cy="13" rx="6" ry="5" fill="#ff6600" opacity="0.8"/>
                    <rect x="9" y="7" width="4" height="3" rx="1" fill="#3d8a3d" opacity="0.7"/>
                    <path d="M8 12 L10 15 L12 12" stroke="#0d0d0d" stroke-width="0.8" fill="none"/>
                    <circle cx="9" cy="13.5" r="0.8" fill="#0d0d0d"/>
                    <circle cx="13" cy="13.5" r="0.8" fill="#0d0d0d"/>
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
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href     = url;
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
                const blob     = await this.generateStripBlob();
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
            const ctx    = canvas.getContext('2d');

            const imgW   = 450;
            const imgH   = 600;
            const pad    = 30;
            const topDec = 60;
            const btmDec = 100;
            const count  = this.maxCaptures;

            if (this.activeLayout === 'grid') {
                canvas.width  = (imgW * 2) + (pad * 3);
                canvas.height = (imgH * 2) + (pad * 3) + topDec + btmDec;
            } else {
                canvas.width  = imgW + pad * 2;
                canvas.height = (imgH * count) + (pad * (count + 1)) + topDec + btmDec;
            }

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
                sakura: () => {
                    const g = ctx.createLinearGradient(0,0,0,canvas.height);
                    g.addColorStop(0,'#fff0f5'); g.addColorStop(1,'#ffe4ef');
                    ctx.fillStyle = g;
                },
                y2k: () => {
                    const g = ctx.createLinearGradient(0,0,canvas.width,canvas.height);
                    g.addColorStop(0,'#ff6ef7'); g.addColorStop(0.33,'#a78bfa');
                    g.addColorStop(0.66,'#67e8f9'); g.addColorStop(1,'#fde68a');
                    ctx.fillStyle = g;
                },
                korean:    () => { ctx.fillStyle = '#fafafa'; },
                halloween: () => { ctx.fillStyle = '#0d0d0d'; },
            };
            (bgMap[this.activeFrame] || bgMap.classic)();
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            await this._drawSvgDecoration(ctx, 'top', canvas.width, topDec, pad, 0);

            for (let i = 0; i < count; i++) {
                const img = new Image();
                img.src   = this.capturedImages[i];
                await new Promise(r => img.onload = r);

                let x, y;
                if (this.activeLayout === 'grid') {
                    const col = i % 2;
                    const row = Math.floor(i / 2);
                    x = pad + col * (imgW + pad);
                    y = topDec + pad + row * (imgH + pad);
                } else {
                    x = pad;
                    y = topDec + pad + i * (imgH + pad);
                }

                ctx.save();
                const filterMap = {
                    vintage: 'sepia(0.3) contrast(1.1) brightness(0.9) saturate(0.8)',
                    bw:      'grayscale(1) contrast(1.2)',
                    y2k:     'hue-rotate(20deg) saturate(1.5) contrast(1.1)',
                    sepia:   'sepia(1)',
                    dreamy:  'brightness(1.1) saturate(1.2)',
                };
                if (filterMap[this.activeFilter]) ctx.filter = filterMap[this.activeFilter];
                ctx.drawImage(img, x, y, imgW, imgH);
                ctx.restore();
            }

            const darkFrames = ['modern','bloxpin','celestial','cinema','vaporwave','halloween','y2k'];
            ctx.fillStyle    = darkFrames.includes(this.activeFrame) ? 'rgba(255,255,255,0.7)' : '#999';
            ctx.font         = 'bold 20px monospace';
            ctx.textAlign    = 'center';
            ctx.fillText('BLOXPIN PHOTOBOOTH', canvas.width / 2, canvas.height - 50);
            ctx.font         = '14px monospace';
            ctx.fillText(new Date().toLocaleDateString(), canvas.width / 2, canvas.height - 25);

            return new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.9));
        },

        async _drawSvgDecoration(ctx, position, canvasWidth, height, padX, offsetY) {
            const frame = this.activeFrame;
            ctx.save();
            ctx.translate(0, offsetY);

            if (frame === 'cinema') {
                ctx.strokeStyle = '#c8a84b';
                ctx.lineWidth   = 2;
                ctx.beginPath(); ctx.moveTo(0, height-10); ctx.lineTo(canvasWidth, height-10); ctx.stroke();
                ctx.fillStyle   = '#c8a84b';
                for (let x = 10; x < canvasWidth - 30; x += 50) {
                    ctx.fillRect(x, 8, 30, 30);
                }
            } else if (frame === 'celestial') {
                ctx.fillStyle  = '#a89bd4';
                ctx.font       = '28px serif';
                ctx.textAlign  = 'center';
                ctx.fillText('✦ ✧ ✦', canvasWidth/2, height - 10);
            } else if (frame === 'botanica') {
                ctx.strokeStyle = '#7aaa5a';
                ctx.lineWidth   = 1.5;
                ctx.beginPath();
                ctx.moveTo(0, height - 5);
                for (let x = 0; x <= canvasWidth; x += 20) {
                    ctx.quadraticCurveTo(x+10, height - 20, x+20, height - 5);
                }
                ctx.stroke();
            } else if (frame === 'sakura') {
                ctx.fillStyle  = '#ffb7c5';
                ctx.font       = '30px serif';
                ctx.textAlign  = 'center';
                ctx.fillText('✿ ❀ ✿', canvasWidth/2, height - 8);
            } else if (frame === 'pink') {
                ctx.fillStyle  = '#ff9ab0';
                ctx.font       = '28px serif';
                ctx.textAlign  = 'center';
                ctx.fillText('♥ ♡ ♥', canvasWidth/2, height - 8);
            } else if (frame === 'y2k') {
                ctx.font       = '24px monospace';
                ctx.textAlign  = 'center';
                ctx.fillStyle  = '#ff6ef7';
                ctx.fillText('★ Y2K ★', canvasWidth/2, height - 8);
            } else if (frame === 'halloween') {
                ctx.fillStyle  = '#ff6600';
                ctx.font       = '28px serif';
                ctx.textAlign  = 'center';
                ctx.fillText('🎃 👻 🎃', canvasWidth/2, height - 8);
            } else if (frame === 'korean') {
                ctx.strokeStyle = '#d4a0a0';
                ctx.lineWidth   = 0.8;
                ctx.beginPath();
                ctx.moveTo(padX, height/2);
                ctx.lineTo(canvasWidth/2 - 60, height/2);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(canvasWidth/2 + 60, height/2);
                ctx.lineTo(canvasWidth - padX, height/2);
                ctx.stroke();
                ctx.fillStyle  = '#c09090';
                ctx.font       = 'italic 18px Georgia';
                ctx.textAlign  = 'center';
                ctx.fillText('✦ studio ✦', canvasWidth/2, height/2 + 6);
            }

            ctx.restore();
        }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* ── Countdown Dramatis ── */
    @keyframes countdown-pop {
        0%   { transform: scale(0.2) rotate(-8deg); opacity: 0; }
        55%  { transform: scale(1.2) rotate(2deg);  opacity: 1; }
        80%  { transform: scale(0.95) rotate(0deg); opacity: 1; }
        100% { transform: scale(1)  rotate(0deg);  opacity: 1; }
    }
    @keyframes countdown-exit {
        0%   { transform: scale(1);   opacity: 1; }
        100% { transform: scale(2.5); opacity: 0; }
    }

    .countdown-num {
        font-size: clamp(72px, 22vw, 130px);
        font-weight: 900;
        line-height: 1;
        user-select: none;
        animation: countdown-pop 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards;
    }
    .countdown-3 {
        color: #ff4444;
        text-shadow: 0 0 30px rgba(255,68,68,0.8), 0 0 60px rgba(255,68,68,0.4), 0 0 100px rgba(255,68,68,0.2);
    }
    .countdown-2 {
        color: #ffcc00;
        text-shadow: 0 0 30px rgba(255,204,0,0.8), 0 0 60px rgba(255,204,0,0.4), 0 0 100px rgba(255,204,0,0.2);
    }
    .countdown-1 {
        color: #44ff88;
        text-shadow: 0 0 30px rgba(68,255,136,0.8), 0 0 60px rgba(68,255,136,0.4), 0 0 100px rgba(68,255,136,0.2);
    }

    /* ── Camera Shake ── */
    @keyframes camera-shake {
        0%,100% { transform: translate(0,0) rotate(0deg); }
        15%     { transform: translate(-5px, 3px) rotate(-1.5deg); }
        30%     { transform: translate(5px,-3px) rotate(1.5deg); }
        45%     { transform: translate(-4px, 4px) rotate(-0.8deg); }
        60%     { transform: translate(4px,-2px) rotate(0.8deg); }
        75%     { transform: translate(-2px, 2px) rotate(-0.4deg); }
        90%     { transform: translate(2px,-1px) rotate(0.2deg); }
    }
    .camera-shake { animation: camera-shake 0.4s ease-out; }

    /* ── Shutter Flash ── */
    @keyframes shutter-flash {
        0%   { opacity: 1; }
        100% { opacity: 0; }
    }
    .shutter-flash { animation: shutter-flash 0.18s ease-out forwards; }

    /* ── Smooth Photo Slide-In ── */
    @keyframes photo-slide-in {
        0%   { transform: translateX(50px) scale(0.9);  opacity: 0; filter: blur(4px); }
        65%  { transform: translateX(-5px) scale(1.02); opacity: 1; filter: blur(0); }
        100% { transform: translateX(0)   scale(1);     opacity: 1; filter: blur(0); }
    }
    .photo-slide-in { animation: photo-slide-in 0.6s cubic-bezier(0.34,1.56,0.64,1) forwards; }

    /* ── Y2K Gradient Anim ── */
    @keyframes y2k-gradient {
        0%,100% { background-position: 0% 50%; }
        50%     { background-position: 100% 50%; }
    }

    /* ── Mobile improvements ── */
    @media (max-width: 1023px) {
        .mobile-camera-box {
            max-height: 48svh;
            max-width: calc(48svh * 0.75);
        }
        .mobile-strip-area {
            min-height: 180px;
            border-style: solid;
        }
        #strip-preview-wrapper {
            transform-origin: top center;
        }
    }
</style>
@endsection