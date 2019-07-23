<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class JWhite_Addons_Font_Awesome
{
	public static function enqueue_font_awesome(){
		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
	}
}

add_action('wp_enqueue_scripts', array( 'JWhite_Addons_Font_Awesome', 'enqueue_font_awesome' ) );
add_action('admin_enqueue_scripts', array( 'JWhite_Addons_Font_Awesome', 'enqueue_font_awesome' ) );