@extends('backend.layouts.base')

@section('content')

    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">User Management</a></li>
            <li><a href="{{ route($routeBaseName . '.index') }}">{{ $baseObject->getLabel() }}</a></li>
            <li>Detail</li>
        </ul>
    </nav>
    
    <h1 class="heading">Detail of "{{ $baseObject->getFormattedValue() }}"</h1>
    <hr />

    <div class="block text-right">
        @if( Route::has($routeBaseName . '.show') )
            {!! nav_menu(route($routeBaseName . ".edit", ['id'=>$baseObject->id]), 'Update', 'fa-pencil') !!}
        @endif
        @if( Route::has($routeBaseName . '.destroy') )
            {!! post_nav_menu(route($routeBaseName . '.destroy', ['id' => $baseObject->id]), 'Delete', csrf_token(), 'Are you sure?', 'fa-remove') !!}
        @endif
    </div>

    <div class="bzg">
        <div class="bzg_c" data-col="l12">
            <table id="{{ class_basename($baseObject) }}_detail" class="table table--zebra">   
                <tbody>
                @foreach($baseObject->getBufferedAttributeSettings() as $key=>$val)
                    @if($key != 'password')
                    <tr>
                        <td><b>{{ $val['label'] }}</b></td>
                        <td>{!! $baseObject->renderAttribute($key) !!}</td>
                    </tr>
                    @endif
                @endforeach
              </tbody>
            </table>
        </div>
    </div>

    <div class="bzg">
        <div class="bzg_c" data-col="l12">
            <div class="dashboard-tab dashboard-tab--white block">
                <div class="dashboard-tab__navs">
                    <a class="dashboard-tab-nav text-uppercase is-active" href="#addresses">Addresses</a>
                    <a class="dashboard-tab-nav text-uppercase" href="#bankaccounts">Bank Accounts</a>
                    <a class="dashboard-tab-nav text-uppercase" href="#resselled">Reselled Products</a>
                    <a class="dashboard-tab-nav text-uppercase" href="#commissions">Commission Applications</a>
                    <a class="dashboard-tab-nav text-uppercase" href="#couriers">Available Couriers</a>
                </div>
                <div class="dashboard-tab__content is-active" id="addresses">
                    <div class="bzg">
                        <div class="bzg_c" data-col="l12">
                            <br><br>
                            <table id="addressestable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.useraddress.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                              <thead>
                                  <tr>
                                    <?php $addressObj = new App\SuitCommerce\Models\Address(); ?>
                                    @foreach($addressObj->getBufferedAttributeSettings() as $key=>$val)
                                      @if( $key != 'user_id' && $val['visible'] )
                                        <td><b>{{ $val['label'] }}</b></td>
                                      @endif
                                    @endforeach
                                    <td><b>Menu</b></td>
                                  </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="dashboard-tab__content" id="bankaccounts">
                    <div class="bzg">
                        <div class="bzg_c" data-col="l12">
                            <br><br>
                            <table id="bankaccountstable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.bankaccounts.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                              <thead>
                                  <tr>
                                    @foreach((new App\SuitCommerce\Models\BankAccount())->getBufferedAttributeSettings() as $key=>$val)
                                      @if( $key != 'user_id' && $val['visible'] )
                                        <td><b>{{ $val['label'] }}</b></td>
                                      @endif
                                    @endforeach
                                    <td><b>Menu</b></td>
                                  </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="dashboard-tab__content" id="resselled">
                    <div class="bzg">
                        <div class="bzg_c" data-col="l12">
                            <br><br>
                            <table id="resselledtable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.reselledproducts.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                              <thead>
                                  <tr>
                                    @foreach((new App\SuitCommerce\Models\ProductReseller())->getBufferedAttributeSettings() as $key=>$val)
                                      @if( $key != 'user_id' && $val['visible'] )
                                        <td><b>{{ $val['label'] }}</b></td>
                                      @endif
                                    @endforeach
                                    <td><b>Menu</b></td>
                                  </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="dashboard-tab__content" id="commissions">
                    <div class="bzg">
                        <div class="bzg_c" data-col="l12">
                            <br><br>
                            <table id="commissionstable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.usercommission.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                              <thead>
                                  <tr>
                                    @foreach((new App\SuitCommerce\Models\CommissionApplication())->getBufferedAttributeSettings() as $key=>$val)
                                      @if( $key != 'user_id' && $val['visible'] )
                                        <td><b>{{ $val['label'] }}</b></td>
                                      @endif
                                    @endforeach
                                    <td><b>Menu</b></td>
                                  </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="dashboard-tab__content" id="couriers">
                    <div class="bzg">
                        <div class="bzg_c" data-col="l12">
                            <br><br>
                            <table id="courierstable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.availablecouriers.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                              <thead>
                                  <tr>
                                    @foreach((new App\SuitCommerce\Models\AvailableCourier())->getBufferedAttributeSettings() as $key=>$val)
                                      @if( $key != 'user_id' && $val['visible'] )
                                        <td><b>{{ $val['label'] }}</b></td>
                                      @endif
                                    @endforeach
                                    <td><b>Menu</b></td>
                                  </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
