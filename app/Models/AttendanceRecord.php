<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'break_minutes',
        'working_minutes',
        'overtime_minutes',
        'status',
        'note',
        'metadata',
    ];

    protected $casts = [
        'work_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'metadata' => 'array',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    // 実働・残業計算ユーティリティ

    public function calculateTimes( $companyRules ) {
        // 簡易例: 実働分・休憩分・残業を計算してフィールドにセットするロジック
        if ( $this->clock_in && $this->clock_out ) {
            $minutes = $this->clock_out->diffInMinutes( $this->clock_in );
            // 休憩の自動挿入ロジック（例）
            $break = 0;
            if ( $minutes > 8*60 ) $break = 60;
            elseif ( $minutes > 6*60 ) $break = 45;
            $this->break_minutes = $break;
            $this->working_minutes = max( 0, $minutes - $break );
            // 残業は所定労働時間を超えた分（簡易）
            $scheduledMinutes = ( $companyRules[ 'scheduled_minutes' ] ?? 8*60 );
            $this->overtime_minutes = max( 0, $this->working_minutes - $scheduledMinutes );
            $this->save();
        }
    }

}