<?php

namespace App\Filament\Resources\Shifts\Schemas;

use App\Models\WorkType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ユーザー
            Select::make('user_id')
                ->required()
                ->searchable()
                ->preload()
                ->relationship('user', 'name'),

            // 勤務形態
            Select::make('work_type_id')
                ->label('勤務形態')
                ->options(WorkType::query()->pluck('name', 'id'))
                ->relationship('workType', 'name')
                ->required()
                ->reactive(),

            /*
            |--------------------------------------------------------------------------
            | 所定労働時間（manager / variable 以外）
            |--------------------------------------------------------------------------
            */
            TextInput::make('daily_work_minutes')
                ->label('所定労働時間（分）')
                ->numeric()
                ->default(480) // ← 8時間
                ->visible(fn ($get) =>
                    ! in_array(
                        optional(WorkType::find($get('work_type_id')))->code,
                        ['manager', 'variable']
                    )
                )
                ->required(fn ($get) =>
                    ! in_array(
                        optional(WorkType::find($get('work_type_id')))->code,
                        ['manager', 'variable']
                    )
                ),

            /*
            |--------------------------------------------------------------------------
            | 休憩時間（manager 以外）
            |--------------------------------------------------------------------------
            */
            TextInput::make('break_minutes')
                ->label('休憩時間（分）')
                ->numeric()
                ->default(60)
                ->visible(fn ($get) =>
                    optional(WorkType::find($get('work_type_id')))->code !== 'manager'
                )
                ->required(fn ($get) =>
                    optional(WorkType::find($get('work_type_id')))->code !== 'manager'
                ),

            /*
            |--------------------------------------------------------------------------
            | 固定勤務・時短勤務
            |--------------------------------------------------------------------------
            */
            TimePicker::make('standard_start_time')
                ->label('所定開始時刻')
                ->seconds(false)
                ->visible(fn ($get) =>
                    in_array(
                        optional(WorkType::find($get('work_type_id')))->code,
                        ['fixed', 'short_time']
                    )
                )
                ->required(fn ($get) =>
                    in_array(
                        optional(WorkType::find($get('work_type_id')))->code,
                        ['fixed', 'short_time']
                    )
                ),

            TimePicker::make('standard_end_time')
                ->label('所定終了時刻')
                ->seconds(false)
                ->visible(fn ($get) =>
                    in_array(
                        optional(WorkType::find($get('work_type_id')))->code,
                        ['fixed', 'short_time']
                    )
                )
                ->required(fn ($get) =>
                    in_array(
                        optional(WorkType::find($get('work_type_id')))->code,
                        ['fixed', 'short_time']
                    )
                ),

            /*
            |--------------------------------------------------------------------------
            | フレックス（コアあり）
            |--------------------------------------------------------------------------
            */
            TimePicker::make('core_start_time')
                ->label('コア開始時刻')
                ->seconds(false)
                ->visible(fn ($get) =>
                    optional(WorkType::find($get('work_type_id')))->code === 'flex'
                )
                ->required(fn ($get) =>
                    optional(WorkType::find($get('work_type_id')))->code === 'flex'
                ),

            TimePicker::make('core_end_time')
                ->label('コア終了時刻')
                ->seconds(false)
                ->visible(fn ($get) =>
                    optional(WorkType::find($get('work_type_id')))->code === 'flex'
                )
                ->required(fn ($get) =>
                    optional(WorkType::find($get('work_type_id')))->code === 'flex'
                ),
        ]);
    }
}