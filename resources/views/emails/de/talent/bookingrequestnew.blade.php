@extends('emails.layout')

@section('content')
<center>
	<h2>DU HAST EINE BUCHUNGSANFRAGE ERHALTEN</h2>
</center>

<p>Hallo {{ $talentName }},</p>

<p>jemand hat Dein Profil gesehen und möchte Dich für ein Event buchen. Hier sind einige Informationen über den Kunden :</p>

<center>
	<table>
		<tr><td>Name</td><td> : </td><td>{{ $userBookingRequest->user ? $userBookingRequest->user->full_name : '-' }}</td></tr>
		<tr><td>Email</td><td> : </td><td>{{ $userBookingRequest->user ? $userBookingRequest->user->email : "-" }}</td></tr>
		<tr><td>Telefon</td><td> : </td><td>{{ $userBookingRequest->user ? $userBookingRequest->user->phone_number : "-" }}</td></tr>
		<tr><td>Event</td><td> : </td><td>{{ $userBookingRequest->event_title }}</td></tr>
		<tr><td>Event-Information</td><td> : </td><td>{{ $userBookingRequest->event_detail }}</td></tr>
		<tr><td>Datum</td><td> : </td><td>{{ ($userBookingRequest->event_date_start ? $userBookingRequest->event_date_start->format('d.m.Y') : 'N/A') . ($userBookingRequest->event_date_end && ($userBookingRequest->event_date_start->format('d F Y') != $userBookingRequest->event_date_end->format('d F Y')) ? " - " . $userBookingRequest->event_date_end->format('d.m.Y') : '') }}</td></tr>
		<tr><td>Uhrzeit</td><td> : </td><td>{{ $userBookingRequest->event_start_time }} - {{ $userBookingRequest->event_end_time }} @if($duration = $userBookingRequest->time_duration) ({{$duration}}) @endif</td></tr>
	</table>
</center>

<p>Wir müssen Deine Verfügbarkeit so bald wie möglich bestätigen.</p>

<p>Bitte bestätige die Anfrage über:</p>

<center>
	<a href="{{ route('user.booking.request') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Accept</a>	
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="{{ route('user.booking.request') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Reject</a>	
</center>
<br>

<p>Wenn Du Hilfe brauchst, nutze bitte das Kontaktformular auf unserer Webseite.</p>

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
