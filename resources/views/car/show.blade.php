<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $car->brand->name }} {{ $car->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div x-data="{ selectedImage: '{{ $car->primaryImage()?->url ?? '' }}' }">
                            @if($car->primaryImage() || $car->gallery()->count() > 0)
                                <img :src="selectedImage || '{{ $car->primaryImage()?->url ?? '' }}'"
                                     alt="{{ $car->brand->name }} {{ $car->model }}"
                                     class="w-full rounded-lg">
                            @else
                                <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                                    <span class="text-gray-500">Nessuna foto disponibile</span>
                                </div>
                            @endif
                            
                            @if($car->gallery()->count() > 0)
                                <div class="mt-4 grid grid-cols-4 gap-2">
                                    @foreach($car->gallery() as $image)
                                        <img src="{{ $image->url }}"
                                             @click="selectedImage = '{{ $image->url }}'"
                                             :class="{ 'ring-2 ring-blue-500 opacity-100': selectedImage === '{{ $image->url }}', 'opacity-70 hover:opacity-100': selectedImage !== '{{ $image->url }}' }"
                                             class="w-full h-20 object-cover rounded cursor-pointer transition">
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold">{{ $car->brand->name }} {{ $car->model }}</h1>
                            <p class="text-gray-600 mt-2">{{ $car->year }} · {{ $car->is_new ? 'Nuova' : 'Usata' }}</p>
                            
                            <div class="mt-6 space-y-4">
                                <div>
                                    <span class="text-4xl font-bold text-blue-600">€ {{ number_format($car->price, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Chilometri:</span>
                                        <p class="font-medium">{{ number_format($car->mileage, 0, ',', '.') }} km</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Carburante:</span>
                                        <p class="font-medium">{{ $car->fuel_type ?: 'N/D' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Cambio:</span>
                                        <p class="font-medium">{{ $car->transmission ?: 'N/D' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Marca:</span>
                                        <p class="font-medium">{{ $car->brand->name }}</p>
                                    </div>
                                </div>

                                @if($car->description)
                                    <div class="mt-6">
                                        <h3 class="font-semibold">Descrizione</h3>
                                        <p class="mt-2 text-gray-600">{{ $car->description }}</p>
                                    </div>
                                @endif

                                @if($car->location)
                                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                        <h3 class="font-semibold">Posizione</h3>
                                        <p class="mt-2 text-gray-600">📍 {{ $car->location->address }}, {{ $car->location->city }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($car->shop)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-semibold">{{ $car->shop->name }}</h3>
                                <p class="text-gray-600 mt-1">Vedi tutte le auto di questo concessionario</p>
                            </div>
                            <a href="{{ route('shops.show', $car->shop->id) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Visita il Negozio
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
