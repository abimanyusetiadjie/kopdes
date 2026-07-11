<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import QrcodeVue from 'qrcode.vue';
import { ArrowLeft, Sprout, FlaskConical, Building2, Truck, ScanLine } from '@lucide/vue';

const props = defineProps({
    log: {
        type: Object,
        required: true,
    },
});

const timelineSteps = [
    {
        icon: Sprout,
        title: 'Panen dari Lahan Kelompok Tani',
        desc: `Dipanen oleh ${props.log.farmer_group_name}, tercatat pada tanggal ${props.log.harvest_date}.`,
        status: 'Selesai',
    },
    {
        icon: FlaskConical,
        title: 'Pengujian Mutu & Keamanan Pangan',
        desc: `Skor Keamanan Pangan: ${props.log.food_safety_score}/100 — Disertifikasi oleh ${props.log.lab_certified_by}.`,
        status: 'Lolos Uji',
    },
    {
        icon: Building2,
        title: 'Agregasi di Gudang Koperasi',
        desc: `Dikelola oleh ${props.log.commodity?.cooperative?.name} (${props.log.commodity?.cooperative?.village_name}).`,
        status: 'Siap Kirim',
    },
    {
        icon: Truck,
        title: 'Distribusi ke Satuan MBG / Mitra',
        desc: 'Komoditas didistribusikan ke Satuan Pelayanan MBG maupun mitra korporasi sesuai kontrak.',
        status: 'Terhubung',
    },
];
</script>

<template>
    <AppLayout title="DesaHub — Traceability Keamanan Pangan">
        <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back -->
            <div class="mb-6">
                <Link
                    href="/catalog"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-navy-400 hover:text-navy-700 transition-colors"
                >
                    <ArrowLeft :size="14" />
                    Kembali ke Katalog
                </Link>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl">
                <!-- Header -->
                <div class="p-6 sm:p-8 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold tracking-widest uppercase text-navy-400 mb-1">
                                Traceability Keamanan Pangan
                            </p>
                            <h1 class="text-xl font-bold text-navy-900">
                                Jejak Asal-Usul Komoditas
                            </h1>
                            <p class="text-xs text-navy-400 mt-1 font-mono">
                                {{ log.qr_code_token }}
                            </p>
                        </div>

                        <div class="text-center px-5 py-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="text-xs text-navy-400 mb-0.5">Skor Keamanan</div>
                            <div class="text-2xl font-bold text-navy-900">{{ log.food_safety_score }}<span class="text-sm font-normal text-navy-400">/100</span></div>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="px-6 sm:px-8 py-6 border-b border-gray-100 flex flex-col sm:flex-row items-center gap-6">
                    <div class="bg-white p-3 border border-gray-200 rounded-lg shrink-0">
                        <QrcodeVue
                            :value="`https://desahub.kemenkop.go.id/trace/${log.qr_code_token}`"
                            :size="120"
                            level="H"
                            render-as="svg"
                            foreground="#0F172A"
                        />
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-900 mb-1">
                            <ScanLine :size="16" />
                            Verifikasi dengan QR Code
                        </div>
                        <p class="text-xs text-navy-500 leading-relaxed">
                            Pindai kode ini untuk memverifikasi asal-usul komoditas dari lahan petani hingga titik distribusi. Token ini bersifat unik dan tidak dapat dipalsukan.
                        </p>
                    </div>
                </div>

                <!-- Commodity Info -->
                <div class="px-6 sm:px-8 py-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <div class="text-xs text-navy-400 mb-0.5">Komoditas</div>
                            <div class="text-sm font-semibold text-navy-900">{{ log.commodity?.name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-navy-400 mb-0.5">Koperasi</div>
                            <div class="text-sm font-semibold text-navy-900">{{ log.commodity?.cooperative?.name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-navy-400 mb-0.5">Koordinat Lahan</div>
                            <div class="text-sm font-mono font-medium text-navy-900">{{ log.location_coordinates }}</div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="px-6 sm:px-8 py-6">
                    <h3 class="text-sm font-semibold text-navy-900 mb-5">Rantai Pasok</h3>

                    <div class="space-y-4">
                        <div
                            v-for="(step, idx) in timelineSteps"
                            :key="idx"
                            class="flex gap-4"
                        >
                            <div class="shrink-0 w-9 h-9 rounded-lg bg-navy-900 text-white flex items-center justify-center">
                                <component :is="step.icon" :size="18" :stroke-width="1.8" />
                            </div>
                            <div class="flex-grow pb-4" :class="idx < timelineSteps.length - 1 ? 'border-b border-gray-100' : ''">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-navy-900">{{ step.title }}</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-tani-50 text-tani-700">
                                        {{ step.status }}
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-navy-500 leading-relaxed">
                                    {{ step.desc }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
