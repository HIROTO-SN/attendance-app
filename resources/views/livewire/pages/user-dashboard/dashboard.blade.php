<div class="min-h-screen flex bg-gradient-to-br from-gray-50 to-gray-200 text-gray-900">
    <!-- Sidebar -->
    {{--
    <livewire:layout.navigation /> --}}

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="w-2 h-10 bg-indigo-600 rounded-full"></span>
                    Attendance Dashboard
                </h1>
                <p class="text-gray-500 mt-2 text-lg">View and manage your monthly attendance</p>
            </div>

            <!-- Attendance Card -->
            <div
                class="bg-white shadow-2xl rounded-3xl border border-gray-100 p-8 transition-all duration-300 hover:shadow-[0_10px_40px_rgba(0,0,0,0.08)]">
                <!-- Month Selector -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <button wire:click="prevMonth"
                            class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">
                            ← Prev
                        </button>

                        <h2 class="text-2xl font-semibold">
                            {{ $this->monthName }} {{ $year }}
                        </h2>

                        <button wire:click="nextMonth"
                            class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">
                            Next →
                        </button>

                        <button wire:click="goToToday"
                            class="ml-4 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition font-semibold">
                            Today
                        </button>
                    </div>

                    <select wire:model.live="year"
                        class="border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach (range(now()->year, now()->year - 5) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-2xl border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wide">
                            <tr>
                                <th class="px-4 py-3 border-b">Date</th>
                                <th class="px-4 py-3 border-b">Day</th>
                                <th class="px-4 py-3 border-b">Clock In</th>
                                <th class="px-4 py-3 border-b">Clock Out</th>
                                <th class="px-4 py-3 border-b">Break</th>
                                <th class="px-4 py-3 border-b">Total Hours</th>
                                <th class="px-4 py-3 border-b">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 text-sm">
                            @foreach (range(1, $this->daysInMonth) as $day)
                            @php
                            $date = \Carbon\Carbon::create($year, $month, $day);
                            $key = $date->format('Y-m-d');
                            $attendance = $this->monthlyAttendances[$key] ?? null;
                            @endphp

                            <tr wire:key="attendance-{{ $year }}-{{ $month }}-{{ $day }}"
                                class="hover:bg-indigo-50 transition cursor-pointer"
                                wire:dblclick="openEditModal('{{ $key }}')">

                                <td class="px-4 py-3 border-b">{{ $key }}</td>
                                <td class="px-4 py-3 border-b">{{ $date->format('D') }}</td>

                                <!-- Clock In -->
                                <td class="px-4 py-3 border-b">
                                    {{ $attendance?->clock_in?->format('H:i') ?? '--' }}
                                </td>

                                <!-- Clock Out -->
                                <td class="px-4 py-3 border-b">
                                    {{ $attendance?->clock_out?->format('H:i') ?? '--' }}
                                </td>

                                <!-- Break -->
                                <td class="px-4 py-3 border-b">
                                    {{ $attendance?->break_minutes ? $attendance->break_minutes.' min' : '--' }}
                                </td>

                                <!-- Total Hours -->
                                <td class="px-4 py-3 border-b">
                                    @if ($attendance && $attendance->clock_in && $attendance->clock_out)
                                    @php
                                    $totalMinutes =
                                    $attendance->clock_in->diffInMinutes($attendance->clock_out)
                                    - $attendance->break_minutes;

                                    $hours = intdiv($totalMinutes, 60);
                                    $minutes = $totalMinutes % 60;
                                    @endphp
                                    {{ $hours }}:{{ $minutes }}
                                    @else
                                    --
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3 border-b">
                                    @if ($attendance)
                                    <span
                                        class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">
                                        Normal
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <livewire:user-dashboard.shift-modal />
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

@livewireAlerts