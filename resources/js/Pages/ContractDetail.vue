<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ArrowLeft, Printer, MessageCircle, CheckCircle } from '@lucide/vue';

const props = defineProps({
    contract: {
        type: Object,
        required: true,
    },
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const totalContractValue = computed(() => {
    return (props.contract.volume_requested || 0) * (props.contract.agreed_price || 0);
});

const telegramLink = computed(() => {
    const coop = props.contract.commodity?.cooperative;
    if (!coop || !coop.whatsapp_number) return '#';
    const message = encodeURIComponent(
        `Halo ${coop.name}, kami dari ${props.contract.buyer_name}. Kontrak Pre-Order No. ${props.contract.contract_number} untuk ${props.contract.commodity.name} sebanyak ${props.contract.volume_requested} ${props.contract.commodity.unit}. Mohon konfirmasi jadwal pengiriman.`
    );
    
    // Format the number for Telegram (e.g., change 0812... to +62812...)
    let phone = coop.whatsapp_number;
    if (phone.startsWith('0')) {
        phone = '+62' + phone.substring(1);
    }
    
    return `https://t.me/${phone}?text=${message}`;
});

const printInvoice = () => {
    window.print();
};
</script>

<template>
    <AppLayout title="DesaHub — Bukti Kontrak Pre-Order">
        <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Nav bar -->
            <div class="flex items-center justify-between mb-6 print:hidden">
                <Link
                    href="/catalog"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-navy-400 hover:text-navy-700 transition-colors"
                >
                    <ArrowLeft :size="14" />
                    Kembali ke Katalog
                </Link>
                <button
                    @click="printInvoice"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-navy-900 text-white text-xs font-semibold hover:bg-navy-800 transition-colors"
                >
                    <Printer :size="14" />
                    Cetak Kontrak
                </button>
            </div>

            <!-- Contract Card -->
            <div class="bg-white border border-gray-200 rounded-xl print:border-none print:shadow-none">
                <!-- Header -->
                <div class="p-6 sm:p-8 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <p class="text-xs font-semibold tracking-widest uppercase text-navy-400 mb-1">
                                Kementerian Koperasi RI — Simkopdes
                            </p>
                            <h1 class="text-xl font-bold text-navy-900">
                                Kontrak Pre-Order B2B
                            </h1>
                            <p class="text-xs text-navy-400 mt-1">
                                No. {{ contract.contract_number }}
                            </p>
                        </div>

                        <div class="px-4 py-2 rounded-lg border-2 border-navy-900 text-center">
                            <div class="text-[10px] font-semibold text-navy-500 uppercase">Status</div>
                            <div class="text-sm font-bold text-navy-900">Aktif</div>
                        </div>
                    </div>
                </div>

                <!-- Parties -->
                <div class="p-6 sm:p-8 border-b border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-[11px] font-semibold text-navy-400 uppercase mb-2">Penyuplai (KUD)</div>
                        <h3 class="text-sm font-semibold text-navy-900">
                            {{ contract.commodity?.cooperative?.name }}
                        </h3>
                        <p class="text-xs text-navy-500 mt-1">
                            {{ contract.commodity?.cooperative?.village_name }}
                        </p>
                        <p class="text-xs text-navy-500">
                            PJ: {{ contract.commodity?.cooperative?.leader_name }}
                        </p>
                    </div>

                    <div>
                        <div class="text-[11px] font-semibold text-navy-400 uppercase mb-2">Pembeli</div>
                        <h3 class="text-sm font-semibold text-navy-900">
                            {{ contract.buyer_name }}
                        </h3>
                        <p class="text-xs text-navy-500 mt-1">
                            {{ contract.buyer_type === 'MBG_Unit' ? 'Satuan Pelayanan MBG' : 'Mitra Korporasi B2B' }}
                        </p>
                        <p class="text-xs text-navy-500">
                            Target Kirim: <span class="font-medium text-navy-700">{{ contract.delivery_target_date }}</span>
                        </p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="p-6 sm:p-8 border-b border-gray-100 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-200 text-[11px] uppercase font-semibold text-navy-400">
                                <th class="py-2 pr-4">Komoditas</th>
                                <th class="py-2 px-4">Kategori</th>
                                <th class="py-2 px-4 text-right">Volume</th>
                                <th class="py-2 px-4 text-right">Harga Satuan</th>
                                <th class="py-2 pl-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-navy-900">
                            <tr>
                                <td class="py-3 pr-4">
                                    <div class="font-semibold">{{ contract.commodity?.name }}</div>
                                    <div class="text-xs text-navy-400">{{ contract.notes || 'Pasokan pangan resmi' }}</div>
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-navy-600 font-medium">
                                        {{ contract.commodity?.category }} / {{ contract.commodity?.grade }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right font-semibold">
                                    {{ contract.volume_requested.toLocaleString('id-ID') }} {{ contract.commodity?.unit }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    {{ formatCurrency(contract.agreed_price) }}
                                </td>
                                <td class="py-3 pl-4 text-right font-bold">
                                    {{ formatCurrency(totalContractValue) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Total & Terms -->
                <div class="p-6 sm:p-8 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="max-w-sm text-xs text-navy-400 leading-relaxed">
                        <span class="font-semibold text-navy-600 block mb-1">Ketentuan:</span>
                        Volume pesanan direservasi di database Simkopdes. Harga terkunci sesuai kesepakatan kontrak.
                    </div>

                    <div class="text-right">
                        <div class="text-xs text-navy-400">Total Nilai Kontrak</div>
                        <div class="text-2xl font-bold text-navy-900 mt-0.5">
                            {{ formatCurrency(totalContractValue) }}
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="p-6 sm:p-8 border-b border-gray-100 grid grid-cols-2 gap-8 text-center text-xs text-navy-600">
                    <div>
                        <div class="font-semibold uppercase text-navy-400 mb-1">Pihak Pertama</div>
                        <div class="text-[11px] text-navy-400">{{ contract.commodity?.cooperative?.name }}</div>
                        <div class="h-20 flex items-end justify-center">
                            <span class="text-navy-300 text-[10px] italic border-b border-gray-200 px-6 pb-1">
                                Tanda Tangan
                            </span>
                        </div>
                        <div class="font-semibold text-navy-900 mt-1">{{ contract.commodity?.cooperative?.leader_name }}</div>
                    </div>

                    <div>
                        <div class="font-semibold uppercase text-navy-400 mb-1">Pihak Kedua</div>
                        <div class="text-[11px] text-navy-400">{{ contract.buyer_name }}</div>
                        <div class="h-20 flex items-end justify-center">
                            <span class="text-navy-300 text-[10px] italic border-b border-gray-200 px-6 pb-1">
                                Tanda Tangan
                            </span>
                        </div>
                        <div class="font-semibold text-navy-900 mt-1">Pejabat Pembuat Komitmen</div>
                    </div>
                </div>

                <!-- Action -->
                <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 print:hidden">
                    <span class="inline-flex items-center gap-1.5 text-xs text-tani-700 font-medium">
                        <CheckCircle :size="14" />
                        Stok komoditas telah dikurangi dari kapasitas KUD
                    </span>
                    <a
                        :href="telegramLink"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm transition-colors"
                    >
                        Kirim ke Telegram Koperasi
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
@media print {
    body { background: white !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    header, nav, .print\:hidden { display: none !important; }
}
</style>
