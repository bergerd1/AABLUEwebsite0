<?php
/*
Plugin Name: Alligator Menu Popup
Plugin URI: http://cubecolour.co.uk/alligator-menu-popup/
Description: Add the 'mpopup' class to a menu item in a custom menu to open the target in a popup Window.
Author: Michael Atkins
Version: 1.0.1
Author URI: http://cubecolour.co.uk/
License: GPLv2

  Copyright 2014 Michael Atkins

  Licenced under the GNU GPL:

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// ==============================================
//  Prevent Direct Access of this file
// ==============================================

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly

// ==============================================
//  Add Admin
// ==============================================

require_once(plugin_dir_path( __FILE__ ) . "admin.php");

// ==============================================
//	Add Links in Plugins Table
// ==============================================
 
add_filter( 'plugin_row_meta', 'cc_mpopup_meta_links', 10, 2 );
function cc_mpopup_meta_links( $links, $file ) {

	$plugin = plugin_basename(__FILE__);
	
// create the links
	if ( $file == $plugin ) {

		$supportlink = 'http://wordpress.org/support/plugin/alligator-menu-popup';
		$donatelink = 'http://cubecolour.co.uk/wp';
		$twitterlink = 'http://twitter.com/cubecolour';
		$iconstyle = 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';
		$adminlink = admin_url( 'options-general.php?page=mpopup-settings' );
		
		return array_merge( $links, array(
			'<a href="' . $adminlink . '"><span class="dashicons dashicons-admin-generic" ' . $iconstyle . ' title="Alligator Menu Popup Admin"></span></a>',
			'<a href="' . $supportlink . '"> <span class="dashicons dashicons-lightbulb" ' . $iconstyle . ' title="Alligator Menu Popup Support"></span></a>',
			'<a href="' . $twitterlink . '"><span class="dashicons dashicons-twitter" ' . $iconstyle . ' title="Cubecolour on Twitter"></span></a>',
			'<a href="' . $donatelink . '"><span class="dashicons dashicons-heart"' . $iconstyle . ' title="Donate"></span></a>'
		) );
	}
	
	return $links;
}


// ==============================================
// Add the Javascript to Popup the Window
// ==============================================

function cc_mpopup_script() {
	$cc_mpopup_showscrollbar = 0;
	
	$cc_mpopup_height	= esc_attr( get_option('cc_mpopup_height') );
	$cc_mpopup_width	= esc_attr( get_option('cc_mpopup_width') );
	$cc_mpopup_scroll	= esc_attr( get_option('cc_mpopup_scroll') );
	if ($cc_mpopup_scroll) {
		$cc_mpopup_showscrollbar = 1;
	}

    echo "\n" . "<script type='text/javascript'>
    jQuery(document).ready(function($) {
		$('.mpopup a').click(function() {
			var w = " . $cc_mpopup_width . ";
			var h = " . $cc_mpopup_height . ";
			var s = " . $cc_mpopup_showscrollbar . ";
			var left = (screen.width/2) - (w/2);
			var top = (screen.height/2) - (h/2);
			var NWin = window.open($(this).prop('href'),'','scrollbars=' + s + ',resizable=yes,width=' + w + ',height=' + h + ',top=' + top + ',left=' + left);
			if (window.focus) { NWin.focus(); }
			return false;
			});
		});
    </script>" . "\n";
}

add_action('wp_head', 'cc_mpopup_script');

