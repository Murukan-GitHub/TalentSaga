@extends('emails.layout')

@section('content')
<center>
	<h2>YOUR BOOKING REQUEST IS ACCEPTED</h2>
</center>

<p>Hello {{ $talentSeekerName }},</p>

<p>We want to inform you that the talent you requested to be booked has accepted your request.  Thank you for choosing Talentsaga to fulfill your needs and we are glad that you find a perfect talent for your event. You can find your booking details below and contact the talent to inform and know more about the details.</p>

<center>
	<table>
		<tr><td>Profession</td><td> : </td><td>{{ $userBookingRequest->talentUser && $userBookingRequest->talentUser->profile && $userBookingRequest->talentUser->profile->talent_profession ? $userBookingRequest->talentUser->profile->talent_profession : 'Any Profession' }}</td></tr>
		<tr><td>Name</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->full_name : '-' }}</td></tr>
		<tr><td>Location</td><td> : </td><td>{{ $userBookingRequest->talentUser && $userBookingRequest->talentUser->profile && $userBookingRequest->talentUser->profile->city ? $userBookingRequest->talentUser->profile->city : '-' }}</td></tr>
		<tr><td>Event</td><td> : </td><td>{{ $userBookingRequest->event_title }}</td></tr>
		<tr><td>Event Details</td><td> : </td><td>{{ $userBookingRequest->event_detail }}</td></tr>
		<tr><td>Date</td><td> : </td><td>{{ ($userBookingRequest->event_date_start ? $userBookingRequest->event_date_start->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : 'N/A') . ($userBookingRequest->event_date_end && ($userBookingRequest->event_date_start->format('d F Y') != $userBookingRequest->event_date_end->format('d F Y')) ? " - " . $userBookingRequest->event_date_end->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : '') }}</td></tr>
		<tr><td>Time</td><td> : </td><td>{{ $userBookingRequest->event_start_time }} - {{ $userBookingRequest->event_end_time }} @if($duration = $userBookingRequest->time_duration) ({{$duration}}) @endif</td></tr>
		<tr><td>Phone</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->phone_number : "-" }}</td></tr>
		<tr><td>Email</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->email : "-" }}</td></tr>
	</table>
</center>

<p>To cancel your booking request, please <a href="{{ route('user.booking.list') }}">Click Here</a> and if you need to change the event details, please contact us directly to {{ settings('phone', '+00000') }} or send an email to {{ settings('email', 'as@ansaworks.com') }}.</p>

<p>We hope you enjoy our services.</p>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
