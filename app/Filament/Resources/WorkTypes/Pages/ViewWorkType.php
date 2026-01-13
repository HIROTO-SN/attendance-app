<?php

namespace App\Filament\Resources\WorkTypes\Pages;

use App\Filament\Resources\WorkTypes\WorkTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkType extends ViewRecord
{
    protected static string $resource = WorkTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
