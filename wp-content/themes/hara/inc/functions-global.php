<?php

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Hara 1.0
 */
define('HARA_THEME_VERSION', '1.0');

/**
 * ------------------------------------------------------------------------------------------------
 * Define constants.
 * ------------------------------------------------------------------------------------------------
 */
define('HARA_THEME_DIR', get_template_directory_uri());
define('HARA_THEMEROOT', get_template_directory());
define('HARA_IMAGES', HARA_THEME_DIR . '/images');
define('HARA_SCRIPTS', HARA_THEME_DIR . '/js');

define('HARA_STYLES', HARA_THEME_DIR . '/css');

define('HARA_INC', 'inc');
define('HARA_MERLIN', HARA_INC . '/merlin');
define('HARA_CLASSES', HARA_INC . '/classes');
define('HARA_VENDORS', HARA_INC . '/vendors');
define('HARA_CONFIG', HARA_VENDORS . '/redux-framework/config');
define('HARA_WOOCOMMERCE', HARA_VENDORS . '/woocommerce');
define('HARA_ELEMENTOR', HARA_THEMEROOT . '/inc/vendors/elementor');
define('HARA_ELEMENTOR_TEMPLATES', HARA_THEMEROOT . '/elementor_templates');
define('HARA_PAGE_TEMPLATES', HARA_THEMEROOT . '/page-templates');
define('HARA_WIDGETS', HARA_INC . '/widgets');

define('HARA_ASSETS', HARA_THEME_DIR . '/inc/assets');
define('HARA_ASSETS_IMAGES', HARA_ASSETS    . '/images');

define('HARA_MIN_JS', '');

if (! isset($content_width)) {
    $content_width = 660;
}

function hara_tbay_get_config($name, $default = '')
{
    global $hara_options;
    if (isset($hara_options[$name])) {
        return $hara_options[$name];
    }
    return $default;
}

function hara_tbay_get_global_config($name, $default = '')
{
    $options = get_option('hara_tbay_theme_options', array());
    if (isset($options[$name])) {
        return $options[$name];
    }
    return $default;
}
