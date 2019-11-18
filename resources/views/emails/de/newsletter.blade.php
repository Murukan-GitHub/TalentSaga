@extends('emails.layout')

@section('content')
<?php
    list($day, $date, $month, $year, $time) = explode('-', Carbon\Carbon::now()->format('l-j-F-Y-H:i'));
?>
<h3>Newsletter {{trans('datetime.'.strtolower($day)).', '.$date.' '.trans('datetime.'.strtolower($month)). ' ' .$year. ', '.$time}}</h3>
@if(!empty($newsletter->banner_top_image))
<div class="banner_top">
<a href="{{$newsletter->banner_top_url}}" target="_blank"><img src="{{(isset($previewOnly) && $previewOnly) ? $newsletter->banner_top_image_medium_cover : $message->embed(public_path(str_replace(url('/'), '', $newsletter->banner_top_image_medium_cover)))}}" alt="{{$newsletter->banner_top_title}}" width="100%" style="max-width: 100%; width: 100%;"></a>
</div>
@endif

<div style="margin: 50px 0;">
{!! htmlspecialchars_decode($newsletter->email_body) !!}
</div>

@if(!empty($newsletter->banner_bottom_image))
<div class="banner_bottom">
<a href="{{$newsletter->banner_bottom_url}}" target="_blank"><img src="{{(isset($previewOnly) && $previewOnly) ? $newsletter->banner_bottom_image_medium_cover : $message->embed(public_path(str_replace(url('/'), '', $newsletter->banner_bottom_image_medium_cover)))}}" alt="{{$newsletter->banner_bottom_title}}" width="100%" style="max-width: 100%; width: 100%;"></a>
</div>
@endif
@endsection
