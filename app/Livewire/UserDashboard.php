<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class UserDashboard extends Component {

    public $year;
    public $month;

    public function mount() {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function prevMonth() {
        $date = Carbon::create( $this->year, $this->month )->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function nextMonth() {
        $date = Carbon::create( $this->year, $this->month )->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function render() {
        return view( 'livewire.user-dashboard' )->layout( 'layouts.app' );
    }

}