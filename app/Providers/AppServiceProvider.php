<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;

class AppServiceProvider extends ServiceProvider {
    /**
    * Register any application services.
    */

    public function register(): void {
        FilamentAsset::register( [
            Js::make( 'sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11' ),
        ] );
    }

    /**
    * Bootstrap any application services.
    */

    public function boot(): void {
        //
    }
}