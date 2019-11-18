@extends('backend.layouts.base')

@section('style-head')
	<style type="text/css">
		.controls {
        	margin-top: 16px;
        	border: 1px solid transparent;
        	border-radius: 2px 0 0 2px;
        	box-sizing: border-box;
        	-moz-box-sizing: border-box;
        	height: 32px;
        	outline: none;
        	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      	}

      	#pac-input {
        	background-color: #fff;
        	font-family: Roboto;
        	font-size: 15px;
        	font-weight: 300;
        	margin-left: 12px;
        	padding: 0 11px 0 13px;
        	text-overflow: ellipsis;
        	width: 400px;
      	}

      	#pac-input:focus {
        	border-color: #4d90fe;
      	}

      	.pac-container {
        	font-family: Roboto;
      	}

      	#type-selector {
        	color: #fff;
        	background-color: #4d90fe;
        	padding: 5px 11px 0px 11px;
      	}

      	#type-selector label {
        	font-family: Roboto;
        	font-size: 13px;
        	font-weight: 300;
      	}
	</style>
@stop

@section('script-head')
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places" type="text/javascript" ></script>
	<script type="text/javascript">
    <?php
    	// key=AIzaSyDHut2N2jsLxyL0RLlWMl1b9T95Bssqmz4&
    	$latitude = Settings::getValue('latitude');
    	$longitude = Settings::getValue('longitude');
    ?>
	 	function load() {
	 		var map = new google.maps.Map(document.getElementById('map'), {
		        zoom: 15,
		        center: new google.maps.LatLng({{ empty($latitude) ? "-6.21772" : $latitude}}, {{ empty($longitude) ? "106.81147" : $longitude }}),
		        scaleControl: true,
		        overviewMapControl: true,
		        overviewMapControlOptions:{opened:true},
		        mapTypeId: google.maps.MapTypeId.ROADMAP
		  	});

		  	// Create the search box and link it to the UI element.
			var input = /** @type {HTMLInputElement} */(
			  document.getElementById('pac-input'));
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

			var searchBox = new google.maps.places.SearchBox(
			/** @type {HTMLInputElement} */(input));

			// [START region_getplaces]
			// Listen for the event fired when the user selects an item from the
			// pick list. Retrieve the matching places for that item.
			google.maps.event.addListener(searchBox, 'places_changed', function() {
				var places = searchBox.getPlaces();
				if (places.length == 0) {
			  		return;
				}
				for (var i = 0, marker; marker = markers[i]; i++) {
			  		marker.setMap(null);
				}
				// For each place, get the icon, place name, and location.
				markers = [];
				var bounds = new google.maps.LatLngBounds();
				for (var i = 0, place; place = places[i]; i++) {
			  		var image = {
			    		url: place.icon,
			    		size: new google.maps.Size(71, 71),
			    		origin: new google.maps.Point(0, 0),
			    		anchor: new google.maps.Point(17, 34),
			    		scaledSize: new google.maps.Size(25, 25)
			  		};

			  		// Create a marker for each place.
			  		var marker = new google.maps.Marker({
			    		map: map,
			    		icon: image,
			    		title: place.name,
			    		position: place.geometry.location
			  		});
			  		markers.push(marker);
			  		bounds.extend(place.geometry.location);
				}

				map.fitBounds(bounds);
			});
			// [END region_getplaces]

			// Bias the SearchBox results towards places that are within the bounds of the
			// current map's viewport.
			google.maps.event.addListener(map, 'bounds_changed', function() {
				var bounds = map.getBounds();
				searchBox.setBounds(bounds);
			});

			/*
			var marker = new GMarker(center, {draggable: true});
        	map.addOverlay(marker);
        	document.getElementById("lat").value = center.lat().toFixed(5);
        	document.getElementById("lng").value = center.lng().toFixed(5);

	  		GEvent.addListener(marker, "dragend", function() {
       			var point = marker.getPoint();
	      		map.panTo(point);
       			document.getElementById("lat").value = point.lat().toFixed(5);
       			document.getElementById("lng").value = point.lng().toFixed(5);
        	});

	 		GEvent.addListener(map, "moveend", function() {
			  	map.clearOverlays();
	    		var center = map.getCenter();
			  	var marker = new GMarker(center, {draggable: true});
			  	map.addOverlay(marker);
			  	document.getElementById("lat").value = center.lat().toFixed(5);
		   		document.getElementById("lng").value = center.lng().toFixed(5);
		 		GEvent.addListener(marker, "dragend", function() {
	      			var point =marker.getPoint();
		     		map.panTo(point);
	      			document.getElementById("lat").value = point.lat().toFixed(5);
		     		document.getElementById("lng").value = point.lng().toFixed(5);
	        	});
        	});

		  	*/
			/*
			var locations = "";
			$("#areaSearch").autocomplete(locations,{
				minChars: 1,
				width: 322,
				matchContains:'false',
				autoFill: false,
				scroll: false,
				formatItem: function(row, i, max) {
				   return row.name;
				},
				formatMatch: function(row, i, max) {
					return row.name;
				},
				formatResult: function(row) {
					return row.name;
				}
			});
			$("#areaSearch").result(function(event, data, formatted) {
				if (data) {
					var map = new GMap2(document.getElementById("map"));
					var point = new GLatLng(data.latitude, data.longitude);
					document.getElementById("lat").value = point.lat().toFixed(5);
					document.getElementById("lng").value = point.lng().toFixed(5);
					map.clearOverlays()
					map.setCenter(point, 14);
					var marker = new GMarker(point, {draggable: true});
					map.addOverlay(marker);

					GEvent.addListener(marker, "dragend", function() {
						var pt = marker.getPoint();
						map.panTo(pt);
						document.getElementById("lat").value = pt.lat().toFixed(5);
						document.getElementById("lng").value = pt.lng().toFixed(5);
					});

					GEvent.addListener(map, "moveend", function() {
						map.clearOverlays();
						var center = map.getCenter();
						var marker = new GMarker(center, {draggable: true});
						map.addOverlay(marker);
						document.getElementById("lat").value= center.lat().toFixed(5);
						document.getElementById("lng").value = center.lng().toFixed(5);
						GEvent.addListener(marker, "dragend", function() {
							var pt = marker.getPoint();
							map.panTo(pt);
							document.getElementById("lat").value= pt.lat().toFixed(5);
							document.getElementById("lng").value = pt.lng().toFixed(5);
						});
					});
				}
			});
			*/
	    }

	    /*
	   	function showAddress() {
			var address = $("#areaSearch").val();
		   	var map = new GMap2(document.getElementById("map"));
	       	map.addControl(new GSmallMapControl());
	       	map.addControl(new GMapTypeControl());
	       	if (geocoder) {
	        	geocoder.getLatLng(
	          		address,
	          		function(point) {
	            		if (!point) {
	              			alert(address + " not found");
	            		} else {
			  				document.getElementById("lat").value = point.lat().toFixed(5);
		   					document.getElementById("lng").value = point.lng().toFixed(5);
			 				map.clearOverlays()
							map.setCenter(point, 14);
	   						var marker = new GMarker(point, {draggable: true});
			 				map.addOverlay(marker);

							GEvent.addListener(marker, "dragend", function() {
	      						var pt = marker.getPoint();
		     					map.panTo(pt);
	      						document.getElementById("lat").value = pt.lat().toFixed(5);
		     					document.getElementById("lng").value = pt.lng().toFixed(5);
	        				});

		 					GEvent.addListener(map, "moveend", function() {
				  				map.clearOverlays();
		    					var center = map.getCenter();
				  				var marker = new GMarker(center, {draggable: true});
				  				map.addOverlay(marker);
			  					document.getElementById("lat").value= center.lat().toFixed(5);
			   					document.getElementById("lng").value = center.lng().toFixed(5);

			 					GEvent.addListener(marker, "dragend", function() {
		     						var pt = marker.getPoint();
			    					map.panTo(pt);
		    						document.getElementById("lat").value= pt.lat().toFixed(5);
			   						document.getElementById("lng").value = pt.lng().toFixed(5);
		        				});
		        			});
	            		}
	          		}
	        	);
	      	}
	    }
	    */

	    google.maps.event.addDomListener(window, 'load', load);
    </script>
