<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BarChart3, ShieldCheck, QrCode, LayoutDashboard, ArrowRight, TrendingUp, ScanFace } from '@lucide/vue';

const props = defineProps({
    totalCooperatives: Number,
    totalCapacityKg: Number,
    totalContractValue: Number,
    totalMbgContracts: Number,
    totalCommodities: Number,
});

const animatedCoops = ref(0);
const animatedCapacity = ref(0);
const animatedContracts = ref(0);
const animatedMbg = ref(0);
const hasAnimated = ref(false);

const easeOut = (t) => 1 - Math.pow(1 - t, 3);

const animateCounter = (target, refVal, duration = 2000) => {
    const startTime = performance.now();
    const step = (now) => {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        refVal.value = Math.floor(easeOut(progress) * target);
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
};

onMounted(() => {
    setTimeout(() => {
        hasAnimated.value = true;
        animateCounter(props.totalCooperatives, animatedCoops, 1200);
        animateCounter(props.totalCapacityKg, animatedCapacity, 1800);
        animateCounter(props.totalContractValue, animatedContracts, 2000);
        animateCounter(props.totalMbgContracts, animatedMbg, 1000);
    }, 200);
});

const formatNumber = (n) => n.toLocaleString('id-ID');
const formatRupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);

const features = [
    {
        icon: BarChart3,
        title: 'Kapasitas Pasokan Real-Time',
        desc: 'Pantau ketersediaan stok langsung dari gudang koperasi desa secara transparan dan terukur.',
    },
    {
        icon: ShieldCheck,
        title: 'Kontrak Pre-Order Terproteksi',
        desc: 'Kunci harga dan volume komoditas untuk masa depan. Mencegah spekulasi dan fluktuasi pasar.',
    },
    {
        icon: QrCode,
        title: 'Traceability Keamanan Pangan',
        desc: 'Lacak asal-usul setiap komoditas dari lahan petani hingga dapur MBG dengan kode QR unik.',
    },
    {
        icon: LayoutDashboard,
        title: 'Dasbor Monitoring Terpadu',
        desc: 'Panel administrasi untuk memantau seluruh aktivitas agregasi, kontrak, dan distribusi pangan nasional.',
    },
];
</script>

