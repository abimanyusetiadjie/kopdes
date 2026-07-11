<?php

namespace App\Filament\Resources\PreOrderContractResource\Pages;

use App\Filament\Resources\PreOrderContractResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreOrderContracts extends ListRecords
{
    protected static string $resource = PreOrderContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
