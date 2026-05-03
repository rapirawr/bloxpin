@extends('layouts.app')

@section('content')

{{-- Google Fonts --}}
@push('head')
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=DM+Serif+Display:ital@0;1&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

<div x-data="photobooth()" x-init="init()" class="photobooth-root">

    {{-- FILM SPROCKET STRIPS --}}
    <div class="sprocket-strip sprocket-left" id="sl"></div>
    <div class="sprocket-strip sprocket-right" id="sr"></div>

    <div class="pb-main">

        {{-- ══ TOP BAR ══ --}}
        <div class="pb-topbar">
            <a href="{{ route('home') }}" class="pb-back-btn">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="pb-frame-counter">
                REC <span x-text="String(capturedImages.length).padStart(2,'0')">00</span>
            </div>
            <div class="pb-iso-badge">ISO 400</div>
        </div>

        {{-- ══ STUDIO GRID ══ --}}
        <div class="pb-studio">

            {{-- ── LEFT: CAMERA ── --}}
            <div class="pb-camera-section">

                {{-- Viewport --}}
                <div class="pb-viewport"
                     x-ref="cameraBox"
                     :class="shakeCam ? 'cam-shake' : ''">

                    <video x-ref="video" autoplay playsinline muted
                           class="pb-video"
                           :style="facingMode === 'user' ? 'transform:scaleX(-1)' : ''"></video>
                    <canvas x-ref="canvas" style="display:none"></canvas>

                    {{-- Overlays --}}
                    <div class="pb-overlay">
                        <div class="pb-vignette"></div>
                        <div class="pb-corners">
                            <div class="pb-corner tl"></div>
                            <div class="pb-corner tr"></div>
                            <div class="pb-corner bl"></div>
                            <div class="pb-corner br"></div>
                        </div>
                        <div class="pb-hud">
                            <span x-text="clockStr">--:--</span>
                            <div class="pb-hud-dots">
                                <template x-for="i in maxCaptures" :key="i">
                                    <div class="pb-hud-dot" :class="capturedImages.length >= i ? 'filled' : ''"></div>
                                </template>
                            </div>
                            <span x-text="activeLayout.toUpperCase()">STRIP</span>
                        </div>
                    </div>

                    {{-- Flash --}}
                    <div class="pb-flash" :class="flash ? 'active' : ''"></div>

                    {{-- Camera Error --}}
                    <div class="pb-cam-error" x-show="cameraError" style="display:none">
                        <div class="pb-error-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <circle cx="12" cy="13" r="3" stroke-width="2"/>
                            </svg>
                        </div>
                        <h3>Akses kamera ditolak</h3>
                        <p>Izinkan akses kamera di browser untuk menggunakan photobooth.</p>
                        <button @click="startStream()">Coba lagi</button>
                    </div>

                    {{-- Mobile shutter (overlaid on camera, bottom center) --}}
                    {{-- <button @click="takeSnap()"
                            :disabled="!isStreaming || isProcessing"
                            class="pb-mobile-shutter lg:hidden">
                        <div class="pb-mobile-shutter-inner"></div>
                    </button> --}}

                    {{-- Flip camera --}}
                    <button @click="switchCamera"
                            :disabled="!isStreaming || isProcessing"
                            class="pb-flip-btn">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>

                {{-- Desktop shutter button + controls --}}
                <div class="pb-capture-row hidden lg:flex">
                    <button @click="resetSession()" class="pb-reset-btn">
                        RETAKE
                    </button>

                    <button @click="takeSnap()"
                            :disabled="!isStreaming || isProcessing"
                            class="pb-shutter-btn">
                        <div class="pb-shutter-inner"></div>
                    </button>

                    <div class="pb-shot-label" x-text="shotLabel">READY</div>
                </div>

            </div>{{-- /camera-section --}}

            {{-- ── MOBILE PREVIEW SECTION ── --}}
            <div class="pb-mobile-preview-area lg:hidden" x-show="capturedImages.length > 0" x-transition>
                <div class="pb-strip-wrap mobile-preview-container"></div>
            </div>

            {{-- ── RIGHT: SIDEBAR (Desktop Only) ── --}}
            <div class="pb-sidebar hidden lg:flex">

                {{-- LAYOUT --}}
                <div class="pb-panel-section">
                    <div class="pb-panel-label">Layout</div>
                    <div class="pb-layout-grid">

                        <button @click="setLayout('single')"
                                :class="activeLayout === 'single' ? 'active' : ''"
                                class="pb-layout-btn">
                            <div class="pb-icon-row">
                                <div class="pb-icon-bar"></div>
                            </div>
                            <span class="pb-layout-name">1</span>
                        </button>

                        <button @click="setLayout('double')"
                                :class="activeLayout === 'double' ? 'active' : ''"
                                class="pb-layout-btn">
                            <div class="pb-icon-row">
                                <div class="pb-icon-bar"></div>
                                <div class="pb-icon-bar"></div>
                            </div>
                            <span class="pb-layout-name">2</span>
                        </button>

                        <button @click="setLayout('strip')"
                                :class="activeLayout === 'strip' ? 'active' : ''"
                                class="pb-layout-btn">
                            <div class="pb-icon-row">
                                <div class="pb-icon-bar"></div>
                                <div class="pb-icon-bar"></div>
                                <div class="pb-icon-bar"></div>
                                <div class="pb-icon-bar"></div>
                            </div>
                            <span class="pb-layout-name">strip</span>
                        </button>

                        <button @click="setLayout('trio')"
                                :class="activeLayout === 'trio' ? 'active' : ''"
                                class="pb-layout-btn">
                            <div class="pb-icon-row">
                                <div class="pb-icon-bar"></div>
                                <div class="pb-icon-bar"></div>
                                <div class="pb-icon-bar"></div>
                            </div>
                            <span class="pb-layout-name">3</span>
                        </button>

                        <button @click="setLayout('grid')"
                                :class="activeLayout === 'grid' ? 'active' : ''"
                                class="pb-layout-btn pb-layout-grid-icon">
                            <div class="pb-grid-cell"></div>
                            <div class="pb-grid-cell"></div>
                            <div class="pb-grid-cell"></div>
                            <div class="pb-grid-cell"></div>
                        </button>

                    </div>
                </div>

                {{-- FILM STOCK / FILTER --}}
                <div class="pb-panel-section">
                    <div class="pb-panel-label">Film Stock</div>
                    <div class="pb-filter-scroll">
                        <template x-for="f in filters" :key="f.id">
                            <button @click="setFilter(f.id)"
                                    :class="activeFilter === f.id ? 'active' : ''"
                                    class="pb-filter-btn">
                                <div class="pb-filter-swatch" :style="f.swatchStyle"></div>
                                <span class="pb-filter-label" x-text="f.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- FRAME --}}
                <div class="pb-panel-section">
                    <div class="pb-panel-label">Frame</div>
                    <div class="pb-frame-scroll">
                        <template x-for="fr in frames" :key="fr.id">
                            <button @click="setFrame(fr.id)"
                                    :class="activeFrame === fr.id ? 'active' : ''"
                                    class="pb-frame-btn">
                                <div class="pb-frame-thumb" :style="fr.thumbStyle"></div>
                                <span class="pb-frame-label" x-text="fr.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- STRIP PREVIEW --}}
                <div class="pb-strip-section">
                    <div class="pb-panel-label">Preview</div>
                    <div class="pb-strip-wrap desktop-preview-container"></div>
                </div>

                {{-- ACTIONS --}}
                <div class="pb-action-panel">
                    <div class="pb-action-row">
                        <button @click="saveToBloxpin"
                                :disabled="capturedImages.length !== maxCaptures || isSaving"
                                class="pb-btn-primary">
                            <template x-if="isSaving">
                                <svg class="pb-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </template>
                            <span x-text="isSaving ? 'PUBLISHING...' : 'PUBLISH'"></span>
                        </button>
                        <button @click="downloadStrip()"
                                :disabled="capturedImages.length === 0"
                                class="pb-btn-secondary">
                            SAVE
                        </button>
                    </div>
                </div>

            </div>{{-- /sidebar --}}

        </div>{{-- /studio --}}

    </div>{{-- /pb-main --}}

    {{-- ══ MOBILE BOTTOM PANEL ══ --}}
    <div class="pb-mobile-panel lg:hidden">

        {{-- Tab bar --}}
        <div class="pb-mobile-tabs">
            <button @click="mobileTab = 'layout'"
                    :class="mobileTab === 'layout' ? 'active' : ''"
                    class="pb-mobile-tab">LAYOUT</button>
            <button @click="mobileTab = 'filter'"
                    :class="mobileTab === 'filter' ? 'active' : ''"
                    class="pb-mobile-tab">FILTER</button>
            <button @click="mobileTab = 'frame'"
                    :class="mobileTab === 'frame' ? 'active' : ''"
                    class="pb-mobile-tab">FRAME</button>
        </div>

        {{-- Layout tab --}}
        <div x-show="mobileTab === 'layout'" class="pb-mobile-scroll">
            <template x-for="l in layoutOptions" :key="l.id">
                <button @click="setLayout(l.id)"
                        :class="activeLayout === l.id ? 'active' : ''"
                        class="pb-mobile-layout-btn">
                    <div class="pb-mobile-layout-icon" x-html="l.iconHtml"></div>
                    <span class="pb-filter-label" x-text="l.label"></span>
                </button>
            </template>
        </div>

        {{-- Filter tab --}}
        <div x-show="mobileTab === 'filter'" style="display:none" class="pb-mobile-scroll">
            <template x-for="f in filters" :key="f.id">
                <button @click="setFilter(f.id)"
                        :class="activeFilter === f.id ? 'active' : ''"
                        class="pb-filter-btn">
                    <div class="pb-filter-swatch" :style="f.swatchStyle"></div>
                    <span class="pb-filter-label" x-text="f.name"></span>
                </button>
            </template>
        </div>

        {{-- Frame tab --}}
        <div x-show="mobileTab === 'frame'" style="display:none" class="pb-mobile-scroll">
            <template x-for="fr in frames" :key="fr.id">
                <button @click="setFrame(fr.id)"
                        :class="activeFrame === fr.id ? 'active' : ''"
                        class="pb-frame-btn">
                    <div class="pb-frame-thumb" :style="fr.thumbStyle"></div>
                    <span class="pb-frame-label" x-text="fr.name"></span>
                </button>
            </template>
        </div>

        {{-- Mobile submit row --}}
        <div class="pb-mobile-submit" x-show="capturedImages.length > 0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">
            <button @click="saveToBloxpin"
                    :disabled="capturedImages.length !== maxCaptures || isSaving"
                    class="pb-btn-primary" style="flex:1">
                <span x-text="isSaving ? 'PUBLISHING...' : 'PUBLISH'"></span>
            </button>
            <button @click="downloadStrip()"
                    :disabled="capturedImages.length === 0"
                    class="pb-btn-secondary">SAVE</button>
            <button @click="resetSession()" class="pb-btn-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>

    </div>{{-- /mobile-panel --}}

