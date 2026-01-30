<div class="min-h-screen flex bg-gradient-to-br from-gray-50 to-gray-200 text-gray-900">
    <main class="flex-1 p-10 overflow-y-auto">
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="mb-10 flex justify-between items-end">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 flex items-center gap-3">
                        <span class="w-2 h-10 bg-indigo-600 rounded-full"></span>
                        Requests
                    </h1>
                    <p class="text-gray-500 mt-2 text-lg">View and manage your requests</p>
                </div>

                <a href="{{ route('requests.create') }}" class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold
                          hover:bg-indigo-700 transition shadow">
                    + New Request
                </a>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-2xl rounded-3xl border border-gray-100 p-8
                       transition-all duration-300 hover:shadow-[0_10px_40px_rgba(0,0,0,0.08)]">

                <!-- Table -->
                <div class="overflow-hidden rounded-2xl border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wide">
                            <tr>
                                <th class="px-4 py-3 border-b">Applied</th>
                                <th class="px-4 py-3 border-b">Type</th>
                                <th class="px-4 py-3 border-b">Target Date</th>
                                <th class="px-4 py-3 border-b text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-800 text-sm">
                            @forelse($requests as $request)
                            <tr class="transition hover:bg-indigo-50">
                                <td class="px-4 py-3 border-b">
                                    {{ $request->created_at->format('Y-m-d') }}
                                </td>

                                <td class="px-4 py-3 border-b font-medium">
                                    {{ $request->type }}
                                </td>

                                <td class="px-4 py-3 border-b">
                                    {{ optional($request->target_date)->format('Y-m-d') ?? '—' }}
                                </td>

                                <td class="px-4 py-3 border-b text-center">
                                    @php
                                    $statusClasses = match($request->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-600',
                                    };
                                    @endphp

                                    <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusClasses }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                    No requests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>
</div>