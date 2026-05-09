<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestione Shops') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">

                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proprietario</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auto</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($shops as $shop)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $shop->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('shops.show', $shop) }}" class="text-indigo-600 hover:text-indigo-800">
                                                    {{ $shop->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $shop->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $shop->cars_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($shop->is_active)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Attivo</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Disattivo</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-sm
                                                        @if($shop->is_active) text-red-600 hover:text-red-800
                                                        @else text-green-600 hover:text-green-800
                                                        @endif">
                                                        @if($shop->is_active) Disattiva
                                                        @else Attiva
                                                        @endif
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $shops->links() }}
                        </div>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
