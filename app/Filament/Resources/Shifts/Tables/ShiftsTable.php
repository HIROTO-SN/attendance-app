<?php

namespace App\Filament\Resources\Shifts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('work_type')
                    ->searchable(),
                TextColumn::make('daily_work_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('break_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('standard_start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('standard_end_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('core_start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('core_end_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
