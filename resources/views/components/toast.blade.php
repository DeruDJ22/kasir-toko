@php
    $type = session('success') ? 'success' : (session('error') ? 'error' : null);
    $message = session('success') ?? session('error');
@endphp

@if ($message)
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition.opacity
        class="fixed bottom-4 left-4 right-4 z-50 sm:left-auto sm:w-96"
        role="status"
    >
        <div class="flex items-start gap-3 rounded-md border px-4 py-3 shadow-lg {{ $type === 'success' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
            <p class="flex-1 text-sm {{ $type === 'success' ? 'text-green-800' : 'text-red-800' }}">{{ $message }}</p>

            <button type="button" @click="show = false" class="text-lg leading-none {{ $type === 'success' ? 'text-green-700 hover:text-green-900' : 'text-red-700 hover:text-red-900' }}" aria-label="Tutup">
                &times;
            </button>
        </div>
    </div>
@endif
