<div>
   {{-- Messages --}}
   @if (session()->has('success'))
        <div class="mt-4 text-green-600 font-semibold">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mt-4 text-red-500 p-2 font-semibold">{{ session('error') }}</div>
    @endif
    @if (session()->has('info'))
        <div class="mt-4 text-blue-500 p-2 font-semibold">{{ session('info') }}</div>
    @endif
    @if (session()->has('warning'))
        <div class="mt-4 text-yellow-600 p-2 font-semibold">{{ session('warning') }}</div>
    @endif
</div>