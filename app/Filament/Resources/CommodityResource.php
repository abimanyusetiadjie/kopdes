<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommodityResource\Pages;
use App\Models\Commodity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommodityResource extends Resource
{
    protected static ?string $model = Commodity::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Koperasi Desa & Komoditas';
    protected static ?string $modelLabel = 'Komoditas Desa';
    protected static ?string $pluralModelLabel = 'Komoditas Desa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Komoditas Pangan')
                    ->description('Pilih Koperasi penyuplai dan kategori komoditas')
                    ->schema([
                        Forms\Components\Select::make('cooperative_id')
                            ->relationship('cooperative', 'name')
                            ->label('Koperasi Unit Desa')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Komoditas')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Beras Organik Pandan Wangi Grade A'),
                        Forms\Components\Select::make('category')
                            ->label('Kategori Pangan')
                            ->options([
                                'Beras' => 'Beras & Karbohidrat',
                                'Telur' => 'Telur & Unggas',
                                'Sayur' => 'Sayuran Segar & Hortikultura',
                            ])
                            ->required()
                            ->default('Beras'),
                        Forms\Components\TextInput::make('grade')
                            ->label('Grade / Kualitas')
                            ->default('Grade A Premium')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Kapasitas Pasokan & Harga B2B')
                    ->description('Kelola pasokan real-time dan harga kontrak dasar untuk Satuan MBG & Korporat')
                    ->schema([
                        Forms\Components\TextInput::make('current_capacity')
                            ->label('Kapasitas Pasokan Tersedia (current_capacity)')
                            ->required()
                            ->numeric()
                            ->helperText('Peringatan otomatis aktif jika pasokan di bawah 100 kg.')
                            ->suffix('Kg / Unit'),
                        Forms\Components\TextInput::make('unit')
                            ->label('Satuan')
                            ->required()
                            ->default('kg')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('base_price_b2b')
                            ->label('Harga Dasar B2B (base_price_b2b)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->helperText('Harga khusus kontrak Satuan MBG & Korporat per satuan.'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Deskripsi & Media')
                    ->schema([
                        Forms\Components\Placeholder::make('preview')
                            ->label('Preview Foto')
                            ->content(fn ($record) => $record && $record->image_url ? new \Illuminate\Support\HtmlString('<img src="'.$record->image_url.'" style="max-width: 250px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover;">') : 'Belum ada foto / Preview'),
                        Forms\Components\TextInput::make('image_url')
                            ->label('URL Foto Komoditas (Gunakan URL Internet atau path lokal)')
                            ->placeholder('https://... atau /images/beras.jpg'),
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan & Spesifikasi Panen')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('image_url')
                    ->label('Foto')
                    ->formatStateUsing(fn ($state) => $state ? '<img src="'.$state.'" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd;">' : '-')
                    ->html(),
                Tables\Columns\TextColumn::make('cooperative.name')
                    ->label('Koperasi Desa')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Komoditas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Beras' => 'warning',
                        'Telur' => 'success',
                        'Sayur' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('current_capacity')
                    ->label('Stok Kapasitas')
                    ->sortable()
                    ->suffix(fn (Commodity $record): string => ' ' . $record->unit)
                    ->badge()
                    ->color(fn (Commodity $record): string => $record->current_capacity < 100 ? 'danger' : 'success')
                    ->description(fn (Commodity $record): ?string => $record->current_capacity < 100 ? 'KRITIS < 100 KG' : 'Pasokan aman'),
                Tables\Columns\TextColumn::make('base_price_b2b')
                    ->label('Harga B2B')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Update Terakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Filter Kategori')
                    ->options([
                        'Beras' => 'Beras',
                        'Telur' => 'Telur',
                        'Sayur' => 'Sayur',
                    ]),
                Tables\Filters\Filter::make('low_capacity')
                    ->label('Pasokan Rendah (< 100 kg)')
                    ->query(fn ($query) => $query->where('current_capacity', '<', 100)),
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
            'index' => Pages\ListCommodities::route('/'),
            'create' => Pages\CreateCommodity::route('/create'),
            'edit' => Pages\EditCommodity::route('/{record}/edit'),
        ];
    }
}
