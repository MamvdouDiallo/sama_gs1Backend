<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GS1 SENEGAL</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
    </style>
</head>

<body class="antialiased">
    @component('mail::message')
    Réinitialiser ou changer votre mot de passe.
    @component('mail::button', ['url' => 'https://samags1.org/#/enter_password?token='.$token])
    Cliquez sur le lien pour changer votre votre mot de passe
    @endcomponent
    MERCI,<br>
    {{ config('app.name') }}
    @endcomponent
</body>

</html>