<?php

namespace App\Filament\Resources\AttendanceRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('work_date')
                    ->required(),
                DateTimePicker::make('clock_in'),
                DateTimePicker::make('clock_out'),
                DateTimePicker::make('break_start'),
                DateTimePicker::make('break_end'),
                TextInput::make('break_minutes')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('working_minutes')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('overtime_minutes')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options([
            'working' => 'Working',
            'finished' => 'Finished',
            'absent' => 'Absent',
            'holiday' => 'Holiday',
            'late' => 'Late',
            'early_leave' => 'Early leave',
        ])
                    ->default('working')
                    ->required(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('metadata')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
