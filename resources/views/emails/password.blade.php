@extends('emails.layout')

@section('content')
<center>
    <h2>FORGOT/CHANGE PASSWORD</h2>
</center>

<p>Hello {{ $user->full_name }},</p>

<p>Need to reset/change your password ? We recently received a request to reset/change your password Talentsaga account. 
If you need it, don’t worry! Let’s get you a new one by click on the button!</p>

<center>
    <a href="{{ $link = route('frontend.user.forgetpassword.confirm', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]) }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Set a New Password</a>    
</center>
<br>

<p>If you didn’t ask to reset/change your password, don’t worry! Your password is still the same and safe. You can also ignore this email.</p>

<p>
Cheers,
<br><br>
Talentsaga Team
</p>
@endsection
