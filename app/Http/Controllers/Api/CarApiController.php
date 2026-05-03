<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;

class CarApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::where('is_active', true)->with(['brand', 'shop', 'location', 'primaryMedia']);

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('model')) {
            $query->where('model', 'like', '%'.$request->model.'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('is_new')) {
            $query->where('is_new', $request->is_new);
        }

        if ($request->filled('city')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $cars = $query->paginate(12);

        return CarResource::collection($cars);
    }

    public function show($id)
    {
        $car = Car::where('is_active', true)
            ->with(['brand', 'shop', 'location', 'user', 'gallery'])
            ->findOrFail($id);

        return new CarResource($car);
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
            'user_id' => $request->user()->id,
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

        return new CarResource($car->load(['brand', 'shop', 'location']));
    }

    public function update(Request $request, $id)
    {
        $car = Car::where('user_id', $request->user()->id)->findOrFail($id);

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

        return new CarResource($car->load(['brand', 'shop', 'location']));
    }
}
