@php
$shift = $this->currentShift;
$progressPercent = $this->monthlyTargetMinutes > 0
? min(100, intval($this->monthlyWorkedMinutes / $this->monthlyTargetMinutes * 100))
: 0;
@endphp

<div class="mb-6 space-y-4">

    @if($this->hasShiftChangedInMonth)
    <div class="flex items-center gap-3 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3">
        <span class="font-bold">⚠</span>
        <span class="text-sm">勤務形態が今月途中で変更されています</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">

        {{-- Work Type --}}
        <div class="bg-white rounded-2xl shadow border p-5">
            <p class="text-sm text-gray-500">Work Type</p>
            <p class="mt-2 text-xl font-bold text-indigo-700">
                {{ $shift?->workType?->name ?? '—' }}
            </p>
        </div>

        {{-- Standard Hours --}}
        <div class="bg-white rounded-2xl shadow border p-5">
            <p class="text-sm text-gray-500">Standard Hours</p>
            <p class="mt-2 text-xl font-bold">
                @switch($shift?->workType?->code)
                @case('fixed')
                @case('short_time')
                {{ $shift->standard_start_time }} – {{ $shift->standard_end_time }}
                @break
                @case('flex')
                Core {{ $shift->core_start_time }} – {{ $shift->core_end_time }}
                @break
                @default
                —
                @endswitch
            </p>
        </div>

        {{-- Monthly Progress --}}
        <div class="bg-white rounded-2xl shadow border p-5">
            <p class="text-sm text-gray-500">Monthly Progress</p>
            <p class="mt-1 font-bold">
                {{ intdiv($this->monthlyWorkedMinutes, 60) }}h /
                {{ intdiv($this->monthlyTargetMinutes, 60) }}h
            </p>

            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full transition-all"
                    style="width: {{ $progressPercent }}%; background-color:#6366f1">
                </div>
            </div>
        </div>


        {{-- Break Minutes --}}
        <div class="bg-white rounded-2xl shadow border p-5">
            <p class="text-sm text-gray-500">Break</p>
            <p class="mt-2 text-xl font-bold">
                {{ $shift?->break_minutes ? $shift->break_minutes.' min' : '—' }}
            </p>
        </div>

        {{-- Overtime --}}
        <div class="bg-white rounded-2xl shadow border p-5">
            <p class="text-sm text-gray-500">Overtime</p>
            <p class="mt-2 text-xl font-bold text-red-600">
                {{ intdiv($this->monthlyOvertimeMinutes, 60) }}h
                {{ $this->monthlyOvertimeMinutes % 60 }}m
            </p>
        </div>

    </div>
</div>