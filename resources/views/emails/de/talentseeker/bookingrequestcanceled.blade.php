@extends('emails.layout')

@section('content')
<center>
	<h2>DER KÜNSTLER HAT DEINE BUCHUNG STORNIERT</h2>
</center>

<p>Hallo {{ $talentSeekerName }},</p>

<p>wir müssen Dir leider mitteilen das der von Dir gebuchte Künstler Deine Buchung storniert hat. Versuch es Doch erneut, es findet sich sicher noch ein anderer Künstler für Dich.</p>

<p>Klick einfach auf den folgenden Link und suche einen passenden Künstler für Dein Event :</p>

<center>
	<a href="{{ route('talent.search') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Talent List</a>	
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
