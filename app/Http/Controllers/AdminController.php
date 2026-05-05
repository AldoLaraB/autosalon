<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Shop;
use App\Models\User;

class AdminController extends Controller
{
    public function usersIndex()
    {
        $users = User::with('roles')->withCount('cars')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function shopsIndex()
    {
        $shops = Shop::with('user')->withCount('cars')->paginate(20);

        return view('admin.shops', compact('shops'));
    }

    public function carsIndex()
    {
        $cars = Car::with(['user', 'brand'])->paginate(20);

        return view('admin.cars', compact('cars'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Stato utente aggiornato!');
    }

    public function toggleShopStatus($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->update(['is_active' => ! $shop->is_active]);

        return back()->with('success', 'Stato negozio aggiornato!');
    }

    public function toggleCarStatus($id)
    {
        $car = Car::findOrFail($id);
        $car->update(['is_active' => ! $car->is_active]);

        return back()->with('success', 'Stato auto aggiornato!');
    }

    public function deleteCar($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return back()->with('success', 'Annuncio eliminato con successo!');
    }
}
