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
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            @if($car->primaryImage())
                                <img src="{{ $car->primaryImage()->url }}" alt="{{ $car->brand->name }} {{ $car->model }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">Nessuna foto</span>
                                </div>
                            @endif
                            
                            <div class="p-4">
                                <h3 class="font-bold text-lg">{{ $car->brand->name }} {{ $car->model }}</h3>
                                <p class="text-gray-600">{{ $car->year }} · {{ number_format($car->mileage, 0, ',', '.') }} km</p>
                                <p class="text-2xl font-bold text-blue-600 mt-2">€ {{ number_format($car->price, 0, ',', '.') }}</p>
                                
                                @if($car->location)
                                    <p class="text-sm text-gray-500 mt-2">📍 {{ $car->location->city }}</p>
                                @endif
                                
                                <a href="{{ route('cars.show', $car->id) }}" class="mt-4 block text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                    Vedi dettagli
                                </a>
                            </div>
                        </div>
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
