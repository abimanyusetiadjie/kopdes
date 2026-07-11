<?php

namespace App\Filament\Widgets;

use App\Models\PreOrderContract;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ContractValueChart extends ChartWidget
{
    protected static ?string $heading = 'Nilai Kontrak per Kategori';
    protected static ?string $description = 'Total nilai kontrak berdasarkan kategori komoditas';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '280px';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        return Cache::remember('filament_contract_value_chart', 30, function () {
            $contracts = PreOrderContract::with('commodity')->get();

            $categories = ['Beras', 'Telur', 'Sayur'];
            $values = [];
            $volumes = [];

            foreach ($categories as $category) {
                $filtered = $contracts->filter(function ($c) use ($category) {
                    return $c->commodity && $c->commodity->category === $category;
                });

                $values[] = $filtered->sum(function ($c) {
                    return ($c->volume_requested ?? 0) * ($c->agreed_price ?? 0);
                });

                $volumes[] = $filtered->sum('volume_requested');
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Nilai Kontrak (Rp)',
                        'data' => $values,
                        'backgroundColor' => [
                            'rgba(15, 23, 42, 0.85)',
                            'rgba(15, 23, 42, 0.6)',
                            'rgba(15, 23, 42, 0.4)',
                        ],
                        'borderColor' => [
                            'rgb(15, 23, 42)',
                            'rgb(15, 23, 42)',
                            'rgb(15, 23, 42)',
                        ],
                        'borderWidth' => 1,
                        'borderRadius' => 6,
                    ],
                    [
                        'label' => 'Volume (kg)',
                        'data' => $volumes,
                        'backgroundColor' => [
                            'rgba(200, 16, 46, 0.7)',
                            'rgba(200, 16, 46, 0.5)',
                            'rgba(200, 16, 46, 0.3)',
                        ],
                        'borderColor' => [
                            'rgb(200, 16, 46)',
                            'rgb(200, 16, 46)',
                            'rgb(200, 16, 46)',
                        ],
                        'borderWidth' => 1,
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => ['Beras & Karbo', 'Telur & Unggas', 'Sayur Segar'],
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
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'rectRounded',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
