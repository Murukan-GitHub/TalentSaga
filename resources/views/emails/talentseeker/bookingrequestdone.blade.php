@extends('emails.layout')

@section('content')
<p>Hello {{ $talentSeekerName }},</p>

<p>We remember that your amazing event has ended. Now we want to thank to you for using Talentsaga and enjoying our services. We hope the talent you chose was perfect for your event.</p>

<p>Your feedback is important to us, for that reason we’d like to ask you for a quick favor by taking a few minutes to give us a review about the talent and about our services. Please click the button below to give a review!</p>

<center>
	<a href="{{ route('user.booking.list') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Give a Review</a>	
</center>
<br>

<p>We hope you enjoy and keep using Talentsaga to find the perfect talent for your events.</p>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
