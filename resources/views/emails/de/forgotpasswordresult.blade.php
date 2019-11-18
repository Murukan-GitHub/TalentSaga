@extends('emails.layout')

@section('content')
<h2>Forgot Password Request</h2>
<p>
    Hallo,
</p>
<p>Your password had been reset : </p>

<b>{{ $newpassword }}</b>

<p>After you login, please change that password to make you more comfortable.</p>

@endsection
