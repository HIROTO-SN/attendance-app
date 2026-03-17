<?php

namespace App\Filament\Resources\RequestTypes\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RequestTypeForm {
    public static function configure( Schema $schema ): Schema {
        return $schema
        ->columns( 1 )
        ->components( [

            Section::make( '基本情報' )
            ->schema( [
                TextInput::make( 'code' )
                ->required()
                ->maxLength( 50 )
                ->unique( ignoreRecord: true )
                ->helperText( 'システム内部用識別子（英数字・snake_case）' ),

                TextInput::make( 'name' )
                ->required()
                ->maxLength( 100 )
                ->label( '申請種別名' ),

                Textarea::make( 'description' )
                ->rows( 3 )
                ->label( '説明' )
                ->columnSpanFull(),

                Toggle::make( 'is_active' )
                ->default( true )
                ->label( '有効' ),
            ] )
            ->columns( 2 )
            ->columnSpanFull(),

            Section::make( '日付選択ルール' )
            ->schema( [

                Grid::make( 12 )
                ->schema( [

                    Select::make( 'date_rule.source' )
                    ->label( '選択可能日' )
                    ->options( [
                        'worked_only' => '打刻申請済みのみ',
                        'non_worked_only' => '打刻申請済み以外',
                        'any' => '打刻制限なし',
                    ] )
                    ->default( 'any' )
                    ->required()
                    ->columnSpan( 3 )
                    ->selectablePlaceholder( false ),

                    CheckboxList::make( 'date_rule.weekdays' )
                    ->label( '曜日' )
                    ->options( [
                        'mon' => '月曜',
                        'tue' => '火曜',
                        'wed' => '水曜',
                        'thu' => '木曜',
                        'fri' => '金曜',
                        'sat' => '土曜',
                        'sun' => '日曜',
                    ] )
                    ->columns( 7 )
                    ->columnSpan( 7 )
                    ->helperText( '未選択の場合は全てが選択可能' ),

                    Toggle::make( 'date_rule.allow_holiday' )
                    ->label( '祝日も選択可能' )
                    ->default( true )
                    ->columnSpan( 2 ),

                ] ),

                Grid::make( 12 )
                ->schema( [

                    TextInput::make('date_rule.before.value')
                    ->numeric()
                    ->minValue(0)
                    ->step(1)
                    ->extraInputAttributes([
                        'min' => 0,
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 0 || $state === '0') {
                            $set('date_rule.before.value', null);
                        }
                    })
                    ->label('過去範囲')
                    ->columnSpan(3)
                    ->helperText('未入力の場合は今日を基準に当月内の当日以前のみ選択可能'),

                    Select::make( 'date_rule.before.unit' )
                    ->label( '単位' )
                    ->options( [
                        'day' => '日',
                        'week' => '週',
                        'month' => '月',
                    ] )
                    ->default( 'week' )
                    ->columnSpan( 3 ),

                    TextInput::make( 'date_rule.after.value' )
                    ->numeric()
                    ->minValue(0)
                    ->step(1)
                    ->extraInputAttributes([
                        'min' => 0,
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 0 || $state === '0') {
                            $set('date_rule.after.value', null);
                        }
                    })
                    ->label( '未来範囲' )
                    ->columnSpan( 3 )
                    ->helperText( '未入力の場合は今日を基準に当月内の当日以後のみ選択可能' ),

                    Select::make( 'date_rule.after.unit' )
                    ->label( '単位' )
                    ->options( [
                        'day' => '日',
                        'week' => '週',
                        'month' => '月',
                    ] )
                    ->default( 'week' )
                    ->columnSpan( 3 ),

                ] ),

            ] )
            ->columnSpanFull()
            ->collapsible(),

            Section::make( '申請項目定義' )
            ->schema( [
                Repeater::make( 'payload_schema.fields' )
                ->label( '申請項目' )
                ->schema( [
                    TextInput::make( 'name' )
                    ->required()
                    ->helperText( 'システム用キー（英数字・snake_case）' ),

                    Select::make( 'type' )
                    ->required()
                    ->options( [
                        'time' => '時刻',
                        'date' => '日付',
                        'text' => 'テキスト',
                        'textarea' => 'テキストエリア',
                        'boolean' => 'チェックボックス',
                    ] )
                    ->label( '入力形式' ),

                    TextInput::make( 'label' )
                    ->required()
                    ->label( '表示ラベル' ),

                    Toggle::make( 'required' )
                    ->label( '必須' ),
                ] )
                ->columns( 4 )
                ->default( [] )
                ->reorderable()
                ->collapsible()
                ->helperText( 'この申請でユーザーに入力させる項目を定義します' ),
            ] )
            ->columnSpanFull(),

        ] );
    }
}