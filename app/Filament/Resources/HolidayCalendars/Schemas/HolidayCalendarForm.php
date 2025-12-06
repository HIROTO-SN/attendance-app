<?php

namespace App\Filament\Resources\HolidayCalendars\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HolidayCalendarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('holiday_date')
                    ->required(),
                TextInput::make('description')
                    ->default(null),
            ]);
    }
}
