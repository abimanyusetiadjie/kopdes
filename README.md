# DesaHub: Solusi Cerdas Agregator Komoditas Desa 🌾

Halo Juri Hackathon Nasional Kemenkop RI 2026! 👋
Selamat datang di repository **DesaHub**.

Ide dari aplikasi ini simpel: kami ingin bantu Koperasi Unit Desa (KUD) jualan hasil panen langsung ke institusi (kayak program Makan Bergizi Gratis/MBG atau perusahaan besar) tanpa lewat banyak tengkulak. Biar petani bisa untung lebih besar (sekitar 30-50%).

---

## ✨ Fitur Utama Kami

1. **Jual Panen AI (AI-Powered Grading)** 🤖
   Petani tinggal foto hasil panennya, terus AI kita bakal otomatis nentuin *grade* (A/B/C/D) sama estimasi harga wajarnya. Nggak perlu cek manual lagi!
2. **Katalog & Stok Real-Time** 🛒
   Semua stok dari berbagai koperasi kelihatan jelas di sini. Kalau stok menipis (di bawah 100 kg), bakal ada peringatan otomatis. Harga B2B juga transparan.
3. **Smart Pre-Order (B2B)** 🤝
   Bikin kontrak pesanan gampang banget. Begitu kontrak dibuat, sistem bakal langsung ngirim notifikasi orderan ke WhatsApp/Telegram pengurus Koperasi yang bersangkutan.
4. **Filament Admin Dashboard (Simkopdes)** 📊
   Ini tempat admin (pemerintah/koperasi) ngatur semuanya. Dari acc pengajuan panen AI, mantau stok, sampai ngeliat *traceability* (keamanan pangan).

---

## 🛠️ Tech Stack yang Dipakai

Kita pakai kombinasi TALL/VILT Stack biar performanya kenceng dan gampang dikembangin lagi ke depannya:

*   **Backend:** Laravel 11.x (PHP 8.3)
*   **Frontend:** Vue.js 3 + Inertia.js
*   **Admin Panel:** Filament v3 (keren banget buat bikin dashboard cepet)
*   **Database:** PostgreSQL (kita hosting di **Supabase**)
*   **Styling:** Tailwind CSS, Lucide Icons
*   **AI & Ticker:** Kita pakai simulasi *workflow* (n8n + Gemini AI) dan mock data ticker BI/Kemendag

---

## ⚙️ Persyaratan Sistem (Prerequisites)

Kalau mau coba nge-run project ini di laptop/PC lokal, pastiin udah install:
*   **PHP** (Minimal v8.2, tapi disarankan v8.3)
*   **Composer**
*   **Node.js** (Minimal v18) & **NPM**
*   **Ekstensi PHP wajib nyala:** `pdo_pgsql`, `pgsql`, `fileinfo`, `gd`, `zip`, `curl` *(Ini penting banget biar nggak error pas nyambung ke Supabase & Filament).*
*   **Git**

---

## 🚀 Cara Menjalankan Aplikasi (Setup Tutorial)

Yuk ikutin langkah-langkah di bawah ini buat jalanin DesaHub di lokal:

### 1. Clone Repositori
```bash
git clone <URL_GITHUB_DESAHUB>
cd desahub
```

### 2. Install Dependensi (Vendor & Node Modules)
```bash
# Install package Laravel
composer install

# Install package Vue & Tailwind
npm install
```

### 3. Setting File .env (Database)
Pertama, copy file example-nya:
```bash
cp .env.example .env
```
Terus, buka file `.env` dan masukin kredensial **Supabase** kita di bagian ini:
```env
   DB_CONNECTION=pgsql
   DB_HOST=aws-1-ap-south-1.pooler.supabase.com
   DB_PORT=6543
   DB_DATABASE=postgres
   DB_USERNAME=postgres.qwmzjxrdgccjwxpvhlvk
   DB_PASSWORD=TrollPredict12
   DB_PREFIX=TrollPredict
```

### 4. Generate Key & Setup Storage
```bash
# Bikin app key baru
php artisan key:generate

# Hubungin folder storage biar foto komoditas bisa muncul
php artisan storage:link

# (Opsional aja kalau mau reset & seed ulang database)
# php artisan migrate:fresh --seed
```

### 5. Build Frontend & Nyalain Server
Buka dua tab Terminal terpisah ya.

**Terminal 1 (Buat nge-build Vue-nya):**
```bash
npm run build
# Atau pakai 'npm run dev' kalau mau sambil ngoding/live reload
```

**Terminal 2 (Buat nyalain server Laravel):**
```bash
php artisan serve
```

### 6. Berhasil! 🎉
Sekarang tinggal buka di browser:
*   **Halaman Depan:** `http://127.0.0.1:8000`
*   **Halaman Admin:** `http://127.0.0.1:8000/admin` *(login pakai akun demo yang udah kita siapin ya).*
*   Demo: admin@desahub.go.id / password *

---
*Dibuat penuh semangat buat memajukan petani Indonesia!* 👨‍🌾👩‍🌾
