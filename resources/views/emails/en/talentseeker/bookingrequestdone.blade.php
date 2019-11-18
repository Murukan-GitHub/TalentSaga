@extends('emails.layout')

@section('content')
<p>Hello {{ $talentSeekerName }},</p>

<p>We noticed that your amazing event has ended. We want to thank to you for using Talentsaga. We hope the artist you chose was perfect for your event.</p>

<p>Your feedback is important to us, for that reason we’d like to ask you for a quick favor by taking a few minutes to give the artist a review.</p>

<center>
	<a href="{{ route('user.booking.list') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Give a Review</a>	
</center>
<br>

<p>We hope you enjoy and continue using Talentsaga to find the perfect artists for your events.</p>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