<template>
    <AppLayout title="DesaHub — Agregator Komoditas Desa untuk Ketahanan Pangan">
        <!-- LIVE TICKER KEMENDAG & BI (MOCK FOR HACKATHON) -->
        <div class="bg-black text-white text-[10px] sm:text-xs py-1.5 px-4 overflow-hidden relative flex items-center border-b border-white/10 shadow-sm z-10">
            <div class="flex items-center gap-2 pr-4 border-r border-white/20 shrink-0 font-bold tracking-wider text-yellow-400 uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                LIVE MARKET
            </div>
            
            <div class="flex-1 overflow-hidden relative">
                <!-- CSS Marquee Animation -->
                <div class="whitespace-nowrap inline-block animate-marquee flex items-center gap-8 pl-4">
                    <span class="text-white/60">Sumb. Data: API SP2KP Kemendag & PIHPS Bank Indonesia</span>
                    
                    <span class="inline-flex items-center gap-1.5">
                        <span class="font-semibold text-white">Beras Medium</span>
                        <span class="text-red-400">Rp14.500 <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> (+1.2%)</span>
                    </span>
                    
                    <span class="inline-flex items-center gap-1.5">
                        <span class="font-semibold text-white">Jagung Pipil</span>
                        <span class="text-green-400">Rp7.200 <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg> (-0.5%)</span>
                    </span>
                    
                    <span class="inline-flex items-center gap-1.5">
                        <span class="font-semibold text-white">Telur Ayam Ras</span>
                        <span class="text-green-400">Rp28.500 <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg> (-2.1%)</span>
                    </span>
                    
                    <span class="inline-flex items-center gap-1.5">
                        <span class="font-semibold text-white">Cabai Merah</span>
                        <span class="text-red-400">Rp55.000 <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> (+5.4%)</span>
                    </span>
                    
                    <span class="inline-flex items-center gap-1.5">
                        <span class="font-semibold text-white">Daging Ayam</span>
                        <span class="text-gray-300">Rp38.000 (Tetap)</span>
                    </span>
                    
                    <span class="text-yellow-400 font-semibold pl-4">Indeks Inflasi Pangan (BI): 3.2% YoY (Aman)</span>
                </div>
            </div>
        </div>

        <!-- HERO with background image + dark overlay -->
        <section class="relative min-h-[520px] flex items-center">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1920&q=80"
                    alt=""
                    class="w-full h-full object-cover"
                />
                <!-- Dark scrim overlay -->
                <div class="absolute inset-0 bg-navy-900/75"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 text-white flex flex-col items-center text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    Menghubungkan Koperasi Desa<br class="hidden sm:block" />dengan Pembeli Institusi
                </h1>

                <p class="mt-6 text-base sm:text-lg text-white/80 leading-relaxed max-w-3xl">
                    Platform B2B yang mempertemukan Koperasi Unit Desa dengan Satuan Pelayanan Makan Bergizi Gratis dan mitra korporasi. Memotong rantai tengkulak, meningkatkan pendapatan petani hingga 30–50%.
                </p>

                <div class="mt-10 flex flex-wrap justify-center items-center gap-4">
                    <Link
                        href="/catalog"
                        class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg bg-merah-600 hover:bg-merah-700 text-white font-bold text-sm transition-colors shadow-lg shadow-merah-600/30"
                    >
                        Lihat Katalog
                        <ArrowRight :size="16" />
                    </Link>
                    <a
                        href="/admin"
                        target="_blank"
                        class="px-8 py-3.5 rounded-lg bg-transparent hover:bg-white/10 border border-white text-white font-semibold text-sm transition-colors backdrop-blur-sm"
                    >
                        Masuk Admin
                    </a>
                </div>

                <!-- Stats Row -->
                <div class="mt-16 w-full grid grid-cols-2 lg:grid-cols-4 gap-4 text-left">
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-xl p-5">
                        <div class="text-2xl sm:text-3xl font-bold tabular-nums">
                            {{ formatNumber(animatedCoops) }}
                        </div>
                        <div class="text-xs text-white/50 mt-1 font-medium">Koperasi Desa Aktif</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-xl p-5">
                        <div class="text-2xl sm:text-3xl font-bold tabular-nums">
                            {{ formatNumber(animatedCapacity) }}
                            <span class="text-base font-normal text-white/50">kg</span>
                        </div>
                        <div class="text-xs text-white/50 mt-1 font-medium">Total Pasokan</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-xl p-5">
                        <div class="text-lg sm:text-xl font-bold tabular-nums">
                            {{ formatRupiah(animatedContracts) }}
                        </div>
                        <div class="text-xs text-white/50 mt-1 font-medium">Nilai Kontrak</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-xl p-5">
                        <div class="text-2xl sm:text-3xl font-bold tabular-nums">
                            {{ animatedMbg }}
                        </div>
                        <div class="text-xs text-white/50 mt-1 font-medium">Kontrak MBG</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES with icons -->
        <section class="py-16 sm:py-20 bg-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-lg mb-12">
                    <h2 class="text-2xl font-bold text-navy-900 tracking-tight">
                        Empat pilar yang menjadi fondasi ekosistem DesaHub
                    </h2>
                    <p class="mt-2 text-sm text-navy-500 leading-relaxed">
                        Dirancang untuk menyelesaikan masalah agregasi pangan desa secara menyeluruh.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div
                        v-for="(feature, idx) in features"
                        :key="idx"
                        class="border border-gray-200 rounded-xl p-6 hover:border-navy-300 transition-colors"
                    >
                        <div class="w-10 h-10 rounded-lg bg-navy-900 text-white flex items-center justify-center mb-4">
                            <component :is="feature.icon" :size="20" :stroke-width="1.8" />
                        </div>
                        <h3 class="text-base font-semibold text-navy-900">
                            {{ feature.title }}
                        </h3>
                        <p class="mt-2 text-sm text-navy-500 leading-relaxed">
                            {{ feature.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- IMPACT -->
        <section class="relative py-16 overflow-hidden">
            <!-- Background image + overlay -->
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=1920&q=80"
                    alt=""
                    class="w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-navy-900/85"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="text-white">
                        <div class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-white/50 mb-3">
                            <TrendingUp :size="14" />
                            Dampak Ekonomi
                        </div>
                        <h2 class="text-2xl font-bold tracking-tight">
                            Meningkatkan pendapatan petani 30–50% dengan memotong rantai tengkulak
                        </h2>
                        <p class="mt-4 text-sm text-white/60 leading-relaxed">
                            Koperasi desa terhubung langsung ke pembeli institusi. Marjin ekonomi yang selama ini hilang ke perantara, kembali sebagai peningkatan SHU koperasi dan kesejahteraan anggota.
                        </p>
                    </div>

                    <div class="space-y-5">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/10">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="font-medium text-white/80">Efisiensi Rantai Pasok</span>
                                <span class="font-semibold text-white">100%</span>
                            </div>
                            <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-white rounded-full transition-all duration-[2s] ease-out" :style="{ width: hasAnimated ? '100%' : '0%' }"></div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/10">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="font-medium text-white/80">Cakupan Provinsi</span>
                                <span class="font-semibold text-white">34 / 34</span>
                            </div>
                            <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-white rounded-full transition-all duration-[2.5s] ease-out" :style="{ width: hasAnimated ? '100%' : '0%' }"></div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/10">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="font-medium text-white/80">Skor Keamanan Pangan</span>
                                <span class="font-semibold text-white">97–99 / 100</span>
                            </div>
                            <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-white rounded-full transition-all duration-[2s] ease-out" :style="{ width: hasAnimated ? '98%' : '0%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-14 bg-white border-t border-gray-200">
            <div class="max-w-2xl mx-auto px-4 text-center">
                <h2 class="text-xl font-bold text-navy-900">
                    Mulai akses pasokan pangan desa
                </h2>
                <p class="mt-2 text-sm text-navy-500">
                    Jelajahi katalog komoditas yang tersedia atau masuk ke panel administrasi.
                </p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <Link
                        href="/catalog"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-navy-900 hover:bg-navy-800 text-white font-semibold text-sm transition-colors"
                    >
                        Katalog Komoditas
                        <ArrowRight :size="16" />
                    </Link>
                    <a
                        href="/admin"
                        target="_blank"
                        class="px-6 py-3 rounded-lg border border-gray-300 hover:border-navy-400 text-navy-700 font-semibold text-sm transition-colors"
                    >
                        Panel Admin
                    </a>
                </div>
                <p class="mt-4 text-xs text-navy-400">
                    Demo: admin@desahub.go.id / password
                </p>
            </div>
        </section>
    </AppLayout>
</template>

<style>
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    animation: marquee 20s linear infinite;
    /* Ensure the text is long enough or duplicated if needed to loop perfectly, 
       but for a hackathon demo, a simple long scroll is often enough */
}
.animate-marquee:hover {
    animation-play-state: paused;
}
</style>
