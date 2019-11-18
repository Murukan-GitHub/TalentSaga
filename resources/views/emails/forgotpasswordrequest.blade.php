@extends('emails.layout')

@section('content')
<center>
	<h2>FORGOT/CHANGE PASSWORD</h2>
</center>

<p>Hello {{ $name }},</p>

<p>Need to reset/change your password ? We recently received a request to reset/change your password Talentsaga account. 
If you need it, don’t worry! Let’s get you a new one by click on the button!</p>

<b>{{ $code }}</b>[Set a New Password]

<p>If you didn’t ask to reset/change your password, don’t worry! Your password is still the same and safe. You can also ignore this email.</p>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
