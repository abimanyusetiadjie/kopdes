<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CooperativeResource\Pages;
use App\Models\Cooperative;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CooperativeResource extends Resource
{
    protected static ?string $model = Cooperative::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Koperasi Desa & Komoditas';
    protected static ?string $modelLabel = 'Koperasi Desa';
    protected static ?string $pluralModelLabel = 'Koperasi Desa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Koperasi Desa')
                    ->description('Daftarkan Koperasi Unit Desa (KUD) penyuplai komoditas pangan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Koperasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Koperasi Tani Makmur Berjaya'),
                        Forms\Components\TextInput::make('village_name')
                            ->label('Nama Desa / Wilayah')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Desa Sukamaju, Kab. Cianjur'),
                        Forms\Components\TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp Resmi')
                            ->required()
                            ->tel()
                            ->placeholder('6281234567890'),
                        Forms\Components\TextInput::make('leader_name')
                            ->label('Ketua Koperasi')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Gudang Koperasi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Koperasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('village_name')
                    ->label('Desa / Wilayah')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->copyable()
                    ->copyMessage('Nomor WhatsApp disalin')
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('commodities_count')
                    ->counts('commodities')
                    ->label('Total Komoditas')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCooperatives::route('/'),
            'create' => Pages\CreateCooperative::route('/create'),
            'edit' => Pages\EditCooperative::route('/{record}/edit'),
        ];
    }
}
