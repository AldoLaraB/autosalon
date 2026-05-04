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
            $query->where('is_active', true)->with('brand');
        }])->findOrFail($id);

        return view('shop.show', compact('shop'));
    }

    public function create()
    {
        return view('shop.create');
    }

    public function store(Request $request)
    {
        // Controlla se l'utente ha già un negozio
        $existingShop = Shop::where('user_id', Auth::id())->first();
        if ($existingShop) {
            return redirect()->route('shops.show', $existingShop->id)
                ->with('error', 'Hai già un negozio!');
        }

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

        // Assegna ruolo editor all'utente (può gestire auto e locations)
        $user = Auth::user();
        if (! $user->hasRole('editor')) {
            $user->assignRole('editor');
        }

        return redirect()->route('shops.show', $shop->id)
            ->with('success', 'Negozio creato con successo! Ora hai accesso come concessionario.');
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
