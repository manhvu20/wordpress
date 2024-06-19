<?php
    if( hara_checkout_optimized() ) return;
    $class_top_bar 	=  '';

    $always_display_logo 			= hara_tbay_get_config('always_display_logo', true);
    if (!$always_display_logo && !hara_catalog_mode_active() && hara_woocommerce_activated() && (is_product() || is_cart() || is_checkout())) {
        $class_top_bar .= ' active-home-icon';
    }
?>
<div class="topbar-device-mobile d-xl-none clearfix <?php echo esc_attr($class_top_bar); ?>">

	<?php
        /**
        * hara_before_header_mobile hook
        */
        do_action('hara_before_header_mobile');

        /**
        * Hook: hara_header_mobile_content.
        *
        * @hooked hara_the_button_mobile_menu - 5
        * @hooked hara_the_logo_mobile - 10
        * @hooked hara_the_title_page_mobile - 10
        */

        do_action('hara_header_mobile_content');

        /**
        * hara_after_header_mobile hook

        * @hooked hara_the_search_header_mobile - 5
        */
        
        do_action('hara_after_header_mobile');
    ?>
</div>