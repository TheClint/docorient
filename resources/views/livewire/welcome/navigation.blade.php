<div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
        <div class="shrink-0 flex items-center">
            <a href="{{ route('welcome') }}" wire:navigate>
                <h1 class="text-2xl font-bold text-red-600">DocOrient</h1>
            </a>
        </div>
        <nav class=" flex items-center space-x-4">
            
                <a
                    href="{{ route('login') }}"
                    class="text-gray-600 hover:text-red-500"
                >
                    Se connecter
                </a>

                <a
                    href="{{ route('register') }}"
                    class="text-gray-600 hover:text-red-500"
                >
                    S'enregistrer
                </a>
           
        </nav>
</div>