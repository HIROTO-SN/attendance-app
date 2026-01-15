@php
/** @var \App\Models\Shift|null $shift */
$shift = $currentShift ?? null;
@endphp

<div class="mb-8">
  <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

    {{-- Work Type --}}
    <div class="bg-white rounded-2xl shadow border p-5">
      <p class="text-sm text-gray-500">Work Type</p>
      <p class="mt-2 text-xl font-bold text-indigo-700">
        {{ $shift?->workType?->name ?? '—' }}
      </p>
    </div>

    {{-- Standard Working Time --}}
    <div class="bg-white rounded-2xl shadow border p-5">
      <p class="text-sm text-gray-500">Standard Hours</p>
      <p class="mt-2 text-xl font-bold">
        @if($shift && in_array($shift->workType->code, ['fixed','short_time']))
        {{ $shift->standard_start_time }} – {{ $shift->standard_end_time }}
        @elseif($shift && $shift->workType->code === 'flex')
        Core {{ $shift->core_start_time }} – {{ $shift->core_end_time }}
        @else
        —
        @endif
      </p>
    </div>

    {{-- Daily Work Minutes --}}
    <div class="bg-white rounded-2xl shadow border p-5">
      <p class="text-sm text-gray-500">Daily Work</p>
      <p class="mt-2 text-xl font-bold">
        @if($shift?->daily_work_minutes)
        {{ intdiv($shift->daily_work_minutes, 60) }}h
        {{ $shift->daily_work_minutes % 60 }}m
        @else
        —
        @endif
      </p>
    </div>

    {{-- Break Minutes --}}
    <div class="bg-white rounded-2xl shadow border p-5">
      <p class="text-sm text-gray-500">Break</p>
      <p class="mt-2 text-xl font-bold">
        {{ $shift?->break_minutes ? $shift->break_minutes.' min' : '—' }}
      </p>
    </div>

    {{-- Monthly Overtime --}}
    <div class="bg-white rounded-2xl shadow border p-5">
      <p class="text-sm text-gray-500">Overtime (Month)</p>

      <p class="mt-2 text-xl font-bold text-red-600">
        {{ intdiv($overtimeMinutes, 60) }}h
        {{ $overtimeMinutes % 60 }}m
      </p>
    </div>

  </div>
</div>