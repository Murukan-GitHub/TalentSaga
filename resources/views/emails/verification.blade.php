@extends('emails.layout')

@section('content')
<h2>Verify your email...</h2>
<p>
    Hi, {{ $name }}
</p>
<p>We need to make sure that your email is valid. Please type code below :</p>

<span style="display: inline-block; color: #fff; background-color: #ff742c; line-height: 34px; padding: 0 20px; text-decoration: none;">{{$code}}</span>

<p>Cheers,</p>

@endsection
