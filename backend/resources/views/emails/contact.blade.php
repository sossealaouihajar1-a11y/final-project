<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau message de contact</title>
</head>
<body style="background-color:#faf9f5; font-family: 'Georgia', serif; color:#2a2a28; padding:20px;">
    <h2 style="color:#6b4f3a; font-weight: 400; font-size:24px;">
       Nouveau message de contact - Nostalgia Collective
    </h2>
    <p style="color:#2a2a28; font-size:14px; margin-bottom:20px;">
        Voici les détails du message envoyé depuis le formulaire de contact :
    </p>

    <p><strong>Nom :</strong> {{ $data['name'] }}</p>
    <p><strong>Email :</strong> {{ $data['email'] }}</p>
    <p><strong>Sujet :</strong> {{ $data['subject'] }}</p>
    <p><strong>Message :</strong></p>
    <p style="background-color:#fdf6ec; padding:10px; border-radius:8px; border:1px solid #d4af37;">
        {{ $data['message'] }}
    </p>
</body>
</html>
