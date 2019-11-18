@extends('emails.layout')

@section('content')
<center>
	<h2>YOUR BOOKING REQUEST IS REJECTED</h2>
</center>

<p>Hello {{ $talentSeekerName }},</p>

<p>We want to inform you that the talent you requested to be booked has rejected your request.  Thank you for choosing Talentsaga to fulfill your need and please don’t be disappointed! You still can search and book another talent that suits you best.</p>

<p>Find more talent that will be perfect for your event by click on the button below :</p>

<center>
	<a href="{{ route('talent.search') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Talent List</a>	
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
