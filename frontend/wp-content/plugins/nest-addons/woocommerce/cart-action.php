<?php
/*===============================
  Cart Action
==============================*/
if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

/*===============================
 Enqueue scripts style
==============================*/
function nest_cart_action_scripts() { 
    if ('no' === get_option('woocommerce_cart_redirect_after_add') && 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {  
        wp_enqueue_script('nest-ajax-cart', NEST_ADDONS_URL . 'assets/js/add-to-cart-ajax.js', array('jquery'),'' , true);
        $nonce = wp_create_nonce('my_ajax_nonce');
        wp_localize_script('nest-ajax-cart', 'nest_cart_action_nonce', array(
            'nonce' => $nonce,
            'ajax_url' => admin_url('admin-ajax.php'),
        )); 
      }
}
add_action('wp_enqueue_scripts', 'nest_cart_action_scripts');

/*===============================
 Cart Ajax handler
==============================*/
if(!function_exists('nest_ajax_add_to_cart_handler')){
    function nest_ajax_add_to_cart_handler() {
        WC_Form_Handler::add_to_cart_action();
        WC_AJAX::get_refreshed_fragments();
} 
add_action( 'wc_ajax_ace_add_to_cart', 'nest_ajax_add_to_cart_handler' );
add_action( 'wc_ajax_nopriv_ace_add_to_cart', 'nest_ajax_add_to_cart_handler' );
function nest_ajax_add_to_cart_add_fragments( $fragments ) {
	$all_notices  = WC()->session->get( 'wc_notices', array());
	$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );
	ob_start();
    foreach ( $notice_types as $notice_type ) {
        if ( wc_notice_count( $notice_type ) > 0 ) {
            wc_get_template( "notices/{$notice_type}.php", array(
                'notices' => array_filter( $all_notices[ $notice_type ] ),
            ) );
        }
    }
	$fragments['notices_html'] = ob_get_clean();
    wc_clear_notices();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'nest_ajax_add_to_cart_add_fragments' );
}

 