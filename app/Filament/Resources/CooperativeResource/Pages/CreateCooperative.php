<?php

namespace App\Filament\Resources\CooperativeResource\Pages;

use App\Filament\Resources\CooperativeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCooperative extends CreateRecord
{
    protected static string $resource = CooperativeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
