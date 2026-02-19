<?php

namespace App\Livewire\UserDashboard;

use App\Models\AttendanceRecord;
use App\Models\Shift;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

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

    public bool $overtimeDetected = false;
    public int $overtimeMinutes = 0;

    // 残業申請用
    public bool $showOvertimeForm = false;
    public array $overtimePayload = [];

    protected $rules = [
        'start_time'     => 'required|date_format:H:i',
        'end_time'       => 'required|date_format:H:i|after:start_time',
        'break_minutes'  => 'nullable|integer|min:0',
    ];

    protected $messages = [
        'start_time.required' => '出勤時刻を入力してください。',
        'start_time.date_format' => '出勤時刻の形式が正しくありません。',

        'end_time.required' => '退勤時刻を入力してください。',
        'end_time.after' => '退勤時刻は出勤時刻より後にしてください。',

        'break_minutes.integer' => '休憩時間は数値で入力してください。',
        'break_minutes.min' => '休憩時間は0以上で入力してください。',
    ];

    public function open( array $payload ) {

        $this->reset();

        $this->show = true;
        $this->shiftId = $payload[ 'shiftId' ];
        $this->date = $payload[ 'date' ];
        $this->start_time = $payload[ 'start_time' ];
        $this->end_time = $payload[ 'end_time' ];
        $this->break_minutes = $payload[ 'break_minutes' ];

        $this->dispatch( 'processing-completed' );
    }

    public function save() {
        $this->validate();

        $userId = auth()->id();

        $shift = Shift::where( 'user_id', $userId )
        ->first();

        if ( ! $shift ) {
            $this->addError( 'date', 'シフトが存在しません。' );
            return;
        }

        $clockIn  = Carbon::parse( $this->date . ' ' . $this->start_time );
        $clockOut = Carbon::parse( $this->date . ' ' . $this->end_time );

        $workedMinutes  =
        $clockIn->diffInMinutes( $clockOut )
        - ( int ) $this->break_minutes;

        /** ===  ===  ===  ===  ===  ===  ===  ===  =
        * 残業チェック（保存前）
        * ===  ===  ===  ===  ===  ===  ===  ===  = */
        if ( $workedMinutes  > $shift->daily_work_minutes ) {

            $this->overtimeMinutes =
            $workedMinutes - $shift->daily_work_minutes;

            // 🔔 アラートを出して保存しない
            LivewireAlert::text( '所定労働時間を超えています。残業申請を行いますか？' )
            ->warning()
            ->position( 'center' )
            ->timer( 100000 )
            ->withOptions( [
                'width' => 360,
                'allowOutsideClick' => false,
                'allowEscapeKey' => false,
            ] )
            ->withConfirmButton( '申請する' )
            ->onConfirm( 'applyOverTime', [] )
            ->withCancelButton( 'しない' )
            ->show();

            return;
        }

        /** ===  ===  ===  ===  ===  ===  ===  ===  =
        * 勤怠保存
        * ===  ===  ===  ===  ===  ===  ===  ===  = */
        AttendanceRecord::updateOrCreate(
            [
                'user_id'   => $userId,
                'work_date' => $this->date,
            ],
            [
                'clock_in'      => $clockIn,
                'clock_out'     => $clockOut,
                'break_minutes' => $this->break_minutes,
            ]
        );

        $this->dispatch( 'shift-updated' );
        $this->close();
        $this->show = false;
    }

    public function applyOverTime() {
        $this->overtimeDetected = true;
        $this->showOvertimeForm = true;
    }

    public function close() {
        $this->show = false;
    }

    public function render() {
        return view( 'livewire.pages.user-dashboard.shift-modal' );
    }
}