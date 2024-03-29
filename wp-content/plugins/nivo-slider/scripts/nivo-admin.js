jQuery(document).ready(function($){ 

    $('select[name="nivo_settings[type]"]').change(function(){
        
        if($('option:selected', this).val() != 'manual'){
            $('.nivo_non_manual').show();
        } else {
            $('.nivo_non_manual').hide();
        }
        
        if($('option:selected', this).val() == 'gallery'){
            $('#nivo_type_gallery').show();
        } else {
            $('#nivo_type_gallery').hide();
        }
        
        if($('option:selected', this).val() == 'category'){
            $('#nivo_type_category').show();
        } else {
            $('#nivo_type_category').hide();
        }
        
        if($('option:selected', this).val() == 'custom'){
            $('#nivo_type_custom').show();
        } else {
            $('#nivo_type_custom').hide();
        }
        
        if($('option:selected', this).val() == 'category' || $('option:selected', this).val() == 'sticky' || $('option:selected', this).val() == 'custom'){
            $('#nivo_type_captions').show();
        } else {
            $('#nivo_type_captions').hide();
        }
        
        
        $('#nivoslider_upload_box').removeClass('slider-type-manual slider-type-gallery slider-type-category slider-type-sticky slider-type-custom');
        $('#nivoslider_upload_box').addClass('slider-type-'+ $('option:selected', this).val());
    });
    
    
   
    
    if($('select[name="nivo_settings[type]"] option:selected').val() != 'manual'){
        $('.nivo_non_manual').show();
    }
    
 
    if($('select[name="nivo_settings[type]"] option:selected').val() == 'gallery'){
        $('#nivo_type_gallery').show();
    }
    
    if($('select[name="nivo_settings[type]"] option:selected').val() == 'category'){
        $('#nivo_type_category').show();
    }
    
    if($('select[name="nivo_settings[type]"] option:selected').val() == 'custom'){
        $('#nivo_type_custom').show();
    }
 
    
    if($('select[name="nivo_settings[type]"] option:selected').val() == 'category' ||
       $('select[name="nivo_settings[type]"] option:selected').val() == 'sticky'   ||
       $('select[name="nivo_settings[type]"] option:selected').val() == 'custom'){
        $('#nivo_type_captions').show();
    }
    
    
    $('#nivoslider_upload_box').addClass('slider-type-'+ $('select[name="nivo_settings[type]"] option:selected').val());
    
    
    
    $('select[name="nivo_settings[sizing]"]').change(function(){
    	if($('option:selected', this).val() == 'fixed'){
            $('#nivo_slider_size').show();
        } else {
            $('#nivo_slider_size').hide();
        }
    });
    
    if($('select[name="nivo_settings[sizing]"] option:selected').val() == 'fixed'){
        $('#nivo_slider_size').show();
    }
    
    $('#add_image_source').on('click', function(){
	    $('#new-image-sources').modal();
	});

});