<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
 {
    protected function schedule( Schedule $schedule ): void
    {
        dd( 'KERNEL LOADED' );
        $schedule->command( 'holiday:import-jp' )
        ->monthlyOn( 1, '03:00' );
        // 毎月1日 03:00
    }

    protected function commands(): void
    {
        $this->load( __DIR__.'/Commands' );
    }
}