<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FarmerSubmissionResource\Pages;
use App\Filament\Resources\FarmerSubmissionResource\RelationManagers;
use App\Models\FarmerSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FarmerSubmissionResource extends Resource
{
    protected static ?string $model = FarmerSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Pengajuan Panen AI';
    protected static ?string $navigationGroup = 'Kemitraan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pengajuan')
                    ->schema([
                        Forms\Components\TextInput::make('farmer_name')->label('Nama Petani')->required(),
                        Forms\Components\TextInput::make('phone_number')->label('No. Telepon'),
                        Forms\Components\TextInput::make('commodity_type')->label('Komoditas')->required(),
                        Forms\Components\TextInput::make('weight_kg')->label('Estimasi Berat (kg)')->numeric()->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Hasil Analisis AI')
                    ->schema([
                        Forms\Components\TextInput::make('ai_grade')->label('Hasil AI (Grade)'),
                        Forms\Components\TextInput::make('ai_confidence')->label('Akurasi AI (%)')->numeric(),
                        Forms\Components\TextInput::make('estimated_price')->label('Estimasi Harga Beli')->numeric(),
                        Forms\Components\Select::make('status')->options([
                            'pending' => 'Menunggu',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        ])->default('pending'),
                        Forms\Components\Placeholder::make('photo_path')
                            ->label('Bukti Foto Panen')
                            ->content(fn ($record) => $record && $record->photo_path ? new \Illuminate\Support\HtmlString('<img src="/storage/'.$record->photo_path.'" style="max-width: 300px; border-radius: 8px; border: 1px solid #ccc;">') : 'Tidak ada foto'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('photo_path')
                    ->label('Foto')
                    ->formatStateUsing(fn ($state) => $state ? '<img src="/storage/'.$state.'" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd;">' : '-')
                    ->html(),
                Tables\Columns\TextColumn::make('farmer_name')->label('Petani')->searchable(),
                Tables\Columns\TextColumn::make('commodity_type')->label('Komoditas')->searchable(),
                Tables\Columns\TextColumn::make('weight_kg')->label('Berat (kg)')->sortable(),
                Tables\Columns\TextColumn::make('ai_grade')
                    ->label('AI Grade')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Grade A' => 'success',
                        'Grade B' => 'warning',
                        'Grade C' => 'danger',
                        'Grade D' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('estimated_price')
                    ->label('Harga (Rp)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (FarmerSubmission $record) => $record->update(['status' => 'approved']))
                    ->visible(fn (FarmerSubmission $record) => $record->status === 'pending'),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (FarmerSubmission $record) => $record->update(['status' => 'rejected']))
                    ->visible(fn (FarmerSubmission $record) => $record->status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFarmerSubmissions::route('/'),
            'create' => Pages\CreateFarmerSubmission::route('/create'),
            'edit' => Pages\EditFarmerSubmission::route('/{record}/edit'),
        ];
    }
}
