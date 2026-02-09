<x-common.modal.base :show="$show">

    <x-slot name="header">
        <h2 class="text-xl font-bold">
            Edit Shift — {{ $date }}
        </h2>
    </x-slot>

    <div class="space-y-4">
        <!-- Clock In -->
        <div>
            <label class="block mb-1 text-sm text-gray-600">Clock In</label>
            <input type="time" wire:model.defer="start_time" class="w-full border rounded-lg px-3 py-2">

            @error('start_time')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Clock Out -->
        <div>
            <label class="block mb-1 text-sm text-gray-600">Clock Out</label>
            <input type="time" wire:model.defer="end_time" class="w-full border rounded-lg px-3 py-2">

            @error('end_time')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Break -->
        <div>
            <label class="block mb-1 text-sm text-gray-600">Break (minutes)</label>
            <input type="number" wire:model.defer="break_minutes" class="w-full border rounded-lg px-3 py-2">

            @error('break_minutes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if($showOvertimeForm)
        <div class="mt-6 border-t pt-4 space-y-4">
            <h3 class="text-lg font-semibold text-yellow-700">
                残業申請（{{ $overtimeMinutes }} 分）
            </h3>

            <div>
                <label class="block text-sm text-gray-600">残業理由</label>
                <textarea wire:model.defer="overtimePayload.reason"
                    class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button wire:click="submitOvertime"
                    class="px-4 py-2 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700">
                    残業申請して保存
                </button>
            </div>
        </div>
        @endif

    </div>

    <x-slot name="footer">
        <button wire:click="close" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
            Cancel
        </button>

        <button wire:click="save" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
            Save
        </button>
    </x-slot>

</x-common.modal.base>

@livewireAlerts