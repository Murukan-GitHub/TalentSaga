@extends('emails.layout')

@section('content')
<center>
	<h2>DEINE BUCHUNGSANFRAGE WURDE ANGENOMMEN</h2>
</center>

<p>Hallo {{ $talentSeekerName }},</p>

<p>herzlichen Glückwunsch! Der von Dir abgefragte Künstler hat Deine Anfrage akzeptiert. Wir freuen uns das Du über uns den passenden Künstler für Dein Event gefunden hast.</p>

<p>Hier die Buchungsdetails. Bitt kontaktiere den Künstler direkt für weitere Details.</p>

<center>
	<table>
		<tr><td>Category</td><td> : </td><td>{{ $userBookingRequest->talentUser && $userBookingRequest->talentUser->profile && $userBookingRequest->talentUser->profile->talentCategory ? $userBookingRequest->talentUser->profile->talentCategory->name : 'Any Category' }}</td></tr>
		<tr><td>Name</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->full_name : '-' }}</td></tr>
		<tr><td>Event</td><td> : </td><td>{{ $userBookingRequest->event_title }}</td></tr>
		<tr><td>Event-Information</td><td> : </td><td>{{ $userBookingRequest->event_detail }}</td></tr>
		<tr><td>Datum</td><td> : </td><td>{{ ($userBookingRequest->event_date_start ? $userBookingRequest->event_date_start->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : 'N/A') . ($userBookingRequest->event_date_end && ($userBookingRequest->event_date_start->format('d F Y') != $userBookingRequest->event_date_end->format('d F Y')) ? " - " . $userBookingRequest->event_date_end->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : '') }}</td></tr>
		<tr><td>Uhrzeit</td><td> : </td><td>{{ $userBookingRequest->event_start_time }} - {{ $userBookingRequest->event_end_time }} @if($duration = $userBookingRequest->time_duration) ({{$duration}}) @endif</td></tr>
		<tr><td>E-Mail</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->email : "-" }}</td></tr>
		<tr><td>Telefon</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->phone_number : "-" }}</td></tr>
	</table>
</center>

<p>Um Deine Buchung zu stornieren <a href="{{ route('user.booking.list') }}">kliche bitte hier</a></p>

<p>Wenn Du Deine Buchung ändern musst kontaktier uns bitte direkt über {{ settings('phone', '+00000') }} oder oder schick uns eine Mail an {{ settings('email', 'as@ansaworks.com') }}.</p>

<p>Wir hoffen Du bist mit unserem Service zufrieden.</p>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
