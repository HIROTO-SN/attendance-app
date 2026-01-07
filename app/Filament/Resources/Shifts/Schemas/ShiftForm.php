<?php

namespace App\Filament\Resources\Shifts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ShiftForm {
    public static function configure( Schema $schema ): Schema {
        return $schema
        ->components( [
            Select::make( 'user_id' )
            ->required()
            ->searchable()
            ->preload()
            ->relationship( 'user', 'name' ),
            TextInput::make( 'work_type' )
            ->required(),
            TextInput::make( 'daily_work_minutes' )
            ->required()
            ->numeric(),
            TextInput::make( 'break_minutes' )
            ->required()
            ->numeric()
            ->default( 60 ),
            TimePicker::make( 'standard_start_time' )
            ->seconds( false )
            ->required(),
            TimePicker::make( 'standard_end_time' )
            ->seconds( false )
            ->required(),
            TimePicker::make( 'core_start_time' )
            ->seconds( false )
            ->required(),
            TimePicker::make( 'core_end_time' )
            ->seconds( false )
            ->required(),
        ] );
    }
}