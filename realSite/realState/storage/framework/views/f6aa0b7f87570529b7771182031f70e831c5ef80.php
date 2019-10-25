    
<?php $__env->startSection('content'); ?>

   
<div style="padding-top:60px"></div>

    <div class="container">

        <div class="page-header">
            <h1>Create Banner Removal</h1>
        </div>

        <form   action="<?php echo e(url('/remove_banner')); ?>" class="order-banner" method="post" enctype="multipart/form-data">
<?php echo e(csrf_field()); ?>

          

            <div class="row">

                <div class="col-md-6">

                    <h3>What order are you creating a removal for?</h3>
                    <select name="order_id" title="Removal Order ID" id="order_id_select" class="form-control">
                        <option value="Unknown">An order from another vendor</option>
                        <?php $__currentLoopData = $banner_placed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($value->id); ?>"><?php echo e($value->address); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                    
                    <hr>

                    <h3>What is the address of signs being removed?</h3>
                    
                    <p id="glocation"><input title="Address" type="text" value="" id="searchInput"   name="address" class="form-control" required></p>

                    <hr>

                    <h3>Any instructions for this work order?</h3>
                    <textarea class="form-control" placeholder="The signs are on the west corner of..." name="removal_instructions"></textarea>

                    <hr>

                    <h3>When should these signs be removed?</h3>
                    <input type="text" title="Remove By Date" class="form-control" name="remove_by" value="<?php echo  date("Y/m/d"); ?>">
                    <input type="hidden" name="Installation_date" id="datepicker" value="N/A">
                    <input type="hidden" name="type" value="removal">
                    <input type="hidden" name="company_name" value="<?php $company_id=  Session::get('company_name'); echo $company_id;?>" >
                    <input type="hidden" name="status" value="Removal Pending">
                          
                    <hr>

                    <input class="btn btn-success btn-block" type="submit" value="Create Removal Order">

                    

                </div>

                <div class="col-md-6">

                    <div class="map-iframe-wrapper">
                      
                        
                         <div class="map" id="map" style="border:0;top:inherit" ></div>
                 <div class="form_area">
                     <input type="hidden" name="location" id="location">
                     <input type="hidden" name="lat" id="lat">
                     <input type="hidden" name="lng" id="lng">
                 </div>
                    </div>

                    <hr style="margin-top:40px">

                    <div id="sign_template" style="display:none">
                        <div class="row">

                            <div class="col-md-3">
                                <img id="template_--SIGN-ID--_image" src="http://demosigns.westallisblue.com/images/loading.gif" class="img-responsive">
                            </div>

                            <div class="col-md-9">
                                <p id="template_--SIGN-ID--_name"><b>Banner Name</b></p>
                                <p id="template_--SIGN-ID--_description">Banner description stuff here</p>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </form>

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
</script> 
    
    


<script type="text/javascript">

    var current_banner_id = 0;

    var total_banners = 0;

    $(document).ready(function() {
        $('#add_banner_button').trigger('click');
    });

    $('#add_banner_button').on('click',function() {
        var BannerTemplate = $('#BannerTemplate');
        BannerTemplate.before(
            '<div id="banner_' + current_banner_id + '" style="display: none;" class="order-block">' +
                BannerTemplate.html().replace(/--BANNER_NO--/g,current_banner_id) +
            '</div>'
        );
        $('#banner_' + current_banner_id)
            .show('fast')
            .removeAttr('id');
        current_banner_id++;
        total_banners++;
    });

    $("#datepicker")
        .datepicker({
            dateFormat: "mm-dd-yy",
            defaultDate: '06-11-2018',
            minDate: new Date()
        })
        .on('change',function() {
            $(this).attr('value', $(this).val());
        });

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            var ImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];  //acceptable file types

            var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
                isImage = ImageTypes.indexOf(extension) > -1,  //is extension in acceptable types
                isPdf = extension === 'pdf',
                isDoc = ['doc','docx'].indexOf(extension) > -1;

            reader.onload = function (e) {

                var src;

                if (isImage) {
                    src = e.target.result;
                }else if (isPdf) {
                    src = '';
                }else if (isDoc) {
                    src = '';
                }

                $(input)
                    .closest(".col-sm-4")
                    .find("img")
                    .attr('src', src)

            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.order-banner').submit(function() {
        $('#BannerTemplate').remove();
        $('input[name="banners_count"]').val(total_banners);
    });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('user_dashboard.layouts.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>