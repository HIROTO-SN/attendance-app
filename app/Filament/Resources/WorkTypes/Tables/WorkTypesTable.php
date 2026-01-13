<?php

namespace App\Filament\Resources\WorkTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkTypesTable {
    public static function configure( Table $table ): Table {
        return $table
        ->columns( [
            TextColumn::make( 'id' )
            ->sortable(),

            TextColumn::make( 'code' )
            ->label( 'コード' )
            ->searchable()
            ->sortable(),

            TextColumn::make( 'name' )
            ->label( '名称' )
            ->searchable()
            ->sortable(),

            TextColumn::make( 'description' )
            ->label( '説明' )
            ->limit( 50 ),

            TextColumn::make( 'created_at' )
            ->dateTime( 'Y-m-d H:i' )
            ->sortable()
            ->toggleable( isToggledHiddenByDefault: true ),
        ] )
        ->filters( [
            //
        ] )
        ->recordActions( [
            ViewAction::make(),
            EditAction::make(),
        ] )
        ->toolbarActions( [
            BulkActionGroup::make( [
                DeleteBulkAction::make(),
            ] ),
        ] );
    }
}