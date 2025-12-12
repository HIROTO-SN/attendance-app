<?php

namespace App\Livewire\UserDashboard;

use App\Models\Shift;
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

    public function getDaysInMonthProperty() {
        return Carbon::create( $this->year, $this->month )->daysInMonth;
    }

    public function getMonthNameProperty() {
        return Carbon::create( $this->year, $this->month )->format( 'F' );
    }

    public function getMonthlyShiftsProperty()
    {
        return Shift::where('user_id', auth()->id())
            ->whereYear('shift_date', $this->year)
            ->whereMonth('shift_date', $this->month)
            ->get()
            ->keyBy(function ($shift) {
                return $shift->shift_date->format('Y-m-d');
            });
    }

    public function render() {
        return view( 'livewire.pages.dashboard.user-dashboard' )
        ->layout( 'layouts.app' );
    }
}