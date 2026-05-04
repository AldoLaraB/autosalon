<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cerca la tua auto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('search.results') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="brand_id" class="block text-sm font-medium text-gray-700">Marca</label>
                                <select name="brand_id" id="brand_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Tutte le marche</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700">Modello</label>
                                <input type="text" name="model" id="model" placeholder="Es. Panda, Serie 3..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">Città</label>
                                <select name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Tutte le cittā</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->city }}">{{ $city->city }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="min_price" class="block text-sm font-medium text-gray-700">Prezzo min (€)</label>
                                <input type="number" name="min_price" id="min_price" placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="max_price" class="block text-sm font-medium text-gray-700">Prezzo max (€)</label>
                                <input type="number" name="max_price" id="max_price" placeholder="100000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Anno</label>
                                <input type="number" name="year" id="year" placeholder="2020" min="1900" max="{{ date('Y') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="fuel_type" class="block text-sm font-medium text-gray-700">Carburante</label>
                                <select name="fuel_type" id="fuel_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Tutti</option>
                                    @foreach($fuelTypes as $fuel)
                                        <option value="{{ $fuel->fuel_type }}">{{ $fuel->fuel_type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Condizione</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_new" value="0" class="form-radio" checked>
                                        <span class="ml-2">Usata</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_new" value="1" class="form-radio">
                                        <span class="ml-2">Nuova</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Cerca Auto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
