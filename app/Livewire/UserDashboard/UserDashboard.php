<?php

namespace App\Livewire\UserDashboard;

use App\Models\Shift;
use Livewire\Component;
use Carbon\Carbon;

class UserDashboard extends Component {
    public $year;
    public $month;

    public function updatedYear()
    {
        $this->month = now()->month;
    }
    
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

    public function openEditModal(string $date)
    {
        $shift = $this->monthlyShifts[$date] ?? null;

        $this->dispatch('openShiftModal', [
            'shiftId' => $shift?->id,
            'date' => $date,
            'start_time' => $shift?->start_time?->format('H:i') ?? '',
            'end_time' => $shift?->end_time?->format('H:i') ?? '',
            'break_minutes' => $shift?->break_minutes ?? 60,
        ])->to('user-dashboard.shift-modal');
    }


    public function render() {
        return view( 'livewire.pages.user-dashboard.dashboard' )
        ->layout( 'layouts.app' );
    }
}