<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayCalendar extends Model {
    use HasFactory;

    protected $fillable = [
        'holiday_date',
        'description',
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];
}