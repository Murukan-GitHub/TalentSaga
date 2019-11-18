@extends('backend.layouts.base')

@section('content')
<!-- BEGIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN Portlet PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green-sharp">
                    {{-- <i class="fa fa-black-tie font-green-sharp"></i>&nbsp; --}}
                    <span class="caption-subject bold uppercase">{{ $baseObject->getFormattedValue() }}</span>
                    <span class="caption-helper">{{ $title or '' }}</span>
                </div>
                <div class="actions">
                    @if( Route::has($routeBaseName . '.create') )
                    {!! nav_menu(route($routeBaseName . ".create"), '', 'icon-plus', 'btn btn-circle btn-icon-only btn-default', 'Create New') !!}
                    @endif
                    @if( Route::has($routeBaseName . '.edit') )
                    {!! nav_menu(route($routeBaseName . ".edit", ['id'=>$baseObject->id]), '', 'icon-pencil', 'btn btn-circle btn-icon-only btn-default', 'Edit') !!}
                    @endif
                    @if( Route::has($routeBaseName . '.destroy') )
                    {!! post_nav_menu(route($routeBaseName . '.destroy', ['id' => $baseObject->id]), '', csrf_token(), 'Are you sure?', 'icon-trash', 'btn btn-circle btn-icon-only btn-default', 'Delete') !!}
                    @endif
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" title="Fullscreen" href="javascript:;"> </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-lg-3 form-horizontal pull-right">
                        @if ($baseObject->timestamps)
                        <div class="note note-warning pull-limit-bottom">
                            <p>
                                <em>
                                    Last edited at {!! $baseObject->renderAttribute('updated_at') !!}
                                </em>
                            </p>
                        </div>
                        <div class="note note-warning pull-limit-bottom">
                            <p>
                                <em>
                                    Created at {!! $baseObject->renderAttribute('created_at') !!}
                                </em>
                            </p>
                            <hr>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-9 form-horizontal form-bordered form-row-stripped">
                        @foreach($baseObject->getBufferedAttributeSettings() as $key => $attribute)
                        @if(!in_array($key, $baseObject->getHidden()) && (!$baseObject->timestamps || !in_array($key, $baseObject->timestampFields)))
                        <div class="form-group clearfix">
                            <label class="control-label col-md-4">{{ $attribute['label'] }}</label>
                            <div class="col-md-8">
                                <div class="form-control-static">{!! $baseObject->renderAttribute($key)  !!}</div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT -->
