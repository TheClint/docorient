<div class="mt-6 flex justify-center">
    <div class="w-full max-w-md space-y-4">
        @if (session()->has('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded shadow text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded shadow text-center font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('info'))
            <div class="bg-blue-100 text-blue-800 p-4 rounded shadow text-center font-semibold">
                {{ session('info') }}
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow text-center font-semibold">
                {{ session('warning') }}
            </div>
        @endif
    </div>
</div>
