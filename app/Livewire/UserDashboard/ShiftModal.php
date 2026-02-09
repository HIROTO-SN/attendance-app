<?php

namespace App\Livewire\UserDashboard;

use App\Models\AttendanceRecord;
use App\Models\Shift;
use Illuminate\Support\Carbon;
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
    }

    public function save() {
        $this->validate();

        $userId = auth()->id();

        /** ===  ===  ===  ===  ===  ===  ===  ===  =
        * Shift / WorkType 取得
        * ===  ===  ===  ===  ===  ===  ===  ===  = */
        $shift = Shift::where( 'user_id', $userId )
        ->with( 'workType' )
        ->first();

        if ( ! $shift ) {
            $this->addError( 'date', 'シフトが存在しません。' );
            return;
        }

        $workTypeId = $shift->work_type_id;

        /** ===  ===  ===  ===  ===  ===  ===  ===  =
        * 打刻時刻生成
        * ===  ===  ===  ===  ===  ===  ===  ===  = */
        $clockIn  = Carbon::parse( $this->date . ' ' . $this->start_time );
        $clockOut = Carbon::parse( $this->date . ' ' . $this->end_time );

        if ( $clockOut->lessThanOrEqualTo( $clockIn ) ) {
            $this->addError( 'end_time', '退勤時刻は出勤時刻より後にしてください。' );
            return;
        }

        /** ===  ===  ===  ===  ===  ===  ===  ===  =
        * ① 開始時刻制限（work_type_id が 2, 3 以外）
        * ===  ===  ===  ===  ===  ===  ===  ===  = */
        if ( ! in_array( $workTypeId, [ 2, 3 ] ) ) {

            // 例：所定開始時刻（Shift にある想定）
            $standardStart = Carbon::parse(
                $this->date . ' ' . $shift->standard_start_time
            );

            // 例：±30分以内のみ許可
            if ( $clockIn->diffInMinutes( $standardStart, false ) < -30 ) {
                $this->addError(
                    'start_time',
                    '出勤時刻が所定開始時刻より早すぎます。'
                );
                return;
            }
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

        /** ===  ===  ===  ===  ===  ===  ===  ===  =
        * ② 残業チェック（work_type_id が 2, 3）
        * ===  ===  ===  ===  ===  ===  ===  ===  = */
        if ( in_array( $workTypeId, [ 2, 3 ] ) ) {

            $workedMinutes =
            $clockIn->diffInMinutes( $clockOut )
            - ( int ) $this->break_minutes;

            if ( $workedMinutes > $shift->daily_work_minutes ) {
                $this->dispatch(
                    'notify',
                    type: 'warning',
                    message: '所定労働時間を超えています。残業申請を提出してください。'
                );
            }
        }

        /** ===  ===  ===  ===  ===  ===  ===  ===  =
        * 後処理
        * ===  ===  ===  ===  ===  ===  ===  ===  = */
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