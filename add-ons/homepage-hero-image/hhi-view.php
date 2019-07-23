<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class JWhite_Addon_Hero_Image_View
{
	static $instance;
    private $post_id;
	
	public function __construct( $post_id ) {
		$this->post_id = $post_id;

		add_action( 'jwhite_after_header', array( $this, 'view_display' ) , 20);
		add_action( 'wp_enqueue_scripts', array( $this, 'load_hero_style' ) );
    }

	public function load_hero_style() { 
		$style_css = plugin_dir_url(__FILE__) . 'style.css';
		wp_register_style( 'jwhite-hero-image', $style_css, array(), '1.0' );
		wp_enqueue_style('jwhite-hero-image' );
	}

	public function view_display() {
		
		$hero_data = get_post_meta($this->post_id, 'jw_hero_data', true);
		$button_url = $hero_data['button_url'];
		$button_text = $hero_data['button_text'];

		if(!empty($hero_data['hero_image'])): ?>		
			<div class="jw-hero-image-container hero-full" style="background-image: url('<?php echo $hero_data['hero_image'] ?>'); height: <?php echo $hero_data['set_height'] ?>px; ">
				<div class="hero-overlay">
					<div class="wrap">
						<div class="hero-text-box">
							<div>
								<h2 class="hero-title"><?php echo $hero_data['hero_title'] ?></h2>
								<div class="hero-description"><?php echo $hero_data['hero_description'] ?></div>
								<?php if ( !empty( $button_url ) && !empty( $button_url ) ): ?>
								<a href="<?php echo $button_url ?>" class="read-more"><?php echo $button_text ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<style>
			.jw-hero-image-container {
    			background-position: 50% <?php echo $hero_data['set_voffset'] ?>% !important;
			}

			.jw-hero-image-container .wrap {
				height: <?php echo $hero_data['set_height'] ?>px; 
				position: relative;
			}
			</style>
		<?php endif;
	}

	public static function init( ) {
		global $post;

		if ( $post->ID == HERO_HOMEPAGE_ID ) {
			self::$instance = new self( HERO_HOMEPAGE_ID );
		}

		return self::$instance;
	}
}

add_action( 'template_redirect', array( 'JWhite_Addon_Hero_Image_View', 'init' ) );