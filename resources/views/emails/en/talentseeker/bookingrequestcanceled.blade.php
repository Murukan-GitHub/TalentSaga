@extends('emails.layout')

@section('content')
<center>
	<h2>THE ARTIST HAS CANCELLED YOUR BOOKING</h2>
</center>

<p>Hello {{ $talentSeekerName }},</p>

<p>We are so sorry to inform you that the artist has cancelled your booking request for some reason.</p>

<p>But, don’t be disappointed! You can still search and book another artist that suits you best. Find more artists that will be perfect for your event by click on the button below :</p>

<center>
	<a href="{{ route('talent.search') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Talent List</a>	
</center>
<br>

<p>If you need some help, please use our Contact Us page on our website.</p>

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
