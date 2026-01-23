<div class="max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">申請作成</h2>

    {{-- 種別 --}}
    <select wire:model="type" class="w-full mb-4 border p-2">
        <option value="">申請種別を選択</option>
        <option value="punch_fix">打刻修正</option>
        <option value="overtime">残業</option>
        <option value="leave">休暇</option>
    </select>

    {{-- 対象日 --}}
    <input type="date" wire:model="target_date" class="w-full mb-4 border p-2">

    {{-- 打刻修正 --}}
    @if($type === 'punch_fix')
    <input type="time" wire:model="start_time" class="w-full mb-2 border p-2" placeholder="出勤">

    <input type="time" wire:model="end_time" class="w-full mb-4 border p-2" placeholder="退勤">
    @endif

    {{-- 理由 --}}
    <textarea wire:model="reason" class="w-full mb-4 border p-2" placeholder="理由"></textarea>

    <button wire:click="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
        申請する
    </button>
</div>