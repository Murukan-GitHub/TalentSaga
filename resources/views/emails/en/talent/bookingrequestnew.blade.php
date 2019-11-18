@extends('emails.layout')

@section('content')
<center>
	<h2>SOMEONE IS REQUESTING TO BOOK YOUR SERVICE</h2>
</center>

<p>Hello {{ $talentName }},</p>

<p>We recently notified that a Talent Seeker has seen your profile and wants to book your service for their event. Here is some information about the Talent Seeker :</p>

<center>
	<table>
		<tr><td>Name</td><td> : </td><td>{{ $userBookingRequest->user ? $userBookingRequest->user->full_name : '-' }}</td></tr>
		<tr><td>Email</td><td> : </td><td>{{ $userBookingRequest->user ? $userBookingRequest->user->email : "-" }}</td></tr>
		<tr><td>Phone</td><td> : </td><td>{{ $userBookingRequest->user ? $userBookingRequest->user->phone_number : "-" }}</td></tr>
		<tr><td>Event</td><td> : </td><td>{{ $userBookingRequest->event_title }}</td></tr>
		<tr><td>Event Details</td><td> : </td><td>{{ $userBookingRequest->event_detail }}</td></tr>
		<tr><td>Date</td><td> : </td><td>{{ ($userBookingRequest->event_date_start ? $userBookingRequest->event_date_start->format('d F Y') : 'N/A') . ($userBookingRequest->event_date_end && ($userBookingRequest->event_date_start->format('d F Y') != $userBookingRequest->event_date_end->format('d F Y')) ? " - " . $userBookingRequest->event_date_end->format('d F Y') : '') }}</td></tr>
		<tr><td>Time</td><td> : </td><td>{{ $userBookingRequest->event_start_time }} - {{ $userBookingRequest->event_end_time }} @if($duration = $userBookingRequest->time_duration) ({{$duration}}) @endif</td></tr>
	</table>
</center>

<p>We need you to confirm your availability as soon as possible.</p>

<p>Let us know if this event match/do not match with you by click on one of the buttons below.</p>

<center>
	<a href="{{ route('user.booking.request') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Accept</a>	
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="{{ route('user.booking.request') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Reject</a>	
</center>
<br>

<p>If you need some help, please send it to Contact Us page on our website.</p>

<center>
	<a href="{{ route('frontend.home.contactus') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Contact Us Link</a>	
</center>
<br>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
