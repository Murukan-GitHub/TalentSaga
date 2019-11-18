@extends('emails.layout')

@section('content')
<p>Hallo {{ $talentSeekerName }},</p>

<p>Dein Event hat erfolgreich stattgefunden! Wir möchten uns bei Dir bedanken, dass Du über Talentsaga gebucht hast. Wir hoffen der gebuchte Künstler war die richtige Wahl für Deine Veranstaltung.</p>

<p>Dein Feedback ist wichtig für uns. Daher möchten wir Dich bitten, Dir ein paar Minuten Zeit zu nehmen um den Künstler zu bewerten.</p>

<center>
	<a href="{{ route('user.booking.list') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Give a Review</a>	
</center>
<br>

<p>Wir hoffen, dass Du auch mit der Talentsaga-Dienstleistung zufrieden bist!</p>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
