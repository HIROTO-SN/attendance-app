<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="h-full flex flex-col bg-white border-r shadow-lg">

    <!-- App Title -->
    <div class="p-6 text-2xl font-bold text-gray-700 border-b">
        勤怠管理システム
    </div>

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-1">

        <a href="{{ route('dashboard') }}" wire:navigate
            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
            📊 ダッシュボード
        </a>

        <a href="{{ route('attendance.monthly') }}" wire:navigate
            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
            🗓️ 勤怠入力（月次）
        </a>

        <a href="{{ route('requests.index') }}" wire:navigate
            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
            📝 申請管理
        </a>

        <a href="{{ route('transport.expense') }}" wire:navigate
            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
            🚉 交通費申請
        </a>

        <a href="{{ route('profile') }}" wire:navigate
            class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
            👤 プロフィール
        </a>
    </nav>

    <!-- Logout Section -->
    <div class="p-4 border-t">
        <button wire:click="logout"
            class="w-full text-left px-4 py-2 rounded-lg text-red-500 hover:bg-red-50 transition">
            🔓 ログアウト
        </button>
    </div>
</div>