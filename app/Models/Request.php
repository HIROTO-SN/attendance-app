<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model {
    protected $fillable = [
        'user_id',
        'request_type_id',
        'target_date',
        'payload',
        'status',
    ];

    protected $casts = [
        'payload' => 'array',
        'target_date' => 'date',
    ];
}