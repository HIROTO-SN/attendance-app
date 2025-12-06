<?php

namespace App\Filament\Resources\HolidayCalendars;

use App\Filament\Resources\HolidayCalendars\Pages\CreateHolidayCalendar;
use App\Filament\Resources\HolidayCalendars\Pages\EditHolidayCalendar;
use App\Filament\Resources\HolidayCalendars\Pages\ListHolidayCalendars;
use App\Filament\Resources\HolidayCalendars\Schemas\HolidayCalendarForm;
use App\Filament\Resources\HolidayCalendars\Tables\HolidayCalendarsTable;
use App\Models\HolidayCalendar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HolidayCalendarResource extends Resource
{
    protected static ?string $model = HolidayCalendar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'holiday_date';

    public static function form(Schema $schema): Schema
    {
        return HolidayCalendarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HolidayCalendarsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHolidayCalendars::route('/'),
            'create' => CreateHolidayCalendar::route('/create'),
            'edit' => EditHolidayCalendar::route('/{record}/edit'),
        ];
    }
}
