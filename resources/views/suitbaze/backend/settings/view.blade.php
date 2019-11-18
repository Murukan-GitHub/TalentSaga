@extends('backend.layouts.base')

@section('content')
    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">Site Management</a></li>
            <li>Settings</li>
        </ul>
    </nav>

    {!! Form::open( ['route' => 'backend.settings.save', 'method'=>'post', 'id'=>'form_setting']) !!}

        <h3>Company Identity</h3>
        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Company Name</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="legalname" type="text" name="settings[legalname]" value="{{ settings('legalname') }}">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Brand Name</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="brandname" type="text" name="settings[brandname]" value="{{ settings('brandname') }}">
                </div>
            </div>
        </div>

        <h3>Social Media</h3>
        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Facebook Page</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="facebook" type="text" name="settings[facebook]" value="{{ settings('facebook') }}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Twitter Page</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="twitter" type="text" name="settings[twitter]" value="{{ settings('twitter') }}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Google+ Page</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="googleplus" type="text" name="settings[googleplus]" value="{{ settings('googleplus') }}">
                </div>
            </div>
        </div>

        <h3>Contact List</h3>
        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Phone Number (Call Center)</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="phone" type="text" name="settings[phone]" value="{{ settings('phone') }}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Fax Number</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="fax" type="text" name="settings[fax]" value="{{ settings('fax') }}">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Address Line 1</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <textarea cols="5" class="form-input" id="address" type="text" name="settings[address]">{{ settings('address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Address Line 2</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <textarea cols="5" class="form-input" id="address2" type="text" name="settings[address2]">{{ settings('address2') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Position Latitude</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="lat" type="text" name="settings[latitude]" value="" readonly>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Position Longitude</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="lng" type="text" name="settings[longitude]" value="" readonly>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l3">
                    <label class="label-inline">Map Locator</label>
                </div>
                <div class="bzg_c" data-col="l9">
                    <input class="form-input" id="areaSearch" type="text" value="">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="bzg">
                <div class="bzg_c" data-col="l9" data-offset="l3">
                    <div id="map" style="width: 100%; height: 400px;"></div>
                </div>
            </div>
        </div>

        <div class="bzg">
            <div class="bzg_c" data-col="l9" data-offset="l3">
                <input class="btn btn--blue" type="submit" value="Save"/>
            </div>
        </div>
    {!! Form::close() !!}

@stop

@section('script-footer')
    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
    <script>
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
    </script>
@stop
