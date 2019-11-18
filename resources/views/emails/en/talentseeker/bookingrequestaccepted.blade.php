@extends('emails.layout')

@section('content')
<center>
	<h2>YOUR BOOKING REQUEST IS ACCEPTED</h2>
</center>

<p>Hello {{ $talentSeekerName }},</p>

<p>Congratulations! The artist you requested has accepted your request.</p>

<p>Thank you for choosing Talentsaga and we are glad that you found a perfect artist for your event. You can find your booking details below and contact the artist to know more about the details.</p>

<center>
	<table>
		<tr><td>Category</td><td> : </td><td>{{ $userBookingRequest->talentUser && $userBookingRequest->talentUser->profile && $userBookingRequest->talentUser->profile->talentCategory ? $userBookingRequest->talentUser->profile->talentCategory->name : 'Any Category' }}</td></tr>
		<tr><td>Name</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->full_name : '-' }}</td></tr>
		<tr><td>Event</td><td> : </td><td>{{ $userBookingRequest->event_title }}</td></tr>
		<tr><td>Event Details</td><td> : </td><td>{{ $userBookingRequest->event_detail }}</td></tr>
		<tr><td>Date</td><td> : </td><td>{{ ($userBookingRequest->event_date_start ? $userBookingRequest->event_date_start->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : 'N/A') . ($userBookingRequest->event_date_end && ($userBookingRequest->event_date_start->format('d F Y') != $userBookingRequest->event_date_end->format('d F Y')) ? " - " . $userBookingRequest->event_date_end->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'd F Y') : '') }}</td></tr>
		<tr><td>Time</td><td> : </td><td>{{ $userBookingRequest->event_start_time }} - {{ $userBookingRequest->event_end_time }} @if($duration = $userBookingRequest->time_duration) ({{$duration}}) @endif</td></tr>
		<tr><td>Email</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->email : "-" }}</td></tr>
		<tr><td>Phone</td><td> : </td><td>{{ $userBookingRequest->talentUser ? $userBookingRequest->talentUser->phone_number : "-" }}</td></tr>
	</table>
</center>

<p>To cancel your booking request, please <a href="{{ route('user.booking.list') }}">Click Here</a></p>

<p>If you need to change the event details, please contact us directly to {{ settings('phone', '+00000') }} or send an email to {{ settings('email', 'as@ansaworks.com') }}.</p>

<p>We hope you enjoy our services.</p>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
