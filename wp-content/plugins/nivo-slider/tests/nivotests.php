<?php

require_once( '../nivo-slider.php' );

class NivoSliderTests extends WP_UnitTestCase
{
	var $plugin;
	
	function setUp() {
	    parent::setUp();
		$this->plugin = new WordpressNivoSlider();
	}
	
	function tearDown() {
		unset($this->plugin);
	}
	
	function testInitialization() {
		$this->assertFalse( $this->plugin == null );
	}
	
	function testDefaultValSuccess() {
	    $options = array('type' => 'manual');
	    $val = $this->plugin->default_val( $options, 'type' );
		$this->assertEquals( 'manual', $val );
	}
	
	function testDefaultValDefault() {
	    $options = array();
	    $val = $this->plugin->default_val( $options, 'type', 'manual' );
		$this->assertEquals( 'manual', $val );
	}
	
	function testShortcodeWorks() {
	    $output = $this->plugin->shortcode(array('id' => 1));
	    $this->assertNotNull( $output );
	}
	
	function testGetThemes() {
	    $themes = $this->plugin->get_themes();
	    $this->assertNotNull( $themes );
	}
}

?>