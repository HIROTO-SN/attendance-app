<div x-show="loading" x-transition.opacity
  class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center" style="display: none;">
  <div class="bg-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
    <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
      viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
    </svg>
    <span class="text-gray-700 font-medium">Loading...</span>
  </div>
</div>