</div>{{-- /photobooth-root --}}


{{-- ══════════════════════════════════════
     SCRIPT
══════════════════════════════════════ --}}
<script>
function photobooth() {
    return {

        // ── State
        isStreaming:     false,
        cameraError:     false,
        capturedImages:  [],
        flash:           false,
        shakeCam:        false,
        isProcessing:    false,
        isSaving:        false,
        facingMode:      'user',
        activeLayout:    'strip',
        activeFilter:    'none',
        activeFrame:     'classic',
        mobileTab:       'layout',
        shotLabel:       'READY',
        clockStr:        '--:--',
        clockInterval:   null,

        // ── Layout map
        get maxCaptures() {
            return { single:1, double:2, trio:3, strip:4, grid:4 }[this.activeLayout] ?? 4;
        },

        // ── Data
        filters: [
            { id:'none',    name:'Normal',  swatchStyle:'background:linear-gradient(135deg,#2a2724,#4a4540)' },
            { id:'vintage', name:'Kodak',   swatchStyle:'background:linear-gradient(135deg,#8b6a40,#c4955a)' },
            { id:'bw',      name:'Ilford',  swatchStyle:'background:linear-gradient(135deg,#3a3a3a,#888888)' },
            { id:'sepia',   name:'Sepia',   swatchStyle:'background:linear-gradient(135deg,#704214,#b07030)' },
            { id:'dreamy',  name:'Dreamy',  swatchStyle:'background:linear-gradient(135deg,#c8a0e8,#80c0f0)' },
            { id:'faded',   name:'Faded',   swatchStyle:'background:linear-gradient(135deg,#b8c8c0,#d8e8e0)' },
        ],

        frames: [
            { id:'classic',  name:'Classic',  thumbStyle:'background:#f5f0e8;border-color:rgba(200,169,110,0.5)', bg:'#f5f0e8', color:'#2a2018', accent:'#c8a96e' },
            { id:'dark',     name:'Dark',     thumbStyle:'background:#111;border-color:rgba(255,255,255,0.15)',   bg:'#111010', color:'#ffffff', accent:'#888888' },
            { id:'blush',    name:'Blush',    thumbStyle:'background:linear-gradient(160deg,#ffe4ef,#ffd4e4);border-color:rgba(255,150,180,0.4)', bg:'linear-gradient(160deg,#ffe4ef,#ffd4e4)', color:'#7a3050', accent:'#ff90b8' },
            { id:'forest',   name:'Forest',   thumbStyle:'background:#1a2e1a;border-color:rgba(100,180,80,0.3)', bg:'#1a2e1a', color:'#c8e8a8', accent:'#6ab050' },
            { id:'polaroid', name:'Polaroid', thumbStyle:'background:#fafaf5;border-color:#ddd',                 bg:'#fafaf5', color:'#333333', accent:'#999999' },
            { id:'cinema',   name:'Cinema',   thumbStyle:'background:#1a1209;border-color:rgba(200,168,75,0.4)', bg:'#1a1209', color:'#c8a84b', accent:'#c8a84b' },
        ],

        layoutOptions: [
            { id:'single', label:'1',     iconHtml:'<div style="display:flex;flex-direction:column;gap:2px;width:18px;"><div style="height:4px;border-radius:1px;background:currentColor;opacity:0.6;"></div></div>' },
            { id:'double', label:'2',     iconHtml:'<div style="display:flex;flex-direction:column;gap:2px;width:18px;"><div style="height:4px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:4px;border-radius:1px;background:currentColor;opacity:0.6;"></div></div>' },
            { id:'strip',  label:'Strip', iconHtml:'<div style="display:flex;flex-direction:column;gap:1.5px;width:18px;"><div style="height:3px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:3px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:3px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:3px;border-radius:1px;background:currentColor;opacity:0.6;"></div></div>' },
            { id:'trio',   label:'3',     iconHtml:'<div style="display:flex;flex-direction:column;gap:2px;width:18px;"><div style="height:4px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:4px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:4px;border-radius:1px;background:currentColor;opacity:0.6;"></div></div>' },
            { id:'grid',   label:'Grid',  iconHtml:'<div style="display:grid;grid-template-columns:1fr 1fr;gap:2px;width:18px;"><div style="height:8px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:8px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:8px;border-radius:1px;background:currentColor;opacity:0.6;"></div><div style="height:8px;border-radius:1px;background:currentColor;opacity:0.6;"></div></div>' },
        ],

        // ── Filter CSS
        filterCSS: {
            none:    '',
            vintage: 'sepia(0.25) contrast(1.1) brightness(0.92) saturate(0.85)',
            bw:      'grayscale(1) contrast(1.15)',
            sepia:   'sepia(0.9) brightness(0.95)',
            dreamy:  'brightness(1.08) saturate(1.3) contrast(0.95)',
            faded:   'saturate(0.5) brightness(1.05) contrast(0.9)',
        },

        // ── Init
        async init() {
            this.buildSprockets();
            this.startClock();
            window.addEventListener('resize', () => this.buildSprockets());
            await this.startStream();
            this.renderStrip();
        },

        // ── Sprocket holes
        buildSprockets() {
            const count = Math.floor(window.innerHeight / 30) + 2;
            ['sl','sr'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.innerHTML = '';
                for (let i = 0; i < count; i++) {
                    const d = document.createElement('div');
                    d.className = 'pb-sprocket-hole';
                    el.appendChild(d);
                }
            });
        },

        // ── Clock
        startClock() {
            const update = () => {
                const now = new Date();
                const h = String(now.getHours()).padStart(2,'0');
                const m = String(now.getMinutes()).padStart(2,'0');
                this.clockStr = `${h}:${m}`;
            };
            update();
            this.clockInterval = setInterval(update, 10000);
        },

        // ── Camera stream
        async startStream() {
            if (this.$refs.video?.srcObject) {
                this.$refs.video.srcObject.getTracks().forEach(t => t.stop());
            }
            try {
                this.cameraError = false;
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: this.facingMode, aspectRatio: 3/4, width: { ideal: 720 } },
                    audio: false
                });
                this.$refs.video.srcObject = stream;
                this.isStreaming = true;
            } catch (e) {
                this.cameraError   = true;
                this.isStreaming   = false;
                window.showToast?.('Izin kamera ditolak atau tidak tersedia.', 'error');
            }
        },

        async switchCamera() {
            this.facingMode = this.facingMode === 'user' ? 'environment' : 'user';
            await this.startStream();
        },

        // ── Manual single-shot snap (no countdown)
        takeSnap() {
            if (!this.isStreaming || this.isProcessing) return;
            if (this.capturedImages.length >= this.maxCaptures) return;

            this.isProcessing = true;

            // Flash
            this.flash = true;
            setTimeout(() => this.flash = false, 140);

            // Camera shake
            this.shakeCam = true;
            setTimeout(() => this.shakeCam = false, 400);

            const video  = this.$refs.video;
            const canvas = this.$refs.canvas;

            let tW = video.videoWidth;
            let tH = video.videoWidth * (4 / 3);
            if (tH > video.videoHeight) {
                tH = video.videoHeight;
                tW = video.videoHeight * (3 / 4);
            }

            canvas.width  = tW;
            canvas.height = tH;
            const ctx = canvas.getContext('2d');

            if (this.facingMode === 'user') {
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
            }

            const ox = (video.videoWidth  - tW) / 2;
            const oy = (video.videoHeight - tH) / 2;
            ctx.drawImage(video, ox, oy, tW, tH, 0, 0, tW, tH);

            this.capturedImages.push(canvas.toDataURL('image/jpeg', 0.92));

            const taken = this.capturedImages.length;
            const total = this.maxCaptures;

            if (taken < total) {
                this.shotLabel   = `${taken}/${total} — SHOOT`;
                this.isProcessing = false;
            } else {
                this.shotLabel   = 'DONE ✓';
                this.isProcessing = false;
            }

            this.$nextTick(() => this.renderStrip());
        },

        // ── Layout / filter / frame setters
        setLayout(l) {
            this.activeLayout = l;
            this.resetSession();
        },

        setFilter(f) {
            this.activeFilter = f;
            this.$nextTick(() => this.renderStrip());
        },

        setFrame(f) {
            this.activeFrame = f;
            this.$nextTick(() => this.renderStrip());
        },

        // ── Reset
        resetSession() {
            this.capturedImages = [];
            this.shotLabel      = 'READY';
            this.$nextTick(() => this.renderStrip());
        },

        // ── Render strip preview
        renderStrip() {
            const wraps = document.querySelectorAll('.pb-strip-wrap');
            if (!wraps.length) return;

            const fr      = this.frames.find(f => f.id === this.activeFrame) ?? this.frames[0];
            const max     = this.maxCaptures;
            const isGrid  = this.activeLayout === 'grid';
            const photoW  = isGrid ? 110 : 100;
            const photoH  = Math.round(photoW * (4 / 3));
            const pad     = 12;
            const gap     = 6;
            const filterV = this.filterCSS[this.activeFilter] ?? '';

            const containerW = isGrid
                ? pad * 2 + photoW * 2 + gap
                : pad * 2 + photoW;

            let slotsHTML = '';
            for (let i = 0; i < max; i++) {
                if (this.capturedImages[i]) {
                    slotsHTML += `<div style="width:${photoW}px;height:${photoH}px;border-radius:2px;overflow:hidden;flex-shrink:0;">
                        <img src="${this.capturedImages[i]}" style="width:100%;height:100%;object-fit:cover;display:block;${filterV ? 'filter:'+filterV+';' : ''}" />
                    </div>`;
                } else {
                    slotsHTML += `<div style="width:${photoW}px;height:${photoH}px;border-radius:2px;background:rgba(0,0,0,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <span style="font-family:'Space Mono',monospace;font-size:14px;letter-spacing:0.05em;color:${fr.color}30;">${i + 1}</span>
                    </div>`;
                }
            }

            const gridOrFlex = isGrid
                ? `display:grid;grid-template-columns:1fr 1fr;gap:${gap}px;padding:${pad}px;`
                : `display:flex;flex-direction:column;gap:${gap}px;padding:${pad}px;`;

            const dateStr = new Date().toLocaleDateString('en-GB', { day:'2-digit', month:'2-digit', year:'2-digit' });

            const bgStyle = fr.bg.startsWith('linear')
                ? `background:${fr.bg};`
                : `background-color:${fr.bg};`;

            const finalHTML = `
                <div style="width:${containerW}px;${bgStyle}border-radius:3px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,0.5),0 0 0 1px rgba(255,255,255,0.06);transition:all 0.4s;">
                    <div style="${gridOrFlex}">${slotsHTML}</div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:3px;padding:6px ${pad}px 10px;">
                        <div style="width:100%;height:1px;background:${fr.color}20;margin-bottom:4px;"></div>
                        <span style="font-family:'DM Serif Display',serif;font-size:12px;letter-spacing:0.2em;text-transform:uppercase;color:${fr.color};opacity:0.8;">filmbooth</span>
                        <span style="font-family:'Space Mono',monospace;font-size:10px;letter-spacing:0.08em;color:${fr.color};opacity:0.4;">${dateStr}</span>
                    </div>
                </div>`;

            wraps.forEach(wrap => {
                wrap.innerHTML = finalHTML;
            });
        },

        // ── Download
        async downloadStrip() {
            if (!this.capturedImages.length) return;

            const fr       = this.frames.find(f => f.id === this.activeFrame) ?? this.frames[0];
            const isGrid   = this.activeLayout === 'grid';
            const max      = this.maxCaptures;
            const c        = document.createElement('canvas');
            const ctx      = c.getContext('2d');
            const iW = 300, iH = 400, pad = 30, gap = 8, topDec = 60, btmDec = 80;

            if (isGrid) {
                c.width  = iW * 2 + pad * 3;
                c.height = iH * 2 + pad * 3 + topDec + btmDec;
            } else {
                c.width  = iW + pad * 2;
                c.height = iH * max + gap * (max - 1) + pad * 2 + topDec + btmDec;
            }

            if (fr.bg.startsWith('linear')) {
                const g = ctx.createLinearGradient(0, 0, 0, c.height);
                g.addColorStop(0, '#ffe4ef'); g.addColorStop(1, '#ffd4e4');
                ctx.fillStyle = g;
            } else {
                ctx.fillStyle = fr.bg;
            }
            ctx.fillRect(0, 0, c.width, c.height);

            const filterMap = {
                vintage: 'sepia(0.25) contrast(1.1) brightness(0.92)',
                bw:      'grayscale(1) contrast(1.15)',
                sepia:   'sepia(0.9)',
                dreamy:  'brightness(1.08) saturate(1.3)',
                faded:   'saturate(0.5) brightness(1.05)',
            };

            for (let i = 0; i < this.capturedImages.length; i++) {
                const img = new Image();
                img.src   = this.capturedImages[i];
                await new Promise(r => img.onload = r);

                let x, y;
                if (isGrid) {
                    x = pad + (i % 2) * (iW + pad);
                    y = topDec + pad + Math.floor(i / 2) * (iH + gap);
                } else {
                    x = pad;
                    y = topDec + pad + i * (iH + gap);
                }

                ctx.save();
                if (filterMap[this.activeFilter]) ctx.filter = filterMap[this.activeFilter];
                ctx.drawImage(img, x, y, iW, iH);
                ctx.restore();
            }

            ctx.fillStyle = fr.color ?? '#2a2018';
            ctx.font      = 'italic 18px Georgia, serif';
            ctx.textAlign = 'center';
            ctx.fillText('filmbooth', c.width / 2, c.height - 40);
            ctx.font = '12px monospace';
            ctx.globalAlpha = 0.5;
            ctx.fillText(new Date().toLocaleDateString(), c.width / 2, c.height - 18);
            ctx.globalAlpha = 1;

            c.toBlob(blob => {
                const url = URL.createObjectURL(blob);
                const a   = document.createElement('a');
                a.href    = url;
                a.download = `filmbooth-${this.activeFrame}-${Date.now()}.jpg`;
                a.click();
            }, 'image/jpeg', 0.92);
        },

        // ── Save to backend
        async saveToBloxpin() {
            @if(!Auth::check())
                window.location.href = "{{ route('login') }}";
                return;
            @endif

            if (this.capturedImages.length !== this.maxCaptures) return;
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

                window.showToast?.('Berhasil diproses!');
                const photoUid = response.data.photos[0].uid;
                window.location.href = `/photo/${photoUid}/edit`;
            } catch (err) {
                console.error(err);
                window.showToast?.('Gagal menyimpan foto.', 'error');
            } finally {
                this.isSaving = false;
            }
        },

        // ── Generate canvas blob for upload
        async generateStripBlob() {
            const c   = document.createElement('canvas');
            const ctx = c.getContext('2d');
            const fr  = this.frames.find(f => f.id === this.activeFrame) ?? this.frames[0];

            const isGrid = this.activeLayout === 'grid';
            const max    = this.maxCaptures;
            const iW = 450, iH = 600, pad = 30, gap = 8, topDec = 60, btmDec = 80;

            if (isGrid) {
                c.width  = iW * 2 + pad * 3;
                c.height = iH * 2 + pad * 3 + topDec + btmDec;
            } else {
                c.width  = iW + pad * 2;
                c.height = iH * max + gap * (max - 1) + pad * 2 + topDec + btmDec;
            }

            if (fr.bg.startsWith('linear')) {
                const g = ctx.createLinearGradient(0, 0, 0, c.height);
                g.addColorStop(0, '#ffe4ef'); g.addColorStop(1, '#ffd4e4');
                ctx.fillStyle = g;
            } else {
                ctx.fillStyle = fr.bg;
            }
            ctx.fillRect(0, 0, c.width, c.height);

            const filterMap = {
                vintage: 'sepia(0.25) contrast(1.1) brightness(0.92)',
                bw:      'grayscale(1) contrast(1.15)',
                sepia:   'sepia(0.9)',
                dreamy:  'brightness(1.08) saturate(1.3)',
                faded:   'saturate(0.5) brightness(1.05)',
            };

            for (let i = 0; i < this.capturedImages.length; i++) {
                const img = new Image();
                img.src   = this.capturedImages[i];
                await new Promise(r => img.onload = r);

                let x, y;
                if (isGrid) {
                    x = pad + (i % 2) * (iW + pad);
                    y = topDec + pad + Math.floor(i / 2) * (iH + gap);
                } else {
                    x = pad;
                    y = topDec + pad + i * (iH + gap);
                }

                ctx.save();
                if (filterMap[this.activeFilter]) ctx.filter = filterMap[this.activeFilter];
                ctx.drawImage(img, x, y, iW, iH);
                ctx.restore();
            }

            ctx.fillStyle = fr.color ?? '#2a2018';
            ctx.font      = 'italic 22px Georgia, serif';
            ctx.textAlign = 'center';
            ctx.fillText('filmbooth', c.width / 2, c.height - 44);
            ctx.font = '14px monospace';
            ctx.globalAlpha = 0.45;
            ctx.fillText(new Date().toLocaleDateString(), c.width / 2, c.height - 20);
            ctx.globalAlpha = 1;

            return new Promise(resolve => c.toBlob(resolve, 'image/jpeg', 0.92));
        },
    };
}
</script>


