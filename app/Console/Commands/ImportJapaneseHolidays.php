<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\HolidayCalendar;

class ImportJapaneseHolidays extends Command {
    protected $signature = 'holiday:import-jp {year?}';
    protected $description = 'Import Japanese holidays into holiday_calendars table';

    public function handle(): int {
        $year = $this->argument( 'year' ) ?? now()->year;

        $response = Http::get( 'https://holidays-jp.github.io/api/v1/date.json' );

        if ( ! $response->ok() ) {
            $this->error( 'Failed to fetch Japanese holidays.' );
            return self::FAILURE;
        }

        $count = 0;

        foreach ( $response->json() as $date => $name ) {
            if ( ! str_starts_with( $date, ( string ) $year ) ) {
                continue;
            }

            HolidayCalendar::updateOrCreate(
                [ 'holiday_date' => $date ],
                [ 'description' => $name ]
            );

            $count++;
        }

        $this->info( "Imported {$count} holidays for {$year}." );

        return self::SUCCESS;
    }

}