<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Risultati Ricerca') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('home') }}" class="text-blue-600 hover:underline">&larr; Nuova ricerca</a>
            </div>

            @if($cars->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($cars as $car)
                        <x-car-card :car="$car" variant="detailed" />
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $cars->links() }}
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                    <p class="text-gray-600">Nessuna auto trovata con i filtri selezionati.</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 hover:underline">Torna alla ricerca</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