{{-- ══════════════════════════════════════
     STYLES
══════════════════════════════════════ --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=DM+Serif+Display:ital@0;1&family=Instrument+Sans:wght@400;500;600&display=swap');

/* ── Root & Reset ──────────────────────────── */
.photobooth-root {
    position: relative;
    min-height: 100vh;
    background: #1a1714;
    color: #f5f0e8;
    font-family: 'Instrument Sans', sans-serif;
    overflow-x: hidden;
}

/* ── Sprocket film strips ──────────────────── */
.sprocket-strip {
    position: fixed;
    top: 0; bottom: 0;
    width: 32px;
    background: #0f0d0b;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 10px 0;
    gap: 16px;
    z-index: 50;
}
@media (max-width: 1023px) {
    .sprocket-strip { display: none !important; }
}
.sprocket-left  { left: 0;  border-right: 1px solid rgba(255,255,255,0.05); }
.sprocket-right { right: 0; border-left:  1px solid rgba(255,255,255,0.05); }
.pb-sprocket-hole {
    width: 15px; height: 11px;
    border-radius: 2px;
    background: #242018;
    border: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
}

/* ── Main wrapper ──────────────────────────── */
.pb-main {
    margin: 0 32px;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
@media (max-width: 1023px) {
    .pb-main { margin: 0; }
}

/* ── Top bar ───────────────────────────────── */
.pb-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px 14px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.pb-logo {
    font-family: 'DM Serif Display', serif;
    font-size: 28px;
    letter-spacing: -0.02em;
    color: #f5f0e8;
}
.pb-logo span { color: #c9363a; font-style: italic; }

.pb-back-btn {
    color: #8a8278;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    width: 32px;
    height: 32px;
    margin-left: -8px;
}
.pb-back-btn:hover {
    color: #f5f0e8;
    transform: translateX(-2px);
}

.pb-frame-counter {
    font-family: 'Space Mono', monospace;
    font-size: 14px;
    color: #8a8278;
    letter-spacing: 0.12em;
    display: flex;
    align-items: center;
    gap: 8px;
}
.pb-frame-counter::before {
    content: '';
    display: inline-block;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #c9363a;
    animation: pb-blink 1.8s ease-in-out infinite;
}
@keyframes pb-blink { 0%,100%{opacity:1} 50%{opacity:0.15} }

.pb-iso-badge {
    font-family: 'Space Mono', monospace;
    font-size: 12px;
    color: #c8a96e;
    background: rgba(200,169,110,0.1);
    border: 1px solid rgba(200,169,110,0.2);
    padding: 4px 10px;
    border-radius: 3px;
    letter-spacing: 0.15em;
}

/* ── Studio ────────────────────────────────── */
.pb-studio {
    display: grid;
    grid-template-columns: 1fr 380px;
    flex: 1;
}

/* ── Camera section ────────────────────────── */
.pb-camera-section {
    padding: 24px;
    border-right: 1px solid rgba(255,255,255,0.06);
    display: flex;
    flex-direction: column;
    gap: 32px;
    align-items: center;
}

.pb-viewport {
    position: relative;
    width: 100%;
    max-width: 480px;
    aspect-ratio: 3/4;
    background: #0d0b09;
    border-radius: 4px;
    overflow: hidden;
    box-shadow:
        0 0 0 1px rgba(255,255,255,0.07),
        0 20px 60px rgba(0,0,0,0.6),
        inset 0 0 40px rgba(0,0,0,0.4);
}
.pb-video {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}

/* Overlay elements */
.pb-overlay   { position: absolute; inset: 0; pointer-events: none; }
.pb-vignette  {
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at center, transparent 35%, rgba(0,0,0,0.75) 100%);
}
.pb-corners   { position: absolute; inset: 12px; }
.pb-corner    {
    position: absolute;
    width: 18px; height: 18px;
    border-color: rgba(200,169,110,0.55);
    border-style: solid;
}
.pb-corner.tl { top:0; left:0;  border-width: 1.5px 0 0 1.5px; }
.pb-corner.tr { top:0; right:0; border-width: 1.5px 1.5px 0 0; }
.pb-corner.bl { bottom:0; left:0;  border-width: 0 0 1.5px 1.5px; }
.pb-corner.br { bottom:0; right:0; border-width: 0 1.5px 1.5px 0; }

.pb-hud {
    position: absolute;
    bottom: 14px; left: 0; right: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px;
    font-family: 'Space Mono', monospace;
    font-size: 12px;
    color: rgba(200,169,110,0.65);
    letter-spacing: 0.1em;
}
.pb-hud-dots { display: flex; gap: 5px; }
.pb-hud-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    border: 1px solid rgba(200,169,110,0.35);
    transition: all 0.3s;
}
.pb-hud-dot.filled {
    background: #c8a96e;
    border-color: #c8a96e;
    box-shadow: 0 0 8px rgba(200,169,110,0.5);
}

/* Flash */
.pb-flash {
    position: absolute; inset: 0;
    background: white;
    opacity: 0;
    pointer-events: none;
    z-index: 10;
    transition: opacity 0.06s;
}
.pb-flash.active { opacity: 1; }

/* Camera shake */
@keyframes pb-cam-shake {
    0%,100% { transform: translate(0,0) rotate(0deg); }
    15%     { transform: translate(-5px,3px) rotate(-1.5deg); }
    30%     { transform: translate(5px,-3px) rotate(1.5deg); }
    55%     { transform: translate(-3px,3px) rotate(-0.6deg); }
    75%     { transform: translate(3px,-1px) rotate(0.4deg); }
}
.cam-shake { animation: pb-cam-shake 0.38s ease-out; }

/* Camera error */
.pb-cam-error {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    background: rgba(0,0,0,0.85);
    gap: 10px; text-align: center; padding: 20px;
    z-index: 20;
}
.pb-error-icon {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: rgba(201,54,58,0.15);
    border: 1px solid rgba(201,54,58,0.4);
    display: flex; align-items: center; justify-content: center;
    color: #c9363a;
}
.pb-cam-error h3 {
    font-family: 'DM Serif Display', serif;
    font-size: 15px; color: #f5f0e8;
}
.pb-cam-error p {
    font-size: 14px; color: #8a8278; line-height: 1.6; max-width: 280px;
}
.pb-cam-error button {
    background: #f5f0e8; color: #1a1714; border: none;
    padding: 8px 20px; border-radius: 3px;
    font-family: 'Space Mono', monospace;
    font-size: 12px; letter-spacing: 0.12em; text-transform: uppercase;
    cursor: pointer; margin-top: 4px;
    transition: background 0.2s;
}
.pb-cam-error button:hover { background: white; }

/* Flip button */
.pb-flip-btn {
    position: absolute; top: 12px; right: 12px;
    width: 36px; height: 36px;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.8);
    cursor: pointer; z-index: 15;
    transition: all 0.2s;
}
.pb-flip-btn:hover  { background: rgba(0,0,0,0.65); color: white; }
.pb-flip-btn:active { transform: scale(0.9); }
.pb-flip-btn:disabled { opacity: 0.3; cursor: not-allowed; }

/* Mobile shutter (overlaid) */
.pb-mobile-shutter {
    position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%);
    width: 64px; height: 64px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(8px);
    border: 2px solid rgba(255,255,255,0.35);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 15;
    transition: all 0.15s;
}
.pb-mobile-shutter:active  { transform: translateX(-50%) scale(0.92); }
.pb-mobile-shutter:disabled { opacity: 0.3; cursor: not-allowed; }
.pb-mobile-shutter-inner {
    width: 46px; height: 46px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    transition: all 0.12s;
}
.pb-mobile-shutter:active .pb-mobile-shutter-inner { background: white; }

