<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #dc2626;">🔔 Nuovo messaggio</h2>

        <p>Ciao <strong>{{ $contact->car->user->name }}</strong>,</p>

        <p>Hai ricevuto un messaggio da <strong>{{ $contact->sender_name }}</strong>
        per il tuo annuncio: <strong>{{ $contact->car->title }}</strong></p>

        <hr style="border: 1px solid #eee; margin: 20px 0;">

        <p style="font-size: 13px; color: #666;">Da: {{ $contact->sender_name }} ({{ $contact->sender_email }})</p>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 15px 0;">
            {{ $contact->message }}
        </div>

        <p>Accedi alla tua area personale per leggere e rispondere:</p>

        <a href="{{ route('messages.index') }}"
           style="display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px;">
            Vedi messaggi
        </a>

        <hr style="border: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #999;">
            Hai ricevuto questa email perché hai un annuncio su {{ config('app.name') }}.
        </p>
    </div>
</body>
</html>
