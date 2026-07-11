<?php

namespace App\Filament\Resources\PreOrderContractResource\Pages;

use App\Filament\Resources\PreOrderContractResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreOrderContract extends EditRecord
{
    protected static string $resource = PreOrderContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
