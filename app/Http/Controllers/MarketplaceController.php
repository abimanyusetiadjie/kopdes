<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\Cooperative;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MarketplaceController extends Controller
{
    /**
     * Display the B2B Village Commodity Catalog with real-time supply capacity.
     */
    public function index(Request $request): Response
    {
        $query = Commodity::with([
            'cooperative:id,name,village_name',
            'traceabilityLogs:id,commodity_id,qr_code_token',
        ])->select([
            'id', 'cooperative_id', 'name', 'category', 'current_capacity',
            'unit', 'base_price_b2b', 'grade', 'image_url',
        ]);

        // Filter by Commodity Category (Beras, Telur, Sayur)
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // Filter by Village Location (village_name)
        if ($request->filled('village') && $request->village !== 'Semua') {
            $query->whereHas('cooperative', function ($q) use ($request) {
                $q->where('village_name', 'like', '%' . $request->village . '%');
            });
        }

        // Search by commodity or cooperative name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('cooperative', function ($cq) use ($search) {
                      $cq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('village_name', 'like', '%' . $search . '%');
                  });
            });
        }

        $commodities = $query->latest()->get();

        // Get unique village locations for filter dropdown
        $villages = Cooperative::distinct('village_name')
            ->pluck('village_name')
            ->filter()
            ->values();

        return Inertia::render('Catalog', [
            'commodities' => $commodities,
            'villages' => $villages,
            'filters' => [
                'category' => $request->input('category', 'Semua'),
                'village' => $request->input('village', 'Semua'),
                'search' => $request->input('search', ''),
            ],
        ]);
    }
}
