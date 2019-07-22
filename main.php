<?php
/**
 * Plugin Name: jWhite Add-On Utilities
 * Description: jWhite Add-Ons & Simple Utilities
 * Author: Julia White
 * Version: 3.0.0
 * Author URI: http://cheezoid.com
 * License: GPL2
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class jWhite_Addon_Utilities {

    protected static $instance = null;
    protected $ABSPATH;
    protected $ASSETS_URL;
    protected $CSS_URL;

	public function __construct()
	{
		$this->ABSPATH = dirname(__FILE__);
        $this->ASSETS_URL = plugin_dir_url(__FILE__) .'assets/';
		$this->CSS_URL = $this->ASSETS_URL .'css/';
		$this->JS_URL = $this->ASSETS_URL .'js/';
		
		if ( is_admin() ) {
			include( $this->ABSPATH . '/admin.php' );
			jWhite_Addons_Admin::get_instance();
		} else {
			add_action( 'wp_enqueue_styles', array( $this, 'wp_enqueue_styles' ), 900 );	
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			add_filter('jpeg_quality', function( $arg ){ return 65; });
		}

		$this->activate_addons();
    }

    public static function install() 
	{

	}
    
    public static function get_instance()
    {
        if ( self::$instance == null) {
            self::$instance = new self();
        }
 
        return self::$instance;
	}

	public function get_selected_addons()
	{
		return get_option('jwhite-selected-addons', array());
	}

	public function set_selected_addons( $addons )
	{
		update_option('jwhite-selected-addons', $addons);
		
		return;
	}

	public function wp_enqueue_styles() { 
		wp_enqueue_style('jwhite_addon_util', $this->CSS_URL . 'style.css', array(), null );
	}
	
	public function wp_enqueue_scripts() {
		wp_enqueue_script( 'jwhite_addon_util', $this->JS_URL . 'script.js', array( 'jquery' ), '1.0.0' );
	}
	
	public function activate_addons() 
	{
		$selected_addons = $this->get_selected_addons();
		if ( count( $selected_addons ) > 0 ) {
			foreach( $selected_addons as $option => $chk ) {
				include( $this->ABSPATH . '/add-ons'.'/'.$option.'/'.$option.'.php');
			}
		}
	}

}
register_activation_hook( __FILE__, array( 'jWhite_Addon_Utilities', 'install' ) );
jWhite_Addon_Utilities::get_instance();




class JW_Gen_Func 
{
	public static function display_datetime($val) 
	{
		$datetime = new DateTime($val);
		return $datetime->format('m/d/Y h:i a'); 
	}

	public static function euro_date_display( $val ) 
	{
		$value = '';
		if ( isset( $val ) && $val != '0000-00-00 00:00:00') {
			$value = date( 'm/d/Y g:i a', strtotime( $val ) );
		}
		return $value;
	}

	public static function year_mod( $mod = 0 ) 
	{
		$now = new DateTime();
		if ( $mod > 0 ) { $now->modify('+'.$mod.' year'); } elseif ($mod < 0) { $now->modify($mod.' year'); }
		return $now->format('Y');
	}

	public static function sanitize_data($val) 
	{
		$val = trim($val);
		$val = strip_tags($val);
		return stripslashes($val);
	}

	public static function ensure_slug($val) 
	{
		$val = sanitize_data($val);
		$val = strtolower($val);
		$val = str_replace(" ","-",$val);
		return preg_replace("/[^a-zA-Z0-9-]/", "", $val);
	}

	public static function write_log( $log, $location = '', $clear = false )  
	{
		if ( empty($location) ) {
			$dir_log = dirname(__FILE__).'/debug.log';
		}

		if ($clear) {
			file_put_contents( $dir_log, "");
		} 

    	if ( is_array( $log ) || is_object( $log ) ) {
        	error_log( print_r( $log, true ), 3, $dir_log );
    	} else {
    		error_log( $log, 3, $dir_log );
    	}
	}

	public static function compress_html($html) 
	{
		$output = str_replace(array("\r\n", "\r"), "\n", $html);
		$lines = explode("\n", $output);
		$new_lines = array();
		
		foreach ($lines as $i => $line) {
			if(!empty($line))
				$new_lines[] = trim($line);
		}

		return implode($new_lines);
	}

	public static function file_size($size) 
	{
        switch(TRUE){
            case $size < 1000:
                return "$size bytes";
                break;
            case $size < 1000000:
                return sprintf("%4.2fK",$size / 1000);
                break;
            default:
                return sprintf("%4.2fM",$size / 1000000);
        }
    }
}