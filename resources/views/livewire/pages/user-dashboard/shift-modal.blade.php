<x-common.modal.base :show="$show">

    <x-slot name="header">
        <h2 class="text-xl font-bold">
            Edit Shift — {{ $date }}
        </h2>
    </x-slot>

    <div class="space-y-4">
        <div>
            <label class="block mb-1 text-sm text-gray-600">Clock In</label>
            <input type="time" wire:model="start_time" class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-600">Clock Out</label>
            <input type="time" wire:model="end_time" class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-600">Break (minutes)</label>
            <input type="number" wire:model="break_minutes" class="w-full border rounded-lg px-3 py-2">
        </div>
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