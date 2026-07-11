<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\PreOrderContract;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the DesaHub Landing Page with aggregated national statistics.
     */
    public function index(): Response
    {
        $totalCooperatives = Cooperative::count();
        $totalCapacityKg = (int) Commodity::sum('current_capacity');
        $totalCommodities = Commodity::count();

        $contracts = PreOrderContract::all();
        $totalContractValue = (int) $contracts->sum(function ($contract) {
            return ($contract->volume_requested ?? 0) * ($contract->agreed_price ?? 0);
        });
        $totalMbgContracts = PreOrderContract::where('buyer_type', 'MBG_Unit')->count();

        return Inertia::render('Home', [
            'totalCooperatives' => $totalCooperatives,
            'totalCapacityKg' => $totalCapacityKg,
            'totalContractValue' => $totalContractValue,
            'totalMbgContracts' => $totalMbgContracts,
            'totalCommodities' => $totalCommodities,
        ]);
    }
}
