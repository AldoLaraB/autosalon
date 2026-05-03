<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Location;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $brands = Brand::orderBy('name')->get();
        $shops = Shop::where('user_id', $user->id)->get();

        $locations = collect();
        if ($shops->isNotEmpty()) {
            $shopIds = $shops->pluck('id');
            $locations = Location::whereIn('shop_id', $shopIds)->get();
        }

        return view('car.create', compact('brands', 'shops', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'nullable|exists:shops,id',
            'location_id' => 'nullable|exists:locations,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.date('Y'),
            'price' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'is_new' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $car = Car::create([
            'user_id' => Auth::id(),
            'shop_id' => $request->shop_id,
            'location_id' => $request->location_id,
            'brand_id' => $request->brand_id,
            'model' => $request->model,
            'year' => $request->year,
            'price' => $request->price,
            'mileage' => $request->mileage,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'is_new' => $request->boolean('is_new'),
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('cars.show', $car->id)
            ->with('success', 'Auto inserita con successo!');
    }

    public function show($id)
    {
        $car = Car::with(['brand', 'shop', 'location', 'user', 'gallery'])
            ->findOrFail($id);

        return view('car.show', compact('car'));
    }

    public function edit($id)
    {
        $car = Car::where('user_id', Auth::id())->findOrFail($id);
        $user = Auth::user();
        $brands = Brand::orderBy('name')->get();
        $shops = Shop::where('user_id', $user->id)->get();

        $locations = collect();
        if ($shops->isNotEmpty()) {
            $shopIds = $shops->pluck('id');
            $locations = Location::whereIn('shop_id', $shopIds)->get();
        }

        return view('car.edit', compact('car', 'brands', 'shops', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $car = Car::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'shop_id' => 'nullable|exists:shops,id',
            'location_id' => 'nullable|exists:locations,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.date('Y'),
            'price' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'is_new' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $car->update($request->only([
            'shop_id', 'location_id', 'brand_id', 'model', 'year',
            'price', 'mileage', 'fuel_type', 'transmission', 'is_new', 'description',
        ]));

        return redirect()->route('cars.show', $car->id)
            ->with('success', 'Auto aggiornata con successo!');
    }
}
