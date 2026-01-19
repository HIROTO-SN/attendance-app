<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkType extends Model {
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function shifts() {
        return $this->hasMany( Shift::class );
    }
}