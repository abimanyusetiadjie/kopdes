<?php

namespace App\Filament\Widgets;

use App\Models\Commodity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class CommodityStockChart extends ChartWidget
{
    protected static ?string $heading = 'Kapasitas Stok per Komoditas';
    protected static ?string $description = 'Level stok saat ini — merah menandakan stok rendah (< 100 kg)';
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '320px';
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        return Cache::remember('filament_commodity_stock_chart', 30, function () {
            $commodities = Commodity::with('cooperative')
                ->orderByDesc('current_capacity')
                ->get();

            $labels = [];
            $data = [];
            $bgColors = [];
            $borderColors = [];

            foreach ($commodities as $commodity) {
                $coopName = $commodity->cooperative?->name ?? 'Unknown';
                $labels[] = $commodity->name . ' (' . $coopName . ')';
                $data[] = $commodity->current_capacity;

                if ($commodity->current_capacity < 100) {
                    // Low stock - red
                    $bgColors[] = 'rgba(220, 38, 38, 0.7)';
                    $borderColors[] = 'rgb(220, 38, 38)';
                } else if ($commodity->current_capacity < 500) {
                    // Medium stock - amber
                    $bgColors[] = 'rgba(217, 119, 6, 0.6)';
                    $borderColors[] = 'rgb(217, 119, 6)';
                } else {
                    // Healthy stock - navy
                    $bgColors[] = 'rgba(15, 23, 42, 0.7)';
                    $borderColors[] = 'rgb(15, 23, 42)';
                }
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Stok (kg)',
                        'data' => $data,
                        'backgroundColor' => $bgColors,
                        'borderColor' => $borderColors,
                        'borderWidth' => 1,
                        'borderRadius' => 4,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
