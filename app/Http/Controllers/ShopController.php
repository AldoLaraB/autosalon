<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function show($id)
    {
        $shop = Shop::with(['locations', 'cars' => function ($query) {
            $query->where('is_active', true)->with('brand', 'primaryMedia');
        }])->findOrFail($id);

        return view('shop.show', compact('shop'));
    }

    public function create()
    {
        return view('shop.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $shop = Shop::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => true,
        ]);

        return redirect()->route('shop.show', $shop->id)
            ->with('success', 'Negozio creato con successo!');
    }

    public function edit($id)
    {
        $shop = Shop::where('user_id', Auth::id())->findOrFail($id);

        return view('shop.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $shop = Shop::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $shop->update($request->only(['name', 'description', 'phone', 'email']));

        return redirect()->route('shop.show', $shop->id)
            ->with('success', 'Negozio aggiornato con successo!');
    }
}
