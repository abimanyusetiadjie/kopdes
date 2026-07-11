<?php

namespace App\Filament\Resources\FarmerSubmissionResource\Pages;

use App\Filament\Resources\FarmerSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFarmerSubmission extends EditRecord
{
    protected static string $resource = FarmerSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
