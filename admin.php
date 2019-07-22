<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class jWhite_Addons_Admin extends jWhite_Addon_Utilities 
{
	public function __construct()
	{
		add_action('admin_menu', array( $this, 'admin_menu' ));
    }
    
    public static function get_instance()
    {
        if ( self::$instance == null) {
            self::$instance = new self();
        }
 
        return self::$instance;
	}

	public function load_admin_style() {		
		wp_register_style('jwhite-addons-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), '1.0' );
	}

	public function admin_menu() {

		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'profile.php' );
		remove_menu_page( 'tools.php' );
		
		apply_filters( 'regenerate_thumbs_cap', 'edit_posts' );

		add_options_page( 
			'jWhite AddOn Utils',
			'jWhite AddOn Utils',
			'manage_options',
			'jwhite-addons-settings',
			array( $this, 'addons_settings' )
		);
	}

	public function addon_options() {
		$addon_options = array();
		$ignore = array('.', '..');	
		$dirArr = scandir(plugin_dir_path( __FILE__ ).'add-ons');
		foreach( $dirArr as $val ) {
			if( !in_array( $val, $ignore ) ) {
				$addon_options[] = $val;
			}
		}
	
		return $addon_options;
	}

	public function addons_settings() {

		$form_nonce = filter_input(INPUT_POST, 'form_nonce');
		if ( !empty($form_nonce) && wp_verify_nonce($form_nonce, "form_nonce")) {
			$addons = filter_input(INPUT_POST, 'addons', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);
			$this->set_selected_addons( $addons );
		}

		$addons_options = $this->addon_options();
		$selected_addons = $this->get_selected_addons();
		?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="<?php echo esc_url( admin_url('options-general.php') ); ?>?page=jwhite-addons-settings" method="post">
		<?php if ( is_array( $addons_options ) ): ?>
			<div>
				<ul id="jwhite-addons-settings">
				<?php foreach ( $addons_options as $opt ): ?>
					<li><input type="checkbox" name="addons[<?php echo $opt ?>]" id="util-<?php echo $opt ?>" value="1" <?php echo ( is_array($selected_addons) && array_key_exists($opt, $selected_addons) ? 'checked ' : '' )?>/> <label for="util-<?php echo $opt ?>"> <?php echo ucwords(str_replace('-',' ',$opt)) ?></label></li>
				<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<div>
		<input type="hidden" name="form_nonce" id="form_nonce" value="<?php echo wp_create_nonce('form_nonce') ?>" />
		<?php submit_button( 'Save Settings' ) ?>
		</div>
		</form>
	</div>
		<?php 
	}
}