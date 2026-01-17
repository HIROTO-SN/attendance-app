<?php

namespace App\Filament\Resources\HolidayCalendars\Pages;

use App\Filament\Resources\HolidayCalendars\HolidayCalendarResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;

class ListHolidayCalendars extends ListRecords
{
    protected static string $resource = HolidayCalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add Holiday')
                ->icon('heroicon-o-plus-circle')
                ->color('success'),
            Action::make('importJapaneseHolidays')
                ->label('🇯🇵 日本の祝日を取得')
                ->icon('heroicon-o-calendar-days')
                ->color('primary')

                // ✅ モーダルフォーム
                ->form([
                    Select::make('year')
                        ->label('取得する年')
                        ->options(
                            collect(range(now()->year + 1, now()->year - 5))
                                ->mapWithKeys(fn ($y) => [$y => $y])
                                ->toArray()
                        )
                        ->default(now()->year)
                        ->required(),
                ])

                ->requiresConfirmation()
                ->modalHeading('日本の祝日を取得')
                ->modalDescription('選択した年の日本の祝日を取得します。既存データは更新されます。')

                ->action(function (array $data) {
                    $year = $data['year'];

                    Artisan::call('holiday:import-jp', [
                        'year' => $year,
                    ]);

                    $output = Artisan::output();

                    Notification::make()
                        ->title('祝日を取得しました')
                        ->body($output)
                        ->success()
                        ->send();
                }),
        ];
    }
}