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

                /*
                |--------------------------------------------------------------------------
                | ユーザー
                |--------------------------------------------------------------------------
                */
                TextColumn::make('user.name')
                    ->label('従業員')
                    ->searchable()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | 勤務形態（work_types.name）
                |--------------------------------------------------------------------------
                */
                TextColumn::make('workType.name')
                    ->label('勤務形態')
                    ->searchable()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | 所定労働時間（分 → 時:分）
                |--------------------------------------------------------------------------
                */
                TextColumn::make('daily_work_minutes')
                    ->label('所定労働時間')
                    ->formatStateUsing(function ($state) {
                        if ($state === null) {
                            return '—';
                        }

                        $hours = intdiv($state, 60);
                        $minutes = $state % 60;

                        return sprintf('%d:%02d', $hours, $minutes);
                    })
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | 休憩時間
                |--------------------------------------------------------------------------
                */
                TextColumn::make('break_minutes')
                    ->label('休憩（分）')
                    ->formatStateUsing(fn ($state) => $state ?? '—')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | 所定時間（固定・時短）
                |--------------------------------------------------------------------------
                */
                TextColumn::make('standard_start_time')
                    ->label('所定開始')
                    ->time('H:i')
                    ->formatStateUsing(fn ($state) => $state ?? '—'),

                TextColumn::make('standard_end_time')
                    ->label('所定終了')
                    ->time('H:i')
                    ->formatStateUsing(fn ($state) => $state ?? '—'),

                /*
                |--------------------------------------------------------------------------
                | コアタイム（フレックス）
                |--------------------------------------------------------------------------
                */
                TextColumn::make('core_start_time')
                    ->label('コア開始')
                    ->time('H:i')
                    ->formatStateUsing(fn ($state) => $state ?? '—'),

                TextColumn::make('core_end_time')
                    ->label('コア終了')
                    ->time('H:i')
                    ->formatStateUsing(fn ($state) => $state ?? '—'),

                /*
                |--------------------------------------------------------------------------
                | 作成日時（補助）
                |--------------------------------------------------------------------------
                */
                TextColumn::make('created_at')
                    ->label('作成日')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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