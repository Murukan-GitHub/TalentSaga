@extends('emails.layout')

@section('content')
<center>
	<h2>DEINE BUCHUNGSANFRAGE WURDE STORNIERT</h2>
</center>

<p>Hallo {{ $talentName }},</p>

<p>es tut uns Leid aber Deine Buchungsanfrage wurde storniert.</p>

<p>Wir hoffen ein anderer Kunde ist schon bald an Dir interessiert!</p>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
