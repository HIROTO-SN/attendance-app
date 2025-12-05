<?php

namespace App\Filament\Resources\AttendanceRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('work_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('clock_in')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('clock_out')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('break_start')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('break_end')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('break_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('working_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overtime_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status'),
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
