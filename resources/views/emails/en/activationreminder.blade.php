@extends('emails.layout')

@section('content')
<center>
	<h2>ACTIVATION REMINDER</h2>
</center>

<p>Hello {{ $name }},</p>

<p>Do you forget something in your inbox ? This is a friendly reminder to remind you that your Talentsaga Account hasn’t verified yet. It’s been 14 days after you create a Talentsaga account and we haven’t got your account activated.</p>

<p>So, if you’ve just forgotten to activate your account, then you can still do it here by click on the button below :</p>

<center>
	<a href="{{ $activationLink or '' }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Click here to verify</a>	
</center>

<p>Please ensure you validate your email registration as soon as possible to avoid the suspension of your account.</p>

<p>If you need some help, please send it to Contact Us page on our website.</p>

<center>
	<a href="{{ route('frontend.home.contactus') }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Contact Us Link</a>	
</center>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