<!-- BEGIN SPECIFY INLINE -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN TAB PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-share font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Related Data</span>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#profile" data-toggle="tab"> User Profile </a>
                    </li>
                    <li>
                        <a href="#userexpertise" data-toggle="tab"> Expertises </a>
                    </li>
                    <li>
                        <a href="#useravailabilityarea" data-toggle="tab"> Availability Area </a>
                    </li>
                    <li>
                        <a href="#priceinclusion" data-toggle="tab"> Price Inclusion </a>
                    </li>
                    <li>
                        <a href="#portofolio" data-toggle="tab"> Portofolio </a>
                    </li>
                    <li>
                        <a href="#gallery" data-toggle="tab"> Gallery </a>
                    </li>
                    <li>
                        <a href="#story" data-toggle="tab"> Story </a>
                    </li>
                </ul>
            </div>
            <div class="portlet-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="profile">
                        <br><br>
                        @if($userProfile)
                            <br>
                            <div class="text-right">
                                {!! nav_menu(route("backend.userprofile.edit", ['id'=>$userProfile->id]), 'Update', 'fa fa-sw fa-pencil', 'btn btn-sm green btn-outline active') !!}
                            </div>
                            <br><br>
                            <table id="userProfile-detail" class="table table--zebra">
                                <tbody>
                                @foreach($userProfile->getBufferedAttributeSettings() as $key=>$val)
                                    @if($key != 'user_id')
                                    <tr>
                                        <td><b>{{ $val['label'] }}</b></td>
                                        <td>{!! $userProfile->renderAttribute($key) !!}</td>
                                    </tr>
                                    @endif
                                @endforeach
                              </tbody>
                            </table>
                        @else
                            <br>
                            <div class="text-right">
                                {!! nav_menu(route("backend.userprofile.create", ['user_id'=>$baseObject->id]), 'Update', 'fa fa-sw fa-pencil', 'btn btn-sm green btn-outline active') !!}
                            </div>
                            <br><br>
                            <center>
                                <i>( no related data)</i>
                            </center>
                        @endif
                    </div>
                    <div class="tab-pane" id="userexpertise">
                    @if($baseObject->profile)
                        <br>
                        <div class="text-right">
                            <a class="btn btn-sm green btn-outline active" href="{{ route('backend.userexpertise.create')."?user_profile_id=".$baseObject->profile->id }}">
                                <span class="fa fa-fw fa-plus"></span>
                                Add New
                            </a>
                        </div>
                        <br><br>
                        <table id="userexpertisetable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.userexpertise.index.json') . "?_token=" . csrf_token() . "&user_profile_id=" . $baseObject->profile->id }}">
                          <thead>
                              <tr>
                                <?php $userexpertise = new App\Models\UserProfileExpertise(); ?>
                                @foreach($userexpertise->getBufferedAttributeSettings() as $key=>$val)
                                  @if( $key != 'user_profile_id' && $val['visible'] )
                                    <td><b>{{ $val['label'] }}</b></td>
                                  @endif
                                @endforeach
                                <td><b>Menu</b></td>
                              </tr>
                          </thead>
                        </table>
                    @else
                        <i>(user profile not set up yet)</i>
                    @endif
                    </div>
                    <div class="tab-pane" id="useravailabilityarea">
                        <br>
                        <div class="text-right">
                            <a class="btn btn-sm green btn-outline active" href="{{ route('backend.useravailabilityarea.create')."?user_id=".$baseObject->id }}">
                                <span class="fa fa-fw fa-plus"></span>
                                Add New
                            </a>
                        </div>
                        <br><br>
                        <table id="availabilityareatable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.useravailabilityarea.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                          <thead>
                              <tr>
                                <?php $availabilityAreaObj = new App\Models\UserAvailabilityArea(); ?>
                                @foreach($availabilityAreaObj->getBufferedAttributeSettings() as $key=>$val)
                                  @if( $key != 'user_id' && $val['visible'] )
                                    <td><b>{{ $val['label'] }}</b></td>
                                  @endif
                                @endforeach
                                <td><b>Menu</b></td>
                              </tr>
                          </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="priceinclusion">
                        <br>
                        <div class="text-right">
                            <a class="btn btn-sm green btn-outline active" href="{{ route('backend.userpriceinclusion.create')."?user_id=".$baseObject->id }}">
                                <span class="fa fa-fw fa-plus"></span>
                                Add New
                            </a>
                        </div>
                        <br><br>
                        <table id="priceinclusiontable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.userpriceinclusion.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                          <thead>
                              <tr>
                                <?php $priceInclusionObj = new App\Models\UserPriceInclusion(); ?>
                                @foreach($priceInclusionObj->getBufferedAttributeSettings() as $key=>$val)
                                  @if( $key != 'user_id' && $val['visible'] )
                                    <td><b>{{ $val['label'] }}</b></td>
                                  @endif
                                @endforeach
                                <td><b>Menu</b></td>
                              </tr>
                          </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="portofolio">
                        <br>
                        <div class="text-right">
                            <a class="btn btn-sm green btn-outline active" href="{{ route('backend.userportofolio.create')."?user_id=".$baseObject->id }}">
                                <span class="fa fa-fw fa-plus"></span>
                                Add New
                            </a>
                        </div>
                        <br><br>
                        <table id="userportofoliotable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.userportofolio.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                          <thead>
                              <tr>
                                <?php $userPortofolioObj = new App\Models\UserPortofolio(); ?>
                                @foreach($userPortofolioObj->getBufferedAttributeSettings() as $key=>$val)
                                  @if( $key != 'user_id' && $val['visible'] )
                                    <td><b>{{ $val['label'] }}</b></td>
                                  @endif
                                @endforeach
                                <td><b>Menu</b></td>
                              </tr>
                          </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="gallery">
                        <br>
                        <div class="text-right">
                            <a class="btn btn-sm green btn-outline active" href="{{ route('backend.usergallery.create')."?user_id=".$baseObject->id }}">
                                <span class="fa fa-fw fa-plus"></span>
                                Add New
                            </a>
                        </div>
                        <br><br>
                        <table id="usergallerytable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.usergallery.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                          <thead>
                              <tr>
                                <?php $userGalleryObj = new App\Models\UserGallery(); ?>
                                @foreach($userGalleryObj->getBufferedAttributeSettings() as $key=>$val)
                                  @if( $key != 'user_id' && $val['visible'] )
                                    <td><b>{{ $val['label'] }}</b></td>
                                  @endif
                                @endforeach
                                <td><b>Menu</b></td>
                              </tr>
                          </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="story">
                        <br>
                        <div class="text-right">
                            <a class="btn btn-sm green btn-outline active" href="{{ route('backend.userstory.create')."?user_id=".$baseObject->id }}">
                                <span class="fa fa-fw fa-plus"></span>
                                Add New
                            </a>
                        </div>
                        <br><br>
                        <table id="userstorytable" class="table table--zebra"  data-enhance-ajax-table="{{ route('backend.userstory.index.json') . "?_token=" . csrf_token() . "&user_id=" . $baseObject->id }}">
                          <thead>
                              <tr>
                                <?php $userStoryObj = new App\Models\UserStory(); ?>
                                @foreach($userStoryObj->getBufferedAttributeSettings() as $key=>$val)
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
        <!-- END TAB PORTLET-->
    </div>
</div>
<!-- END SPECIFY INLINE -->
@stop
