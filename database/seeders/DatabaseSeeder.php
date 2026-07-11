<?php

namespace Database\Seeders;

use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\PreOrderContract;
use App\Models\TraceabilityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with Ministry of Cooperatives Hackathon Demo Data.
     */
    public function run(): void
    {
        // 1. Create Default Admin User for FilamentPHP
        User::firstOrCreate([
            'email' => 'admin@desahub.go.id',
        ], [
            'name' => 'Administrator Kementerian Koperasi RI',
            'password' => bcrypt('password'),
        ]);

        // 2. Create Village Cooperatives (Koperasi Desa)
        $coopCianjur = Cooperative::create([
            'name' => 'Koperasi Tani Makmur Berjaya',
            'village_name' => 'Desa Sukamaju, Kab. Cianjur',
            'whatsapp_number' => '6281234567890',
            'leader_name' => 'H. Bambang Subroto',
            'address' => 'Jl. Raya Desa Sukamaju No. 12, Warungkondang, Cianjur, Jawa Barat',
        ]);

        $coopBrebes = Cooperative::create([
            'name' => 'KUD Ternak Sejahtera Unggas',
            'village_name' => 'Desa Jatibarang, Kab. Brebes',
            'whatsapp_number' => '6281345678901',
            'leader_name' => 'Siti Nurhaliza, S.Pt.',
            'address' => 'Sentra Peternakan Unggas Rakyat, Jatibarang Lor, Brebes, Jawa Tengah',
        ]);

        $coopLembang = Cooperative::create([
            'name' => 'Koperasi Agribisnis Sayur Lestari',
            'village_name' => 'Desa Cibodas, Lembang',
            'whatsapp_number' => '6281456789012',
            'leader_name' => 'Deden Kurniawan',
            'address' => 'Kawasan Agroekologi Cibodas, Lembang, Kab. Bandung Barat',
        ]);

        // 3. Create Commodities (Featuring Safe > 100 kg and Critical < 100 kg stocks)
        $berasPandanWangi = Commodity::create([
            'cooperative_id' => $coopCianjur->id,
            'name' => 'Beras Organik Pandan Wangi Grade A',
            'category' => 'Beras',
            'current_capacity' => 4500, // Safe >= 100 kg
            'unit' => 'kg',
            'base_price_b2b' => 14500.00,
            'grade' => 'Grade A Premium Sertifikasi Organik',
            'description' => 'Beras pulen harum asli Pandan Wangi dari persawahan irigasi pegunungan Cianjur. Bebas pestisida kimia sintetis, cocok untuk menu bergizi MBG.',
            'image_url' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=800&q=80',
        ]);

        $telurBrebes = Commodity::create([
            'cooperative_id' => $coopBrebes->id,
            'name' => 'Telur Ayam Ras Omega-3 Segar',
            'category' => 'Telur',
            'current_capacity' => 1250, // Safe >= 100 kg
            'unit' => 'kg',
            'base_price_b2b' => 28500.00,
            'grade' => 'Grade A Super Omega-3',
            'description' => 'Telur ayam petelur kaya Omega-3 dan protein tinggi. Dipanen harian dengan standar sanitasi ketat untuk nutrisi anak sekolah.',
            'image_url' => 'https://images.unsplash.com/photo-1506976785307-8732e854ad03?auto=format&fit=crop&w=800&q=80',
        ]);

        $brokoliOrganik = Commodity::create([
            'cooperative_id' => $coopLembang->id,
            'name' => 'Brokoli Hijau Dataran Tinggi Lembang',
            'category' => 'Sayur',
            'current_capacity' => 65, // < 100 kg -> WILL DISPLAY CRITICAL ALERT BADGE
            'unit' => 'kg',
            'base_price_b2b' => 18000.00,
            'grade' => 'Grade A Hortikultura Organik',
            'description' => 'Sayuran brokoli segar dipanen langsung subuh hari dari lahan agroekologi Cibodas Lembang. Kaya serat, vitamin C, dan antioksidan alami.',
            'image_url' => 'https://images.unsplash.com/photo-1459411621453-7b03977f4bfc?auto=format&fit=crop&w=800&q=80',
        ]);

        // 4. Create Pre-Order Contracts (MBG Unit and Corporate Buyers)
        PreOrderContract::create([
            'commodity_id' => $berasPandanWangi->id,
            'buyer_type' => 'MBG_Unit',
            'buyer_name' => 'Satuan Pelayanan Makan Bergizi Gratis (MBG) Dapur #04 - Sukabumi',
            'volume_requested' => 500,
            'agreed_price' => 14500.00,
            'contract_status' => 'Active',
            'delivery_target_date' => now()->addDays(5)->toDateString(),
            'contract_number' => 'DSH-MBG-8A91F2B0',
            'notes' => 'Pasokan karbohidrat harian untuk 3.000 porsi siswa SD/SMP Sukabumi.',
        ]);

        PreOrderContract::create([
            'commodity_id' => $telurBrebes->id,
            'buyer_type' => 'Corporate',
            'buyer_name' => 'PT Boga Nusantara Sejahtera (Mitra Katering Industri)',
            'volume_requested' => 300,
            'agreed_price' => 28500.00,
            'contract_status' => 'Active',
            'delivery_target_date' => now()->addDays(7)->toDateString(),
            'contract_number' => 'DSH-MBG-4C72E9D1',
            'notes' => 'Suplai protein mingguan dapur sentral katering karyawan.',
        ]);

        // 5. Create Traceability Logs with QR Tokens
        TraceabilityLog::create([
            'commodity_id' => $berasPandanWangi->id,
            'farmer_group_name' => 'Kelompok Tani Harapan Mulya II (Ketua: Kang Asep)',
            'harvest_date' => now()->subDays(3)->toDateString(),
            'qr_code_token' => 'TRC-BERAS-CIANJUR-2026-001',
            'food_safety_score' => 99,
            'lab_certified_by' => 'Lab Pengujian Mutu Pangan Sucofindo & Dinas Pertanian Jabar',
            'location_coordinates' => '-6.8582, 107.0945',
        ]);

        TraceabilityLog::create([
            'commodity_id' => $telurBrebes->id,
            'farmer_group_name' => 'Kelompok Peternak Mandiri Unggas Lestari',
            'harvest_date' => now()->subDay()->toDateString(),
            'qr_code_token' => 'TRC-TELUR-BREBES-2026-002',
            'food_safety_score' => 98,
            'lab_certified_by' => 'Laboratorium Kesehatan Masyarakat Veteriner (Kesmavet) Jateng',
            'location_coordinates' => '-6.8712, 109.0432',
        ]);

        TraceabilityLog::create([
            'commodity_id' => $brokoliOrganik->id,
            'farmer_group_name' => 'Gapoktan Agro Lestari Cibodas Lembang',
            'harvest_date' => now()->toDateString(),
            'qr_code_token' => 'TRC-SAYUR-LEMBANG-2026-003',
            'food_safety_score' => 97,
            'lab_certified_by' => 'Badan Sertifikasi Pangan Organik Seloliman (LeSOS)',
            'location_coordinates' => '-6.8124, 107.6431',
        ]);

        $ayamKampung = Commodity::create([
            'cooperative_id' => $coopBrebes->id,
            'name' => 'Daging Ayam Kampung Probiotik Segar',
            'category' => 'Telur',
            'current_capacity' => 850,
            'unit' => 'kg',
            'base_price_b2b' => 46000.00,
            'grade' => 'Grade A Karkas Utuh Higienis',
            'description' => 'Ayam kampung dibesarkan dengan pakan herbal probiotik tanpa antibiotik sintetis. Sangat bergizi tinggi untuk kebutuhan protein hewani program MBG.',
            'image_url' => 'https://images.unsplash.com/photo-1587593810167-a84920ea0781?auto=format&fit=crop&w=800&q=80',
        ]);

        $kedelaiLokal = Commodity::create([
            'cooperative_id' => $coopCianjur->id,
            'name' => 'Kedelai Lokal Unggul Non-GMO Anjasmoro',
            'category' => 'Beras',
            'current_capacity' => 3200,
            'unit' => 'kg',
            'base_price_b2b' => 13500.00,
            'grade' => 'Grade A Biji Bernas Terpilih',
            'description' => 'Kedelai varietas lokal Anjasmoro panen segar. Kaya protein nabati untuk bahan baku tempe dan tahu bergizi tinggi dapur sehat MBG.',
            'image_url' => 'https://images.unsplash.com/photo-1515543237350-b3eea1ec8082?auto=format&fit=crop&w=800&q=80',
        ]);

        $cabaiMerah = Commodity::create([
            'cooperative_id' => $coopLembang->id,
            'name' => 'Cabai Merah Keriting Segar Hortikultura',
            'category' => 'Sayur',
            'current_capacity' => 420,
            'unit' => 'kg',
            'base_price_b2b' => 32000.00,
            'grade' => 'Grade A Merah Merata Pilihan',
            'description' => 'Cabai merah keriting dipetik segar dengan tingkat kematangan optimal dari kebun pegunungan Lembang.',
            'image_url' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=800&q=80',
        ]);

        // Traceability Logs for additional commodities
        TraceabilityLog::create([
            'commodity_id' => $ayamKampung->id,
            'farmer_group_name' => 'Kelompok Ternak Unggas Sehat Brebes',
            'harvest_date' => now()->toDateString(),
            'qr_code_token' => 'TRC-AYAM-BREBES-2026-004',
            'food_safety_score' => 99,
            'lab_certified_by' => 'Laboratorium Kesehatan Masyarakat Veteriner (Kesmavet) Jateng',
            'location_coordinates' => '-6.8790, 109.0511',
        ]);

        TraceabilityLog::create([
            'commodity_id' => $kedelaiLokal->id,
            'farmer_group_name' => 'Kelompok Tani Palawija Makmur Cianjur',
            'harvest_date' => now()->subDays(2)->toDateString(),
            'qr_code_token' => 'TRC-KEDELAI-CIANJUR-2026-005',
            'food_safety_score' => 98,
            'lab_certified_by' => 'Balai Pengawasan & Sertifikasi Benih Pertanian Jabar',
            'location_coordinates' => '-6.8450, 107.1023',
        ]);

        TraceabilityLog::create([
            'commodity_id' => $cabaiMerah->id,
            'farmer_group_name' => 'Gapoktan Sayur Pegunungan Lembang',
            'harvest_date' => now()->subDay()->toDateString(),
            'qr_code_token' => 'TRC-CABAI-LEMBANG-2026-006',
            'food_safety_score' => 97,
            'lab_certified_by' => 'Badan Sertifikasi Pangan Organik Seloliman (LeSOS)',
            'location_coordinates' => '-6.8180, 107.6390',
        ]);
    }
}
