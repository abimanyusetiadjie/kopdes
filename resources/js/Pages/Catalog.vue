<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { MapPin, Warehouse, AlertTriangle, CheckCircle, FileText, X, Lock } from '@lucide/vue';

const props = defineProps({
    commodities: {
        type: Array,
        required: true,
    },
    villages: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ category: 'Semua', village: 'Semua', search: '' }),
    },
});

const selectedCategory = ref(props.filters?.category || 'Semua');
const selectedVillage = ref(props.filters?.village || 'Semua');
const searchQuery = ref(props.filters?.search || '');

const categories = ['Semua', 'Beras', 'Telur', 'Sayur'];

const categoryLabel = (cat) => {
    if (cat === 'Semua') return 'Semua';
    if (cat === 'Beras') return 'Beras & Karbo';
    if (cat === 'Telur') return 'Telur & Unggas';
    return 'Sayur Segar';
};

const applyFilters = (category = selectedCategory.value) => {
    selectedCategory.value = category;
    router.get('/catalog', {
        category: selectedCategory.value,
        village: selectedVillage.value,
        search: searchQuery.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const isModalOpen = ref(false);
const activeCommodity = ref(null);

const form = useForm({
    commodity_id: '',
    buyer_type: 'MBG_Unit',
    buyer_name: 'Satuan Pelayanan Makan Bergizi Gratis (MBG) Dapur #04 - Sukabumi',
    volume_requested: 100,
    delivery_target_date: new Date(Date.now() + 7 * 86400000).toISOString().split('T')[0],
    notes: 'Kebutuhan suplai gizi harian program dapur sehat bergizi gratis.',
});

const openPreOrderModal = (commodity) => {
    activeCommodity.value = commodity;
    form.commodity_id = commodity.id;
    form.volume_requested = Math.min(150, commodity.current_capacity);
    form.clearErrors();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    activeCommodity.value = null;
};

const submitContract = () => {
    form.post('/contract/store', {
        onSuccess: () => {
            closeModal();
        },
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const newsHeadlines = [
    'Satuan Pelayanan MBG #04 Sukabumi mengunci kontrak 500 kg Beras Organik dari Koperasi Tani Makmur Berjaya',
    'Koperasi Agribisnis Sayur Lestari (Lembang) menyuplai pasokan segar langsung ke dapur pangan sehat',
    'Semua komoditas terverifikasi uji laboratorium keamanan pangan — Skor 98/100 sesuai standar Kemenkop RI',
];
const activeNewsIndex = ref(0);

let newsTimer = null;
onMounted(() => {
    newsTimer = setInterval(() => {
        activeNewsIndex.value = (activeNewsIndex.value + 1) % newsHeadlines.length;
    }, 5000);
});
onUnmounted(() => {
    if (newsTimer) clearInterval(newsTimer);
});
</script>

<template>
    <AppLayout title="DesaHub — Katalog Komoditas">
        <!-- News ticker -->
        <div class="bg-navy-900 text-xs py-2 px-4">
            <div class="max-w-6xl mx-auto flex items-center gap-3">
                <span class="shrink-0 px-2 py-0.5 rounded bg-merah-600 text-white text-[10px] font-semibold uppercase tracking-wide">
                    Info
                </span>
                <Transition
                    mode="out-in"
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-0.5"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <span :key="activeNewsIndex" class="truncate text-navy-300 font-medium">
                        {{ newsHeadlines[activeNewsIndex] }}
                    </span>
                </Transition>
            </div>
        </div>

        <!-- Page header -->
        <section class="bg-white border-b border-gray-200 py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-start lg:items-end justify-between gap-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-navy-900">
                            Katalog Komoditas
                        </h1>
                        <p class="mt-1 text-sm text-navy-500">
                            Pasokan langsung dari Koperasi Unit Desa untuk Satuan Pelayanan MBG dan mitra korporasi.
                        </p>
                    </div>

                    <div class="flex items-center gap-6 text-sm">
                        <div>
                            <span class="text-2xl font-bold text-navy-900">{{ commodities.length }}</span>
                            <span class="text-navy-400 ml-1">komoditas</span>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        <button
                            v-for="category in categories"
                            :key="category"
                            @click="applyFilters(category)"
                            :class="[
                                'px-4 py-2 rounded-lg text-xs font-semibold transition-colors',
                                selectedCategory === category
                                    ? 'bg-navy-900 text-white'
                                    : 'bg-gray-100 text-navy-600 hover:bg-gray-200'
                            ]"
                        >
                            {{ categoryLabel(category) }}
                        </button>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <select
                            v-model="selectedVillage"
                            @change="applyFilters()"
                            class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs font-medium text-navy-800 focus:outline-none focus:border-navy-400"
                        >
                            <option value="Semua">Semua Desa</option>
                            <option v-for="village in villages" :key="village" :value="village">
                                {{ village }}
                            </option>
                        </select>

                        <div class="relative flex-grow sm:w-56">
                            <input
                                v-model="searchQuery"
                                @keyup.enter="applyFilters()"
                                type="text"
                                placeholder="Cari komoditas..."
                                class="w-full bg-white border border-gray-200 rounded-lg pl-9 pr-4 py-2 text-xs font-medium text-navy-900 placeholder-navy-400 focus:outline-none focus:border-navy-400"
                            />
                            <svg class="w-4 h-4 text-navy-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Commodity Grid -->
        <section class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div v-if="commodities.length === 0" class="border border-gray-200 rounded-xl p-12 text-center">
                <h3 class="text-base font-semibold text-navy-900">Komoditas tidak ditemukan</h3>
                <p class="text-sm text-navy-500 mt-1">Coba sesuaikan filter atau reset pencarian.</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div
                    v-for="commodity in commodities"
                    :key="commodity.id"
                    class="border border-gray-200 rounded-xl overflow-hidden bg-white hover:border-navy-300 transition-colors flex flex-col group"
                >
                    <!-- Image with overlay -->
                    <div v-if="commodity.image_url" class="relative h-44 overflow-hidden bg-gray-100">
                        <img
                            :src="commodity.image_url"
                            :alt="commodity.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                            @error="$event.target.style.display='none'"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>

                        <!-- Status badge on image -->
                        <div class="absolute top-3 right-3">
                            <span
                                v-if="commodity.current_capacity < 100"
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-red-600 text-white"
                            >
                                <AlertTriangle :size="11" />
                                Stok Terbatas
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-tani-600 text-white"
                            >
                                <CheckCircle :size="11" />
                                Tersedia
                            </span>
                        </div>

                        <!-- Location badge on image bottom -->
                        <div class="absolute bottom-3 left-3 inline-flex items-center gap-1 text-[11px] text-white/90 font-medium">
                            <MapPin :size="12" />
                            {{ commodity.cooperative?.village_name }}
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex-grow flex flex-col">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-navy-400">
                                <Warehouse :size="12" />
                                {{ commodity.cooperative?.name }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-navy-600">
                                {{ commodity.category }}
                            </span>
                        </div>

                        <h3 class="text-base font-semibold text-navy-900 leading-snug">
                            {{ commodity.name }}
                        </h3>
                        <p class="text-xs text-navy-400 mt-0.5">{{ commodity.grade }}</p>

                        <!-- Supply & Price -->
                        <div class="mt-4 pt-3 border-t border-gray-100 space-y-2 flex-grow">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-navy-400">Stok</span>
                                <span class="text-sm font-semibold text-navy-900">
                                    {{ commodity.current_capacity.toLocaleString('id-ID') }} {{ commodity.unit }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-navy-400">Harga B2B</span>
                                <span class="text-sm font-semibold text-navy-900">
                                    {{ formatCurrency(commodity.base_price_b2b) }}
                                    <span class="text-xs font-normal text-navy-400">/ {{ commodity.unit }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Traceability link -->
                        <div v-if="commodity.traceability_logs && commodity.traceability_logs.length > 0" class="mt-3">
                            <Link
                                :href="`/trace/${commodity.traceability_logs[0].qr_code_token}`"
                                class="inline-flex items-center gap-1 text-xs text-merah-600 hover:text-merah-700 font-medium transition-colors"
                            >
                                <FileText :size="13" />
                                Lihat jejak keamanan pangan
                            </Link>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="px-5 pb-5">
                        <button
                            @click="openPreOrderModal(commodity)"
                            :disabled="commodity.current_capacity <= 0"
                            :class="[
                                'w-full py-2.5 px-4 rounded-lg font-semibold text-sm transition-colors inline-flex items-center justify-center gap-2',
                                commodity.current_capacity > 0
                                    ? 'bg-navy-900 text-white hover:bg-navy-800'
                                    : 'bg-gray-100 text-navy-400 cursor-not-allowed'
                            ]"
                        >
                            <Lock :size="14" />
                            Buat Kontrak Pre-Order
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRE-ORDER MODAL -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isModalOpen"
                class="fixed inset-0 z-50 overflow-y-auto bg-navy-900/50 backdrop-blur-sm flex items-center justify-center p-4"
            >
                <div class="bg-white max-w-lg w-full rounded-xl border border-gray-200 shadow-xl relative">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 border-b border-gray-100">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900">Kontrak Pre-Order</h3>
                            <p class="text-xs text-navy-400 mt-0.5">Reservasi pasokan komoditas</p>
                        </div>
                        <button
                            @click="closeModal"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-navy-400 hover:text-navy-900 hover:bg-gray-100 transition-colors"
                        >
                            <X :size="16" />
                        </button>
                    </div>

                    <!-- Commodity Summary -->
                    <div v-if="activeCommodity" class="mx-5 mt-5 p-4 rounded-lg bg-gray-50 border border-gray-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-xs text-navy-400">{{ activeCommodity.cooperative?.name }}</div>
                                <div class="text-sm font-semibold text-navy-900 mt-0.5">{{ activeCommodity.name }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-navy-400">Stok</div>
                                <div class="text-sm font-semibold text-navy-900">
                                    {{ activeCommodity.current_capacity }} {{ activeCommodity.unit }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitContract" class="p-5 space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-navy-600 mb-1">Tipe Pembeli</label>
                            <select
                                v-model="form.buyer_type"
                                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-navy-900 focus:outline-none focus:border-navy-400"
                            >
                                <option value="MBG_Unit">Satuan Pelayanan MBG</option>
                                <option value="Corporate">Mitra Korporasi B2B</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-navy-600 mb-1">Nama Institusi</label>
                            <input
                                v-model="form.buyer_name"
                                type="text"
                                required
                                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-navy-900 focus:outline-none focus:border-navy-400"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-navy-600 mb-1">
                                    Volume ({{ activeCommodity?.unit }})
                                </label>
                                <input
                                    v-model.number="form.volume_requested"
                                    type="number"
                                    required
                                    :max="activeCommodity?.current_capacity"
                                    min="1"
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-semibold text-navy-900 focus:outline-none focus:border-navy-400"
                                />
                                <span v-if="form.errors.volume_requested" class="text-xs text-red-600 mt-1 block">
                                    {{ form.errors.volume_requested }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-navy-600 mb-1">Target Kirim</label>
                                <input
                                    v-model="form.delivery_target_date"
                                    type="date"
                                    required
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-navy-900 focus:outline-none focus:border-navy-400"
                                />
                            </div>
                        </div>

                        <!-- Price breakdown -->
                        <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="flex justify-between items-center text-xs text-navy-500">
                                <span>Harga per {{ activeCommodity?.unit }}</span>
                                <span class="font-medium text-navy-700">{{ formatCurrency(activeCommodity?.base_price_b2b || 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm font-bold text-navy-900 mt-2 pt-2 border-t border-gray-200">
                                <span>Total Nilai Kontrak</span>
                                <span>{{ formatCurrency((activeCommodity?.base_price_b2b || 0) * (form.volume_requested || 0)) }}</span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full py-3 rounded-lg bg-navy-900 hover:bg-navy-800 text-white font-semibold text-sm transition-colors inline-flex items-center justify-center gap-2"
                        >
                            <Lock :size="14" />
                            <span v-if="form.processing">Memproses...</span>
                            <span v-else>Konfirmasi Kontrak</span>
                        </button>
                    </form>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>
