<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model {
    protected $fillable = [
        'code',
        'name',
        'description',
        'payload_schema',
        'date_rule',
        'is_active',
    ];

    protected $casts = [
        'payload_schema' => 'array',
        'is_active' => 'boolean',
        'date_rule' => 'array',
    ];

    /**
    * Scope: active request types only
    */

    public function scopeActive( $query ) {
        return $query->where( 'is_active', true );
    }
}