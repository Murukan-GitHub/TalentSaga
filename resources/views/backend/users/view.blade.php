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
@section('extra-navigation')
@include('backend.users.nav-extra')
@stop
@include('layouts.users_nav')
<br>
<div id="user-profile-1" class="user-profile row">
    <div class="col-xs-12 col-sm-3 center">
        <div>
            <span class="profile-picture">
                <img id="avatar" class="editable img-responsive" onError="this.onerror=null;this.src='http://upload.wikimedia.org/wikipedia/commons/d/d3/User_Circle.png';" src="{{ $user->picture_small_square }}" />
            </span>
            <div class="space-4"></div>
        </div>
        <div class="space-6"></div>
    </div>
    <div class="col-xs-12 col-sm-9">
        <table id="user" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td>{{$user->id}}</td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td>{{$user->username}}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>{{$user->name}}</td>
                </tr>
                <tr>
                    <td>Birth Date</td>
                    <td>{{ $user->birthdate == "0000-00-00" ? "N/A" : date("d F Y", strtotime($user->birthdate))}}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Alamat</b></td>
                </tr>
                <tr>
                    <td>&nbsp;Jalan</td>
                    <td>{{$user->address_street}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Kelurahan</td>
                    <td>{{$user->location && $user->location->kelurahan ? $user->location->kelurahan->nama_kel : "N/A"}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Kecamatan</td>
                    <td>{{$user->location && $user->location->kecamatan ? $user->location->kecamatan->nama_kec : "N/A"}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Kab/Kota</td>
                    <td>{{$user->location && $user->location->kota ? $user->location->kota->nama_kabkot : "N/A"}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Provinsi</td>
                    <td>{{$user->location && $user->location->provinsi ? $user->location->provinsi->nama_prov : "N/A"}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Negara</td>
                    <td>{{$user->address_country}}</td>
                </tr>
                <tr>
                    <td>&nbsp;Kode Pos</td>
                    <td>{{$user->address_zipcode}}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Contact</b></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{$user->email}}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td>{{$user->phone_number}}</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Others</b></td>
                </tr>
                <tr>
                    <td>Newsletter</td>
                    <td>
                        @if ($user->newsletter ===1)
                        <img width="5%" height="5%" src="{{asset('backend/img/tick1.png')}}" />
                        @else
                        <img width="5%" height="5%" src="{{asset('backend/img/tick0.png')}}" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Active</td>
                    <td>
                        @if ($user->isActive())
                        <img width="5%" height="5%" src="{{asset('backend/img/tick1.png')}}" />
                        @else
                        <img width="5%" height="5%" src="{{asset('backend/img/tick0.png')}}" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Registration Date</td>
                    <td>{{date("d F Y", strtotime($user->registration_date))}}</td>
                </tr>
                <tr>
                    <td>Last Visit</td>
                    <td>{{date("d F Y", strtotime($user->last_visit))}}</td>
                </tr>
            </tbody>
        </table>
    </div><!-- /span -->
</div>
<div class="row">
    <h2> {{$user->username}}'s Orders </h2>
    <table id="order" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <td><b>ID</b></td>
                <td><b>Courier</b></td>
                <td><b>Status</b></td>
                <td><b>Order Date</b></td>
                <td><b>Total Price (Rp)</b></td>
                <td><b>Payment Date</b></td>
                <td><b>Payment Method</b></td>
                <td><b>Payment Confirmed</b></td>
                <td><b>Menu</b></td>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{$order->id}}</td>
                <td>{{ App\SuitCommerce\Models\Courier::find($order->courier_id) ? App\SuitCommerce\Models\Courier::find($order->courier_id)->company_name : "N/A" }}</td>
                <td>{{ App\SuitCommerce\Models\AppConfig::getStatusName('order', $order->status) }}</td>
                <td>{{date("d F Y G:i:s", strtotime($order->order_date))}}</td>
                <td align="right">{{number_format($order->totalprice,0,',','.')}}</td>
                <td>{{$order->payment_date == null ? "N/A" : date("d F Y G:i:s", strtotime($order->payment_date))}}</td>
                <td>{{ ($order->payment_method ? App\SuitCommerce\Models\AppConfig::getStatusName('payment', $order->payment_method->type) : "N/A") }}</td>
                <td>{{ ($order->payment_confirmed == 1 ? "Yes" : "No")}}</td>
                <td><a href="{{url('admin/order/view/'.$order->id)}}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-zoom-in"></span></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    <h2> {{$user->username}}'s Product Reviews </h2>
    <table id="review" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <td><b>ID</b></td>
                <td><b>Reviewed Product</b></td>
                <td><b>Title</b></td>
                <td><b>Content</b></td>
                <td><b>Rating</b></td>
                <td><b>Flagged</b></td>
                <td><b>Menu</b></td>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{$review->id}}</td>
                <td>{{App\SuitCommerce\Models\Product::find($review->product_id)->name}}</td>
                <td>{{$review->title}}</td>
                <td>{{$review->content}}</td>
                <td align="center">
                    <span style="display:none;">{{$review->rating}}</span>
                    <div class='rating_bar'>
                        <div class='rating' style='width:{{$review->rating * 20}}%;'>
                        </div>
                    </div>
                </td>
                <td>{{$review->flag == 1 ? "Yes" : "No"}}</td>
                <td><a href="{{url('admin/user/deletereview/'.$review->id)}}" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="row">
    <h2> {{$user->username}}'s Seller Reviews </h2>
    <table id="review" class="table table-striped table-bordered table-hover table-condensed" cellspacing="0" width="100%">
        <thead>
            <tr>
                <td><b>ID</b></td>
                <td><b>Reviewer</b></td>
                <td><b>Title</b></td>
                <td><b>Content</b></td>
                <td><b>Rating</b></td>
                <td><b>Flag</b></td>
                <td><b>Menu</b></td>
            </tr>
        </thead>
        <tbody>
            @foreach($sellerreviews as $review)
            <tr>
                <td>{{$review->id}}</td>
                <td>{{@$review->user->getFullName()}}</td>
                <td>{{$review->title}}</td>
                <td>{{$review->content}}</td>
                <td align="center">
                    <span style="display:none;">{{$review->rating}}</span>
                    <div class='rating_bar'>
                        <div class='rating' style='width:{{$review->rating * 20}}%;'>
                        </div>
                    </div>
                </td>
                <td>{{$review->flag == 1 ? "Good" : "Bad"}}</td>
                <td><a href="{{url('admin/user/deletesellerreview/'.$review->id)}}" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('page_script')
<script>
    $(function() {
        $( "#order" ).datepicker({ dateFormat: 'yy-mm-dd', changeYear: true,changeMonth: true,defaultDate: new Date('1990-01-01')});
    });
</script>
@stop
