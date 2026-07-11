<?php

namespace App\Filament\Widgets;

use App\Models\Commodity;
use App\Models\Cooperative;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class SupplyDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Stok Komoditas';
    protected static ?string $description = 'Perbandingan kapasitas pasokan antar Koperasi Desa';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '280px';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        return Cache::remember('filament_supply_distribution_chart', 30, function () {
            $cooperatives = Cooperative::with('commodities')->get();

            $labels = [];
            $data = [];

            foreach ($cooperatives as $coop) {
                $totalCapacity = $coop->commodities->sum('current_capacity');
                if ($totalCapacity > 0) {
                    $labels[] = $coop->name;
                    $data[] = $totalCapacity;
                }
            }

            $colors = [
                'rgba(15, 23, 42, 0.9)',
                'rgba(30, 41, 59, 0.8)',
                'rgba(51, 65, 85, 0.75)',
                'rgba(71, 85, 105, 0.7)',
                'rgba(100, 116, 139, 0.65)',
                'rgba(148, 163, 184, 0.6)',
            ];

            $borderColors = [
                'rgb(15, 23, 42)',
                'rgb(30, 41, 59)',
                'rgb(51, 65, 85)',
                'rgb(71, 85, 105)',
                'rgb(100, 116, 139)',
                'rgb(148, 163, 184)',
            ];

            return [
                'datasets' => [
                    [
                        'label' => 'Kapasitas (kg)',
                        'data' => $data,
                        'backgroundColor' => array_slice($colors, 0, max(1, count($data))),
                        'borderColor' => array_slice($borderColors, 0, max(1, count($data))),
                        'borderWidth' => 2,
                        'hoverOffset' => 8,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'padding' => 16,
                    ],
                ],
            ],
            'cutout' => '60%',
        ];
    }
}
