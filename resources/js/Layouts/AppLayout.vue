<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Home, ShoppingBasket, Settings, Menu, X, Leaf, ScanFace } from '@lucide/vue';

defineProps({
    title: {
        type: String,
        default: 'DesaHub — Agregator Komoditas Desa'
    }
});

const mobileMenuOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-white text-navy-900 flex flex-col font-sans">


        <!-- Main Navbar -->
        <header class="sticky top-0 z-50 bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <!-- Brand -->
                    <Link href="/" class="flex items-center gap-2.5">
                        <img src="/logo.png" alt="KOP DES MERAH PUTIH" class="h-10 sm:h-12 object-contain" />
                    </Link>

                    <!-- Desktop Nav -->
                    <nav class="hidden md:flex items-center gap-6 text-sm">
                        <Link
                            href="/"
                            class="inline-flex items-center gap-1.5 text-navy-500 hover:text-navy-900 font-medium transition-colors py-2"
                        >
                            <Home :size="15" :stroke-width="1.8" />
                            Beranda
                        </Link>
                        <Link
                            href="/catalog"
                            class="inline-flex items-center gap-1.5 text-navy-500 hover:text-navy-900 font-medium transition-colors py-2"
                        >
                            <ShoppingBasket :size="15" :stroke-width="1.8" />
                            Katalog
                        </Link>
                        <Link
                            href="/jual"
                            class="inline-flex items-center gap-1.5 text-merah-600 hover:text-merah-700 font-bold transition-colors py-2 bg-merah-50 px-3 rounded-full"
                        >
                            <ScanFace :size="15" :stroke-width="2" />
                            Jual Panen (AI)
                        </Link>
                        <a
                            href="/admin"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 text-navy-500 hover:text-navy-900 font-medium transition-colors py-2"
                        >
                            <Settings :size="15" :stroke-width="1.8" />
                            Admin
                        </a>
                    </nav>

                    <!-- Mobile menu button -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 text-navy-500 hover:text-navy-900"
                    >
                        <Menu v-if="!mobileMenuOpen" :size="20" />
                        <X v-else :size="20" />
                    </button>
                </div>

                <!-- Mobile Nav -->
                <div v-if="mobileMenuOpen" class="md:hidden pb-3 border-t border-gray-100 mt-1 space-y-1">
                    <Link href="/" class="flex items-center gap-2 py-2 text-sm text-navy-600 font-medium">
                        <Home :size="15" /> Beranda
                    </Link>
                    <Link href="/catalog" class="flex items-center gap-2 py-2 text-sm text-navy-600 font-medium">
                        <ShoppingBasket :size="15" /> Katalog
                    </Link>
                    <Link href="/jual" class="flex items-center gap-2 py-2 text-sm text-merah-600 font-bold">
                        <ScanFace :size="15" /> Jual Panen (AI)
                    </Link>
                    <a href="/admin" target="_blank" class="flex items-center gap-2 py-2 text-sm text-navy-600 font-medium">
                        <Settings :size="15" /> Admin
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200 bg-white py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-navy-400">
                <div class="flex items-center gap-2">
                    <img src="/logo.png" alt="KOP DES MERAH PUTIH" class="h-6 object-contain" />
                    <span>Hackathon Kemenkop RI 2026</span>
                </div>
                <span>Sistem Informasi Koperasi Desa</span>
            </div>
        </footer>

        <!-- Floating Telegram Button with Label -->
        <div class="fixed bottom-6 right-6 z-50 flex flex-col items-center gap-2">
            <!-- Balon Teks -->
            <div class="bg-white px-3 py-1.5 rounded-lg shadow-lg text-[11px] sm:text-xs font-semibold text-navy-800 border border-gray-100 animate-bounce relative">
                Tanya Admin 💬
                <!-- Segitiga penunjuk ke bawah -->
                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2.5 h-2.5 bg-white border-b border-r border-gray-100 transform rotate-45"></div>
            </div>
            
            <a 
                href="https://t.me/TanyaRapiBot" 
                target="_blank"
                rel="noopener noreferrer"
                class="bg-[#229ED9] hover:bg-[#0088cc] text-white p-3.5 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex items-center justify-center"
                title="Chat Bot Telegram"
            >
                <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor" class="group-hover:animate-pulse ml-[-2px]">
                    <path d="M20.665 3.717l-17.73 6.837c-1.21.486-1.203 1.161-.222 1.462l4.552 1.42l10.532-6.645c.498-.303.953-.14.579.192l-8.533 7.701h-.002l.002.001-.314 4.692c.46 0 .663-.211.921-.46l2.211-2.15 4.599 3.397c.848.467 1.457.227 1.668-.785l3.019-14.228c.309-1.239-.473-1.8-1.282-1.434z"/>
                </svg>
            </a>
        </div>
    </div>
</template>
