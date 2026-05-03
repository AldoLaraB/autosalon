<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Location;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->get();
        $cities = Location::select('city')->distinct()->orderBy('city')->get();
        $fuelTypes = Car::select('fuel_type')->distinct()->whereNotNull('fuel_type')->get();

        return view('search.index', compact('brands', 'cities', 'fuelTypes'));
    }

    public function search(Request $request)
    {
        $query = Car::with(['brand', 'shop', 'location'])
            ->where('is_active', true);

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
        $brands = Brand::orderBy('name')->get();

        return view('search.results', compact('cars', 'brands'));
    }
}
