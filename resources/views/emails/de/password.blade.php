@extends('emails.layout')

@section('content')
<center>
    <h2>PASSWORT VERGESSEN / ÄNDERN</h2>
</center>

<p>Hallo {{ $user->full_name }},</p>

<p>hast Du Dein Passwort vergessen oder möchtest es ändern? Wir haben gerade eine Anfrage erhalten, dass Du Dein Passwort vergessen hast oder ändern möchtest.</p>

<p>Über den folgenden Link kannst Du ein neues Passwort anfordern:</p>

<center>
    <a href="{{ $link = route('frontend.user.forgetpassword.confirm', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]) }}" style="display: inline-block; color: #fff; background-color: #6FB6B8; line-height: 34px; padding: 0 20px; text-decoration: none;">Set a New Password</a>    
</center>
<br>

<p>Falls die Anfrage nicht von Dir kam, mach Dir keine Sorgen. Dein Passwort wurde nicht geändert und ist sicher. Du kannst Diese Mail ignorieren.</p>

<p>
Viele Grüße,
<br><br>
Talentsaga Team
</p>
@endsection
