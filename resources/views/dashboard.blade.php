<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (auth()->user()->hasRole('admin'))
                <!-- Admin Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('admin.users') }}" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <h4 class="font-medium">Gestisci Users</h4>
                                <p class="text-sm text-gray-600">Visualizza e modifica utenti</p>
                            </a>
                            <a href="{{ route('admin.shops') }}" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                <h4 class="font-medium">Gestisci Shops</h4>
                                <p class="text-sm text-gray-600">Approva e gestisci negozi</p>
                            </a>
                            <a href="{{ route('admin.cars') }}" class="block p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                                <h4 class="font-medium">Gestisci Annunci</h4>
                                <p class="text-sm text-gray-600">Modera annunci auto</p>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Admin: I tuoi Annunci (stesso blocco di User) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">I tuoi Annunci</h3>
                        @php
                            $userCars = auth()->user()->cars()->where('is_active', true)->with('brand')->latest()->limit(5)->get();
                        @endphp
                        @if($userCars->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                @foreach($userCars as $car)
                                    <x-car-card :car="$car" variant="dashboard" :showEdit="true" :linkable="false" />
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @elseif (auth()->user()->hasRole('editor'))
                <!-- Editor/Dealer Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">Il tuo Spazio Concessionario</h3>
                        @php
                            $shop = auth()->user()->shop;
                        @endphp
                        @if($shop)
                            <div class="mb-4">
                                <p class="mb-2">Negozio: <strong>{{ $shop->name }}</strong></p>
                                <div class="flex gap-3">
                                    <a href="{{ route('shops.show', $shop) }}"
                                       class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Visualizza Vetrina
                                    </a>
                                    <a href="{{ route('shops.edit', $shop) }}"
                                       class="inline-block px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                        Modifica Negozio
                                    </a>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                <a href="{{ route('cars.create') }}"
                                   class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                    <h4 class="font-medium">Inserisci Auto</h4>
                                    <p class="text-sm text-gray-600">Aggiungi una nuova auto al tuo negozio</p>
                                </a>
                                <a href="{{ route('cars.create') }}?show_locations=1"
                                   class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                    <h4 class="font-medium">Gestisci Locations</h4>
                                    <p class="text-sm text-gray-600">Aggiungi punti vendita</p>
                                </a>
                            </div>
                        @else
                            <p class="mb-4">Non hai ancora creato il tuo spazio.</p>
                            <a href="{{ route('shops.create') }}"
                               class="inline-block px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Crea il tuo Spazio
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Regular User Dashboard -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg mb-4">I tuoi Annunci</h3>
                        @php
                            $userCars = auth()->user()->cars()->where('is_active', true)->with('brand')->latest()->limit(5)->get();
                        @endphp
                        @if($userCars->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                @foreach($userCars as $car)
                                    <x-car-card :car="$car" variant="dashboard" :showEdit="true" :linkable="false" />
                                @endforeach
                            </div>
                        @endif
                        <div class="flex gap-3">
                            @if(auth()->user()->hasVerifiedEmail())
                                <a href="{{ route('shops.create') }}"
                                   class="inline-block px-5 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    Oppure Crea un Negozio
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
