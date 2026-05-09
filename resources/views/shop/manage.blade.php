<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestisci il tuo Shop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- INFO BASE --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Informazioni</h3>
                    <form action="{{ route('shops.update', $shop->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome Negozio *</label>
                                <input type="text" name="name" id="name" required maxlength="255"
                                       value="{{ old('name', $shop->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" maxlength="255"
                                       value="{{ old('email', $shop->email) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telefono</label>
                                <input type="text" name="phone" id="phone" maxlength="20"
                                       value="{{ old('phone', $shop->phone) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrizione</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $shop->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Salva modifiche
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- PUBBLICA ANNUNCIO PER IL NEGOZIO --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Pubblica Annuncio</h3>
                        <p class="text-sm text-gray-600">Inserisci un nuovo annuncio collegato al tuo negozio</p>
                    </div>
                    <a href="{{ route('cars.create', ['shop_id' => $shop->id]) }}"
                       class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Pubblica Annuncio
                    </a>
                </div>
            </div>

            {{-- LOGO --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Logo</h3>
                    <div class="flex items-center space-x-6">
                        <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                            @php $logo = $shop->primaryMedia('logo'); @endphp
                            @if($logo)
                                <img src="{{ $logo->url }}" alt="Logo {{ $shop->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-400 text-xs text-center">Nessun logo</span>
                            @endif
                        </div>
                        <div class="space-y-2">
                            <form action="{{ route('shops.logo.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="logo" accept="image/*" required
                                       class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <button type="submit"
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
                                    Carica Logo
                                </button>
                            </form>
                            @if($logo)
                                <form action="{{ route('shops.logo.destroy', $shop->id) }}" method="POST"
                                      onsubmit="return confirm('Rimuovere il logo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm text-red-600 hover:text-red-800">
                                        Rimuovi Logo
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- COPERTINA / BANNER --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Copertina / Banner</h3>
                    <div class="flex items-center space-x-6">
                        <div class="w-48 h-24 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                            @php $cover = $shop->primaryMedia('cover'); @endphp
                            @if($cover)
                                <img src="{{ $cover->url }}" alt="Copertina {{ $shop->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-400 text-xs text-center">Nessuna copertina</span>
                            @endif
                        </div>
                        <div class="space-y-2">
                            <form action="{{ route('shops.cover.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="cover" accept="image/*" required
                                       class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <button type="submit"
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
                                    Carica Copertina
                                </button>
                            </form>
                            @if($cover)
                                <form action="{{ route('shops.cover.destroy', $shop->id) }}" method="POST"
                                      onsubmit="return confirm('Rimuovere la copertina?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-sm text-red-600 hover:text-red-800">
                                        Rimuovi Copertina
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PUNTI VENDITA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Punti Vendita</h3>

                    @if($shop->locations->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            @foreach($shop->locations as $location)
                                <div class="border rounded-lg p-4">
                                    <p class="font-medium">{{ $location->address }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $location->city }}
                                        @if($location->province) ({{ $location->province }})@endif
                                        @if($location->zip_code) · {{ $location->zip_code }}@endif
                                    </p>
                                    <form action="{{ route('locations.destroy', [$shop->id, $location->id]) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800"
                                                onclick="return confirm('Rimuovere questo punto vendita?')">
                                            Rimuovi
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mb-4">Nessun punto vendita aggiunto.</p>
                    @endif

                    <details class="border rounded-lg p-4">
                        <summary class="text-sm text-blue-600 hover:text-blue-800 cursor-pointer font-medium">
                            + Aggiungi nuovo punto vendita
                        </summary>
                        <form action="{{ route('locations.store', $shop->id) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Indirizzo *</label>
                                    <input type="text" name="address" id="address" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">Città *</label>
                                    <input type="text" name="city" id="city" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700">Provincia</label>
                                    <input type="text" name="province" id="province" maxlength="2"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700">CAP</label>
                                    <input type="text" name="zip_code" id="zip_code"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
                                    Aggiungi
                                </button>
                            </div>
                        </form>
                    </details>
                </div>
            </div>

            {{-- LINK VETRINA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Vetrina Pubblica</h3>
                        <p class="text-sm text-gray-600">Visualizza come appare il tuo shop ai visitatori</p>
                    </div>
                    <a href="{{ route('shops.show', $shop->id) }}"
                       class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Visualizza Vetrina
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
