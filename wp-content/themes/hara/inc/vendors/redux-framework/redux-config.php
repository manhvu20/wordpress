<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if ( !hara_redux_framework_activated() )  return;

$opt_name = 'hara_tbay_theme_options';

$theme = wp_get_theme();

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'          => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'      => $theme->get('Name'),
    // Name that appears at the top of your panel
    'display_version'   => esc_html__('Version ', 'hara').$theme->get('Version'),
    'ajax_save'         => true,
    // Version that appears at the top of your panel
    'menu_type'         => 'menu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'    => true,
    // Show the sections below the admin menu item or not
    'menu_title'        => esc_html__('Hara Options', 'hara'),
    'page_title'        => esc_html__('Hara Options', 'hara'),

    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'    => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography' => false,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar' => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon' => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority' => 50,
    // Choose an priority for the admin bar menu
    'global_variable' => 'hara_options',
    // Set a different name for your global variable other than the opt_name
    'dev_mode' => false,
    'forced_dev_mode_off' => false,
    // Show the time the page took to load, etc
    'update_notice' => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer' => true,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority' => 61,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent' => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions' => 'manage_options',
    // Specify a custom URL to an icon
    'last_tab' => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon' => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug' => 'hara_options',
    // Page slug used to denote the panel
    'save_defaults' => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show' => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark' => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export' => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time' => 60 * MINUTE_IN_SECONDS,
    'output' => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag' => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database' => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'system_info' => false,
    // REMOVE

    // HINTS
    'hints' => array(
        'icon' => 'icon-question-sign',
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'light',
            'shadow' => true,
            'rounded' => false,
            'style' => '',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'mouseover',
            ),
            'hide' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'click mouseleave',
            ),
        ),
    )
);

$args['intro_text']     = '';
$args['footer_text']    = '';

Redux::set_args( $opt_name, $args );

if (!function_exists('hara_settings_columns')) {
    function hara_settings_columns()
    {
        $settings = array(
            '1' => esc_html__('1 Column', 'hara'),
            '2' => esc_html__('2 Columns', 'hara'),
            '3' => esc_html__('3 Columns', 'hara'),
            '4' => esc_html__('4 Columns', 'hara'),
            '5' => esc_html__('5 Columns', 'hara'),
            '6' => esc_html__('6 Columns', 'hara')
        );

        return $settings;
    }
}

if (!function_exists('hara_settings_aspect_ratio')) {
    function hara_settings_aspect_ratio()
    {
        $settings = array(
            '16_9' => '16:9',
            '4_3' => '4:3',
        );

        return $settings;
    }
}

if (!function_exists('hara_settings_blog_image_size')) {
    function hara_settings_blog_image_size()
    {
        $settings = array(
            'thumbnail'         => esc_html__('Thumbnail', 'hara'),
            'medium'            => esc_html__('Medium', 'hara'),
            'large'             => esc_html__('Large', 'hara'),
            'full'              => esc_html__('Full', 'hara'),
        );

        return $settings;
    }
}