<?php

namespace App\Filament\Resources\WorkTypes\Pages;

use App\Filament\Resources\WorkTypes\WorkTypeResource;
use App\Models\WorkType;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkType extends EditRecord {
    protected static string $resource = WorkTypeResource::class;

    protected function getHeaderActions(): array {
        return [
            ViewAction::make(),
            DeleteAction::make()
            ->visible( fn ( WorkType $record ) => $record->shifts()->count() === 0 ),
        ];
    }
}