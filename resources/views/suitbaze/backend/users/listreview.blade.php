@extends('backend.layouts.base')

@section('style-head')
<style type="text/css">
    .rating_bar {
        width: 80px;
        height: 16px;
        background: url({{asset('backend/img/star.png')}});
        background-repeat: repeat-x;
        background-position: 0 0;
        text-align: left;
    }
    .rating {
        height: 16px;
        background: url({{asset('backend/img/star.png')}});
        background-position: 0 -16px;
        background-repeat: repeat-x;
    }
</style>
@stop

@section('content')
   <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">Product Management</a></li>
            <li><a href="{{ route('backend.user.review') }}">User Reviews</a></li>
            <li>List</li>
        </ul>
    </nav>
    
    <h1 class="heading">Product Review List</h1>
    <hr />

    <table id="review" class="table table--zebra">
      <thead>
          <tr>
            <td><b>ID</b></td>
            <td><b>Product</b></td>
            <td><b>Reviewer</b></td>
            <td><b>Title</b></td>
            <td><b>Content</b></td>
            <td><b>Rating</b></td>
            <td><b>Flag</b></td>
            <td><b>Menu</b></td>
          </tr>
      </thead>
      <tbody>
        @foreach($reviews as $review)
          <tr>
            <td>{{$review->id}}</td>
            <td><a href="{{route('backend.product.view',['id'=>$review->product_id])}}" target="_BLANK">{{App\SuitCommerce\Models\Product::find($review->product_id)->name}}</a></td>
            <td><a href="{{route('backend.user.view',['id'=>$review->user_id])}}" target="_BLANK">{{App\SuitCommerce\Models\User::find($review->user_id)->name}}</a></td>
            <td>{{$review->title}}</td>
            <td>{{$review->content}}</td>
              <td align="center">
                  <span style="display:none;">{{$review->rating}}</span><div class='rating_bar'>
                      <div class='rating' style='width:{{$review->rating * 20}}%;'>
                      </div>
                  </div>
              </td>
            <td>
              @if ($review->flag == 1)
                  <img width="24px" src="{{asset('backend/img/tick1.png')}}" />
              @else
                  <img width="24px" src="{{asset('backend/img/tick0.png')}}" />
              @endif
            </td>
            <td>
              {!! Form::open(['route' => ['backend.user.deletereview', $review->id], 'method' => 'delete']) !!}
              {!! Form::button('<span class="fa fa-fw fa-remove"></span>', ['type' => 'submit', 'class' => 'btn btn--red', 'onclick' => 'return confirm("Are you sure to delete this review?")']) !!}
              {!! Form::close() !!}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

@stop

@section('page_script')
<script>
  $(document).ready(function() {
    $('#review').dataTable({
        "scrollX": true,
        "searchHighlight": true
    });
  });
</script>
@stop
