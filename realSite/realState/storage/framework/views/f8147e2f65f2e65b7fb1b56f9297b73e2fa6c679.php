<!--header nav-->



  



<?php $__env->startSection('content'); ?>


<!--creat company body part-->
<div class="container">
<br/><br/>
        <div class="page-header">
            <h1>Create Company </h1>
        </div>

        <div class="col-md-9 col-md-offset-1 panel panel-default">

            
            <div class="panel-body">
                <form action="<?php echo e(url('admin/company')); ?>" method="POST">
                  <?php echo e(csrf_field()); ?>

                    <div class="form-group ">
                        <label for="name-field">Name</label>
                        <input type="text" id="name-field" name="name" class="form-control">
                       </div>

                    <div class="form-group ">
                        <label for="address-field">Address</label>
                      
                            <input type="text" id="searchInput"" name="address" class="form-control" value="" placeholder="Enter a location" >

                       
                                            </div>
                 

      <!-- rotate map -->
       <div class="map" id="map" style="width: 100%; height: 300px;"></div>
                 <div class="form_area">
                     <input type="hidden" name="location" id="location">
                     <input type="hidden" name="lat" id="lat">
                     <input type="hidden" name="lng" id="lng">
                 </div>
                            </div>








                    <div class="form-group ">
                        <label for="phone-field">Phone</label>
                        <input type="text" id="phone-field" name="phone" class="form-control">
                                            </div>

                    <div class="form-group ">
                        <label for="fax-field">Fax</label>
                        <input type="text" id="fax-field" name="fax" class="form-control">
                                            </div>

                    <a class="btn btn-default" href="<?php echo e(url('admin/company')); ?>">Back</a>

                    <button class="btn btn-primary" type="submit">Create</button>

                </form>
            </div>

        </div>

    </div>
<!--creat company body part-->

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
</script> 
    
    
    
    
    



<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.backend_layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>