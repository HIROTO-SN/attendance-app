<?php

namespace App\Filament\Resources\HolidayCalendars\Pages;

use App\Filament\Resources\HolidayCalendars\HolidayCalendarResource;
use App\Models\HolidayCalendar;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateHolidayCalendar extends CreateRecord {
    protected static string $resource = HolidayCalendarResource::class;

    protected function handleRecordCreation( array $data ): Model {
        foreach ( $data[ 'holidays' ] as $holiday ) {
            HolidayCalendar::updateOrCreate(
                [ 'holiday_date' => $holiday[ 'holiday_date' ] ],
                [ 'description' => $holiday[ 'description' ] ]
            );
        }

        // ダミーで1件返す（Filament仕様）
        return HolidayCalendar::first();
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl( 'index' );
    }
}