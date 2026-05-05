@props([
    'car',
    'variant' => 'compact',
    'linkable' => true,
    'showEdit' => false,
])

@php
    $imageUrl = $car->primaryImage()?->url;
    $imageAlt = $car->title ?? $car->brand->name.' '.$car->model;
    $formattedPrice = '€ '.number_format($car->price, 0, ',', '.');

    $imageHeight = $variant === 'detailed' ? 'h-48' : 'h-40';

    $wrapperClasses = $linkable
        ? 'block bg-white dark:bg-[#161615] rounded-lg overflow-hidden hover:shadow-lg transition'
        : 'bg-white dark:bg-[#161615] rounded-lg overflow-hidden';

    if ($variant === 'compact') {
        $wrapperClasses .= ' shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]';
    } elseif ($variant === 'detailed') {
        $wrapperClasses .= ' shadow-md';
    } else {
        $wrapperClasses .= ' border';
    }
@endphp

@if($linkable)
    <a
        href="{{ route('cars.show', $car) }}"
        class="{{ $wrapperClasses }}"
        aria-label="{{ $imageAlt }}"
    >
@else
    <div class="{{ $wrapperClasses }}">
@endif

    {{-- Immagine --}}
    @if($imageUrl)
        <img
            src="{{ $imageUrl }}"
            alt="{{ $imageAlt }}"
            class="w-full {{ $imageHeight }} object-cover"
            loading="lazy"
        >
    @else
        <div class="w-full {{ $imageHeight }} bg-gray-200 dark:bg-[#3E3E3A] flex items-center justify-center">
            <span class="text-gray-400 dark:text-gray-500 text-sm">Nessuna foto</span>
        </div>
    @endif

    {{-- Informazioni auto --}}
    <div class="p-4">
        @if($variant === 'detailed')
            <h3 class="font-bold text-lg text-gray-900 dark:text-[#EDEDEC]">
                {{ $car->brand->name }} {{ $car->model }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-[#A1A09A] mt-1">
                {{ $car->year }}
                @if($car->mileage)
                    · {{ number_format($car->mileage, 0, ',', '.') }} km
                @endif
                · {{ $car->is_new ? 'Nuova' : 'Usata' }}
            </p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                {{ $formattedPrice }}
            </p>
            @if($car->location)
                <p class="text-sm text-gray-500 dark:text-[#A1A09A] mt-2">
                    📍 {{ $car->location->city }}
                </p>
            @endif
            <div class="mt-4">
                <span class="block text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition text-sm">
                    Vedi dettagli
                </span>
            </div>
        @else
            <h3 class="font-medium text-gray-900 dark:text-[#EDEDEC]">
                @if(!$linkable)
                    <a href="{{ route('cars.show', $car) }}" class="hover:underline">{{ $car->title }}</a>
                @else
                    {{ $car->title }}
                @endif
            </h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">
                {{ $car->brand->name }} • {{ $formattedPrice }}
            </p>
        @endif

        {{-- Firma inserzionista (avatar a destra, nome su hover) --}}
        @if($car->relationLoaded('user'))
        <div class="flex justify-end mt-2">
            @php
                $userAvatar = $car->user->primaryMedia('avatar');
            @endphp
            @if($userAvatar)
                <img src="{{ $userAvatar->url }}"
                     alt="{{ $car->user->name }}"
                     title="{{ $car->user->name }}"
                     class="w-8 h-8 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-[#3E3E3A] flex items-center justify-center text-xs text-gray-600 dark:text-gray-400 flex-shrink-0"
                     title="{{ $car->user->name }}">
                    {{ substr($car->user->name, 0, 1) }}
                </div>
            @endif
        </div>
        @endif

        {{-- Slot per azioni extra (es. Modifica, Elimina) --}}
        @if($variant === 'dashboard' && $showEdit)
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-[#3E3E3A] flex justify-between">
                <a
                    href="{{ route('cars.edit', $car) }}"
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium"
                >
                    Modifica
                </a>

                <form action="{{ route('cars.destroy', $car) }}" method="POST"
                      onsubmit="return confirm('Sei sicuro di voler eliminare questo annuncio?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">
                        Elimina
                    </button>
                </form>
            </div>
        @endif

        {{ $slot ?? '' }}
    </div>

@if($linkable)
    </a>
@else
    </div>
@endif
