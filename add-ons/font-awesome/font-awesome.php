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


// Remove emoji scripts
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles', 'print_emoji_styles', 20);
remove_action( 'admin_print_scripts', 'print_emoji_detection_script', 20);
remove_action( 'admin_print_styles', 'print_emoji_styles', 20);