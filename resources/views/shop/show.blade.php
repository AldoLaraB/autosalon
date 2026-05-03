<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $shop->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    @if($shop->logo())
                        <img src="{{ $shop->logo()->url }}" alt="{{ $shop->name }}" class="w-32 h-32 object-cover rounded-lg mb-4">
                    @endif
                    
                    <h1 class="text-3xl font-bold">{{ $shop->name }}</h1>
                    
                    @if($shop->description)
                        <p class="mt-4 text-gray-600">{{ $shop->description }}</p>
                    @endif
                    
                    <div class="mt-4 text-sm text-gray-500">
                        @if($shop->phone)
                            <p>📞 {{ $shop->phone }}</p>
                        @endif
                        @if($shop->email)
                            <p>✉️ {{ $shop->email }}</p>
                        @endif
                    </div>

                    @if(auth()->id() === $shop->user_id)
                        <div class="mt-6 space-x-4">
                            <a href="{{ route('shops.edit', $shop->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Modifica Negozio
                            </a>
                            <a href="{{ route('cars.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Aggiungi Auto
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($shop->locations->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Punti Vendita</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($shop->locations as $location)
                                <div class="border p-4 rounded">
                                    <p class="font-medium">{{ $location->address }}</p>
                                    <p class="text-gray-600">{{ $location->city }} ({{ $location->province }})</p>
                                    @if(auth()->id() === $shop->user_id)
                                        <form action="{{ route('locations.destroy', [$shop->id, $location->id]) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 text-sm" onclick="return confirm('Sei sicuro?')">
                                                Rimuovi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if(auth()->id() === $shop->user_id)
                            <div class="mt-4">
                                <a href="#location-form" class="text-blue-600 hover:underline">+ Aggiungi nuovo punto vendita</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Auto in Vendita ({{ $shop->cars->count() }})</h3>
                    
                    @if($shop->cars->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($shop->cars as $car)
                                <div class="border rounded-lg overflow-hidden">
                                    @if($car->primaryImage())
                                        <img src="{{ $car->primaryImage()->url }}" alt="{{ $car->brand->name }} {{ $car->model }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500">Nessuna foto</span>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h4 class="font-bold">{{ $car->brand->name }} {{ $car->model }}</h4>
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
                    @else
                        <p class="text-gray-600">Nessuna auto in vendita al momento.</p>
                    @endif
                </div>
            </div>

            @if(auth()->id() === $shop->user_id)
                <div id="location-form" class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Aggiungi Punto Vendita</h3>
                        <form action="{{ route('locations.store', $shop->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Indirizzo</label>
                                    <input type="text" name="address" id="address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">Cittā</label>
                                    <input type="text" name="city" id="city" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700">Provincia (2 lettere)</label>
                                    <input type="text" name="province" id="province" maxlength="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700">CAP</label>
                                    <input type="text" name="zip_code" id="zip_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Aggiungi Punto Vendita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
