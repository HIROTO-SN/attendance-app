<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
    * The attributes that are mass assignable.
    *
    * @var list<string>
    */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_code',
        'department_id',
        'position',
        'employment_type',
        'join_date',
        'leave_date',
        'is_admin',
    ];

    protected $casts = [
        'join_date' => 'date',
        'leave_date' => 'date',
        'is_admin' => 'boolean',
    ];

    /**
    * The attributes that should be hidden for serialization.
    *
    * @var list<string>
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* Relationships */

    // 所属部署

    public function department() {
        return $this->belongsTo( Department::class );
    }

    // 勤怠レコード

    public function attendanceRecords() {
        return $this->hasMany( AttendanceRecord::class );
    }

    // 休暇申請

    public function leaveRequests() {
        return $this->hasMany( LeaveRequest::class );
    }

    // 承認した休暇申請

    public function approvedLeaveRequests() {
        return $this->hasMany( LeaveRequest::class, 'approver_id' );
    }

    // シフト

    public function shifts() {
        return $this->hasOne( Shift::class );
    }
}