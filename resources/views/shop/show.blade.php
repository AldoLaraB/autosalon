<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight
            {{ $shop->theme === 'modern' ? 'text-white' : '' }}
            {{ $shop->theme === 'elegant' ? 'text-amber-800' : '' }}">
            {{ $shop->name }}
        </h2>
    </x-slot>

    @php
        $theme = $shop->theme ?? 'default';
        
        $bgMain = match($theme) {
            'modern' => 'bg-gray-900',
            'elegant' => 'bg-amber-50',
            default => 'bg-gray-100',
        };
        
        $cardBg = match($theme) {
            'modern' => 'bg-gray-800 text-white',
            'elegant' => 'bg-white border-amber-200',
            default => 'bg-white',
        };
        
        $titleClass = match($theme) {
            'modern' => 'text-white',
            'elegant' => 'text-amber-800 text-4xl',
            default => 'text-gray-900',
        };
        
        $textClass = match($theme) {
            'modern' => 'text-gray-300',
            'elegant' => 'text-amber-900',
            default => 'text-gray-600',
        };
        
        $cover = $shop->primaryMedia('cover');
    @endphp

    <div class="py-12 {{ $bgMain }} min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Cover/Banner --}}
            @if($cover)
                <div class="rounded-lg overflow-hidden mb-6 {{ $theme === 'modern' ? 'ring-1 ring-gray-700' : '' }} {{ $theme === 'elegant' ? 'ring-2 ring-amber-300' : '' }}">
                    <img src="{{ $cover->url }}" alt="Copertina {{ $shop->name }}" class="w-full h-48 object-cover">
                </div>
            @endif

            <div class="{{ $cardBg }} overflow-hidden shadow-sm sm:rounded-lg mb-6 transition-colors">
                <div class="p-6">
                    @if($shop->logo())
                        <img src="{{ $shop->logo()->url }}" alt="{{ $shop->name }}"
                             class="w-32 h-32 object-cover rounded-lg mb-4
                             {{ $theme === 'modern' ? 'ring-2 ring-gray-600' : '' }}
                             {{ $theme === 'elegant' ? 'ring-2 ring-amber-300' : '' }}">
                    @endif
                    
                    <h1 class="text-3xl font-bold {{ $titleClass }}
                        {{ $theme === 'elegant' ? 'font-serif' : '' }}">
                        {{ $shop->name }}
                    </h1>
                    
                    @if($shop->description)
                        <p class="mt-4 {{ $textClass }}">{{ $shop->description }}</p>
                    @endif
                    
                    <div class="mt-4 text-sm {{ $textClass }}">
                        @if($shop->phone)
                            <p>📞 {{ $shop->phone }}</p>
                        @endif
                        @if($shop->email)
                            <p>✉️ {{ $shop->email }}</p>
                        @endif
                    </div>

                    @if(auth()->id() === $shop->user_id)
                        <div class="mt-6 space-x-4">
                            <a href="{{ route('shops.manage') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Gestisci Shop
                            </a>
                            <a href="{{ route('cars.create', ['shop_id' => $shop->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Aggiungi Auto
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($shop->locations->count() > 0)
                <div class="{{ $cardBg }} overflow-hidden shadow-sm sm:rounded-lg mb-6 transition-colors">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 {{ $titleClass }}">Punti Vendita</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($shop->locations as $location)
                                <div class="border rounded-lg p-4
                                    {{ $theme === 'modern' ? 'border-gray-700 bg-gray-750' : '' }}
                                    {{ $theme === 'elegant' ? 'border-amber-200 bg-amber-50/50' : '' }}">
                                    <p class="font-medium {{ $titleClass }}">{{ $location->address }}</p>
                                    <p class="text-sm {{ $textClass }}">
                                        {{ $location->city }}
                                        @if($location->province) ({{ $location->province }})@endif
                                        @if($location->zip_code) · {{ $location->zip_code }}@endif
                                    </p>
                                    @if(auth()->id() === $shop->user_id)
                                        <form action="{{ route('locations.destroy', [$shop->id, $location->id]) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('Sei sicuro?')">
                                                Rimuovi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ $cardBg }} overflow-hidden shadow-sm sm:rounded-lg transition-colors">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 {{ $titleClass }}">Auto in Vendita ({{ $shop->cars->count() }})</h3>
                    
                    @if($shop->cars->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($shop->cars as $car)
                                @php
                                    $imageUrl = $car->primaryImage()?->url;
                                @endphp
                                <div class="rounded-lg overflow-hidden
                                    {{ $theme === 'modern' ? 'bg-gray-750 border border-gray-700' : 'bg-white border' }}
                                    {{ $theme === 'elegant' ? 'border-amber-300 shadow-lg' : 'border-gray-200' }}">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $car->brand->name }} {{ $car->model }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500">Nessuna foto</span>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h4 class="font-bold {{ $titleClass }}">{{ $car->brand->name }} {{ $car->model }}</h4>
                                        <p class="text-sm {{ $textClass }}">{{ $car->year }} · {{ number_format($car->mileage, 0, ',', '.') }} km</p>
                                        <p class="text-2xl font-bold text-blue-600 mt-2">€ {{ number_format($car->price, 0, ',', '.') }}</p>
                                        @if($car->location)
                                            <p class="text-sm {{ $textClass }} mt-2">📍 {{ $car->location->city }}</p>
                                        @endif
                                        <a href="{{ route('cars.show', $car->id) }}" class="mt-4 block text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                            Vedi dettagli
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="{{ $textClass }}">Nessuna auto in vendita al momento.</p>
                    @endif
                </div>
            </div>

            @if(auth()->id() === $shop->user_id)
                <div id="location-form" class="mt-6 {{ $cardBg }} overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 {{ $titleClass }}">Aggiungi Punto Vendita</h3>
                        <form action="{{ route('locations.store', $shop->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="address" class="block text-sm font-medium {{ $textClass }}">Indirizzo</label>
                                    <input type="text" name="address" id="address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium {{ $textClass }}">Città</label>
                                    <input type="text" name="city" id="city" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium {{ $textClass }}">Provincia (2 lettere)</label>
                                    <input type="text" name="province" id="province" maxlength="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium {{ $textClass }}">CAP</label>
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
