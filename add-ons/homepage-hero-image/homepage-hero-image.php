<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$frontpage_id = (int)get_option( 'page_on_front' );
if ( $frontpage_id > 0 ) {
    define('HERO_HOMEPAGE_ID', $frontpage_id);
    include('hhi-view.php');
    include('hhi-admin.php');
}

