@extends('emails.layout')

@section('content')
<center>
	<h2>WILLKOMEN BEI TALENTSAGA</h2>
</center>

<p>Hallo {{ $name }},</p>

<p>willkommen bei Talentsaga! Nun kannst Du schon fast mit anderen Künstlern Kontakt aufnehmen, Du musst nur noch Dein Konto aktivieren.</p>

<p>Um Dein Konto zu aktivieren stelle zuerst sicher ob Deine Email Anmeldung gültig ist. Dazu klicke einfach den folgenden Link :</p>

<center>
	<a href="{{ $activationLink or '' }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Click here to verify</a>	
</center>
<br>

<p>Wenn Du Hilfe brauchst nutze bitte das Kontaktformular auf unserer Webseite.</p>

<center>
	<a href="{{ route('frontend.home.contactus') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Contact Us Link</a>	
</center>
<br>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
