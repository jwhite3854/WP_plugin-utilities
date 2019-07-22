<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Example usage:
 * 
 *  In function:
 *  jWhite_Flash_Message::set_message( 'company_page', 'success', 'Company information updated.' );
 * 
 *  Render results on page:
 *  echo jWhite_Flash_Message::get_message_div( 'company_page' );
 * 
 */

class jWhite_Flash_Message
{
    private static function generate_id() 
    {        
        $user_id = get_current_user_id();
        return md5( 'jwp_flash_'.$user_id );
    }

    public static function set_message( $code, $type = 'success', $message = '' ) 
    {
        $session_id = self::generate_id();
        $acceptable = array( 'secondary', 'success', 'danger', 'warning', 'info' );

        if ( !in_array( $type, $acceptable ) ) $type = 'default';

        $flash = json_encode( array( 'type' => $type, 'message' => $message ) );
		add_option( "_jwp_flash_{$code}_{$session_id}", $flash, '', 'no' );
    }

    public static function unset_message( $code ) 
    {
        $session_id = self::generate_id();
        delete_option( "_jwp_flash_{$code}_{$session_id}" );
    }

    public static function get_message_container( $code ) 
    {
        $session_id = self::generate_id();
        $container = get_option( "_jwp_flash_{$code}_{$session_id}" );
        delete_option( "_jwp_flash_{$code}_{$session_id}" );
		return json_decode($container);
    }

    public static function get_message_div( $code, $dismissable = true ) 
    {
        $container = self::get_message_container( $code ); 
        if ( $container ) {
            $full_message = '<div class="alert alert-'. $container->type;
            if ( $dismissable ) {
                $full_message .= ' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
            } else {
                $full_message .= '" role="alert">';
            }
            $full_message .= $container->message. '</div>';
        }

        return ( $container && !empty($container->message) ? $full_message : '' ); 
    }
}