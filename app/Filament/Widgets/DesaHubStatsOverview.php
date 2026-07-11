<?php

namespace App\Filament\Widgets;

use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\PreOrderContract;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class DesaHubStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return Cache::remember('filament_stats_overview', 30, function () {
            $totalCooperatives = Cooperative::count();
            $totalCapacity = Commodity::sum('current_capacity');
            $totalCommodities = Commodity::count();

            $contracts = PreOrderContract::all();
            $totalContractValue = $contracts->sum(function ($contract) {
                return ($contract->volume_requested ?? 0) * ($contract->agreed_price ?? 0);
            });

            // Optimasi N+1 Queries: Gunakan collection dari yang sudah di-get()
            $mbgContractsCount = $contracts->where('buyer_type', 'MBG_Unit')->count();
            $corpContractsCount = $contracts->where('buyer_type', 'Corporate')->count();
            $totalContracts = $contracts->count();
            $activeContracts = $contracts->where('contract_status', 'Active')->count();
            $completedContracts = $contracts->where('contract_status', 'Completed')->count();

            $lowStockCount = Commodity::where('current_capacity', '<', 100)->count();

            return [
                Stat::make('Koperasi Aktif', $totalCooperatives . ' unit')
                    ->description('Terverifikasi dalam sistem')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->chart([2, 3, 3, 3, $totalCooperatives])
                    ->color('success'),

                Stat::make('Total Komoditas', $totalCommodities . ' jenis')
                    ->description($lowStockCount > 0 ? $lowStockCount . ' stok rendah' : 'Semua stok aman')
                    ->descriptionIcon($lowStockCount > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                    ->chart([4, 5, 6, 7, 8, $totalCommodities])
                    ->color($lowStockCount > 0 ? 'warning' : 'success'),

                Stat::make('Total Pasokan', number_format($totalCapacity, 0, ',', '.') . ' kg')
                    ->description('Kapasitas tersedia saat ini')
                    ->descriptionIcon('heroicon-m-scale')
                    ->chart([5000, 6000, 7500, 8000, 9000, (int) $totalCapacity])
                    ->color('primary'),

                Stat::make('Total Kontrak', $totalContracts . ' kontrak')
                    ->description($activeContracts . ' aktif, ' . $completedContracts . ' selesai')
                    ->descriptionIcon('heroicon-m-document-check')
                    ->chart([1, 2, 3, 4, $totalContracts])
                    ->color('info'),

                Stat::make('Nilai Kontrak', 'Rp ' . number_format($totalContractValue, 0, ',', '.'))
                    ->description($mbgContractsCount . ' MBG, ' . $corpContractsCount . ' korporasi')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->chart([500000, 1000000, 3000000, 10000000, (int) $totalContractValue])
                    ->color('warning'),

                Stat::make('Kontrak MBG', $mbgContractsCount . ' kontrak')
                    ->description('Satuan Pelayanan Makan Bergizi')
                    ->descriptionIcon('heroicon-m-heart')
                    ->chart([0, 1, 1, 2, $mbgContractsCount])
                    ->color('danger'),
            ];
        });
    }
}