@stop

@section('content')
	<br>
    <div class="row">
        <div class="col-sm-12">
			{!! Form::model($settings, array('class'=>'form-global','id'=>'form_settings')) !!}

		    <div class="form-group">
		        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address </label>
		        <div class="col-sm-9">
		            {!! Form::text('settings[address]', Settings::getValue('address'), array(
		                'id'=> 'settings_address',
		                'class'=> 'form-control',
		                'placeholder'=>'type your current central address ...'
		            )) !!}
		        </div>
		    </div>

		    <div class="form-group">
		        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone Number </label>
		        <div class="col-sm-9">
		            {!! Form::text('settings[phone]', Settings::getValue('phone'), array(
		                'id'=> 'settings_phone',
		                'class'=> 'form-control',
		                'placeholder'=>'type your current phone number ...'
		            )) !!}
		        </div>
		    </div>

		    <div class="form-group">
		        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Position Latitude </label>
		        <div class="col-sm-9">
		            {!! Form::text('settings[latitude]', $latitude, array(
		                'id'=> 'lat',
		                'class'=> 'form-control',
		                'readonly'=>true
		            )) !!}
		        </div>
		    </div>

		    <div class="form-group">
		        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Position Longitude </label>
		        <div class="col-sm-9">
		            {!! Form::text('settings[longitude]', $longitude, array(
		                'id'=> 'lng',
		                'class'=> 'form-control',
		                'readonly'=>true
		            )) !!}
		        </div>
		    </div>

		    <div class="form-group">
		        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Location </label>
		        <div class="col-sm-8">
		            {!! Form::text('addressSearch', '', array(
		                'id'=> 'areaSearch',
		                'class'=> 'form-control',
		                'placeholder'=>'type area name that you want to search ...'
		            )) !!}
		        </div>
		        <div class="col-sm-1" align="right"><input type="button" value="Search!" onclick="showAddress(); return false" /></div>
		    </div>

		    <div class="form-group">
		        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">&nbsp;</label>
		        <div class="col-sm-9">
		        	<input id="pac-input" class="controls" type="text" placeholder="Search Box">
		        	<div id="map" style="margin: 10px 10px 10px 10px; width: 100%; height: 400px;"></div>
		        </div>
		    </div>

		    <div class="form-group">
		        <div class="col-md-offset-3 col-md-9">
		            {!! Form::submit('Save', array('class'=>'btn btn-primary')) !!}
		        </div>
		    </div>

		    {!! Form::close() !!}
    	</div>
    </div>

@stop
