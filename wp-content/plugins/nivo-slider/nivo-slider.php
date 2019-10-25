<?php
/*
Plugin Name: Nivo Slider WordPress Plugin
Plugin URI: http://nivo.dev7studios.com/wordpress
Description: The official WordPress plugin for the <a href="http://nivo.dev7studios.com">Nivo Slider</a>
Version: 1.9.1
Author: Dev7studios
Author URI: http://dev7studios.com
*/
if ( !session_id() ) session_start();

$wordpress_nivo_slider = new WordpressNivoSlider();
class WordpressNivoSlider {

    var $plugin_folder = 'nivo-slider';
    var $call_scripts = false;

    function __construct() {	
        add_action('init', array(&$this, 'init'));
        add_filter('post_updated_messages', array(&$this, 'updated_messages'));
        add_action('manage_edit-nivoslider_columns', array(&$this, 'edit_columns'));
        add_action('manage_nivoslider_posts_custom_column', array(&$this, 'custom_columns'));
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_print_styles', array(&$this, 'admin_print_styles'));
        add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
        add_action('admin_print_scripts', array(&$this, 'admin_print_scripts'));
        add_action('save_post', array(&$this, 'save_post'));
        add_action('wp_ajax_nivoslider_load_images', array(&$this, 'load_images'));
        add_action('wp_ajax_nivoslider_upload', array(&$this, 'upload_image'));
        add_action('wp_ajax_nivoslider_load_meta', array(&$this, 'load_image_meta'));
        add_action('wp_ajax_nivoslider_edit', array(&$this, 'edit_image'));
        add_action('wp_ajax_nivoslider_remove', array(&$this, 'remove_image'));
        add_action('wp_ajax_nivoslider_order_save', array(&$this, 'save_order'));
        add_action('wp_footer', array(&$this, 'print_scripts_styles'));
        add_shortcode('nivoslider', array(&$this, 'shortcode'));
        
        load_plugin_textdomain( 'nivo-slider', false, dirname( plugin_basename( __FILE__ ) ) .'/lang/' );

        require_once('wp-updates-plugin.php');
        new WPUpdatesPluginUpdater( 'http://wp-updates.com/api/1/plugin', 116, plugin_basename(__FILE__) );
	}
    
    function init() {
        $labels = array(
            'name' => _x( 'Nivo Slider', 'post type general name' ),
            'singular_name' => _x( 'Nivo Slider', 'post type singular name' ),
            'add_new' => __( 'Add New', 'nivo-slider' ),
            'add_new_item' => __( 'Add New Slider', 'nivo-slider' ),
            'edit_item' => __( 'Edit Slider', 'nivo-slider' ),
            'new_item' => __( 'New Slider', 'nivo-slider' ),
            'view_item' => __( 'View Slider', 'nivo-slider' ),
            'search_items' => __( 'Search Sliders', 'nivo-slider' ),
            'not_found' =>  __( 'No Sliders found', 'nivo-slider' ),
            'not_found_in_trash' => __( 'No Sliders found in Trash', 'nivo-slider' ), 
            'parent_item_colon' => ''
        );
        
        register_post_type(
            'nivoslider',
            array(
                'labels' => $labels,
                'public' => false,
                'show_ui' => true,
                'menu_position' => 100,
                'supports' => array('title'),
                'menu_icon' => plugins_url( 'images/favicon.png' , __FILE__ )
            )
        );
        
        if( current_user_can('edit_posts') && current_user_can('edit_pages') && get_user_option('rich_editing') == 'true' ){  
            add_filter('mce_external_plugins', array(&$this, 'mce_add_plugin'));  
            add_filter('mce_buttons_2', array(&$this, 'mce_register_button'));  
        }  
        
        // Register scripts and styles
        wp_enqueue_script( 'jquery' );
        wp_register_script( 'nivoslider',plugins_url( 'scripts/nivo-slider/jquery.nivo.slider.pack.js' , __FILE__ ), array('jquery') );
        wp_register_style( 'nivoslider', plugins_url( 'scripts/nivo-slider/nivo-slider.css' , __FILE__ ) );
        wp_enqueue_style( 'nivoslider' );
        $themes = $this->get_themes();
        foreach($themes as $theme){
            wp_register_style( 'nivoslider-theme-'. $theme['theme_name'], $theme['theme_url'] );
        }
    }
    
