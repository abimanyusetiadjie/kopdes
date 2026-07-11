<?php

namespace App\Filament\Widgets;

use App\Models\PreOrderContract;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestContractsTable extends BaseWidget
{
    protected static ?string $heading = 'Kontrak Terbaru';
    protected static ?int $sort = 6;
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PreOrderContract::with('commodity.cooperative')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('No. Kontrak')
                    ->weight('bold')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('buyer_name')
                    ->label('Pembeli')
                    ->limit(30),
                Tables\Columns\TextColumn::make('buyer_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'MBG_Unit' => 'MBG',
                        'Corporate' => 'Korporasi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'MBG_Unit' => 'success',
                        'Corporate' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('commodity.name')
                    ->label('Komoditas'),
                Tables\Columns\TextColumn::make('volume_requested')
                    ->label('Volume')
                    ->suffix(' kg')
                    ->numeric(),
                Tables\Columns\TextColumn::make('agreed_price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id_ID'),
                Tables\Columns\TextColumn::make('contract_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Active' => 'success',
                        'Completed' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
