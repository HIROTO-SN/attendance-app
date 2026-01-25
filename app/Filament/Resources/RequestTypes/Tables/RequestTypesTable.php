<?php

namespace App\Filament\Resources\RequestTypes\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RequestTypesTable {
    public static function configure( Table $table ): Table {
        return $table
        ->columns( [
            TextColumn::make( 'code' )
            ->searchable()
            ->sortable(),

            TextColumn::make( 'name' )
            ->sortable(),

            IconColumn::make( 'is_active' )
            ->boolean(),

            TextColumn::make( 'updated_at' )
            ->dateTime()
            ->sortable(),
        ] )
        ->filters( [
            TernaryFilter::make( 'is_active' )
            ->label( 'Active' ),
        ] )
        ->actions( [
            EditAction::make(),
        ] )
        ->bulkActions( [
            DeleteBulkAction::make(),
        ] );
    }
}