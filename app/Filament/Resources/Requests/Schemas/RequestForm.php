<?php

namespace App\Filament\Resources\Requests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RequestForm {
    public static function configure( Schema $schema ): Schema {
        return $schema
        ->components( [
            TextInput::make( 'user_id' )
            ->required()
            ->numeric(),
            TextInput::make( 'request_type_id' )
            ->required(),
            DatePicker::make( 'target_date' ),
            Textarea::make( 'payload' )
            ->formatStateUsing(function ($state, $record) {
                    if (!is_array($state)) return '';

                    $schema = $record->requestType?->payload_schema['fields'] ?? [];

                    $labels = collect($schema)->pluck('label', 'name');

                    return collect($state)
                        ->map(function ($value, $key) use ($labels) {
                            $label = $labels[$key] ?? $key;
                            return "{$label}: {$value}";
                        })
                        ->implode("\n");
                })            
            ->default( null )
            ->columnSpanFull(),
            TextInput::make( 'status' )
            ->required()
            ->default( 'pending' ),
        ] );
    }
}