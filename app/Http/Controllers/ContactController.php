<?php

namespace App\Http\Controllers;

use App\Mail\ContactSellerMail;
use App\Mail\ReplyToVisitorMail;
use App\Models\Car;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request, $carId)
    {
        $car = Car::findOrFail($carId);

        $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contact = Contact::create([
            'car_id' => $car->id,
            'sender_name' => $request->sender_name,
            'sender_email' => $request->sender_email,
            'message' => $request->message,
        ]);

        Mail::to($car->user->email)
            ->queue(new ContactSellerMail($contact));

        return back()->with('success', 'Messaggio inviato con successo!');
    }

    public function index()
    {
        $messages = Contact::whereHas('car', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->with('car.brand')
            ->latest()
            ->paginate(20);

        $unreadCount = Contact::whereHas('car', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->where('is_read', false)
            ->count();

        return view('messages.index', compact('messages', 'unreadCount'));
    }

    public function markAsRead(Contact $contact)
    {
        abort_if($contact->car->user_id !== auth()->id(), 403);

        $contact->update(['is_read' => true]);

        return back()->with('success', 'Messaggio segnato come letto.');
    }

    public function destroy(Contact $contact)
    {
        abort_if($contact->car->user_id !== auth()->id(), 403);

        $contact->delete();

        return back()->with('success', 'Messaggio eliminato.');
    }

    public function reply(Request $request, Contact $contact)
    {
        abort_if($contact->car->user_id !== auth()->id(), 403);

        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);

        Mail::to($contact->sender_email)
            ->queue(new ReplyToVisitorMail($contact, $request->reply));

        $contact->update(['replied_at' => now()]);

        return back()->with('success', 'Risposta inviata con successo!');
    }
}
