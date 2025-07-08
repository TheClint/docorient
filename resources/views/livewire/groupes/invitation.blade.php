<div class="bg-white p-6 rounded shadow max-w-md">
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="invite" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email à inviter</label>
            <input type="email" wire:model.live="email" id="email" class="w-full border-gray-300 rounded" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <input type="hidden" wire:model="groupe_id" />

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Envoyer une invitation
        </button>
    </form>

    @if ($invitationLink)
        <div class="mt-4 p-4 bg-gray-100 border rounded text-sm break-all">
            Lien d’invitation : 
            <a href="{{ $invitationLink }}" class="text-blue-600 underline">
                {{ $invitationLink }}
            </a>
        </div>
    @endif
</div>
