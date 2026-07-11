<?php

namespace App\Filament\Resources\PreOrderContractResource\Pages;

use App\Filament\Resources\PreOrderContractResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePreOrderContract extends CreateRecord
{
    protected static string $resource = PreOrderContractResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
