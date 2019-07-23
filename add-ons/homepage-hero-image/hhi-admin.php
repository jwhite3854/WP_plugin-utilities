<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class JWhite_Addon_Hero_Image 
{
	static $instance;
	private $post_id;
	private $defaults;

    public function __construct( $post_id )
    {
		$this->post_id = $post_id;

		$this->defaults = array(
			'hero_image' => '',
			'set_height' => '400',
			'set_voffset' => '0',
			'hero_title' => '',
			'hero_description' => '',
			'button_url' => '',
			'button_text' => '',
		);
		
		add_meta_box( 'hero_image_meta_box', 'Hero Image', array( $this, 'hero_image_meta_box_wrapper'), 'page', 'side', 'low' );
        add_action( 'save_post', array( $this, 'save_post') );
    }

    public function get_args()
    {
		$args = get_post_meta( $this->post_id, 'jw_hero_data', true );
	
		return wp_parse_args( $args, $this->defaults );
	}
	
    public function save_post()
    {
        // Check the user's permissions.
		if ( !current_user_can( 'edit_post', $this->post_id ) ) {
			return $this->post_id;
		}
        
		$fields = filter_input(INPUT_POST, 'hero_data', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);
		$this->save_args( $fields );
	}

	public function save_args( $fields )
	{
		$data = array();
		foreach ( $fields as $key => $field ) {
			if ( array_key_exists( $key, $this->defaults ) ) {
				$data[$key] = sanitize_text_field( $field );
			}
		}

		update_post_meta( $this->post_id, 'jw_hero_data', $data );
	}
	

	public function hero_image_meta_box_wrapper( $post_type )
	{
		$msg = '';	
		$data = $this->get_args();

		?>
		<div id="hero-image-submitpost" class="submitbox">
			<div id="hero-image-box">
				<div style="margin-top: 0;">
				<!-- defaults -->
					<?php if(!empty($data['hero_image'])): ?>
			
					<div>Set Height of Image (px): 
						<input type="text" name="hero_data[set_height]" value="<?php echo esc_attr( stripslashes( $data['set_height'] ) ); ?>" id="set_height" class="regular-text" style="width: 50px;" />
					</div>
					<div>Set Vertical Offset of Image (%): 
						<input type="text" name="hero_data[set_voffset]" value="<?php echo esc_attr( stripslashes( $data['set_voffset'] ) ); ?>" id="set_voffset" class="regular-text" style="width: 50px;" />
					</div>
				
					<div class="clear"></div>
					<?php endif; ?>
					<?php if(!empty($data['hero_image'])): ?>
					<div style="position: relative; margin-top: 10px;">
						<img id="logo-img" src="<?php echo esc_attr( $data['hero_image'] ); ?>" width="100%" />
						<div class="overlay"></div>
					</div>
					<?php endif; ?>
					<input type="text" name="hero_data[hero_image]" value="<?php echo esc_attr( stripslashes( $data['hero_image'] ) ); ?>" id="hero_image" class="regular-text" style="width: 100%;" />
					<input type="button" class="button-secondary upload-btn" value="Select/Upload Image" />
					<input type="button" class="button-secondary clear-btn" value="Clear" />
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						$('#hero-image-box .upload-btn').click(function(e) {
							e.preventDefault();
							var image = wp.media({ 
								title: 'Upload Image',
								multiple: false
							}).open()
							.on('select', function(e){
								var uploaded_image = image.state().get('selection').first();
								var image_url = uploaded_image.toJSON().url;
								$('#hero_image').val(image_url);
							});
						});
						$('#hero-image-box .clear-btn').click(function(e) {
							$('#hero_image').val("");
						});
					});
				</script>
				<div class="clear">&nbsp;</div>
				<hr/>
				<div class="hero-manual" >
					<label for="jw_hero_title_option">
						<?php echo _e('Hero Title:'); ?>
					</label>
					<input type="text" name="hero_data[hero_title]" value="<?php echo esc_attr( stripslashes( $data['hero_title'] ) ); ?>" id="jw_hero_title_option" class="regular-text" style="width: 100%;">
					<div class="clear">&nbsp;</div>
					
					<label for="jw_hero_description">
						<?php echo _e('Hero Content:'); ?>
					</label>
					<textarea id="jw_hero_description" class="regular-text" rows="" cols="" name="hero_data[hero_description]" style="width: 100%; height: 150px;"><?php echo esc_attr( stripslashes( $data['hero_description'] ) ); ?></textarea>
					<div class="clear">&nbsp;</div>
				
					<label for="jw_hero_button_url">
						<?php echo _e('Button URL: (ie: http://www.google.com)'); ?>
					</label>
					<input type="text" name="hero_data[button_url]" value="<?php echo esc_attr( stripslashes( $data['button_url'] ) ); ?>" id="jw_hero_button_url" class="regular-text" style="width: 100%;">
					<div class="clear"></div>
					
					<label for="jw_hero_button_text">
						<?php echo _e('Button Text:'); ?>
					</label>
					<input type="text" name="hero_data[button_text]" value="<?php echo esc_attr( stripslashes( $data['button_text'] ) ); ?>" id="jw_hero_button_text" class="regular-text" style="width: 100%;">
					<div class="clear"><br/></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
<?php }

	public static function get_instance( $post_id ) {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( $post_id );
		}

		return self::$instance;
	}
}

function jw_hero_image_admin_init() {

	$get_post = filter_input(INPUT_GET, 'post');
	$get_post_ID = filter_input(INPUT_POST, 'post_ID');

	if ( is_admin() && ( $get_post  == HERO_HOMEPAGE_ID || $get_post_ID == HERO_HOMEPAGE_ID ) ) {
   		JWhite_Addon_Hero_Image::get_instance( HERO_HOMEPAGE_ID );
    }
}
add_action( 'admin_init', 'jw_hero_image_admin_init' );