<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreOrderContractResource\Pages;
use App\Models\PreOrderContract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PreOrderContractResource extends Resource
{
    protected static ?string $model = PreOrderContract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Kontrak & Transaksi B2B';
    protected static ?string $modelLabel = 'Kontrak Pre-Order';
    protected static ?string $pluralModelLabel = 'Kontrak Pre-Order';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembeli Institusi')
                    ->description('Satuan Pelayanan Makan Bergizi Gratis (MBG) atau Mitra Korporasi')
                    ->schema([
                        Forms\Components\Select::make('buyer_type')
                            ->label('Tipe Pembeli')
                            ->options([
                                'MBG_Unit' => 'Satuan Pelayanan Makan Bergizi Gratis (MBG)',
                                'Corporate' => 'Mitra Korporasi B2B',
                            ])
                            ->required()
                            ->default('MBG_Unit'),
                        Forms\Components\TextInput::make('buyer_name')
                            ->label('Nama Institusi / Satuan Pelayanan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Satuan Pelayanan MBG Dapur Sehat #012'),
                        Forms\Components\TextInput::make('contract_number')
                            ->label('Nomor Kontrak')
                            ->required()
                            ->default(fn () => 'DSH-MBG-' . strtoupper(uniqid()))
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Rincian Kontrak Komoditas')
                    ->schema([
                        Forms\Components\Select::make('commodity_id')
                            ->relationship('commodity', 'name')
                            ->label('Komoditas Pilihan')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('volume_requested')
                            ->label('Volume Diminta')
                            ->required()
                            ->numeric()
                            ->suffix('Kg / Unit'),
                        Forms\Components\TextInput::make('agreed_price')
                            ->label('Harga Kontrak Terkunci')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\DatePicker::make('delivery_target_date')
                            ->label('Target Pengiriman')
                            ->required(),
                        Forms\Components\Select::make('contract_status')
                            ->label('Status Kontrak')
                            ->options([
                                'Pending' => 'Pending (Menunggu Konfirmasi)',
                                'Active' => 'Active (Terkunci & Diproses)',
                                'Completed' => 'Completed (Selesai Dikirim)',
                            ])
                            ->required()
                            ->default('Active'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contract_number')
                    ->label('Nomor Kontrak')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('buyer_type')
                    ->label('Tipe Pembeli')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'MBG_Unit' => 'Satuan Pelayanan MBG',
                        'Corporate' => 'Mitra Korporasi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'MBG_Unit' => 'success',
                        'Corporate' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('buyer_name')
                    ->label('Nama Institusi / Pembeli')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('commodity.name')
                    ->label('Komoditas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume_requested')
                    ->label('Volume')
                    ->sortable()
                    ->suffix(' Kg')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('agreed_price')
                    ->label('Harga Sepakat')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contract_status')
                    ->label('Status Kontrak')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Active' => 'success',
                        'Completed' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('delivery_target_date')
                    ->label('Target Pengiriman')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('buyer_type')
                    ->label('Filter Tipe Pembeli')
                    ->options([
                        'MBG_Unit' => 'Satuan Pelayanan MBG',
                        'Corporate' => 'Mitra Korporasi',
                    ]),
                Tables\Filters\SelectFilter::make('contract_status')
                    ->label('Filter Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Active' => 'Active',
                        'Completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printInvoice')
                    ->label('Lihat Surat Kontrak')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (PreOrderContract $record): string => route('contract.show', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreOrderContracts::route('/'),
            'create' => Pages\CreatePreOrderContract::route('/create'),
            'edit' => Pages\EditPreOrderContract::route('/{record}/edit'),
        ];
    }
}
