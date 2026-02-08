<?php

namespace App\Filament\Resources\Requests;

use App\Filament\Resources\Requests\Pages\CreateRequest;
use App\Filament\Resources\Requests\Pages\EditRequest;
use App\Filament\Resources\Requests\Pages\ListRequests;
use App\Filament\Resources\Requests\Schemas\RequestForm;
use App\Filament\Resources\Requests\Tables\RequestsTable;
use App\Models\Request;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RequestResource extends Resource {
    protected static ?string $model = Request::class;

    protected static ?string $navigationLabel = '申請一覧';
    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'type';

    public static function form( Schema $schema ): Schema {
        return RequestForm::configure( $schema );
    }

    public static function table( Table $table ): Table {
        return RequestsTable::configure( $table );
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListRequests::route( '/' ),
            'create' => CreateRequest::route( '/create' ),
            'edit' => EditRequest::route( '/{record}/edit' ),
        ];
    }

    public static function getNavigationItems(): array {
        return [
            NavigationItem::make( static::getNavigationLabel() )
            ->group( '申請管理' )
            ->icon( static::getNavigationIcon() )
            ->url( static::getUrl() )
            ->sort( static::$navigationSort ),
        ];
    }
}