@extends('emails.layout')

@section('content')
<center>
	<h2>THE BOOKING REQUEST IS CANCELLED</h2>
</center>

<p>Hello {{ $talentName }},</p>

<p>We are so sorry to inform you that the talent seeker has cancelled the booking request for some reason.</p>

<p>We really hope that you will get another exciting booking request soon!</p>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
