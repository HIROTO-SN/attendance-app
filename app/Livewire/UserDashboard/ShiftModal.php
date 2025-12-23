<?php

namespace App\Livewire\UserDashboard;

use App\Models\AttendanceRecord;
use Livewire\Component;

class ShiftModal extends Component {
    protected $listeners = [
        'openShiftModal' => 'open',
    ];

    /** モーダル表示制御 */
    public bool $show = false;

    /** 勤怠データ */
    public ?int $shiftId = null;
    public string $date = '';
    public string $start_time = '';
    public string $end_time = '';
    public int $break_minutes = 60;

    protected $rules = [
        'start_time'     => 'required|date_format:H:i',
        'end_time'       => 'required|date_format:H:i|after:start_time',
        'break_minutes'  => 'nullable|integer|min:0',
    ];

    public function open( array $payload ) {

        $this->reset();

        $this->show = true;

        $this->shiftId = $payload[ 'shiftId' ];
        $this->date = $payload[ 'date' ];
        $this->start_time = $payload[ 'start_time' ];
        $this->end_time = $payload[ 'end_time' ];
        $this->break_minutes = $payload[ 'break_minutes' ];
    }

    public function save() {

        $this->validate();

        AttendanceRecord::updateOrCreate(
            [
                'user_id'   => auth()->id(),
                'work_date' => $this->date,
            ],
            [
                'clock_in'      => $this->date . ' ' . $this->start_time,
                'clock_out'     => $this->date . ' ' . $this->end_time,
                'break_minutes' => $this->break_minutes,
            ]
        );

        $this->dispatch( 'shift-updated' );

        $this->close();
        $this->show = false;
    }

    public function close() {
        $this->show = false;
    }

    public function render() {
        return view( 'livewire.pages.user-dashboard.shift-modal' );
    }
}