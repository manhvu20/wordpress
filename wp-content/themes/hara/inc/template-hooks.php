<?php if (! defined('HARA_THEME_DIR')) {
    exit('No direct script access allowed');
}
/**
 * Hara woocommerce Template Hooks
 *
 * Action/filter hooks used for Hara woocommerce functions/templates.
 *
 */


/**
 * Hara Header Mobile Content.
 *
 * @see hara_the_button_mobile_menu()
 * @see hara_the_logo_mobile()
 */
add_action('hara_header_mobile_content', 'hara_the_button_mobile_menu', 5);
add_action('hara_header_mobile_content', 'hara_the_icon_home_page_mobile', 10);
add_action('hara_header_mobile_content', 'hara_the_logo_mobile', 15);
add_action('hara_header_mobile_content', 'hara_the_icon_mini_cart_header_mobile', 20);


/**
 * Hara Header Mobile before content
 *
 * @see hara_the_hook_header_mobile_all_page
 */
add_action('hara_before_header_mobile', 'hara_the_hook_header_mobile_all_page', 5);

/**Page Cart**/
remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