    function updated_messages( $messages ) {
        global $post, $post_ID;

        $messages['nivoslider'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => __('Slider updated.', 'nivo-slider'),
            2 => __('Custom field updated.', 'nivo-slider'),
            3 => __('Custom field deleted.', 'nivo-slider'),
            4 => __('Slider updated.', 'nivo-slider'),
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf( __('Slider restored to revision from %s', 'nivo-slider'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => __('Slider published.', 'nivo-slider'),
            7 => __('Slider saved.', 'nivo-slider'),
            8 => __('Slider submitted.', 'nivo-slider'),
            9 => sprintf( __('Slider scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Slider</a>', 'nivo-slider'),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i', 'nivo-slider' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => __('Slider draft updated.', 'nivo-slider')
        );

        return $messages;
    }
    
    function edit_columns( $columns ) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Title', 'nivo-slider' ),
            'shortcode' => __( 'Shortcode', 'nivo-slider' ),
            'author' => __( 'Author', 'nivo-slider' ),
            'images' => __( 'Images', 'nivo-slider' ),
            'date' => __( 'Date', 'nivo-slider' )
        );

        return $columns;
    }

    function custom_columns( $column ) {
        global $post;
        if ($post->post_type != 'nivoslider') return;
        switch ( $column )
        {
            case 'images':     
                $limit = 5;
                if(isset($_GET['mode']) && $_GET['mode'] == 'excerpt') $limit = 20;
                $images = $this->get_slider_images( $post->ID, array(32, 32), $limit );
                if ( $images ) {
                    echo '<ul class="nivoslider-thumbs">';
                    foreach( $images as $image ){
                        echo '<li><img src="'. $image['image_src'] .'" alt="'. $image['alt_text'] .'" style="width:32px;height:32px;" /></li>';
                    }
                    echo '</ul>'; 
                }
                break;
            case 'shortcode':  
                echo '<code>[nivoslider id="'. $post->ID .'"]</code>';
                if($post->post_name != '') echo '<br /><code>[nivoslider slug="'. $post->post_name .'"]</code>';
                break;
        }
    }
    
    function admin_menu() {
    	add_submenu_page( 'edit.php?post_type=nivoslider', 'Settings', 'Settings', 'manage_options', 'nivoslider-settings', array(&$this, 'settings_page') );
    }
    
    function settings_page_header() {
	    $header = 'Nivo Slider Settings';
	    return apply_filters( 'nivoslider_settings_page_header', $header);
    }
    
    function settings_page() {
    		?>
    	<div class="wrap">
    		<div id="icon-options-general" class="icon32"></div>
			<h2><?php echo $this->settings_page_header() ?></h2>
			<form action="options.php" method="post">
				<?php settings_fields('nivoslider-settings'); ?>
				<?php do_settings_sections('nivoslider-settings'); ?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e( 'Save Changes', 'nivoslider' ); ?>"></p>
			</form>
		</div>
    	<?php
    }
    
    
	function setting_image_sources() {
		$sources = $this->get_image_sources();
		$defaults = $this->image_sources_defaults();  
		$html = '';
		$count = 0;
		if ($sources) {
			$html .= '<input type="hidden" name="nivoslider_settings[sources]" value="sources">';
			foreach ($sources as $key => $source) {
				if (!array_key_exists($key, $defaults)) {
					$count++;
					$html .= '<input type="checkbox" id="'. $key . '" name="nivoslider_settings['. $key .']" checked="checked" /> ';
					$html .= $source .'<br/>';	
				}
			}
		} 	
		if($count == 0) $html = 'No connected image sources';
		echo $html;
	}
	
	function save_image_sources($input) {
		if($input && is_array($input) && isset($input['sources'])) {
	    	$options = get_option( 'nivoslider_settings' );
	    	$saved_sources = array();
	    	if (isset($options['image_sources'])) $saved_sources = is_array($options['image_sources'])? $options['image_sources'] : array($options['image_sources']);
	
			$sources = $this->image_sources_defaults(); 
			foreach($input as $key => $value) {	    	
		    	if (array_key_exists($key, $saved_sources)) {
		    		$sources[strtolower($key)] = ucfirst($key);	
		    		unset($input[$key]);
		    	}
		    }
		    $input['image_sources'] = $sources;
		    foreach($saved_sources as $key => $value) {
			    if (!array_key_exists($key, $sources)) {
		    		unset($input[$key .'-settings']);
		    	}
			}
			unset($input['sources']);
		} 
		return $input;
	}

	function setting_custom_roles() {
		$options = get_option( 'nivoslider_settings' );
		global $wp_roles;
		$role_names = $wp_roles->get_names();
		if( !isset($options['custom-roles']) ) {
			$defaults = array();
			foreach ( $role_names as $key => $value ) {
				if ($key != 'administrator') $defaults[] = $key;
			}
			$options['custom-roles'] = $defaults;
		}		
		$roles = array();
		foreach ( $role_names as $key => $value ) {
			if ($key != 'administrator') {
				$roles[ $key ] = get_role( $key );
				$roles[ $key ]->display_name = $value;
				if (in_array($roles[ $key ]->name, $options['custom-roles'] )) $roles[ $key ]->selected = true;
			}
		}
		echo '<input type="hidden" name="nivoslider_settings[roles]" value="roles">';
		foreach ( $roles as $role ):
			echo '<input type="checkbox" id="'. $role->name . '" name="nivoslider_settings['. $role->name .']" '. (isset($role->selected) && ($role->selected)  ? ' checked="checked"' : '') .'/> ';
			echo $role->display_name .'<br/>';	
		endforeach;
	}
	
	function save_custom_roles($input) {
		if($input && is_array($input) && isset($input['roles'])) {
	    	$custom_roles = array();
	    	global $wp_roles;
	    	$role_names = $wp_roles->get_names();
	    	$roles = array();
			foreach ( $role_names as $key => $value ) {
				if ($key != 'administrator') {
					$roles[ $key ] = get_role( $key );
					$roles[ $key ]->display_name = $value;
				}
			}
			foreach($input as $key => $value) {	    	
		    	if (array_key_exists($key, $roles)) {
		    		$custom_roles[] = $key;	
		    		unset($input[$key]);
		    	}
		    }
		    unset($input['roles']);
		    $custom_roles[] = 'administrator';
		    $input['custom-roles'] = $custom_roles;
		} 
		return $input;
	}
	
	function settings_validate( $input ) { 
		$options = get_option( 'nivoslider_settings' );
		if ($options && is_array($options)) {
			foreach ($options as $key => $option) {
				if (!array_key_exists($key, $input)) $input[$key] = $option;	
			}
		} 

		//custom saving for checkboxes
		$input = $this->save_custom_roles($input);
		$input = $this->save_image_sources($input);
		
		return $input; 
    }
    
    function remove_nivo_menu() {
		$options = get_option( 'nivoslider_settings' );
		if(isset($options['custom-roles'])) {
			$custom_roles = $options['custom-roles'];
			if ($custom_roles) {
				$allowed_roles = apply_filters( 'nivoslider_allowed_roles', $custom_roles );
				global $current_user;
				$user_roles = $current_user->roles;
				$show = false;
				foreach ($allowed_roles as $role) {
					if (in_array($role , $user_roles)) $show = true;
				}
				if (!$show) remove_menu_page( 'edit.php?post_type=nivoslider' );
			}
		}
	}
       
    function settings_intro() {
	    echo apply_filters( 'nivoslider_settings_intro', '');
	}

    function admin_init() {
        add_meta_box( 'nivoslider_upload_box', __( 'Upload Images', 'nivo-slider' ), array(&$this, 'meta_box_upload'), 'nivoslider', 'normal' );
        add_meta_box( 'nivoslider_settings_box', __( 'Settings', 'nivo-slider' ), array(&$this, 'meta_box_settings'), 'nivoslider', 'normal' );
        add_meta_box( 'nivoslider_shortcode_box', __( 'Using this Slider', 'nivo-slider' ), array(&$this, 'meta_box_shortcode'), 'nivoslider', 'side' );
        add_meta_box( 'nivoslider_usefullinks_box', __( 'Useful Links', 'nivo-slider' ), array(&$this, 'meta_box_usefullinks'), 'nivoslider', 'side' );
        
        register_setting( 'nivoslider-settings', 'nivoslider_settings', array(&$this, 'settings_validate') );
		add_settings_section( 'nivoslider-settings', '', array(&$this, 'settings_intro'), 'nivoslider-settings' );
	
		add_settings_field( 'custom-roles', __( 'Nivo Slider Management Roles', 'nivo-slider' ), array(&$this, 'setting_custom_roles'), 'nivoslider-settings', 'nivoslider-settings' );
		add_settings_field( 'image-sources', __( 'Connected Image Sources', 'nivo-slider' ), array(&$this, 'setting_image_sources'), 'nivoslider-settings', 'nivoslider-settings' );
		
		$this->remove_nivo_menu();		
		$this->image_sources_header();
    }
    
    function admin_print_styles() {
        global $post;

        if(isset($post->post_type) && $post->post_type == 'nivoslider'){
            wp_enqueue_style( 'nivoslider-admin-css', plugins_url( 'styles/nivo-admin.css' , __FILE__ )); 
        }
    }
    
    function admin_enqueue_scripts() {
        global $post;

        if(isset($post->post_type) && $post->post_type == 'nivoslider'){
            wp_register_script( 'nivo_plupload', plugins_url( 'scripts/plupload/plupload.full.js' , __FILE__ ), array('jquery') );
            wp_enqueue_script( 'nivo_plupload' ); 
            wp_register_script( 'jquery-simplemodal', plugins_url( 'scripts/jquery.simplemodal.1.4.1.min.js' , __FILE__ ), array('jquery') );
            wp_enqueue_script( 'jquery-simplemodal' );
            wp_register_script( 'nivoslider-admin-js', plugins_url( 'scripts/nivo-admin.js' , __FILE__ ), array('jquery') );
            wp_enqueue_script( 'nivoslider-admin-js' );
            wp_enqueue_script('jquery-ui-sortable');
        }
    }
    
    function admin_print_scripts() {
    	 // Sliders list for TinyMCE dropdown
        $sliders = get_posts( array('post_type' => 'nivoslider', 'posts_per_page' => -1) );
        $list = array();
        foreach( $sliders as $slider ){
            $list[] = array(
                'id' => $slider->ID, 
                'name' => $slider->post_title
            );
        }       
        echo "<script type='text/javascript'>\n";
		echo 'var NivoSlider = '. json_encode(array('sliders' => json_encode($list)) ) .'';
		echo "\n</script>";
    }
    
    function image_sources_header() {
		
		if(isset($_GET['post']) && isset($_GET['type'])) {
		    global $post;
			$post = get_post($_GET['post']);
			if ($post->post_type != 'nivoslider') return;
			
		   	$options = get_option( 'nivoslider_settings' );
		    $source = $_GET['type'];
		    $request_code = $_GET['oauth_verifier'];

		    if (isset($_SESSION[$source .'_oauth_token']) && isset($_SESSION[$source .'_oauth_token_secret'])) {
				$auth_token = $_SESSION[$source .'_oauth_token'];
			    $auth_token_secret = $_SESSION[$source .'_oauth_token_secret'];

			    (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ) ? $protocol = "https://" : $protocol = "http://"; 
			    $callback =  $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];	
			    $callback = substr($callback, 0, strpos($callback, $source) + strlen($source));
			    
			    $source_dir = dirname(__FILE__) .'/includes/sources/'; 
				include_once($source_dir . $source .'.php');
				
				$var = 'nivo_source_'. $source;
				$obj = new $var($auth_token, $auth_token_secret);
			    $token = $obj->getAccessToken($request_code, $callback);
	
			    $nivo_settings = get_post_meta( $_GET['post'], 'nivo_settings', true );	
			    $nivo_settings['type'] = $source;
			    update_post_meta($_GET['post'], 'nivo_settings', $nivo_settings);
			    
			    $options[$source .'-settings'] = array('access-token' => $token);
			    $image_sources = $options['image_sources'];
			    $image_sources[$source] = ucfirst($source);
			    $options['image_sources'] = $image_sources;	
				update_option('nivoslider_settings', $options);
				
				if (isset($_SESSION[$source .'_oauth_token'])) unset($_SESSION[$source .'_oauth_token']);
				if (isset($_SESSION[$source .'_oauth_token_secret'])) unset($_SESSION[$source .'_oauth_token_secret']);    
			}
		}   
	}

    function image_sources_defaults() {
	    $defaults = array( 	'manual' => __('Manual', 'nivo-slider'),
							'gallery' => __('Gallery', 'nivo-slider'), 
							'category' => __('Category', 'nivo-slider'), 
							'sticky' => __('Sticky Posts', 'nivo-slider')
					);
		$post_types=get_post_types(array( 'public'   => true, '_builtin' => false),'objects');
        if(sizeof($post_types) > 0) { 
            $defaults['custom'] = __('Custom Post Type', 'nivo-slider');
        }  
		return $defaults;
    }
    
    function get_image_sources() {
	    $options = get_option( 'nivoslider_settings' );
		if( !isset($options['image_sources']) ) {
			$defaults = $this->image_sources_defaults();            			
            $options['image_sources'] = $defaults;
			update_option('nivoslider_settings', $options);								
		} 
		return $options['image_sources'];   
    } 
    
