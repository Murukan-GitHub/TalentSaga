@extends('emails.layout')

@section('content')
<center>
    <h2>PASSWORD CHANGED</h2>
</center>

<p>Hallo {{ $name }},</p>

<p>We just want to inform you that your password recently has changed!</p>

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
