<?php

namespace App\Filament\Resources\HolidayCalendars\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class HolidayCalendarsTable {
    public static function configure(Table $table): Table
    {
        return $table
            // ✅ 昇順ソート（デフォルト）
            ->defaultSort('holiday_date', 'asc')

            // ✅ 表示件数 30
            ->defaultPaginationPageOption(50)

            ->columns([
                TextColumn::make('holiday_date')
                    ->label('Holiday Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
            ])

            // ✅ 年フィルタ
            ->filters([
                SelectFilter::make('years')
                    ->label('Year')
                    ->multiple()
                    ->options(self::yearOptions())
                    ->default([now()->year])
                    ->query(function (Builder $query, array $state) {
                        $years = $state['value'] ?? [];

                        if (! empty($years)) {
                            $query->whereIn(
                                \DB::raw('YEAR(holiday_date)'),
                                $years
                            );
                        }
                    }),
            ]);
    }
    protected static function yearOptions(): array {
        $currentYear = now()->year;

        return collect( range( $currentYear + 1, $currentYear - 5 ) )
        ->mapWithKeys( fn ( $year ) => [ $year => $year ] )
        ->toArray();
    }
}