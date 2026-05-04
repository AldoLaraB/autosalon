<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifica Auto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('cars.update', $car->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="brand_id" class="block text-sm font-medium text-gray-700">Marca *</label>
                                <select name="brand_id" id="brand_id" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Seleziona marca...</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $car->brand_id == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700">Modello *</label>
                                <input type="text" name="model" id="model" required maxlength="255"
                                       value="{{ old('model', $car->model) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Anno *</label>
                                <input type="number" name="year" id="year" required min="1900" max="{{ date('Y') }}"
                                       value="{{ old('year', $car->year) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Prezzo (€) *</label>
                                <input type="number" name="price" id="price" required min="0" step="0.01"
                                       value="{{ old('price', $car->price) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="mileage" class="block text-sm font-medium text-gray-700">Chilometri</label>
                                <input type="number" name="mileage" id="mileage" min="0"
                                       value="{{ old('mileage', $car->mileage) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="fuel_type" class="block text-sm font-medium text-gray-700">Carburante</label>
                                <select name="fuel_type" id="fuel_type" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Seleziona...</option>
                                    <option value="Benzina" {{ $car->fuel_type == 'Benzina' ? 'selected' : '' }}>Benzina</option>
                                    <option value="Diesel" {{ $car->fuel_type == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="GPL" {{ $car->fuel_type == 'GPL' ? 'selected' : '' }}>GPL</option>
                                    <option value="Metano" {{ $car->fuel_type == 'Metano' ? 'selected' : '' }}>Metano</option>
                                    <option value="Elettrica" {{ $car->fuel_type == 'Elettrica' ? 'selected' : '' }}>Elettrica</option>
                                    <option value="Ibrida" {{ $car->fuel_type == 'Ibrida' ? 'selected' : '' }}>Ibrida</option>
                                </select>
                            </div>

                            <div>
                                <label for="transmission" class="block text-sm font-medium text-gray-700">Cambio</label>
                                <select name="transmission" id="transmission" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Seleziona...</option>
                                    <option value="Manuale" {{ $car->transmission == 'Manuale' ? 'selected' : '' }}>Manuale</option>
                                    <option value="Automatico" {{ $car->transmission == 'Automatico' ? 'selected' : '' }}>Automatico</option>
                                    <option value="Semi-automatico" {{ $car->transmission == 'Semi-automatico' ? 'selected' : '' }}>Semi-automatico</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Condizione</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_new" value="0" {{ !$car->is_new ? 'checked' : '' }} class="form-radio">
                                        <span class="ml-2">Usata</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_new" value="1" {{ $car->is_new ? 'checked' : '' }} class="form-radio">
                                        <span class="ml-2">Nuova</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if($shops->count() > 0)
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium mb-4">Collega al tuo Negozio</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="shop_id" class="block text-sm font-medium text-gray-700">Negozio</label>
                                        <select name="shop_id" id="shop_id" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Nessuno (annuncio personale)</option>
                                            @foreach($shops as $shop)
                                                <option value="{{ $shop->id }}" {{ $car->shop_id == $shop->id ? 'selected' : '' }}>
                                                    {{ $shop->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="location_id" class="block text-sm font-medium text-gray-700">Punto Vendita</label>
                                        <select name="location_id" id="location_id" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Seleziona prima un negozio...</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}" data-shop="{{ $location->shop_id }}"
                                                        {{ $car->location_id == $location->id ? 'selected' : '' }}>
                                                    {{ $location->address }}, {{ $location->city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrizione</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $car->description) }}</textarea>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Aggiorna Annuncio
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($car->gallery()->count() > 0)
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Foto Attuali</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($car->gallery() as $photo)
                                <div class="relative">
                                    <img src="{{ $photo->url }}" class="w-full h-32 object-cover rounded">
                                    <form action="#" method="POST" class="absolute top-1 right-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                            ×
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-6 border-t pt-6">
                <form action="{{ route('cars.destroy', $car) }}" method="POST"
                      onsubmit="return confirm('Sei sicuro? L\'operazione è irreversibile.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                        Elimina Annuncio
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if($shops->count() > 0)
        <script>
            document.getElementById('shop_id').addEventListener('change', function() {
                const locationSelect = document.getElementById('location_id');
                const shopId = this.value;
                
                for (let option of locationSelect.options) {
                    if (option.value === '') {
                        option.style.display = 'block';
                        continue;
                    }
                    if (!shopId || option.dataset.shop === shopId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
                
                if (locationSelect.value && locationSelect.selectedOptions[0].style.display === 'none') {
                    locationSelect.value = '';
                }
            });
        </script>
    @endif
</x-app-layout>
