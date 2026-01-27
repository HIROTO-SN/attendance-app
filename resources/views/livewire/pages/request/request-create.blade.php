<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-200 text-gray-900">
    <main class="p-10">
        <div class="max-w-3xl mx-auto">

            <!-- Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold tracking-tight flex items-center gap-3">
                    <span class="w-2 h-10 bg-indigo-600 rounded-full"></span>
                    Create Request
                </h1>
                <p class="text-gray-500 mt-2 text-lg">
                    Submit a new attendance-related request
                </p>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-2xl rounded-3xl border border-gray-100 p-8
                        transition-all duration-300 hover:shadow-[0_10px_40px_rgba(0,0,0,0.08)]">

                <!-- Request Type -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Request Type
                    </label>
                    <select wire:model.live="requestTypeId" class="w-full rounded-xl border-gray-300 shadow-sm
                               focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select request type</option>
                        @foreach ($this->requestTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Target Date -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Target Date
                    </label>
                    <input type="date" wire:model.live="target_date" class="w-full rounded-xl border-gray-300 shadow-sm
                               focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Dynamic Payload Fields -->
                @if($this->selectedType?->payload_schema)
                <div class="space-y-6">
                    @foreach ($this->selectedType->payload_schema['fields'] as $field)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $field['label'] }}
                            @if(!empty($field['required']))
                            <span class="text-red-500 ml-1">*</span>
                            @endif
                        </label>

                        @switch($field['type'])

                        @case('time')
                        <input type="time" wire:model.live="payload.{{ $field['name'] }}" class="w-full rounded-xl border-gray-300 shadow-sm
                                                   focus:ring-indigo-500 focus:border-indigo-500">
                        @break

                        @case('date')
                        <input type="date" wire:model.live="payload.{{ $field['name'] }}" class="w-full rounded-xl border-gray-300 shadow-sm
                                                   focus:ring-indigo-500 focus:border-indigo-500">
                        @break

                        @case('text')
                        <input type="text" wire:model.live="payload.{{ $field['name'] }}" class="w-full rounded-xl border-gray-300 shadow-sm
                                                   focus:ring-indigo-500 focus:border-indigo-500">
                        @break

                        @case('textarea')
                        <textarea rows="4" wire:model.live="payload.{{ $field['name'] }}" class="w-full rounded-xl border-gray-300 shadow-sm
                                                   focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        @break

                        @case('boolean')
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model.live="payload.{{ $field['name'] }}"
                                class="rounded border-gray-300 text-indigo-600">
                            <span class="text-gray-700 text-sm">
                                {{ $field['label'] }}
                            </span>
                        </div>
                        @break

                        @endswitch
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-end gap-4 mt-10">
                    <a href="{{ route('requests.index') }}"
                        class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition font-semibold">
                        Cancel
                    </a>

                    <button wire:click="submit" class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold
                               hover:bg-indigo-700 transition shadow">
                        Submit Request
                    </button>
                </div>

            </div>
        </div>
    </main>
</div>