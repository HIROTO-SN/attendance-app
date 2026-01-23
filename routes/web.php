<?php

use App\Livewire\Request\RequestCreate;
use App\Livewire\Request\RequestIndex;
use App\Livewire\UserDashboard\UserDashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// -------------------------------------------------------
// ログイン後（認証済みユーザー）
// -------------------------------------------------------
Route::middleware([
    'auth',
    'verified',
])->group(function () {

    // ダッシュボード（ログイン後の最初の画面）
    Route::get('/dashboard', UserDashboard::class)
        ->name('dashboard');

    // 月次勤怠入力
    // Route::get('/attendance/monthly', MonthlyAttendance::class)
    Route::get('/attendance/monthly', UserDashboard::class)
        ->name('attendance.monthly');

    // 申請管理一覧
    // Route::get('/requests', RequestIndex::class)
    Route::get('/requests', RequestIndex::class)
        ->name('requests.index');    
    Route::get('/requests/create', RequestCreate::class)->name('requests.create');

    // 交通費申請
    // Route::get('/transport/expense', TransportExpense::class)
    Route::get('/transport/expense', UserDashboard::class)
        ->name('transport.expense');

    // プロフィール（Jetstream 標準ルート）
    // Route::get('/profile', [ProfileController::class, 'edit'])
    Route::get('/profile', [UserDashboard::class, 'edit'])
        ->name('profile');
});

require __DIR__.'/auth.php';