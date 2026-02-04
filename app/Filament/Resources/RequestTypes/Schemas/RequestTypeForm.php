<?php

namespace App\Filament\Resources\RequestTypes\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RequestTypeForm {
    public static function configure( Schema $schema ): Schema {
        return $schema->components( [

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
            ->label( '説明' ),

            Toggle::make( 'is_active' )
            ->default( true )
            ->label( '有効' ),

            /**
            * 管理画面では JSON を見せない
            */
            Repeater::make( 'payload_schema.fields' )
            ->label( '申請項目定義' )
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
            ->default( [] )
            ->reorderable()
            ->collapsible()
            ->helperText( 'この申請でユーザーに入力させる項目を定義します' ),
        ] );
    }
}