    function get_all_sources() {
		$options = get_option( 'nivoslider_settings' );
		$image_sources = $options['image_sources'];
		(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ) ? $protocol = "https://" : $protocol = "http://"; 
		$callback =  $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];	
		if (strpos($callback,'post=') === false) {
			global $post;
			$callback .= '&post='. $post->ID;
		}
		$source_dir = glob(dirname(__FILE__) .'/includes/sources/*');
		$sources = array();	    
		if ($source_dir) {
		    foreach($source_dir as $dir) {
			    if (!array_key_exists(basename($dir, ".php"), $image_sources) ) {
				    include_once($dir);
				    $source = basename($dir, ".php");
				    $var = 'nivo_source_'. $source;
				    $obj = new $var();
					$sources[$source] = $obj->get_authorise_url($callback, $source);
				}
			}
		}
		return $sources;
	}
     
    function get_image_source_details($image_source, $selected_source) {
	   	$defaults = $this->image_sources_defaults();   
	   	if (!array_key_exists($image_source, $defaults)) {
			
			global $post;
			$options = get_post_meta( $post->ID, 'nivo_settings', true );
			
			$source_dir = dirname(__FILE__) .'/includes/sources/'; 
			include_once($source_dir . $image_source .'.php');
			$var = 'nivo_source_'. $image_source;
			$obj = new $var();
			$settings = $obj->show_details();
			
			$display = ($image_source != $selected_source) ? 'style="display: none;"' : '';
			$header = '<tr valign="top" id="nivo_type_'. $image_source .'" class="image_source" '. $display .'>';
		    $header .= '<th scope="row"> '. ucfirst($image_source) .' settings</th>';
		    $header .= '<td>';
		    $body 	= 	'<label>';
		    $body 	.= 	'	<select name="nivo_settings['. $image_source .'_type]" class="image_source_type" >';
			
			foreach($settings as $method => $value) {
				$selected = ($this->default_val($options, $image_source .'_type', '')) == $method ?  'selected="selected"' : '';
				$body 	.= 	'<option '. $selected .' value="'. $method .'">'. $value['name'].'</option>';
			}
			
			$body 	.= 	'	</select>';
			$body 	.= 	'	<span class="description">Choose the type of images from '. ucfirst($image_source) .' </span>';
			$body 	.= 	'<br/><br/>';
			$body 	.= 	'</label>';
			
			foreach($settings as $method => $value) {
				if (isset($value['param_type'])) {
					$body 	.= 	'<div id="'. $image_source .'_param_'. $method .'" class="image_source_param">';
					if($value['param_type'] == 'text') {
						$body 	.= 	'	<input id="'. $image_source .'_'. $method .'" type="text" value="'. $this->default_val($options, $image_source .'_' . $method, '') .'" name="nivo_settings['. $image_source .'_'. $method .']">';
					}
					
					if($value['param_type'] == 'select') {
						$body 	.= 	'	<input id="'. $image_source .'_'. $method .'" type="text" value="'. $this->default_val($options, $image_source .'_' . $method, '') .'" name="nivo_settings['. $image_source .'_'. $method .']">';
					}
					$body 	.= 	(isset($value['param_desc'])) ? '	<span class="description">'. $value['param_desc'] .'</span>' : '';
					$body 	.= 	'</div>';
				}
			}
			$footer = '</td></tr>';
			$html =  $header.$body.$footer;
			return $html;
		}
	} 
    
    function image_source_default() {
	    return apply_filters( 'nivoslider_image_source_default', 'manual' );
    } 

    function meta_box_settings() {
        global $post;
        $options = get_post_meta( $post->ID, 'nivo_settings', true );
    
        wp_nonce_field( plugin_basename( __FILE__ ), 'nivoslider_noncename' );
        ?>
        <table class="form-table">
        	<tr valign="top">
                <th scope="row"><?php _e('Slider Type', 'nivo-slider'); ?></th>
                <td><select name="nivo_settings[type]">
                    <?php 
                    $image_source = $this->default_val($options, 'type', $this->image_source_default());
                    $images_sources = $this->get_image_sources();
                    if ($images_sources) {
	                    foreach ($images_sources as $source => $value) {
		                  	echo '<option value="'. $source .'"';
		                   	if($image_source == $source) echo ' selected="selected"';
		                   	echo '>'. __( $value, 'nivo-slider') .'</option>';
		                }
		            } else { 
	                   echo '<option value="none">'.__('No Sources', 'nivo-slider').'</option>';
	                } ?>
                </select><a id="add_image_source" href="#" class="button">Add Image Source</a><br />
                <span class="description"><?php _e('Choose to manually upload images or use post thumbnails', 'nivo-slider'); ?></span></td>
            </tr>
            <div id="new-image-sources">
				<p><strong>Add Image Source</strong></p>			
				<?php 
				if ($post->post_status == 'auto-draft' ) {
					echo 'You can add new sources once the slider has been published';	
				} else {
					$sources = $this->get_all_sources(); 
					if ($sources) foreach ($sources as $key => $value) echo '<a href="'. $value .'" class="button image-source" id="'. $key .'">'. ucfirst($key) .'</a> ';
					else echo 'No more sources available';
				}
				?>
			</div>
            <tr valign="top" id="nivo_type_gallery">
                <th scope="row">- <?php _e('Post', 'nivo-slider'); ?></th>
                <td><select name="nivo_settings[type_gallery]">
                    <?php 
                    $posts = get_posts( array('numberposts' => -1) );
                    foreach($posts as $post_item){
                    	//echo '<pre>'.print_r($post_item,true).'</pre>';
                        echo '<option value="'. $post_item->ID .'"';
                        if($this->default_val($options, 'type_gallery') == $post_item->ID) echo ' selected="selected"';
                        echo '>'. $post_item->post_title .'</option>';
                    }
                    ?>
                </select><br />
                <span class="description"><?php _e('Select the post gallery you want to use', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top" id="nivo_type_category">
                <th scope="row">- <?php _e('Category', 'nivo-slider'); ?></th>
                <td><select name="nivo_settings[type_category]">
                    <?php 
                    $categories = get_categories();
                    foreach($categories as $category){
                        echo '<option value="'. $category->cat_ID .'"';
                        if($this->default_val($options, 'type_category') == $category->cat_ID) echo ' selected="selected"';
                        echo '>'. $category->name .'</option>';
                    }
                    ?>
                </select><br />
                <span class="description"><?php _e('Select the category you want to use for post thumbnails', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top" id="nivo_type_custom">
                <th scope="row">- <?php _e('Custom Post Type', 'nivo-slider'); ?></th>
                <td><select name="nivo_settings[type_custom]">
                    <?php 
                    $post_types=get_post_types(array( 'public'   => true, '_builtin' => false),'objects');
					foreach($post_types as $post_type){
                        echo '<option value="'. $post_type->name .'"';
                        if($this->default_val($options, 'type_custom') == $post_type->name) echo ' selected="selected"';
                        echo '>'. $post_type->labels->name .'</option>';
                    }
                    ?>
                </select><br />
                <span class="description"><?php _e('Select the custom post type you want to use for post thumbnails', 'nivo-slider'); ?></span></td>
            </tr>
            <?php
	            $seleced_source = $this->default_val($options, 'type', $this->image_source_default());
	            $images_sources = $this->get_image_sources();
	            $defaults = $this->image_sources_defaults(); 
                if ($images_sources) {
                    foreach ($images_sources as $source => $value) {
	                    if (!array_key_exists($source, $defaults)) {
	                    	echo $this->get_image_source_details($source, $seleced_source); 
	           		    }
	                }
	            } 
	            ?>
            <tr valign="top" id="nivo_type_captions">
                <th scope="row">- <?php _e('Enable Captions', 'nivo-slider'); ?></th>
                <td><input type="hidden" name="nivo_settings[enable_captions]" value="off" />
                <input type="checkbox" name="nivo_settings[enable_captions]" value="on"<?php if($this->default_val($options, 'enable_captions', 'on') == 'on') echo 'checked="checked"'; ?>/><br />
                <span class="description"><?php _e('Enable automatic captions from post titles', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top" class="nivo_non_manual">
                <th scope="row">- <?php _e('Number of Images', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[number_images]" value="<?php echo $this->default_val($options, 'number_images', ''); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('The number of images to use in the slider. Leave blank for all images. External sources default to 20.', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Slider Sizing', 'nivo-slider'); ?></th>
                <td><select name="nivo_settings[sizing]">
                    <?php $slider_sizing = $this->default_val($options, 'sizing', 'responsive'); ?>
                    <option value="responsive"<?php if($slider_sizing == 'responsive') echo ' selected="selected"'; ?>><?php _e('Responsive', 'nivo-slider'); ?></option>
                    <option value="fixed"<?php if($slider_sizing == 'fixed') echo ' selected="selected"'; ?>><?php _e('Fixed Size', 'nivo-slider'); ?></option>
                </select><br />
                <span class="description"><?php _e('Responsive sliders will fill the width of the container', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top" id="nivo_slider_size">
                <th scope="row">- <?php _e('Slider Size', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[dim_x]" value="<?php echo $this->default_val($options, 'dim_x', 400); ?>" /> x 
                <input type="text" name="nivo_settings[dim_y]" value="<?php echo $this->default_val($options, 'dim_y', 150); ?>" /><br />
                <span class="description"><?php _e('(Size in px) Images will be cropped to these dimensions (eg 400 x 150)', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Slider Theme', 'nivo-slider'); ?></th>
                <td><select name="nivo_settings[theme]">
                    <option value="">None</option>
                    <?php 
                    $slider_theme = $this->default_val($options, 'theme'); 
                    $themes = $this->get_themes();
                    foreach($themes as $theme){
                        echo '<option value="'. $theme['theme_name'] .'"';
                        if($slider_theme == $theme['theme_name']) echo ' selected="selected"'; 
                        echo '>'. $theme['theme_details']['SkinName'] .'</option>';
                    }
                    ?>
                </select><br />
                <span class="description"><?php _e('Use a pre-built theme or provide your own styles.', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Transition Effect', 'nivo-slider'); ?></th>
                <td><select name="nivo_settings[effect]">
                    <?php $transition_effect = $this->default_val($options, 'effect', 'random'); ?>
                    <option value="random"<?php if($transition_effect == 'random') echo ' selected="selected"'; ?>>Random</option>
                    <option value="fade"<?php if($transition_effect == 'fade') echo ' selected="selected"'; ?>>Fade</option>
                    <option value="fold"<?php if($transition_effect == 'fold') echo ' selected="selected"'; ?>>Fold</option>
                    <option value="sliceDown"<?php if($transition_effect == 'sliceDown') echo ' selected="selected"'; ?>>Slice Down</option>
                    <option value="sliceDownLeft"<?php if($transition_effect == 'sliceDownLeft') echo ' selected="selected"'; ?>>Slice Down (Left)</option>
                    <option value="sliceUp"<?php if($transition_effect == 'sliceUp') echo ' selected="selected"'; ?>>Slice Up</option>
                    <option value="sliceUpLeft"<?php if($transition_effect == 'sliceUpLeft') echo ' selected="selected"'; ?>>Slice Up (Left)</option>
                    <option value="sliceUpDown"<?php if($transition_effect == 'sliceUpDown') echo ' selected="selected"'; ?>>Slice Up/Down</option>
                    <option value="sliceUpDownLeft"<?php if($transition_effect == 'sliceUpDownLeft') echo ' selected="selected"'; ?>>Slice Up/Down (Left)</option>
                    <option value="slideInRight"<?php if($transition_effect == 'slideInRight') echo ' selected="selected"'; ?>>Slide In (Right)</option>
                    <option value="slideInLeft"<?php if($transition_effect == 'slideInLeft') echo ' selected="selected"'; ?>>Slide In (Left)</option>
                    <option value="boxRandom"<?php if($transition_effect == 'boxRandom') echo ' selected="selected"'; ?>>Box Random</option>
                    <option value="boxRain"<?php if($transition_effect == 'boxRain') echo ' selected="selected"'; ?>>Box Rain</option>
                    <option value="boxRainReverse"<?php if($transition_effect == 'boxRainReverse') echo ' selected="selected"'; ?>>Box Rain (Reverse)</option>
                    <option value="boxRainGrow"<?php if($transition_effect == 'boxRainGrow') echo ' selected="selected"'; ?>>Box Rain Grow</option>
                    <option value="boxRainGrowReverse"<?php if($transition_effect == 'boxRainGrowReverse') echo ' selected="selected"'; ?>>Box Rain Grow (Reverse)</option>
                </select></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Slices', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[slices]" value="<?php echo $this->default_val($options, 'slices', 15); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('The number of slices to use in the "Slice" transitions (eg 15)', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Box (Cols x Rows)', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[boxCols]" value="<?php echo $this->default_val($options, 'boxCols', 8); ?>" /> x 
                <input type="text" name="nivo_settings[boxRows]" value="<?php echo $this->default_val($options, 'boxRows', 4); ?>" /><br />
                <span class="description"><?php _e('The number of columns and rows to use in the "Box" transitions (eg 8 x 4)', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Animation Speed', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[animSpeed]" value="<?php echo $this->default_val($options, 'animSpeed', 500); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('The speed of the transition animation in milliseconds (eg 500)', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top" id="nivo_thumb_nav">
                <th scope="row"><?php _e('Enable Thumbnail Navigation', 'nivo-slider'); ?></th>
                <td><input type="hidden" name="nivo_settings[controlNavThumbs]" value="off" />
                <input type="checkbox" name="nivo_settings[controlNavThumbs]" value="on"<?php if($this->default_val($options, 'controlNavThumbs', 'off') == 'on') echo 'checked="checked"'; ?>/></td>
            </tr>
            <tr valign="top" id="nivo_thumb_size">
                <th scope="row"><?php _e('Thumbnail Size', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[thumbSizeWidth]" value="<?php echo $this->default_val($options, 'thumbSizeWidth', 70); ?>" /> x 
                <input type="text" name="nivo_settings[thumbSizeHeight]" value="<?php echo $this->default_val($options, 'thumbSizeHeight', 50); ?>" /><br />
                <span class="description"><?php _e('The width and height of the thumbnails', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Pause Time', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[pauseTime]" value="<?php echo $this->default_val($options, 'pauseTime', 3000); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('The amount of time to show each slide in milliseconds (eg 3000)', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Start Slide', 'nivo-slider'); ?></th>
                <td><input type="text" name="nivo_settings[startSlide]" value="<?php echo $this->default_val($options, 'startSlide', 0); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Set which slide the slider starts from (zero based index: usually 0)', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Enable Direction Navigation', 'nivo-slider'); ?></th>
                <td><input type="hidden" name="nivo_settings[directionNav]" value="off" />
                <input type="checkbox" name="nivo_settings[directionNav]" value="on"<?php if($this->default_val($options, 'directionNav', 'on') == 'on') echo 'checked="checked"'; ?>/>
                <span class="description"><?php _e('Prev &amp; Next arrows', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Enable Control Navigation', 'nivo-slider'); ?></th>
                <td><input type="hidden" name="nivo_settings[controlNav]" value="off" />
                <input type="checkbox" name="nivo_settings[controlNav]" value="on"<?php if($this->default_val($options, 'controlNav', 'on') == 'on') echo 'checked="checked"'; ?>/>
                <span class="description"><?php _e('eg 1,2,3...', 'nivo-slider'); ?></span></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Pause the Slider on Hover', 'nivo-slider'); ?></th>
                <td><input type="hidden" name="nivo_settings[pauseOnHover]" value="off" />
                <input type="checkbox" name="nivo_settings[pauseOnHover]" value="on"<?php if($this->default_val($options, 'pauseOnHover', 'on') == 'on') echo 'checked="checked"'; ?>/></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Manual Transitions', 'nivo-slider'); ?></th>
                <td><input type="hidden" name="nivo_settings[manualAdvance]" value="off" />
                <input type="checkbox" name="nivo_settings[manualAdvance]" value="on"<?php if($this->default_val($options, 'manualAdvance', 'off') == 'on') echo 'checked="checked"'; ?>/> 
                <span class="description"><?php _e('Slider is always paused', 'nivo-slider'); ?></span></td>
            </tr>   
            <tr valign="top">
                <th scope="row"><?php _e('Random Start Slide', 'nivo-slider'); ?></th>
                <td><input type="hidden" name="nivo_settings[randomStart]" value="off" />
                <input type="checkbox" name="nivo_settings[randomStart]" value="on"<?php if($this->default_val($options, 'randomStart', 'off') == 'on') echo 'checked="checked"'; ?>/> 
                <span class="description"><?php _e('Overrides Start Slide value', 'nivo-slider'); ?></span></td>
            </tr>                          
        </table>
        <script type="text/javascript"> 
        jQuery(document).ready(function($){  
            var nivo_themes = <?php echo json_encode($themes); ?>; 
            $('select[name="nivo_settings[theme]"]').change(function(){ 
                nivo_theme_thumbs_enabled(); 
            }); 
            nivo_theme_thumbs_enabled(); 
             
            function nivo_theme_thumbs_enabled(){ 
                var current_theme = $('select[name="nivo_settings[theme]"] option:selected').val(); 
                var controlNavThumbs = $('input[name="nivo_settings[controlNavThumbs]"]');
                     
                $('#nivo_thumb_nav,#nivo_thumb_size').show();
                if(nivo_themes[current_theme] != undefined){ 
                    if(nivo_themes[current_theme].theme_details.SupportsThumbs == 'false'){ 
                        controlNavThumbs.attr('checked', false);
                        $('#nivo_thumb_nav,#nivo_thumb_size').hide();
                    }
                }
            } 
        }); 
        </script> 
        <?php
    }
    
    function default_val( $options, $value, $default = '' ){
        if( !isset($options[$value]) ) return $default;
        else return $options[$value];
    }
    
    function save_post( $post_id ){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;

        if ( !isset($_POST['nivoslider_noncename']) || !wp_verify_nonce( $_POST['nivoslider_noncename'], plugin_basename( __FILE__ ) ) )
            return;

        // Check permissions
        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) )
                return;
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
        }

        // Good to go
        $settings = $_POST['nivo_settings'];
        
        if( !is_numeric($settings['dim_x']) || $settings['dim_x'] <= 0 ) $settings['dim_x'] = 400;
        if( !is_numeric($settings['dim_y']) || $settings['dim_y'] <= 0 ) $settings['dim_y'] = 150;
        if( !is_numeric($settings['slices']) || $settings['slices'] <= 0 ) $settings['slices'] = 15;
        if( !is_numeric($settings['boxCols']) || $settings['boxCols'] <= 0 ) $settings['boxCols'] = 8;
        if( !is_numeric($settings['boxRows']) || $settings['boxRows'] <= 0 ) $settings['boxRows'] = 4;
        if( !is_numeric($settings['animSpeed']) || $settings['animSpeed'] <= 0 ) $settings['animSpeed'] = 500;
        if( !is_numeric($settings['pauseTime']) || $settings['pauseTime'] <= 0 ) $settings['pauseTime'] = 3000;
        if( !is_numeric($settings['startSlide']) || $settings['startSlide'] < 0 ) $settings['startSlide'] = 0;
        if( !is_numeric($settings['thumbSizeWidth']) || $settings['thumbSizeWidth'] <= 0 ) $settings['thumbSizeWidth'] = 70;
        if( !is_numeric($settings['thumbSizeHeight']) || $settings['thumbSizeHeight'] <= 0 ) $settings['thumbSizeHeight'] = 50;
        
        update_post_meta( $post_id, 'nivo_settings', $settings );
    }
    
    function meta_box_upload() {
        global $post;
        ?>
        <ul id="nivoslider-images"></ul>
        <div id="nivo-file-uploader">
	        <ul id="filelist"></ul>
	        <a id="pickfiles" href="#" class="button">Select files</a> <a id="uploadfiles" href="#" class="button">Upload files</a>
	    </div>
        <div id="nivo-edit-image">
            <p><strong>Edit Image</strong></p>
            <table class="form-table">
            	<tr valign="top">
                    <th scope="row"><?php _e('Image Caption', 'nivo-slider'); ?></th>
                    <td><input type="text" name="nivo_meta_caption" id="nivo_meta_caption" value="" class="regular-text" /><br />
                    <span class="description"><?php _e('e.g. Example caption (Certain HTML allowed)', 'nivo-slider'); ?></span></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Image Link', 'nivo-slider'); ?></th>
                    <td><input type="text" name="nivo_meta_link" id="nivo_meta_link" value="" class="regular-text" /><br />
                    <span class="description"><?php _e('e.g. http://www.example.com', 'nivo-slider'); ?></span></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Image Alt Text', 'nivo-slider'); ?></th>
                    <td><input type="text" name="nivo_meta_alt" id="nivo_meta_alt" value="" class="regular-text" /><br />
                    <span class="description"><?php _e('Used to specify alternative text for the image', 'nivo-slider'); ?></span></td>
                </tr>
            </table>
            <p class="submit"><input type="button" name="nivo_meta_submit" id="nivo_meta_submit" class="button-primary" value="<?php _e( 'Save Changes', 'nivo-slider' ); ?>"></p>
        </div>
        <script type="text/javascript">
        jQuery(document).ready(function($){ 
            // File Uploader
            var uploader = new plupload.Uploader({
				runtimes: 'gears,html5,flash,silverlight',
				browse_button: 'pickfiles',
				container: 'nivo-file-uploader',
				max_file_size: '10mb',
				url: ajaxurl +'?action=nivoslider_upload&post_id=<?php echo $post->ID; ?>&nonce=<?php echo wp_create_nonce('nivoslider_upload'); ?>',
				flash_swf_url: '<?php echo plugins_url( 'scripts/plupload/plupload.flash.swf' , __FILE__ ); ?>',
				silverlight_xap_url: '<?php echo plugins_url( 'plupload/plupload.silverlight.xap' , __FILE__ ); ?>',
				filters: [
					{ title:'Image files', extensions:'jpg,gif,png' }
				]
			});
		
			$('#uploadfiles').click(function(e) {
				uploader.start();
				e.preventDefault();
			});
		
			uploader.init();
		
			uploader.bind('FilesAdded', function(up, files) {
				$.each(files, function(i, file) {
					$('#filelist').append('<li id="'+ file.id +'">' + file.name + ' (' + plupload.formatSize(file.size) + ') <strong></strong></li>');
				});
		
				up.refresh(); // Reposition Flash/Silverlight
			});
		
			uploader.bind('UploadProgress', function(up, file) {
				$('#' + file.id + ' strong').html(file.percent + '%');
			});
		
			uploader.bind('Error', function(up, err) {
				$('#filelist').append('<li class="error">Error: ' + err.code + ', Message: ' + err.message + 
				(err.file ? ", File: " + err.file.name : "") + '</li>');
		
				up.refresh(); // Reposition Flash/Silverlight
			});
		
			uploader.bind('FileUploaded', function(up, file, resp) {
				$('#' + file.id).remove();
				
				var response = $.parseJSON(resp.response);
				if(response.error){
					if(response.message != undefined)
                    	alert(response.message);
                    else 
                    	alert(response.error.message);
                } else {
                    $('#nivoslider-images').append('<li id="attachment-' + response.attachment_id + '">' +
                        '<div class="image-overlay"></div>' +
                        '<img src="' + response.upload_path + '/' + response.file + '" alt="" class="attachment-thumbnail" />' +
                        '<a href="#" class="edit-image" rel="' + response.attachment_id + '" title="Edit Caption">Edit</a>' +
                        '<a href="#" class="remove-image" rel="' + response.attachment_id + '" title="Remove Image">Remove</a></li>');
                }
                
                up.refresh(); // Reposition Flash/Silverlight
			});
            
            // Edit Caption
            $('#nivoslider-images .edit-image').live('click', function(){
                var edit = $(this);
                $('#nivo_meta_link').val('');
                $('#nivo_meta_caption').val('');
                $('#nivo_meta_alt').val('');
                $('#nivo-edit-image').data('attach_id', edit.attr('rel'));
                $('#nivo-edit-image').modal();
                
                $('#nivo-edit-image strong').addClass('loading');
                $.post('<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php', 
                    { action:'nivoslider_load_meta', id:$('#nivo-edit-image').data('attach_id'), 
                      nonce:'<?php echo wp_create_nonce('nivoslider_load_meta'); ?>' }, 
                    function(data){
                        $('#nivo_meta_link').val(data.link);
                        $('#nivo_meta_caption').val(data.caption);
                        $('#nivo_meta_alt').val(data.alt);
                        $('#nivo-edit-image strong').removeClass('loading');
                    }
                , 'json');
                return false;
            });
            $('#nivo_meta_submit').live('click', function(){
                $('#nivo_meta_submit').val('Saving...');
                $.post('<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php', 
                    { action:'nivoslider_edit', id:$('#nivo-edit-image').data('attach_id'), 
                      link:$('#nivo_meta_link').val(), caption:$('#nivo_meta_caption').val(),
                      alt:$('#nivo_meta_alt').val(),
                      nonce:'<?php echo wp_create_nonce('nivoslider_edit'); ?>' }, 
                    function(data){
                        $('#nivo_meta_submit').val('Save Changes');
                        $.modal.close();
                    }
                );
            });
            
            // Remove Image
            $('#nivoslider-images .remove-image').live('click', function(){
                var remove = $(this);
                $.post('<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php', 
                    { action:'nivoslider_remove', data:remove.attr('rel'),
                      nonce:'<?php echo wp_create_nonce('nivoslider_remove'); ?>' }, 
                    function(data){
                        remove.parent().fadeOut(500, function(){
                            remove.remove();
                        });
                    }
                );
                
                return false;
            });
            
            // Drag & Drop sort images
            $('#nivoslider-images').sortable({
                update: function(event, ui){
                    $.post('<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php', 
                    $(this).sortable('serialize') + '&action=nivoslider_order_save&nonce=<?php echo wp_create_nonce('nivoslider_order_save'); ?>', 
                    function(response){
                        response = jQuery.parseJSON(response);
                        if(response.error){
                            alert(response.message);
                        }
                    });
                }
            });
            
            // Change Slider Type
		    $('#nivoslider_settings_box').on('change', 'select[name="nivo_settings[type]"]', function(){
		        var image_source = $('option:selected', this).val(); 
		        var source = image_source;       
		        if(image_source != 'gallery' && image_source != 'manual' && image_source != 'category' && image_source != 'custom' && image_source != 'sticky') {
			        source = 'external';
			        $('.image_source').hide();
			        $('#nivo_type_' + image_source).show();
			        
			        display_params();	
			    } 
		        else $('.image_source').hide();
		        
		        if(image_source != 'manual'){
			        $('.nivo_non_manual').show();
			    }
		       
		        if(image_source == 'manual'){
                    $('#nivoslider-images').sortable('enable');
                    $('.qq-upload-drop-area').hide(); // Strange bug
                } else {
                    $('#nivoslider-images').sortable('disable');
                }
		        
		        $('#nivoslider_upload_box').addClass('image-source-'+ source);
		        
		        loadImages();
		    });
            
            // Image Source Type Change
		    $('#nivoslider_settings_box').on('change', 'select.image_source_type', function(){
		    	display_params();
		    	loadImages();
		    });
		    
		    // number of images change
		    $('#nivoslider_settings_box').on('change', 'input[name="nivo_settings[number_images]"]', function(){
		    	loadImages();
		    });
		    
		    // type gallery change
		    $('#nivoslider_settings_box').on('change', 'select[name="nivo_settings[type_gallery]"]', function(){
		    	loadImages();
		    });
		    
		    // type category change
		    $('#nivoslider_settings_box').on('change', 'select[name="nivo_settings[type_category]"]', function(){
		    	loadImages();
		    });
		    
		    // type custom change
		    $('#nivoslider_settings_box').on('change', 'select[name="nivo_settings[type_custom]"]', function(){
		    	loadImages();
		    });
		    
		    display_params();
		    
		    function display_params() {
			    var source = $('select[name="nivo_settings[type]"] option:selected').val();
		    	var method = $('select[name="nivo_settings[' + source + '_type]"] option:selected').val();
		    	$('.image_source_param').hide();
		    	$('#' + source + '_param_' + method).show();
		    }
		    
		    $('#nivoslider_settings_box').on('change', '.image_source_param', function(){
		    	loadImages();
		    });

            
            if($('select[name="nivo_settings[type]"] option:selected').val() == 'manual'){
                $('#nivoslider-images').sortable('enable');
                $('.qq-upload-drop-area').hide(); // Strange bug
            } else {
                $('#nivoslider-images').sortable('disable');
            }
            
            loadImages(); // Initial load
            function loadImages(){
                var image_source = $('select[name="nivo_settings[type]"] option:selected').val();
		        var image_method = $('select[name="nivo_settings[' + image_source + '_type]"] option:selected').val();
		        var image_param = $('#' + image_source + '_' + image_method).val();
		   
		        var number_images = $('input[name="nivo_settings[number_images]"]').val();
		  
		        $('#nivo-file-uploader').hide();
		        if (image_source == 'manual') $('#nivo-file-uploader').show();
		        
		        if (image_param != '') {
                
	                $('#nivoslider-images').html('<li class="loading">Loading...</li>');
	                $.ajax({
	                    url: '<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php', 
	                    type: 'POST',
	                    dataType: 'json',
	                    data: { action:'nivoslider_load_images', id:'<?php echo $post->ID; ?>', 
	                      slider_type:image_source, category:$('select[name="nivo_settings[type_category]"] option:selected').val(),
	                      gallery:$('select[name="nivo_settings[type_gallery]"] option:selected').val(),
	                      custom:$('select[name="nivo_settings[type_custom]"] option:selected').val(),
	                      method:image_method,
	                      param:image_param,
	                      number_images:number_images,
	                      nonce:'<?php echo wp_create_nonce('nivoslider_load_images'); ?>' }, 
	                    success: function(response){
	                        if(response.error){
	                            alert(response.message);
	                        } else {
	                            $('#nivoslider-images').html('');
	                            for(var i in response.images){
	                                var image = response.images[i];
	                                var output = '<li id="attachment-' + image.id + '">' +
	                                    '<img src="' + image.src + '" alt="" class="attachment-thumbnail" />';
	                                if(image_source == 'manual'){
	                                    output += '<div class="image-overlay"></div><a href="#" class="edit-image" rel="' + image.id + '" title="Edit Caption">Edit</a>' +
	                                              '<a href="#" class="remove-image" rel="' + image.id + '" title="Remove Image">Remove</a></li>';
	                                }
	                                $('#nivoslider-images').append(output);
	                            }
	                        }
	                    },
	                    error: function(response, status, error){
	                        alert('Error: ' + error.replace(/(<([^>]+)>)/ig,""));
	                    }
	                });
	            }
            }
        });
        </script>
        <?php 
    }
    
    function load_images(){
        // Verify this came from the our screen and with proper authorization
        if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], plugin_basename('nivoslider_load_images') ))
            return 0;
            
        $response['error'] = false;
        $response['message'] = '';
        $response['images'] = array();
        $images = array();
        
        $number_images = (isset($_POST['number_images']) && $_POST['number_images'] != '') ? $_POST['number_images'] : -1;
        
        
        if($_POST['slider_type'] == 'manual'){
            $args = array(
                'orderby'        => 'menu_order ID',
                'order'          => 'ASC',
                'post_type'      => 'attachment',
                'post_parent'    => $_POST['id'],
                'post_mime_type' => 'image',
                'post_status'    => null,
                'numberposts'    => -1
            );
            $attachments = get_posts( $args );
            if( $attachments ){
                foreach( $attachments as $attachment ){
                    $image = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ); 
                    $images[] = array(
                        'id' => $attachment->ID,
                        'src' => $image[0]
                    );
                }
            }
        } else if($_POST['slider_type'] == 'gallery'){
            $args = array(
                'orderby'        => 'menu_order ID',
                'order'          => 'ASC',
                'post_type'      => 'attachment',
                'post_parent'    => $_POST['gallery'],
                'post_mime_type' => 'image',
                'post_status'    => null,
                'numberposts'    => $number_images
            );
            $attachments = get_posts( $args );
            if( $attachments ){
                foreach( $attachments as $attachment ){
                    $image = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ); 
                    $images[] = array(
                        'id' => $attachment->ID,
                        'src' => $image[0]
                    );
                }
            }
        } else if($_POST['slider_type'] == 'category'){
            $args = array(
                'post_type'      => 'post',
                'numberposts'    => $number_images,
                'category'       => $_POST['category']
            );
            $posts = get_posts( $args );
            if( $posts ){
                foreach( $posts as $post ){
                    if( has_post_thumbnail($post->ID) ) {
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
                        $images[] = array(
                            'id' => get_post_thumbnail_id($post->ID),
                            'src' => $image[0]
                        );
                    }
                }
            }
        } else if($_POST['slider_type'] == 'sticky'){
            $sticky = get_option( 'sticky_posts' );
            $args = array(
                'post_type'      => 'post',
                'numberposts'    => $number_images,
                'post__in'       => $sticky
            );
            $posts = get_posts( $args );
            if( $posts ){
                foreach( $posts as $post ){
                    if( has_post_thumbnail($post->ID) ) {
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
                        $images[] = array(
                            'id' => get_post_thumbnail_id($post->ID),
                            'src' => $image[0]
                        );
                    }
                }
            }
        } else if($_POST['slider_type'] == 'custom'){
            $args = array(
                'post_type'      => $_POST['custom'],
                'numberposts'    => $number_images
            );
            $posts = get_posts( $args );
            if( $posts ){
                foreach( $posts as $post ){
                    if( has_post_thumbnail($post->ID) ) {
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
                        $images[] = array(
                            'id' => get_post_thumbnail_id($post->ID),
                            'src' => $image[0]
                        );
                    }
                }
            }
        } else {
	       	$image_source =  $_POST['slider_type'];
	    	$options = get_option( 'nivoslider_settings' );
	    	if(isset($options[$image_source .'-settings'])) {
		    	$source_settings = $options[$image_source .'-settings'];
		    	$access_token = $source_settings['access-token'];
		    	$source_dir = dirname(__FILE__) .'/includes/sources/'; 
				include_once($source_dir . $image_source .'.php');
				$var = 'nivo_source_'. $image_source;
				$obj = new $var($access_token['oauth_token'], $access_token['oauth_token_secret']);
				$method = $_POST['method'];
				$count = $_POST['number_images'];
				$params = array();
				if (isset($_POST['param'])) $params[] = $_POST['param'];
				if ($count != '' && $count) $params[] = $count;
				$source_images = call_user_func_array(array($obj, $method), $params);
				if ($source_images) {
					foreach( $source_images as $image){ 
                    $images[] = array(
                        'id' => $image['id'],
                        'src' => $image['thumbnail']
                    );
                }
					
				} else {
					$response['error'] = true;
		        	$response['message'] = 'Failed to get '. $image_source .' feed';
				}
		    }	
		}
        
        $response['images'] = $images;
        
        echo json_encode($response);
        die;
    }
    
    function upload_image(){
        // Verify this came from the our screen and with proper authorization
        if ( !isset($_GET['nonce']) || !wp_verify_nonce( $_GET['nonce'], plugin_basename('nivoslider_upload') ))
            return 0;
            
        $wp_uploads = wp_upload_dir();
        if( isset($wp_uploads['error']) && $wp_uploads['error'] != false ){
            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open upload directory"}, "id" : "id"}');
        }
            
        //@set_time_limit(5 * 60);
        $targetDir = $wp_uploads['path'];
		$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
		$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
		
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . "/" . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);
		
			$count = 1;
			while (file_exists($targetDir . "/" . $fileName_a . '_' . $count . $fileName_b))
				$count++;
		
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}
		
		// Create target dir
		if (!file_exists($targetDir)) @mkdir($targetDir);
		
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];
		
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen($targetDir . "/" . $fileName, $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");
		
					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else {
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
				}
			} else {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}
		} else {
			// Open temp file
			$out = fopen($targetDir . "/" . $fileName, $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");
		
				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		
				fclose($in);
				fclose($out);
			} else {
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
		}
		
		// Attach image to the post
		$uploadfile = $targetDir . "/" . $fileName;
        $wp_filetype = wp_check_filetype( basename($uploadfile), null );
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($uploadfile)),
            'post_content' => '',
            'post_status' => 'inherit',
            'menu_order' => 1
        );
        $attach_id = wp_insert_attachment( $attachment, $uploadfile, $_GET['post_id'] );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $uploadfile );
        wp_update_attachment_metadata( $attach_id,  $attach_data );
        
        $response['error'] = false;
        $response['message'] = '';
        $response['upload_path'] = $wp_uploads['url'];
        if(!empty($attach_data['sizes'])){
            $response['file'] = $attach_data['sizes']['thumbnail']['file'];
            $response['file_medium'] = $attach_data['sizes']['medium']['file'];
        } else {
            $response['file'] = basename($attach_data['file']);
            $response['file_medium'] = basename($attach_data['file']);
        }
        $response['file_full'] = basename($attach_data['file']);
        $response['attachment_id'] = $attach_id;
        $response['success'] = true;
        
        echo htmlspecialchars( json_encode($response), ENT_NOQUOTES );
        die;
    }
    
    function load_image_meta() {
        // Verify this came from the our screen and with proper authorization
        if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], plugin_basename('nivoslider_load_meta') ))
            return 0;
            
        $response['error'] = false;
        $response['message'] = '';

        $meta = wp_get_attachment_metadata($_POST['id']);        
        $response['caption'] = $meta['image_meta']['caption'];
        $response['link'] = '';
        if( isset($meta['link']) ) $response['link'] = $meta['link'];
        $response['alt'] = '';
        if( isset($meta['alt']) ) $response['alt'] = $meta['alt'];
        $response['message'] = 'success';
        
        echo json_encode($response);
        die;
    }
    
    function edit_image() {
        // Verify this came from the our screen and with proper authorization
        if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], plugin_basename('nivoslider_edit') ))
            return 0;
            
        $response['error'] = false;
        $response['message'] = '';
            
        $meta = wp_get_attachment_metadata($_POST['id']);
        $meta['image_meta']['caption'] = strip_tags($_POST['caption'], '<a><strong><em><h1><h2><h3><h4><h5><h6><code>');
        $meta['link'] = strip_tags($_POST['link']);
        $meta['alt'] = strip_tags($_POST['alt']);
        wp_update_attachment_metadata( $_POST['id'], $meta );
        
        $response['caption'] = $meta['image_meta']['caption'];
        $response['link'] = $meta['link'];
        $response['alt'] = $meta['alt'];
        $response['message'] = 'success';
        
        echo json_encode($response);
        die;
    }
    
    function remove_image() {
        // Verify this came from the our screen and with proper authorization
        if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], plugin_basename('nivoslider_remove') ))
            return 0;
            
        $response['error'] = false;
        $response['message'] = '';
            
        wp_delete_attachment( $_POST['data'] );
        $response['message'] = 'success';
        
        echo json_encode($response);
        die;
    }

    function save_order() {    
        // Verify this came from the our screen and with proper authorization
        if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], plugin_basename('nivoslider_order_save') ))
            return 0;
            
        $response['error'] = false;
        $response['message'] = '';
            
        $i = 0;
        $data = $_POST['attachment'];
        foreach( $data as $attach_id ){
            $attachment = array();
            $attachment['ID'] = $attach_id;
            $attachment['menu_order'] = $i;
            wp_update_post( $attachment );
            $i++;
        }
        
        $response['data'] = $data;
        $response['message'] = 'success';
        
        echo json_encode($response);
        die;
    }
    
    function meta_box_shortcode() {
        global $post;
        
        echo '<p>'. __('To use this slider in your posts or pages use the following shortcode:', 'nivo-slider') .'</p>
        <p><code>[nivoslider id="'. $post->ID .'"]</code>';
        if($post->post_name != '') echo 'or</p><p><code>[nivoslider slug="'. $post->post_name .'"]</code>';
        echo '</p>';
    }
    
    function meta_box_usefullinks() {
        echo '<p>'. __('Website:', 'nivo-slider') .' <a href="http://nivo.dev7studios.com" target="_blank">Nivo Slider</a></p>
        <p>'. __('Created by:', 'nivo-slider') .' <a href="http://dev7studios.com" target="_blank">Dev7studios</a></p>
        <p>'. __('Support:', 'nivo-slider') .' <a href="http://support.dev7studios.com/discussions" target="_blank">Support Forums</a></p>
        <p>'. __('Changelog:', 'nivo-slider') .' <a href="http://nivo.dev7studios.com/changelog/" target="_blank">Changelog</a></p>';
    }
        
    function print_scripts_styles() {
        if ( !$this->call_scripts ) return;
        
        wp_print_scripts( 'nivoslider' );
        $themes = $this->get_themes();
        foreach ( $themes as $theme ) {
        	wp_print_styles( 'nivoslider-theme-'. $theme['theme_name'] );
        }
    }
    
    function shortcode( $atts ) {
        extract( shortcode_atts( array(
            'id' => 0,
            'slug' => ''
        ), $atts ) );
        
        if(!$id && !$slug) return;
        
        if(!$id){
            $slider = get_page_by_path( $slug, OBJECT, 'nivoslider' );
            if($slider){
                $id = $slider->ID;
            } else {
                return;
            }
        }
        
        $this->call_scripts = true; // We have used a shortcode
        
        $output = '';
        $options = get_post_meta( $id, 'nivo_settings', true );
        $type = $options['type'];
        
        $defaults = $this->image_sources_defaults(); 
        $slider_type = (!array_key_exists($type, $defaults)) ? 'external' : '';
	            
        $images = $this->get_slider_images( $id );
        
        if( $images ){
        	$captions = array();
            $output .= '<div class="slider-wrapper';
            if(isset($options['theme']) && $options['theme'] != '') $output .= ' theme-'. $options['theme'];
            if(isset($options['controlNavThumbs']) && $options['controlNavThumbs'] == 'on') $output .= ' controlnav-thumbs';
            $output .='"><div class="ribbon"></div>';
            $output .= '<div id="nivoslider-'. $id .'" class="nivoSlider"';
            if($options['sizing'] == 'fixed') $output .= ' style="width:'. $options['dim_x'] .'px;height:'. $options['dim_y'] .'px;"';
            $output .= '>';
            $i = 0;
            foreach( $images as $image ){
                if(isset($image['post_permalink']) && $image['post_permalink'] != '') $output .= '<a href="'. $image['post_permalink'] .'">';

                if($options['sizing'] == 'fixed'){
	                $resized_image = $this->resize_image( $image['attachment_id'], '', $options['dim_x'], $options['dim_y'], true );
	                if ( is_wp_error($resized_image) ){
	                    echo '<p>Error: '. $resized_image->get_error_message() .'</p>';
	                    $output .= '<img src="" ';
	                } else {
	                    $output .= '<img src="'. $resized_image['url'] .'" ';
	                }
                } else {
                	$output .= '<img src="'. $image['image_src'] .'" ';
                }
                
                if(($options['type'] == 'manual' || $options['type'] == 'gallery') && isset($image['post_title']) && $image['post_title'] != ''){
                	$captions[] = $image['post_title'];
                	$output .= 'title="#nivoslider-'. $id .'-caption-'. $i .'" ';
                	$i++;
                }
                if(($options['type'] == 'category' || $options['type'] == 'sticky'  || $options['type'] == 'custom') && $options['enable_captions'] == 'on' && isset($image['post_title']) && $image['post_title'] != ''){ 
                	$captions[] = $image['post_title'];
                	$output .= 'title="#nivoslider-'. $id .'-caption-'. $i .'" ';
                	$i++;
                }
                
                if(isset($options['controlNavThumbs']) && $options['controlNavThumbs'] == 'on'){
                    if (isset($image['thumbnail'])) {
	                    $output .= 'data-thumb="'. $image['thumbnail'] .'" ';
                    } else {
	                  	$resized_image = $this->resize_image( $image['attachment_id'], '', $options['thumbSizeWidth'], $options['thumbSizeHeight'], true );
	                    if ( is_wp_error($resized_image) ){
	                        echo '<p>Error: '. $resized_image->get_error_message() .'</p>';
	                        $output .= 'data-thumb="" ';
	                    } else {
	                        $output .= 'data-thumb="'. $resized_image['url'] .'" ';
	                    }
                    }
                }
                
                $output .= 'alt="'. $image['alt_text'] .'" />';
                if(isset($image['post_permalink']) && $image['post_permalink'] != '') $output .= '</a>';
            }
            $output .= '</div></div>';
            
            if (isset($options['controlNavThumbs']) && $options['controlNavThumbs'] == 'on' && $slider_type == 'external') {
	            $output .= '<style type="text/css" media="screen">				'."\n";
				$output .= '.nivo-thumbs-enabled img {'							."\n";
				$output .= '	width: '. $options['thumbSizeWidth'] .'px;'		."\n";
				$output .= '	height: '. $options['thumbSizeHeight'] .'px;'	."\n";
				$output .= '}'													."\n";
				$output .= '</style>											'."\n";
            }
            
            $i = 0;
            foreach( $captions as $caption ){
            	$output .= '<div id="nivoslider-'. $id .'-caption-'. $i .'" class="nivo-html-caption">';
            	$output .= $caption;
            	$output .= '</div>';
            	$i++;
            }
            
            if( count($images) > 1){
                $output .= '<script type="text/javascript">' ."\n";
                $output .= 'jQuery(window).load(function(){' ."\n";
                $output .= '    jQuery("#nivoslider-'. $id .'").nivoSlider({' ."\n";
                $output .= '        effect:"'. $options['effect'] .'",' ."\n";
                $output .= '        slices:'. $options['slices'] .',' ."\n";
                $output .= '        boxCols:'. $options['boxCols'] .',' ."\n";
                $output .= '        boxRows:'. $options['boxRows'] .',' ."\n";
                $output .= '        animSpeed:'. $options['animSpeed'] .',' ."\n";
                $output .= '        pauseTime:'. $options['pauseTime'] .',' ."\n";
                if(isset($options['randomStart']) && $options['randomStart'] == 'on') $output .= '        startSlide:'. floor(rand(0, count($images))) .',' ."\n";
                else $output .= '        startSlide:'. $options['startSlide'] .',' ."\n";
                $output .= '        directionNav:'. (($options['directionNav'] == 'on') ? 'true' : 'false') .',' ."\n";
                $output .= '        controlNav:'. (($options['controlNav'] == 'on') ? 'true' : 'false') .',' ."\n";
                $output .= '        controlNavThumbs:'. ((isset($options['controlNavThumbs']) && $options['controlNavThumbs'] == 'on') ? 'true' : 'false') .',' ."\n";
                $output .= '        pauseOnHover:'. (($options['pauseOnHover'] == 'on') ? 'true' : 'false') .',' ."\n";
                $output .= '        manualAdvance:'. (($options['manualAdvance'] == 'on') ? 'true' : 'false') ."\n";
                $output .= '    });' ."\n";
                $output .= '});' ."\n";
                $output .= '</script>' ."\n";
            } else {
                $output .= '<script type="text/javascript">' ."\n";
                $output .= 'jQuery(window).load(function(){' ."\n";
                $output .= '    jQuery("#nivoslider-'. $id .' img").css("position","relative").show();' ."\n";
                $output .= '});' ."\n";
                $output .= '</script>' ."\n";
            }
        }
        
        return $output;
    }
    
    function get_slider_images( $post_id, $size = 'full', $limit = -1 ) {
        $options = get_post_meta( $post_id, 'nivo_settings', true );
        if(!$options) return;
        $images = array();
        
        $nivoslider_options = get_option( 'nivoslider_settings' );
        
        $sources = array();
        if (isset($nivoslider_options['image_sources'])) $sources = is_array($nivoslider_options['image_sources'])? $nivoslider_options['image_sources'] : array($nivoslider_options['image_sources']);
	
        if (!array_key_exists($options['type'], $sources)) $image_source = $this->image_source_default();
	    else $image_source = $this->default_val($options, 'type', $this->image_source_default());
        
        $number_images = (isset($options['number_images']) && $options['number_images'] != '') ? $options['number_images'] : -1;
        if ($limit == -1 && $options['type'] != 'manual') $limit = $number_images;
        
        if($options['type'] == 'manual'){
            $args = array(
                'orderby'        => 'menu_order ID',
                'order'          => 'ASC',
                'post_type'      => 'attachment',
                'post_parent'    => $post_id,
                'post_mime_type' => 'image',
                'post_status'    => null,
                'numberposts'    => $limit
            );
            $attachments = get_posts( $args );
            if( $attachments ){
                foreach( $attachments as $attachment ){
                    $image = wp_get_attachment_image_src( $attachment->ID, $size );
                    $meta = wp_get_attachment_metadata( $attachment->ID );        
                    $caption = (isset($meta['image_meta']['caption'])) ? $meta['image_meta']['caption'] : '';
                    $link = '';
                    if( isset($meta['link']) ) $link = $meta['link'];
                    $alt = '';
                    if( isset($meta['alt']) ) $alt = $meta['alt'];
                    $images[] = array(
                        'image_src' => $image[0],
                        'post_permalink' => $link,
                        'post_title' => $caption,
                        'alt_text' => $alt,
                        'attachment_id' => $attachment->ID
                    );
                }
            }
        } else if($options['type'] == 'gallery'){
            $args = array(
                'orderby'        => 'menu_order ID',
                'order'          => 'ASC',
                'post_type'      => 'attachment',
                'post_parent'    => $options['type_gallery'],
                'post_mime_type' => 'image',
                'post_status'    => null,
                'numberposts'    => $limit
            );
            
            
            $attachments = get_posts( $args );
            if( $attachments ){
                foreach( $attachments as $attachment ){
                    $image = wp_get_attachment_image_src( $attachment->ID, $size );
                    $meta = wp_get_attachment_metadata( $attachment->ID );      
                    $caption = get_post_field( 'post_excerpt', $attachment->ID );
                    $link = get_attachment_link( $attachment->ID ); 
                    
                    $images[] = array(
                        'image_src' => $image[0],
                        'post_permalink' => $link,
                        'post_title' => $caption,
                        'alt_text' => $caption,
                        'attachment_id' => $attachment->ID
                    );
                }
            }
        } else if($options['type'] == 'category'){
            $args = array(
                'post_type'      => 'post',
                'numberposts'    => $limit,
                'category'       => $options['type_category']
            );
            $posts = get_posts( $args );
            if( $posts ){
                foreach( $posts as $post ){
                    if( has_post_thumbnail($post->ID) ) {
                    	$attachment_id = get_post_thumbnail_id($post->ID);
                        $image = wp_get_attachment_image_src( $attachment_id, $size ); 
                        $title = get_the_title($post->ID);
                        $link = (get_post_meta($post->ID, 'nivo_image_link', true) ? get_post_meta($post->ID, 'nivo_image_link', true) : get_permalink($post->ID));  
                        $images[] = array(
                            'image_src' => $image[0],
                            'post_permalink' => $link,
                            'post_title' => $title,
                            'alt_text' =>  $title,
                            'attachment_id' => $attachment_id
                        );
                    }
                }
            }
        } else if($options['type'] == 'sticky'){
            $sticky = get_option( 'sticky_posts' );
            $args = array(
                'post_type'      => 'post',
                'numberposts'    => $limit,
                'post__in'       => $sticky
            );
            $posts = get_posts( $args );
            if( $posts ){
                foreach( $posts as $post ){
                    if( has_post_thumbnail($post->ID) ) {
                    	$attachment_id = get_post_thumbnail_id($post->ID);
                        $image = wp_get_attachment_image_src( $attachment_id, $size );
                        $link = (get_post_meta($post->ID, 'nivo_image_link', true) ? get_post_meta($post->ID, 'nivo_image_link', true) : get_permalink($post->ID));  
                        $title = get_the_title($post->ID);
                        $images[] = array(
                            'image_src' => $image[0],
                            'post_permalink' => $link,
                            'post_title' => $title,
                            'alt_text' => $title,
                            'attachment_id' => $attachment_id
                        );
                    }
                }
            }
        } else if($options['type'] == 'custom'){
            $args = array(
                'post_type'      => $options['type_custom'],
                'numberposts'    => $limit
            );
            $posts = get_posts( $args );
            if( $posts ){
                foreach( $posts as $post ){
                    if( has_post_thumbnail($post->ID) ) {
                    	$attachment_id = get_post_thumbnail_id($post->ID);
                        $image = wp_get_attachment_image_src( $attachment_id, $size ); 
                        $title = get_the_title($post->ID);
                        $link = (get_post_meta($post->ID, 'nivo_image_link', true) ? get_post_meta($post->ID, 'nivo_image_link', true) : get_permalink($post->ID));  
                        $images[] = array(
                            'image_src' => $image[0],
                            'post_permalink' => $link,
                            'post_title' => $title,
                            'alt_text' =>  $title,
                            'attachment_id' => $attachment_id
                        );
                    }
                }
            }
        } else {
	    	if(isset($nivoslider_options[$image_source .'-settings'])) {
		    	$source_settings = $nivoslider_options[$image_source .'-settings'];
		    	$access_token = $source_settings['access-token'];
		    	$source_dir = dirname(__FILE__) .'/includes/sources/'; 
				include_once($source_dir . $image_source .'.php');
				$var = 'nivo_source_'. $image_source;
				$obj = new $var($access_token['oauth_token'], $access_token['oauth_token_secret']);
				if (isset($options[$image_source .'_type'])) {
					$method = $options[$image_source .'_type'];
					$params = array();
					if (isset($options[$image_source .'_'. $method])) $params[] = $options[$image_source .'_'. $method];
					$count = $this->default_val($options, 'number_images', '');
					if ($count != '') $params[] = $count;
					$images = call_user_func_array(array($obj, $method), $params);	
				}
		    }
		}
        
        return $images;
    }

    function mce_add_plugin( $plugin_array ) {
        $plugin_array['nivoslider'] = plugins_url( 'scripts/mce-nivoslider/nivoslider.js' , __FILE__ );
        return $plugin_array;
    }
    
    function mce_register_button( $buttons ) {
        array_push($buttons, '|', 'nivoslider');
        return $buttons;
    }
    
    function get_themes(){
        $nivo_theme_specs = array(
            'SkinName' => 'Skin Name',
            'SkinURI' => 'Skin URI',
            'Description' => 'Description',
            'Version' => 'Version',
            'Author' => 'Author',
            'AuthorURI' => 'Author URI',
            'SupportsThumbs' => 'Supports Thumbs'
        );
        
        $plugin_themes = glob(WP_PLUGIN_DIR .'/'. $this->plugin_folder .'/scripts/nivo-slider/themes/*', GLOB_ONLYDIR);
        //$plugin_themes[] = 0;
        
        $upload_dir = wp_upload_dir();
        $custom_themes = glob($upload_dir['basedir'].'/nivo-themes/*', GLOB_ONLYDIR);
        //$custom_themes[] = 1;
        
        $nivo_themes = array();
        if( !is_array($plugin_themes) ) $plugin_themes = array();
        if( !is_array($custom_themes) ) $custom_themes = array();
        $nivo_themes = array_merge($plugin_themes, $custom_themes);
               
        $themes = array();
        if($nivo_themes){
            foreach($nivo_themes as $theme_dir){
                $theme_name = basename($theme_dir);
                $theme_path = $theme_dir .'/'. $theme_name .'.css';
                if( file_exists($theme_path) ){
                    if (strpos($theme_dir,'uploads/nivo-themes') !== false) {
					    $theme_url = $upload_dir['baseurl'].'/nivo-themes/'.$theme_name.'/'. $theme_name .'.css';
					} else {
						$theme_url =  plugins_url( 'scripts/nivo-slider/themes/'.$theme_name.'/'. $theme_name .'.css', __FILE__ );
					}
					$themes[$theme_name] = array(
                        'theme_name' => $theme_name,
                        'theme_path' => $theme_path,
                        'theme_url' => $theme_url,
                        'theme_details' => get_file_data($theme_path, $nivo_theme_specs)
                    );
                }
            }
        }
        
        return $themes;
    }

    
    /*
     * Resize images dynamically using wp built in functions
     * Victor Teixeira
     *
     * php 5.2+
     *
     * Example usage:
     * 
     * <?php 
     * $thumb = get_post_thumbnail_id(); 
     * $image = resize_image( $thumb, '', 140, 110, true );
     * ?>
     * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
     *
     * @param int $attach_id
     * @param string $img_url
     * @param int $width
     * @param int $height
     * @param bool $crop
     * @return array
     */
    function resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
        // this is an attachment, so we have the ID
        if ( $attach_id ) {
            $image_src = wp_get_attachment_image_src( $attach_id, 'full' );
            $file_path = get_attached_file( $attach_id );
        
        // this is not an attachment, let's use the image url
        } else if ( $img_url ) {
            
            $file_path = parse_url( $img_url );
            $file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
            
            //$file_path = ltrim( $file_path['path'], '/' );
            //$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
            
            if( !file_exists($file_path) ){ 
                return new WP_Error('broke', __('File doesn\'t  exist: '. $file_path, 'nivo-slider'));
            }
            
            $orig_size = getimagesize( $file_path );
            
            $image_src[0] = $img_url;
            $image_src[1] = $orig_size[0];
            $image_src[2] = $orig_size[1];
        }
        $file_info = pathinfo( $file_path );
        $extension = '.'. $file_info['extension'];
        // the image path without the extension
        $no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
        $cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.strtolower($extension);
        // checking if the file size is larger than the target size
        // if it is smaller or the same size, stop right here and return
        if ( $image_src[1] > $width || $image_src[2] > $height ) {
            // the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
            
            if ( file_exists( $cropped_img_path ) ) {
                $cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
        
                $vt_image = array (
                    'url' => $cropped_img_url,
                    'width' => $width,
                    'height' => $height
                );
                
                return $vt_image;
            }
            // $crop = false
            if ( $crop == false ) {
            
                // calculate the size proportionaly
                $proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
                $resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
                // checking if the file already exists
                if ( file_exists( $resized_img_path ) ) {
                
                    $resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
                    $vt_image = array (
                        'url' => $resized_img_url,
                        'width' => $proportional_size[0],
                        'height' => $proportional_size[1]
                    );
                    
                    return $vt_image;
                }
            }
              
            // no cache files - let's finally resize it
            $editor = wp_get_image_editor( $file_path );
	        if ( is_wp_error( $editor ) )
	                return $editor;
	        
	        $editor->set_quality( 90 );
	        
	        $resized = $editor->resize( $width, $height, $crop );
	        if ( is_wp_error( $resized ) )
	                return $resized;
	
	        $new_img_path = $editor->generate_filename( $width.'x'.$height, null );
	        $saved = $editor->save( $new_img_path );
	
	        if ( is_wp_error( $saved ) )
	                return $saved;
	        
            $new_img_size = getimagesize( $new_img_path );
            $new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
            // resized output
            $vt_image = array (
                'url' => $new_img,
                'width' => $new_img_size[0],
                'height' => $new_img_size[1]
            );
            
            return $vt_image;
        }
        // default output - without resizing
        $vt_image = array (
            'url' => $image_src[0],
            'width' => $image_src[1],
            'height' => $image_src[2]
        );
        
        return $vt_image;
    }
    
}

if (!function_exists('nivo_slider')){
	
	function nivo_slider($slider, $return = false) {
	
		$slug = '';
		$id = 0;
		
		if (is_numeric($slider)) {
			$id = $slider;			
		} else {			
			$slug = $slider;
		}
		
		if ($return) {
		    return do_shortcode('[nivoslider slug="'.$slug.'" id="'.$id.'"]');
		} else {
		    echo do_shortcode('[nivoslider slug="'.$slug.'" id="'.$id.'"]');
		}
	
	}
}

?>