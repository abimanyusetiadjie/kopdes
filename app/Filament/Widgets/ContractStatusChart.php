<?php

namespace App\Filament\Widgets;

use App\Models\PreOrderContract;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class ContractStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Kontrak';
    protected static ?string $description = 'Komposisi status kontrak pre-order saat ini';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '280px';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        return Cache::remember('filament_contract_status_chart', 30, function () {
            // Eager load ALL then filter locally to save queries
            $contracts = PreOrderContract::all();
            
            $pending = $contracts->where('contract_status', 'Pending')->count();
            $active = $contracts->where('contract_status', 'Active')->count();
            $completed = $contracts->where('contract_status', 'Completed')->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Kontrak',
                        'data' => [$pending, $active, $completed],
                        'backgroundColor' => [
                            'rgba(217, 119, 6, 0.8)',   // amber - pending
                            'rgba(22, 163, 74, 0.8)',    // green - active
                            'rgba(15, 23, 42, 0.8)',     // navy - completed
                        ],
                        'borderColor' => [
                            'rgb(217, 119, 6)',
                            'rgb(22, 163, 74)',
                            'rgb(15, 23, 42)',
                        ],
                        'borderWidth' => 2,
                        'hoverOffset' => 8,
                    ],
                ],
                'labels' => ['Pending', 'Aktif', 'Selesai'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'pie';
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
        ];
    }
}
