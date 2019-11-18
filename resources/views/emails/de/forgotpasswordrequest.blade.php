@extends('emails.layout')

@section('content')
<center>
	<h2>PASSWORT VERGESSEN / ÄNDERN</h2>
</center>

<p>Hallo {{ $name }},</p>

<p>hast Du Dein Passwort vergessen oder möchtest es ändern? Wir haben gerade eine Anfrage erhalten, dass Du Dein Passwort vergessen hast oder ändern möchtest.</p>

<p>Über den folgenden Link kannst Du ein neues Passwort anfordern:</p>

<b>{{ $code }}</b>[Set a New Password]

<p>Falls die Anfrage nicht von Dir kam, mach Dir keine Sorgen. Dein Passwort wurde nicht geändert und ist sicher. Du kannst Diese Mail ignorieren.</p>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
