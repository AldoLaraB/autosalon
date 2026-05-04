<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inserisci Nuova Auto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                            <strong>Attenzione!</strong> Ci sono errori nel form:
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="brand_id" class="block text-sm font-medium text-gray-700">Marca *</label>
                                <select name="brand_id" id="brand_id" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Seleziona marca...</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700">Modello *</label>
                                <input type="text" name="model" id="model" required maxlength="255"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                       value="{{ old('model') }}">
                                @error('model')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Anno *</label>
                                <input type="number" name="year" id="year" required min="1900" max="{{ date('Y') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                       value="{{ old('year') }}">
                                @error('year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Prezzo (€) *</label>
                                <input type="number" name="price" id="price" required min="0" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                       value="{{ old('price') }}">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="mileage" class="block text-sm font-medium text-gray-700">Chilometri</label>
                                <input type="number" name="mileage" id="mileage" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                       value="{{ old('mileage') }}">
                                @error('mileage')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fuel_type" class="block text-sm font-medium text-gray-700">Carburante</label>
                                <select name="fuel_type" id="fuel_type" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Seleziona...</option>
                                    <option value="Benzina" {{ old('fuel_type') == 'Benzina' ? 'selected' : '' }}>Benzina</option>
                                    <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="GPL" {{ old('fuel_type') == 'GPL' ? 'selected' : '' }}>GPL</option>
                                    <option value="Metano" {{ old('fuel_type') == 'Metano' ? 'selected' : '' }}>Metano</option>
                                    <option value="Elettrica" {{ old('fuel_type') == 'Elettrica' ? 'selected' : '' }}>Elettrica</option>
                                    <option value="Ibrida" {{ old('fuel_type') == 'Ibrida' ? 'selected' : '' }}>Ibrida</option>
                                </select>
                                @error('fuel_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="transmission" class="block text-sm font-medium text-gray-700">Cambio</label>
                                <select name="transmission" id="transmission" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Seleziona...</option>
                                    <option value="Manuale" {{ old('transmission') == 'Manuale' ? 'selected' : '' }}>Manuale</option>
                                    <option value="Automatico" {{ old('transmission') == 'Automatico' ? 'selected' : '' }}>Automatico</option>
                                    <option value="Semi-automatico" {{ old('transmission') == 'Semi-automatico' ? 'selected' : '' }}>Semi-automatico</option>
                                </select>
                                @error('transmission')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Condizione</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_new" value="0" {{ old('is_new', '0') == '0' ? 'checked' : '' }} class="form-radio">
                                        <span class="ml-2">Usata</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_new" value="1" {{ old('is_new') == '1' ? 'checked' : '' }} class="form-radio">
                                        <span class="ml-2">Nuova</span>
                                    </label>
                                </div>
                                @error('is_new')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                            <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                                {{ $shop->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shop_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="location_id" class="block text-sm font-medium text-gray-700">Punto Vendita</label>
                                    <select name="location_id" id="location_id" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Seleziona prima un negozio...</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" data-shop="{{ $location->shop_id }}"
                                                {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->address }}, {{ $location->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrizione</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Foto (minimo 1, massimo 5)</label>
                            <div class="mt-2" id="photo-upload-area">
                                <input type="file" name="photos[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       id="photo-input" onchange="updateFileList()">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF, WEBP. Massimo 5 file. Le immagini verranno ottimizzate automaticamente.</p>
                                <div id="file-list" class="mt-3 space-y-1"></div>
                                <p id="file-count" class="mt-2 text-sm text-gray-600"></p>
                            </div>
                            @error('photos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('photos.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Pubblica Annuncio
                            </button>
                        </div>
                    </form>
                </div>
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

    <script>
        function updateFileList() {
            const input = document.getElementById('photo-input');
            const fileListDiv = document.getElementById('file-list');
            const fileCountP = document.getElementById('file-count');
            const files = input.files;
            
            fileListDiv.innerHTML = '';
            
            if (files.length === 0) {
                fileCountP.textContent = '';
                return;
            }
            
            if (files.length > 5) {
                fileCountP.innerHTML = '<span class="text-red-600 font-semibold">Troppi file! Massimo 5 immagini.</span>';
                input.value = '';
                return;
            }
            
            fileCountP.innerHTML = '<span class="' + (files.length > 5 ? 'text-red-600' : 'text-green-600') + ' font-semibold">' 
                + files.length + ' di 5 file selezionati</span>';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'flex items-center space-x-3 p-2 bg-gray-50 rounded';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="h-12 w-12 object-cover rounded">
                        <span class="text-sm text-gray-700">${file.name} (${(file.size / 1024).toFixed(1)} KB)</span>
                    `;
                    fileListDiv.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
