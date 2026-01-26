<?php

namespace App\Filament\Resources\RequestTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RequestTypeForm {
    public static function configure( Schema $schema ): Schema {
        return $schema
        ->components( [
            TextInput::make( 'code' )
            ->required()
            ->maxLength( 50 )
            ->unique( ignoreRecord: true )
            ->helperText( 'System identifier (do not change once created)' ),

            TextInput::make( 'name' )
            ->required()
            ->maxLength( 100 ),

            Textarea::make( 'description' )
            ->rows( 3 ),

            Toggle::make( 'is_active' )
            ->default( true ),

            Textarea::make( 'payload_schema' )
            ->label('Payload Schema (JSON)')
            ->rows(12)
            ->formatStateUsing(fn ($state) =>
                is_array($state)
                    ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    : $state
            )
            ->dehydrateStateUsing(fn ($state) =>
                is_string($state) ? json_decode($state, true) : $state
            )
            ->json()
            ->helperText('JSON形式で入力してください'),
        ] );
    }
}