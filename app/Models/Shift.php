<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_date',
        'start_time',
        'end_time',
        'break_minutes',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function user() {
        return $this->belongsTo( User::class );
    }
}