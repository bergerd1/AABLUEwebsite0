<?php

/* --------------------------------------------- *\

	Package: Alligator Menu Popup plugin for WordPress
	Author: cubecolour
	Version: 1.0.0
	
	File: Admin Panel
	admin.php

\* --------------------------------------------- */

// ==============================================
//  Prevent Direct Access of this file
// ==============================================

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if direct access to this file is attempted

// ==============================================
//  Register and enqueue the stylesheet
// ==============================================

function cc_mpopup_admin_css() {
    wp_register_style('cc-mpopup-admin-css', plugins_url('css/admin.css', __FILE__),'', '1.0.0', 'all' );
	wp_enqueue_style('cc-mpopup-admin-css');
}

add_action( 'admin_enqueue_scripts', 'cc_mpopup_admin_css' );

// ==============================================
//	Add Options sub-page
// ==============================================

if ( is_admin() ){ //add menu & call register settings function
	add_action('admin_menu' , 'cc_mpopup_menu');
	add_action( 'admin_init', 'cc_register_mpopup_settings' );
	}

function cc_mpopup_menu() {
	//create new sub-menu under the Settings top level menu
    $page = add_submenu_page('options-general.php', 'Menu Popup', 'Menu Popup', 'manage_options', 'mpopup-settings', 'cc_mpopup_settings_page');
	}
   
// ==============================================
//  Create the Settings
// ==============================================

function cc_register_mpopup_settings() {

	// Assign default values
	add_option( 'cc_mpopup_width', '1024' );
	add_option( 'cc_mpopup_height', '768' );
	add_option( 'cc_mpopup_scroll', '1' );

	//register the settings
	register_setting( 'cc-mpopup-group', 'cc_mpopup_width', 'intval' );
	register_setting( 'cc-mpopup-group', 'cc_mpopup_height', 'intval' );
	register_setting( 'cc-mpopup-group', 'cc_mpopup_scroll' );
	}

function cc_mpopup_settings_page() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
		
	echo '<div class="wrap" id="mpopup">';
	echo '<h2>Alligator Menu Popup</h2>';
	echo '<p>by <a href="http://cubecolour.co.uk/wp">cubecolour</a></p>';

	?>

	<form method="post" action="options.php">
		
	<?php settings_fields( 'cc-mpopup-group' ); ?>
	<?php do_settings_sections( 'cc-mpopup-group' ); ?>

	<div class="mpopup-wrapper">
		<ul class="mpopup-options">
		
		<li><h3>Menu Popup Settings</h3></li>
		
        <li><label for="cc_mpopup_width">Width:</label>
        <input type="text" class="small-text" maxlength="4" name="cc_mpopup_width" value="<?php echo esc_attr( get_option('cc_mpopup_width') ); ?>" /> px</li>

        <li><label for="cc_mpopup_height">Height:</label>
        <input type="text" class="small-text" maxlength="4" name="cc_mpopup_height" value="<?php echo esc_attr( get_option('cc_mpopup_height') ); ?>" /> px</li>

		<li><label for="cc_mpopup_scroll">Include Scrollbars:</label>
		<input type="checkbox" name="cc_mpopup_scroll" value="1" <?php checked( get_option('cc_mpopup_scroll'), 1 ); ?> /></li>
		</ul>
		<?php submit_button(); ?>
	</div>
</form>

	<div class="inline-documentation">
	<h3>Usage</h3>
	<p>The <strong>Alligator Menu Popup</strong> plugin enables you to specify that a menu item on your WordPress custom menu will open in a new <em>popup</em> window.</p>
	<h4>Edit your Custom menu:</h4>
	<ul>
	<li>Set the dimensions of the popup window and select whether you want the popup window to be scrollable</li>
	<li>Enable the <strong>CSS Classes</strong> option in the <strong>Screen Options</strong> pull-down panel on the menu editor page.</li>
	<li>Add the <strong>mpopup</strong> class to any menu item where you want the target page to open in a popup window.</li>
	</ul>
	<p>When the menu item is clicked, the link will open in a popup window.</p>
	</div>
	
<?php
echo '</div>';
}

