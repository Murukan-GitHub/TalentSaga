@extends('emails.layout')

@section('content')
<center>
	<h2>RE-ACTIVATE YOUR TALENTSAGA ACCOUNT</h2>
</center>

<p>Hallo {{ $name }},</p>

<p>Thank you for joining Talentsaga! Previously you have requested to re-activate your Talentsaga account. Please click the button below to re-activate your account.</p>
<center>
	<a href="{{ $activationLink or '' }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Click here to re-activate</a>	
</center>

<p>If you need some help, please send it to Contact Us page on our website.</p>

<center>
	<a href="{{ route('frontend.home.contactus') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Contact Us Link</a>	
</center>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
