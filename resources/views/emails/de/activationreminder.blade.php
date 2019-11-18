@extends('emails.layout')

@section('content')
<center>
	<h2>ERINNERUNG AN DEINE REGISTRIERUNG</h2>
</center>

<p>Hallo {{ $name }},</p>

<p>Hast Du die Mail in Deinem Posteingang übersehen? Wir möchten Dich erinnern, dass Dein Konto bei Talentsaga noch nicht aktivert wurde.</p>

<p>s :</p>

<center>
	<a href="{{ $activationLink or '' }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Click here to verify</a>	
</center>

<p>Wenn Du Hilfe brauchst, nutze bitte das Kontaktformular auf unserer Webseite.</p>

<center>
	<a href="{{ route('frontend.home.contactus') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Contact Us Link</a>	
</center>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
