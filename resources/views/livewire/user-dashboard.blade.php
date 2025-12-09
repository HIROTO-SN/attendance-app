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
                        <button class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">← Prev</button>
                        <h2 class="text-2xl font-semibold">March 2025</h2>
                        <button class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">Next →</button>
                    </div>

                    <select class="border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option>2025</option>
                        <option>2024</option>
                        <option>2023</option>
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
                            @foreach(range(1, 31) as $day)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="px-4 py-3 border-b">2025-03-{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3 border-b">Mon</td>
                                <td class="px-4 py-3 border-b">09:00</td>
                                <td class="px-4 py-3 border-b">18:00</td>
                                <td class="px-4 py-3 border-b">1h</td>
                                <td class="px-4 py-3 border-b">8h</td>
                                <td class="px-4 py-3 border-b">
                                    <span
                                        class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Normal</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>