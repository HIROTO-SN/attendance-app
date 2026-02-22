<?php

namespace App\Livewire\UserDashboard;

use App\Models\AttendanceRecord;
use App\Models\HolidayCalendar;
use App\Models\Shift;
use Livewire\Component;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class UserDashboard extends Component {
    public $year;
    public $month;

     protected $listeners = [
        'shift-updated' => 'shiftUpdated',
    ];

    public function yearChanged()
    {
        $this->month = now()->month;
        $this->dispatch( 'processing-completed' );
    }

    public function mount() {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function prevMonth() {
        $date = Carbon::create( $this->year, $this->month )->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->dispatch( 'processing-completed' );
    }

    public function nextMonth() {
        $date = Carbon::create( $this->year, $this->month )->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->dispatch( 'processing-completed' );
    }

    public function getDaysInMonthProperty() {
        return Carbon::create( $this->year, $this->month )->daysInMonth;
    }

    public function getMonthNameProperty() {
        return Carbon::create( $this->year, $this->month )->format( 'F' );
    }

    public function getMonthlyAttendancesProperty()
    {
        return AttendanceRecord::where('user_id', auth()->id())
            ->whereYear('clock_in', $this->year)
            ->whereMonth('clock_out', $this->month)
            ->get()
            ->keyBy(function ($attendance) {
                return $attendance->work_date->format('Y-m-d');
            });
    }

    public function getMonthlyShiftsProperty()
    {
        return Shift::with('workType')
            ->where('user_id', auth()->id())
            ->get();
    }

    public function resolveShiftForDate(Carbon $date)
    {
        return $this->monthlyShifts
            ->last(fn ($shift) =>
                $shift->effective_from <= $date &&
                (
                    is_null($shift->effective_to) ||
                    $shift->effective_to >= $date
                )
            );
    }

    public function getMonthlyRowsProperty()
    {
        $rows = [];

        $daysInMonth = Carbon::create($this->year, $this->month)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($this->year, $this->month, $day);
            $dateKey = $date->format('Y-m-d');

            $attendance = $this->monthlyAttendances[$dateKey] ?? null;
            $shift = $this->resolveShiftForDate($date);

            $rows[$dateKey] = [
                'date' => $date,
                'attendance' => $attendance,
                'shift' => $shift,
            ];
        }

        return $rows;
    }

    public function getCurrentMonthShiftProperty()
    {
        return \App\Models\Shift::with('workType')
            ->where('user_id', auth()->id())
            ->latest('id') // or created_at
            ->first();
    }

    public function openEditModal(string $date)
    {
        $shift = $this->monthlyAttendances[$date] ?? null;

        $this->dispatch('openShiftModal', [
            'shiftId' => $shift?->id,
            'date' => $date,
            'start_time' => $shift?->clock_in?->format('H:i') ?? '',
            'end_time' => $shift?->clock_out?->format('H:i') ?? '',
            'break_minutes' => $shift?->break_minutes ?? 60,
        ])->to('user-dashboard.shift-modal');
    }

    public function goToToday()
    {
        $today = Carbon::today();

        $this->year = $today->year;
        $this->month = $today->month;
        $this->dispatch( 'processing-completed' );
    }

    public function shiftUpdated () {
        LivewireAlert::title( 'Success' )
        ->text( 'Your shift has been registered successfully' )
        ->position( 'center' )
        ->success()
        ->show();
    }

    public function getHolidayDatesProperty(): array
    {
        return HolidayCalendar::whereBetween(
            'holiday_date',
            [$this->monthStart(), $this->monthEnd()]
        )->pluck('holiday_date')
        ->map(fn ($d) => $d->toDateString())
        ->toArray();
    }

    protected function monthStart(): Carbon
    {
        return Carbon::create($this->year, $this->month, 1)->startOfMonth();
    }

    protected function monthEnd(): Carbon
    {
        return Carbon::create($this->year, $this->month, 1)->endOfMonth();
    }

    public function render() {
        return view( 'livewire.pages.user-dashboard.dashboard' )
        ->layout( 'layouts.app' );
    }
}