<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2563eb;">💬 Risposta al tuo messaggio</h2>

        <p>Ciao <strong>{{ $contact->sender_name }}</strong>,</p>

        <p><strong>{{ $contact->car->user->name }}</strong> ha risposto al tuo messaggio
        per <strong>{{ $contact->car->title }}</strong>:</p>

        <hr style="border: 1px solid #eee; margin: 20px 0;">

        <p style="font-size: 13px; color: #666;">Il tuo messaggio originale:</p>
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0; font-style: italic;">
            {{ $contact->message }}
        </div>

        <p style="font-size: 13px; color: #666;">Risposta di {{ $contact->car->user->name }}:</p>
        <div style="background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 15px 0;">
            {{ $reply }}
        </div>

        <p>Puoi contattare <strong>{{ $contact->car->user->name }}</strong> rispondendo direttamente a questa email.</p>

        <hr style="border: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #999;">
            Hai ricevuto questa email perché hai richiesto informazioni su un annuncio su {{ config('app.name') }}.
        </p>
    </div>
</body>
</html>
