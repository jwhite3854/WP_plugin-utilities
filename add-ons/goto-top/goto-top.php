<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// -- GOTO TOP ---------------------------------------------- //

add_action( 'jwhite_after_header', 'add_gototop_target' );
function add_gototop_target() {
	echo '<div id="top"></div>';
}

add_filter( 'jwhite_before_footer', 'add_gototop_bar', 0 );
function add_gototop_bar() {
	echo '<div id="scroll-top"><a href="/back-to-top/" rel="nofollow" id="scroll-top-link">Back to Top</a></div>';
}