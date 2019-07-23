<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



class jWhite_Addon_Google_Analytics
{

    public static function customize_register( $wp_customize )
    {
        self::gua_code( $wp_customize );
    }

    public static function gua_code( $wp_customize )
    {
        $wp_customize->add_setting(
            'gua_code',
            array(
                'default'     => '',
            )
        );
            
        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'gua_code_id',
                array(
                    'label'      => __( 'Google Universal Analytics Code' ),
                    'section'    => 'title_tagline',
                    'description'    => 'Enter GUA Code (ex. UA-######-#)',
                    'settings'   => 'gua_code',
                    'priority' 	 => 900,
                )
            )
        );
	}
	
	public static function add_gua_code_to_bottom() {
		$gua_code = get_theme_mod('gua_code'); ?>
		<!-- Google Analytics -->
			<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
			ga('create', '<?php echo $gua_code ?>', 'auto');
			ga('send', 'pageview');
			</script>
			<!-- End Google Analytics -->
		<?php 
	}
}

add_action( 'customize_register', array('jWhite_Addon_Google_Analytics', 'customize_register' ) );
add_action( 'wp_footer', array('jWhite_Addon_Google_Analytics', 'add_gua_code_to_bottom' ), 999);