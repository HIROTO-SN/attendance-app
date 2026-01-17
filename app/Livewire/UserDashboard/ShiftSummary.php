<?php

namespace App\Livewire\UserDashboard;

use App\Models\AttendanceRecord;
use App\Models\HolidayCalendar;
use App\Models\Shift;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ShiftSummary extends Component {
    public int $year;
    public int $month;

    public function mount(int $year, int $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /* =========================
       基本日付
       ========================= */

    protected function monthStart(): Carbon
    {
        return Carbon::create($this->year, $this->month, 1)->startOfMonth();
    }

    protected function monthEnd(): Carbon
    {
        return Carbon::create($this->year, $this->month, 1)->endOfMonth();
    }

    /* =========================
       Shift（現行）
       ========================= */

    public function getCurrentShiftProperty()
    {
        return Shift::with('workType')
            ->where('user_id', auth()->id())
            ->first();
    }

    /* =========================
       Attendance（今月）
       ========================= */

    protected function monthlyAttendances()
    {
        return AttendanceRecord::where('user_id', auth()->id())
            ->whereBetween('work_date', [$this->monthStart(), $this->monthEnd()])
            ->get();
    }

    /* =========================
       集計値
       ========================= */

    public function getMonthlyWorkedMinutesProperty(): int
    {
        return $this->monthlyAttendances()->sum(function ($a) {
            if (! $a->clock_in || ! $a->clock_out) {
                return 0;
            }

            return
                $a->clock_in->diffInMinutes($a->clock_out)
                - ($a->break_minutes ?? 0);
        });
    }

    public function getMonthlyTargetMinutesProperty(): int
    {
        $shift = $this->currentShift;

        if (! $shift?->daily_work_minutes) {
            return 0;
        }

        $start = Carbon::create($this->year, $this->month, 1);
        $end   = $start->copy()->endOfMonth();

        // 祝日一覧
        $holidays = HolidayCalendar::whereBetween(
            'holiday_date',
            [$start, $end]
        )->pluck('holiday_date')->map(fn ($d) => $d->toDateString())->toArray();

        $workDays = 0;

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            // 土日除外
            if ($date->isWeekend()) {
                continue;
            }

            // 祝日除外
            if (in_array($date->toDateString(), $holidays)) {
                continue;
            }

            $workDays++;
        }
        return $workDays * $shift->daily_work_minutes;
    }

    public function getMonthlyOvertimeMinutesProperty(): int
    {
        if (! $this->currentShift?->daily_work_minutes) {
            return 0;
        }

        $daily = $this->currentShift->daily_work_minutes;

        return $this->monthlyAttendances()->sum(function ($a) use ($daily) {
            if (! $a->clock_in || ! $a->clock_out) {
                return 0;
            }

            $worked =
                $a->clock_in->diffInMinutes($a->clock_out)
                - ($a->break_minutes ?? 0);

            return max(0, $worked - $daily);
        });
    }

    public function getHasShiftChangedInMonthProperty(): bool
    {
        return Shift::where('user_id', auth()->id())->count() > 1;
    }

    public function render() {
        return view( 'livewire.pages.user-dashboard.shift-summary' );
    }
}