<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(Request $request, $shopId)
    {
        $shop = Shop::where('user_id', Auth::id())->findOrFail($shopId);

        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'nullable|string|size:2',
            'zip_code' => 'nullable|string|max:10',
        ]);

        Location::create([
            'shop_id' => $shop->id,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'zip_code' => $request->zip_code,
        ]);

        return redirect()->back()
            ->with('success', 'Punto vendita aggiunto con successo!');
    }

    public function destroy($shopId, $locationId)
    {
        $shop = Shop::where('user_id', Auth::id())->findOrFail($shopId);
        $location = Location::where('shop_id', $shop->id)->findOrFail($locationId);

        $location->delete();

        return redirect()->back()
            ->with('success', 'Punto vendita rimosso!');
    }
}
