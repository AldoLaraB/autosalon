<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                    >
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <!-- Search Box -->
    <div class="w-full lg:max-w-4xl max-w-[335px] mb-8">
        <form action="{{ route('search.results') }}" method="GET" class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
            <h2 class="text-lg font-medium mb-4">Cerca la tua auto</h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="brand" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Tutte le marche</option>
                    @foreach(\App\Models\Brand::all() as $brand)
                        <option value="{{ $brand->slug }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="q" placeholder="Cerca per modello..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="px-5 py-2 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-md hover:bg-[#d42202] transition">
                    Cerca
                </button>
            </div>
            <a href="{{ route('search.index') }}" class="inline-block mt-3 text-sm text-[#f53003] dark:text-[#FF4433] underline">Ricerca avanzata</a>
        </form>
    </div>

    <!-- Recent Cars -->
    @if(isset($recentCars) && $recentCars->count() > 0)
    <div class="w-full lg:max-w-4xl max-w-[335px] mb-8">
        <h2 class="text-lg font-medium mb-4">Ultime auto inserite</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($recentCars as $car)
                <a href="{{ route('cars.show', $car) }}" class="block bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg overflow-hidden hover:shadow-lg transition">
                    @if($car->primaryImage())
                        <img src="{{ $car->primaryImage()->url }}" alt="{{ $car->title }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gray-200 dark:bg-[#3E3E3A] flex items-center justify-center">
                            <span class="text-gray-400">No image</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-medium">{{ $car->title }}</h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $car->brand->name }} • €{{ number_format($car->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- CTA for registration -->
    @guest
    <div class="w-full lg:max-w-4xl max-w-[335px] mb-8">
        <div class="bg-[#fff2f2] dark:bg-[#1D0002] rounded-lg p-6 text-center">
            <h2 class="text-lg font-medium mb-2">Vuoi vendere la tua auto?</h2>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">Registrati gratuitamente e crea il tuo spazio</p>
            <a href="{{ route('register') }}" class="inline-block px-5 py-2 bg-[#f53003] dark:bg-[#FF4433] text-white rounded-sm hover:bg-[#d42202] transition text-sm">
                Registrati ora
            </a>
        </div>
    </div>
    @endguest
</body>
</html>
