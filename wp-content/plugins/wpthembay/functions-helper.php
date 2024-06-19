<?php
/**
 * functions for WPthembay Core
 *
 * @package    wpthembay
 * @author     Thembay Teams <thembayteam@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2021-2022 WPthembay Core
 */

if( ! function_exists( 'wpthembay_get_widget_locate' ) ) {
    function wpthembay_get_widget_locate( $name, $plugin_dir = WPTHEMBAY_ELEMENTOR_DIR ) {
    	$template = '';
    	
    	// Child theme
    	if ( ! $template && ! empty( $name ) && file_exists( get_stylesheet_directory() . "/widgets/{$name}" ) ) {
    		$template = get_stylesheet_directory() . "/widgets/{$name}";
    	}

    	// Original theme
    	if ( ! $template && ! empty( $name ) && file_exists( get_template_directory() . "/widgets/{$name}" ) ) {
    		$template = get_template_directory() . "/widgets/{$name}";
    	}

    	// Plugin
    	if ( ! $template && ! empty( $name ) && file_exists( $plugin_dir . "/templates/widgets/{$name}" ) ) {
    		$template = $plugin_dir . "/templates/widgets/{$name}";
    	}

    	// Nothing found
    	if ( empty( $template ) ) {
    		throw new Exception( "Template /templates/widgets/{$name} in plugin dir {$plugin_dir} not found." );
    	}

    	return $template;
    }
}

