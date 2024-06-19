<?php
/**
 * hara functions and definitions.
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @see https://codex.wordpress.org/Theme_Development
 * @see https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 * @since Hara 1.0
 */
require get_theme_file_path('inc/functions-global.php');

/*Start Class Main*/
require get_theme_file_path('inc/classes/class-main.php');

/*
 Include Required Plugins
*/
require get_theme_file_path('inc/functions-plugins.php');

require_once get_parent_theme_file_path(HARA_INC.'/classes/class-tgm-plugin-activation.php');

/**Include Merlin Import Demo**/
require_once get_parent_theme_file_path(HARA_MERLIN.'/vendor/autoload.php');
require_once get_parent_theme_file_path(HARA_MERLIN.'/class-merlin.php');
require_once get_parent_theme_file_path(HARA_INC.'/merlin-config.php');

require_once get_parent_theme_file_path(HARA_INC.'/functions-helper.php');
require_once get_parent_theme_file_path(HARA_INC.'/functions-frontend.php');
require_once get_parent_theme_file_path(HARA_INC.'/functions-mobile.php');

require_once get_parent_theme_file_path(HARA_INC.'/customizer/functions.php');

/**
 * Customizer.
 */
require_once get_parent_theme_file_path(HARA_INC.'/customizer/custom-header.php');
require_once get_parent_theme_file_path(HARA_INC.'/customizer/customizer.php');
require_once get_parent_theme_file_path(HARA_INC.'/customizer/custom-styles.php');
/**
 * Classess file.
 */

/**
 * Implement the Custom Styles feature.
 */
require_once get_parent_theme_file_path(HARA_CLASSES.'/custommenu.php');

/**
 * Custom template tags for this theme.
 */
require_once get_parent_theme_file_path(HARA_INC.'/template-tags.php');
require_once get_parent_theme_file_path(HARA_INC.'/template-hooks.php');

if (hara_is_cmb2()) {
    require_once get_parent_theme_file_path(HARA_VENDORS.'/cmb2/page.php');
    require_once get_parent_theme_file_path(HARA_VENDORS.'/cmb2/post.php');
}

if (hara_wpml_is_activated()) {
    require_once get_parent_theme_file_path(HARA_VENDORS.'/compatible/wpml.php');
}

if (hara_woocommerce_activated()) {
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/wc-admin.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/classes/class-wc.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/classes/class-wc-ajax.php');

    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/wc-template-functions.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/wc-template-hooks.php');

    /**Modules**/
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/modules/swatches.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/modules/breadcrumb.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/modules/quick-view.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/modules/image-mode.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/modules/form-login.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/modules/recently-viewed.php');

    /*compatible*/
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/compatible/wc_vendors.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/compatible/wc-dokan.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/compatible/wcfm_multivendor.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/compatible/wcmp_vendor.php');
    require_once get_parent_theme_file_path(HARA_WOOCOMMERCE.'/compatible/wc-germanized.php');
}

if (hara_wpthembay_core_activated()) {
    if (function_exists('mc4wp_show_form')) {
        require_once get_parent_theme_file_path(HARA_WIDGETS.'/popup_newsletter.php');
    }

    require_once get_parent_theme_file_path(HARA_WIDGETS.'/recent_post.php');
    require_once get_parent_theme_file_path(HARA_WIDGETS.'/socials.php');

    if (hara_elementor_activated()) {
        require_once get_parent_theme_file_path(HARA_WIDGETS.'/template_elementor.php');
    }
}

if (hara_redux_framework_activated()) {
    /*Redux FrameWork*/
    require_once get_parent_theme_file_path(HARA_VENDORS.'/redux-framework/class-redux.php');
    require_once get_parent_theme_file_path(HARA_VENDORS.'/redux-framework/redux-config.php');

    /**Reudx Settings**/
    require_once get_parent_theme_file_path(HARA_CONFIG.'/01-general.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/02-header.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/03-footer.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/04-mobile.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/05-style.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/06-woocommerce.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/07-blog.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/08-page-404.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/09-social-share.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/10-performance.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/11-custom-css-js.php');
    require_once get_parent_theme_file_path(HARA_CONFIG.'/12-import-export.php');
}

require_once get_parent_theme_file_path(HARA_VENDORS.'/megamenu/megamenu.php');

if (hara_elementor_activated()) {
    require_once get_parent_theme_file_path(HARA_VENDORS.'/elementor/class-elementor.php');

    if (hara_elementor_pro_activated()) {
        require_once get_parent_theme_file_path(HARA_VENDORS.'/elementor/class-elementor-pro.php');
    }

    require_once get_parent_theme_file_path(HARA_VENDORS.'/elementor/icons/icons.php');
} 