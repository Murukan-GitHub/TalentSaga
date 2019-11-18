@extends('backend.layouts.base')

@section('page_title')
@endsection

@section('content')
{!! Form::open( ['route' => 'backend.settings.save', 'method'=>'post', 'id'=>'form_setting']) !!}
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title">Settings
    <!-- <small>subtitle</small> -->
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <input class="btn blue btn-sm" type="submit" value="Save"/>
        </div>
    </div>
</h3>
<!-- END PAGE TITLE-->
<!-- BEGIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-haze">
                    <i class="fa fa-building-o font-green-haze"></i>&nbsp;
                    <span class="caption-subject bold uppercase">Company Identity</span>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group form-md-line-input">
                        <input type="text" class="form-control" id="legalname" name="settings[legalname]" value="{{ settings('legalname') }}" placeholder="Enter company name" />
                        <label for="legalname">Company Name</label>
                        <span class="help-block">Typing company name...</span>
                    </div>
                    <div class="form-group form-md-line-input">
                        <input type="text" class="form-control" id="brandname" name="settings[brandname]" value="{{ settings('brandname') }}" placeholder="Enter your name" />
                        <label for="brandname">Brand Name</label>
                        <span class="help-block">Typing brand name...</span>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label">Enable Landing</label>
                        <div class="col-md-10">
                            {!! Form::checkbox('settings[enable_landing]', 'true', settings('enable_landing', false), ['id' => 'enable_landing']) !!}
                            Yes
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FORM PORTLET-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-haze">
                    <i class="icon-link font-green-haze"></i>&nbsp;
                    <span class="caption-subject bold uppercase">Social Media</span>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="facebook" name="settings[facebook]" value="{{ settings('facebook') }}" placeholder="Enter facebook page" />
                            <label for="facebook">Facebook Page</label>
                            <span class="help-block">Typing facebook url...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-facebook"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="twitter" name="settings[twitter]" value="{{ settings('twitter') }}" placeholder="Enter twitter page" />
                            <label for="twitter">Twitter Page</label>
                            <span class="help-block">Typing twitter url...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-twitter"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="youtube" name="settings[youtube]" value="{{ settings('youtube') }}" placeholder="Enter youtube page" />
                            <label for="youtube">Youtube Page</label>
                            <span class="help-block">Typing youtube url...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-youtube"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="instagram" name="settings[instagram]" value="{{ settings('instagram') }}" placeholder="Enter instagram page" />
                            <label for="instagram">Instagram Page</label>
                            <span class="help-block">Typing instagram url...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-instagram"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FORM PORTLET-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-haze">
                    <i class="icon-call-end font-green-haze"></i>&nbsp;
                    <span class="caption-subject bold uppercase">Contact Info</span>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="email" name="settings[email]" value="{{ settings('email', 'as@ansaworks.com') }}" placeholder="Enter email address" />
                            <label for="email">Email Address</label>
                            <span class="help-block">Typing email address...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>



                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="email" name="settings[email]" value="{{ settings('email', 'as@ansaworks.com') }}" placeholder="Enter email address" />
                            <label for="email">Email Address</label>
                            <span class="help-block">Typing email address...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="phone" name="settings[phone]" value="{{ settings('phone') }}" placeholder="Enter phone number" />
                            <label for="phone">Phone Number (Call Center)</label>
                            <span class="help-block">Typing phone number...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div class="input-group">
                            <input type="text" class="form-control" id="fax" name="settings[fax]" value="{{ settings('fax') }}" placeholder="Enter fax number" />
                            <label for="fax">Fax Number </label>
                            <span class="help-block">Typing fax number...</span>
                            <span class="input-group-addon">
                                <i class="fa fa-fax"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <textarea class="form-control" id="address" name="settings[address]" rows="3" placeholder="Enter address line 1">{{ settings('address') }}</textarea>
                        <label for="address">Address Line 1 </label>
                        <span class="help-block">Typing address line 1...</span>
                    </div>
                    <div class="form-group form-md-line-input">
                        <textarea class="form-control" id="address2" name="settings[address2]" rows="3" placeholder="Enter address line 2">{{ settings('address2') }}</textarea>
                        <label for="address2">Address Line 2 </label>
                        <span class="help-block">Typing address line 2...</span>
                    </div>
                    <div class="form-group form-md-line-input">
                        <input class="form-control" id="areaSearch" type="text" value="">
                        <label for="areaSearch">Location on Map</label>
                    </div>
                    <div class="form-group form-md-line-input">
                        <div id="map" style="width: 100%; height: 400px;"></div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <input class="form-control" id="lat" type="text" name="settings[latitude]" value="" readonly>
                        <label for="lat">Latitude</label>
                    </div>
                    <div class="form-group form-md-line-input">
                        <input class="form-control" id="lng" type="text" name="settings[longitude]" value="" readonly>
                        <label for="lng">Longitude</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FORM PORTLET-->
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-haze">
                    <i class="icon-call-end font-green-haze"></i>&nbsp;
                    <span class="caption-subject bold uppercase">Notification</span>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label">Newsletter</label>
                        <div class="col-md-10">
                            Send daily at
                            <input maxlength="2" style="width: 30px;" id="newsletter_send_hour" type="text" name="settings[newsletter_send_hour]" value="{{ settings('newsletter_send_hour', '07') }}" placeholder="07">
                            <input maxlength="2" style="width: 30px;" id="newsletter_send_minute" type="text" name="settings[newsletter_send_minute]" value="{{ settings('newsletter_send_minute', '00') }}" placeholder="00"> WIB
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FORM PORTLET-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green-haze">
                    <i class="fa fa-building-o font-green-haze"></i>&nbsp;
                    <span class="caption-subject bold uppercase">Other Settings</span>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group form-md-line-input">
                        <input type="number" class="form-control" id="maxProfileDescLength" name="settings[maxProfileDescLength]" value="{{ settings('maxProfileDescLength', 200) }}" placeholder="Enter max length for talent profile description ..." />
                        <label for="maxProfileDescLength">Max Profile Description Length</label>
                        <span class="help-block">Typing max length...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FORM PORTLET-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <input class="btn blue" type="submit" value="Save"/>
    </div>
</div>
<!-- END CONTENT -->
{!! Form::close() !!}
@stop

@section('page_script')
    <script type="text/javascript">
        function initMap() {
            $('#map').locationpicker({
                location: {latitude: {{ empty(settings('latitude')) ? "-6.194288191779069" : settings('latitude')  }}, longitude: {{ empty(settings('longitude')) ? "106.92647360610965" : settings('longitude')  }} },
                radius: 200,
                inputBinding: {
                    latitudeInput: $('#lat'),
                    longitudeInput: $('#lng'),
                    locationNameInput: $('#areaSearch')
                },
                enableAutocomplete: true
            });
            $('#areaSearch').on('keypress', function(e) {
                var code = e.keyCode || e.which;
                if(code == 13) return false;
            });
        }
    </script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=places&key={{ env('GOOGLE_API_KEY','AIzaSyDzbTbiPooayXM1WieJaKWJHKXKm0k_aLM') }}&callback=initMap" async defer></script>
@stop
