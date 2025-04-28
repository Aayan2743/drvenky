<?php
/*
**   ==============================   
**    Nest Ecommerce Function File
**   ==============================
*/ 
//Mobile Detect
require_once get_template_directory() . '/includes/lib/Mobile_Detect.php';
/*
==========================================
Metabox admin styles
==========================================
*/
function isMobile() {
    if ( ! class_exists( 'Mobile_Detect' ) ) {
        return false;
    }
    $detect = new Mobile_Detect;
    $mobile = false;
    if( $detect->isMobile() || $detect->isTablet() ){
        $mobile = true;
    }
    return $mobile;
}
require get_template_directory() . '/includes/functions/theme.php';
 // ============================== theme update ============================
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
if(class_exists('Nest_update')):
$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://themepanthers.com/updatedplugin/nest/theme.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'nest-theme-update'
);
endif;
// ============================== theme update ============================

/*
==========================================
Disable Elementor Boarding
==========================================
*/
add_action('init', 'nest_disable_elementor_onboarding_redirect');
function nest_disable_elementor_onboarding_redirect() {
    delete_transient( 'elementor_activation_redirect' );
}
// Disable redirection after activating The Woocommerce
add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );   
// Disable WooCommerce Page Installation
add_filter( 'woocommerce_create_pages', '__return_false' ); 