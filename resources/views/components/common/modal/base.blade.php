<div>
  @if($show ?? false)
  <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">

      {{-- Header --}}
      <div class="mb-4">
        {{ $header ?? '' }}
      </div>

      {{-- Body --}}
      <div>
        {{ $slot }}
      </div>

      {{-- Footer --}}
      @isset($footer)
      <div class="mt-6 flex justify-end gap-3">
        {{ $footer }}
      </div>
      @endisset

    </div>
  </div>
  @endif
</div>