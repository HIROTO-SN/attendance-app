<?php

namespace App\Filament\Resources\HolidayCalendars\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HolidayCalendarForm {
    public static function configure( Schema $schema ): Schema {
        return $schema->components( [
            Repeater::make( 'holidays' )
            ->label( 'Holidays' )
            ->schema( [
                DatePicker::make( 'holiday_date' )
                ->label( 'Holiday Date' )
                ->required()
                ->native( false ),

                TextInput::make( 'description' )
                ->label( 'Description' )
                ->placeholder( '例：会社創立記念日' )
                ->required()
                ->maxLength( 255 ),
            ] )
            ->defaultItems( 1 )
            ->addActionLabel( 'Add another holiday' )
            ->columns( 2 )
            ->required(),
        ] );
    }
}