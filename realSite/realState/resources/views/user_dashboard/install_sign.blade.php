@extends('user_dashboard.layouts.layout')
<!--header nav-->

@section('content')

<div style="padding-top:60px"></div>

<div class="container">

    
    <div class="row">
        <div class="col-sm-12">
            <h1>Install a Sign</h1>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-8">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs install-sign" role="tablist">

                <li role="presentation" class=" active ">
                    <a  style="cursor: pointer;" onclick="document.getElementById('navStep1').submit()"  aria-controls="home" role="tab">Step 1: <strong>Set Location</strong></a>
                                    </li>

                <li role="presentation" class="">
                    <a  aria-controls="profile" role="tab">Step 2: <strong>Order Signs</strong></a>
                                    </li>

                <li role="presentation" class="">
                    <a  aria-controls="messages" role="tab">Step 3: <strong>Install Options</strong></a>
                                    </li>

                <li role="presentation" class="">
                    <a onclick="document.getElementById('navStep4').submit()" aria-controls="settings" role="tab">Step 4: <strong>Review Order</strong></a>
                </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active choose-sign-blocks">

                                                                    <div class="row">
    <div class="col-sm-12">
        <h2 class="tab-title">Where would you like the sign installed?</h2>
    </div>
</div>

<div class="row">

        <form action="{{url('/install_sign_data')}}" class="sign-location" method="post" id="the_form" enctype="multipart/form-data">
             {{csrf_field()}}

    <div class="col-sm-4">
            <div class="row">

                <!--<input type="hidden" name="user_id" >-->
                
                <div class="col-sm-12">
                    <label for="address-field">Location</label>
                                      <p id="glocation"><input type="text"  name="address"  id="searchInput"  required></p>

                </div>

                <div class="col-sm-12">
                    <label for="date">Installation date:</label>
                                        <input type="text" name="date" id="datepicker" class="datepicker"
                           value="<?php echo  date("Y/m/d"); ?>">
                </div>

                <div class="col-sm-12">
                    <label for="special_instructions">Special instructions:</label>
                                        <textarea name="special_instructions" id="special_instructions"></textarea>
                </div>

                <div class="col-sm-12">
                    <label for="attachment">Map or photo of desired location:</label>
                                        <input name="attachment" type="file"  id="attachment">
                    <small></small>
                </div>
               
                           <input type="hidden" name="status" value="Waiting For Printing">

            </div>
        
    </div>

    <div class="col-sm-8">
        <div class="map-iframe-wrapper">
          
            <div class="map" id="map" style="height:100%; width:100%;position: absolute" ></div>
                 <div class="form_area">
                     <input type="hidden" name="address" value="" id="location">
                     <input type="hidden" name="lat" id="lat">
                     <input type="hidden" name="lng" id="lng">
                 </div>
        </div>
    </div>
</form>
</div>

<div class="row">
    <div class="col-sm-12">
        <button id="next_step_button" type="submit" form="the_form" class="btn btn-primary next-step">
            Next: Choose Signs <span class="glyphicon glyphicon-chevron-right"></span>
        </button>
            </div>
</div>

                    
                </div>
            </div>

        </div>

        <div class="col-md-4 sidebar" style="padding-top:60px">
            <div class="sidebar-inner">

                <h2 class="tab-title">Your Order</h2>

                <div class="row">

                    <div class="col-xs-7">

                        <h4>Installation Location</h4>

 
                        <span class="address"></span>
 

                    </div>

                    <div class="col-xs-5">
                                            </div>

                </div>

                <div class="col-xs-12">
                    <hr>
                </div>

                <div class="row">

                    <div class="col-xs-12">
                        <h4>Sign Costs:</h4>
                                            </div>

                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table">

                                <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Item</th>
                                                                    </tr>
                                </thead>

                                <tbody>

                                
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12">
                    <hr>
                </div>

                <div class="row">

                    <div class="col-xs-12">
                        <h4>Installation Costs</h4>
                                            </div>

                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table">

                                <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Item</th>
                                                                    </tr>
                                </thead>

                                <tbody>

                                
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

                
            </div>
        </div>

    </div>

</div>

 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBz-LGvlIHTQoeJ1E7GMplSXzbFy1d6Qso&libraries=places"></script>

 <!--<input id="searchInput" class="input-controls" type="text" placeholder="Enter a location">-->

<script>
/* script */
function initialize() {
   var latlng = new google.maps.LatLng(28.5355161,77.39102649999995);
    var map = new google.maps.Map(document.getElementById('map'), {
      center: latlng,
      zoom: 13
    });
    var marker = new google.maps.Marker({
      map: map,
      position: latlng,
      draggable: true,
      anchorPoint: new google.maps.Point(0, -29)
   });
    var input = document.getElementById('searchInput');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();   
    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
       
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);          
    
        bindDataToForm(place.formatted_address,place.geometry.location.lat(),place.geometry.location.lng());
        infowindow.setContent(place.formatted_address);
        infowindow.open(map, marker);
       
    });
    // this function will work on marker move event into map 
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {        
              bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, marker);
          }
        }
        });
    });
}
function bindDataToForm(address,lat,lng){
   document.getElementById('location').value = address;
   document.getElementById('lat').value = lat;
   document.getElementById('lng').value = lng;
}

google.maps.event.addDomListener(window, 'load', initialize);
 function one(){
    setTimeout(function(){
        debugger;
        if(document.getElementById('searchInput').style.position === 'absolute'){
            two();
        }
        else{
            one();
        }
    },50);  
  }
  function two(){
      $($("#searchInput").detach()).appendTo("#glocation");
  $("#searchInput").removeAttr("style") ; 
  }
  one();
  
  
  $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
</script> 
    
    
 
@endsection