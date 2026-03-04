<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_type_id',
        'daily_work_minutes',
        'break_minutes',
        'standard_start_time',
        'standard_end_time',
        'core_start_time',
        'core_end_time',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }

    public function workType() {
        return $this->belongsTo( WorkType::class );
    }
}