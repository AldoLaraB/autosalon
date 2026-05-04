<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopApiController extends Controller
{
    public function index()
    {
        $shops = Shop::where('is_active', true)
            ->with(['user', 'locations', 'cars' => function ($query) {
                $query->where('is_active', true)->limit(3);
            }])
            ->paginate(12);

        return ShopResource::collection($shops);
    }

    public function show($id)
    {
        $shop = Shop::where('is_active', true)
            ->with(['user', 'locations', 'cars' => function ($query) {
                $query->where('is_active', true)->with('brand');
            }])
            ->findOrFail($id);

        return new ShopResource($shop);
    }

    public function requestDealer(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // Qui potresti implementare un sistema di richieste
        // Per ora creiamo direttamente il negozio
        $shop = Shop::create([
            'user_id' => $request->user()->id,
            'name' => $request->shop_name,
            'description' => $request->description,
            'phone' => $request->phone,
            'is_active' => false, // Richiede approvazione admin
        ]);

        return response()->json([
            'message' => 'Richiesta inviata con successo. In attesa di approvazione.',
            'shop' => new ShopResource($shop),
        ], 201);
    }
}