/* ── Desktop shutter row ───────────────────── */
.pb-capture-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    width: 100%;
    max-width: 520px;
}
.pb-reset-btn {
    background: none;
    border: 1px solid rgba(255,255,255,0.14);
    color: #8a8278;
    font-family: 'Space Mono', monospace;
    font-size: 12px; letter-spacing: 0.14em;
    padding: 8px 14px; border-radius: 3px;
    cursor: pointer; text-transform: uppercase;
    transition: all 0.2s;
}
.pb-reset-btn:hover { border-color: rgba(255,255,255,0.3); color: #f5f0e8; }

.pb-shutter-btn {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: none;
    border: 2px solid rgba(255,255,255,0.18);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.18s;
}
.pb-shutter-btn:hover  { border-color: rgba(255,255,255,0.45); transform: scale(1.04); }
.pb-shutter-btn:active { transform: scale(0.93); }
.pb-shutter-btn:disabled { opacity: 0.3; cursor: not-allowed; transform: none; }
.pb-shutter-inner {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: #f5f0e8;
    transition: all 0.12s;
}
.pb-shutter-btn:hover .pb-shutter-inner { background: white; }
.pb-shutter-btn:active .pb-shutter-inner { transform: scale(0.9); }

.pb-shot-label {
    font-family: 'Space Mono', monospace;
    font-size: 13px; color: #8a8278;
    letter-spacing: 0.1em;
    min-width: 60px; text-align: center;
}

/* ── Sidebar ───────────────────────────────── */
.pb-sidebar {
    display: flex;
    flex-direction: column;
    border-left: 1px solid rgba(255,255,255,0.06);
    min-height: 100%;
}
.pb-panel-section {
    padding: 18px 18px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.pb-panel-label {
    font-family: 'Space Mono', monospace;
    font-size: 12px; letter-spacing: 0.2em;
    color: #8a8278; text-transform: uppercase;
    margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
}
.pb-panel-label::after {
    content: ''; flex: 1; height: 1px;
    background: rgba(255,255,255,0.05);
}

/* Layout grid */
.pb-layout-grid {
    display: grid;
    grid-template-columns: repeat(5,1fr);
    gap: 5px;
}
.pb-layout-btn {
    aspect-ratio: 1;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 4px;
    cursor: pointer;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 2px; padding: 6px 5px 7px;
    transition: all 0.18s;
}
.pb-layout-btn:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.18); }
.pb-layout-btn.active {
    background: rgba(201,54,58,0.12);
    border-color: rgba(201,54,58,0.5);
}
.pb-icon-row { display: flex; flex-direction: column; gap: 2px; width: 20px; }
.pb-icon-bar { height: 4px; border-radius: 1px; background: rgba(255,255,255,0.28); width: 100%; }
.pb-layout-btn.active .pb-icon-bar { background: #c9363a; }
.pb-layout-grid-icon {
    display: grid !important;
    grid-template-columns: 1fr 1fr;
    gap: 2px; padding: 7px;
    flex-direction: unset;
}
.pb-grid-cell { border-radius: 1px; background: rgba(255,255,255,0.28); aspect-ratio: 1; }
.pb-layout-btn.active .pb-grid-cell { background: #c9363a; }
.pb-layout-name {
    font-family: 'Space Mono', monospace;
    font-size: 9px; color: #8a8278;
    letter-spacing: 0.08em; text-transform: uppercase; margin-top: 1px;
}
.pb-layout-btn.active .pb-layout-name { color: #c9363a; }

/* Filter strip */
.pb-filter-scroll {
    display: flex; gap: 7px;
    overflow-x: auto; padding-bottom: 2px;
    scrollbar-width: none;
}
.pb-filter-scroll::-webkit-scrollbar { display: none; }
.pb-filter-btn {
    flex-shrink: 0; width: 52px;
    display: flex; flex-direction: column;
    align-items: center; gap: 5px;
    background: none; border: none; cursor: pointer; padding: 0;
}
.pb-filter-swatch {
    width: 52px; height: 38px;
    border-radius: 3px;
    border: 1.5px solid transparent;
    transition: all 0.2s;
}
.pb-filter-btn.active .pb-filter-swatch {
    border-color: #c8a96e;
    box-shadow: 0 0 10px rgba(200,169,110,0.3);
}
.pb-filter-label {
    font-family: 'Space Mono', monospace;
    font-size: 10px; color: #8a8278;
    letter-spacing: 0.08em; text-transform: uppercase;
}
.pb-filter-btn.active .pb-filter-label { color: #c8a96e; }

/* Frame strip */
.pb-frame-scroll {
    display: flex; gap: 7px;
    overflow-x: auto; padding-bottom: 2px;
    scrollbar-width: none;
}
.pb-frame-scroll::-webkit-scrollbar { display: none; }
.pb-frame-btn {
    flex-shrink: 0; width: 42px;
    display: flex; flex-direction: column;
    align-items: center; gap: 5px;
    background: none; border: none; cursor: pointer; padding: 0;
}
.pb-frame-thumb {
    width: 42px; height: 50px;
    border-radius: 3px;
    border: 1.5px solid rgba(255,255,255,0.1);
    transition: all 0.2s;
}
.pb-frame-btn.active .pb-frame-thumb {
    border-color: #c8a96e;
    box-shadow: 0 0 10px rgba(200,169,110,0.22);
}
.pb-frame-label {
    font-family: 'Space Mono', monospace;
    font-size: 10px; color: #8a8278;
    letter-spacing: 0.06em; text-transform: uppercase;
}
.pb-frame-btn.active .pb-frame-label { color: #c8a96e; }

/* Strip section */
.pb-strip-section {
    flex: 1; padding: 18px;
    display: flex; flex-direction: column; align-items: center;
    overflow: hidden;
}
.pb-strip-wrap { transition: all 0.4s; }

/* Actions */
.pb-action-panel {
    padding: 14px 18px;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.pb-action-row { display: flex; gap: 8px; }

.pb-btn-primary {
    flex: 1;
    padding: 12px 16px;
    background: #c9363a; color: white; border: none;
    border-radius: 3px;
    font-family: 'Space Mono', monospace;
    font-size: 13px; letter-spacing: 0.15em; text-transform: uppercase;
    cursor: pointer; transition: all 0.18s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.pb-btn-primary:hover   { background: #d94044; }
.pb-btn-primary:active  { transform: scale(0.97); }
.pb-btn-primary:disabled { opacity: 0.3; cursor: not-allowed; }

.pb-btn-secondary {
    padding: 12px 14px;
    background: rgba(255,255,255,0.05);
    color: #8a8278; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 3px;
    font-family: 'Space Mono', monospace;
    font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase;
    cursor: pointer; transition: all 0.18s;
}
.pb-btn-secondary:hover   { background: rgba(255,255,255,0.1); color: #f5f0e8; }
.pb-btn-secondary:disabled { opacity: 0.3; cursor: not-allowed; }

.pb-btn-icon {
    width: 44px; height: 44px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 3px;
    display: flex; align-items: center; justify-content: center;
    color: #8a8278; cursor: pointer; transition: all 0.18s; flex-shrink: 0;
}
.pb-btn-icon:hover { background: rgba(255,255,255,0.1); color: #f5f0e8; }

.pb-spin { animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Mobile bottom panel ───────────────────── */
.pb-mobile-panel {
    position: fixed; bottom: 0; left: 0; right: 0;
    background: rgba(20,18,16,0.98);
    backdrop-filter: blur(20px);
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 0 0 max(12px, env(safe-area-inset-bottom)) 0;
    z-index: 40;
}
.pb-mobile-tabs {
    display: flex;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    padding: 0 4px;
}
.pb-mobile-tab {
    flex: 1; padding: 12px 8px;
    background: none; border: none;
    font-family: 'Space Mono', monospace;
    font-size: 9px; letter-spacing: 0.18em; text-transform: uppercase;
    color: rgba(255,255,255,0.3); cursor: pointer;
    position: relative; transition: color 0.2s;
}
.pb-mobile-tab.active { color: #f5f0e8; }
.pb-mobile-tab.active::after {
    content: ''; position: absolute;
    bottom: -1px; left: 50%; transform: translateX(-50%);
    width: 28px; height: 1.5px;
    background: #c9363a; border-radius: 1px;
}
.pb-mobile-scroll {
    display: flex; gap: 10px; overflow-x: auto;
    padding: 12px 16px 8px; scrollbar-width: none;
}
.pb-mobile-scroll::-webkit-scrollbar { display: none; }

.pb-mobile-layout-btn {
    flex-shrink: 0;
    display: flex; flex-direction: column;
    align-items: center; gap: 6px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    padding: 10px 12px 8px;
    color: rgba(255,255,255,0.5);
    cursor: pointer; transition: all 0.18s; min-width: 52px;
}
.pb-mobile-layout-btn:hover { background: rgba(255,255,255,0.08); }
.pb-mobile-layout-btn.active {
    background: rgba(201,54,58,0.12);
    border-color: rgba(201,54,58,0.45);
    color: #c9363a;
}
.pb-mobile-layout-icon { width: 22px; }

.pb-mobile-submit {
    display: flex; gap: 8px;
    padding: 10px 16px 4px;
    border-top: 1px solid rgba(255,255,255,0.06);
}

/* ── Mobile overrides ──────────────────────── */
@media (max-width: 1023px) {
    .pb-studio   { display: flex; flex-direction: column; }
    .pb-main     { margin: 0; padding-bottom: 240px; }
    .pb-camera-section {
        padding: 12px 14px;
        border-right: none;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .pb-viewport { max-width: 100%; border-radius: 12px; }
    .pb-sidebar { display: none !important; }
    .pb-mobile-preview-area {
        padding: 20px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
}

/* ── Utility ───────────────────────────────── */
[x-cloak] { display: none !important; }

/* Hide global navbar for this page */
nav { display: none !important; }
#main-navbar { display: none !important; }

/* Force full-screen dark mode and remove layout gaps */
body { 
    background-color: #1a1714 !important; 
    margin: 0 !important; 
    padding: 0 !important; 
}
main { 
    padding-top: 0 !important; 
    margin-top: 0 !important; 
}
#app {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
</style>

@endsection