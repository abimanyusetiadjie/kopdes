<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TraceabilityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — DesaHub (B2B Village Commodity Aggregator & Pre-Order)
|--------------------------------------------------------------------------
|
| Hackathon Nasional Kementerian Koperasi RI
| Architecture: Laravel 11 + Inertia.js (Vue 3 + Tailwind CSS 3) + FilamentPHP 3 + PostgreSQL
|
*/

// Landing Page with National Statistics
Route::get('/', [HomeController::class, 'index'])->name('home');

// 1. Client-facing B2B Village Commodity Catalog
Route::get('/catalog', [MarketplaceController::class, 'index'])->name('catalog');

// 2. Atomic Smart Pre-Order Contract Submission & Stock Reservation
Route::post('/contract/store', [ContractController::class, 'store'])->name('contract.store');

// 3. B2B Smart Contract Invoice & Digital Terms Detail Page
Route::get('/contract/{id}', [ContractController::class, 'show'])->name('contract.show');

// 4. Public QR Food Safety & Origin Traceability Journey Page
Route::get('/trace/{token}', [TraceabilityController::class, 'show'])->name('trace.show');

// 5. Jual Panen Petani (AI Computer Vision Simulation)
Route::get('/jual', function () {
    return inertia('JualPanen');
})->name('jual.index');

Route::post('/jual', function (Illuminate\Http\Request $request) {
    $request->validate([
        'farmer_name' => 'required',
        'commodity_type' => 'required',
        'weight_kg' => 'required|numeric',
        'photo_base64' => 'required'
    ]);

    // Save image physically for Admin Dashboard (bypassing multipart upload bugs)
    $path = 'submissions/' . uniqid() . '.jpg';
    \Illuminate\Support\Facades\Storage::disk('public')->put($path, base64_decode($request->photo_base64));

    // Fast Mock AI Logic (100% offline, instantly generates Grade & Confidence)
    $grades = ['Grade A', 'Grade A', 'Grade B', 'Grade B', 'Grade C', 'Grade D'];
    $grade = $grades[array_rand($grades)];
    $confidence = rand(88, 99);
    
    // Determine Price
    $basePrice = 11000;
    if ($grade === 'Grade A') $basePrice = 14500;
    if ($grade === 'Grade B') $basePrice = 12000;
    if ($grade === 'Grade C') $basePrice = 9000;
    if ($grade === 'Grade D') $basePrice = 7500;
    $estimatedPrice = $basePrice + rand(-300, 300);

    $resi = 'TRC-' . strtoupper(\Illuminate\Support\Str::random(6));

    // SIMULASI DEMO: Dimatikan sementara agar presentasi tidak hang akibat lag jaringan Supabase.
    // Jika internet saat presentasi sangat kencang, blok ini bisa diaktifkan kembali.
    /*
    try {
        \App\Models\FarmerSubmission::create([
            'farmer_name' => $request->farmer_name,
            'phone_number' => $request->phone_number ?? '-',
            'commodity_type' => $request->commodity_type,
            'weight_kg' => $request->weight_kg,
            'photo_path' => $path,
            'ai_grade' => $grade,
            'ai_confidence' => $confidence,
            'estimated_price' => $estimatedPrice,
            'status' => 'pending'
        ]);
    } catch (\Exception $e) {}
    */

    return back()->with('success', [
        'grade' => $grade,
        'confidence' => $confidence,
        'price' => $estimatedPrice,
        'resi' => $resi
    ]);
})->name('jual.store');
