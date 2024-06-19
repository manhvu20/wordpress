<?php
/**
 * Redux Framework checkbox config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;


/** Page 404 Settings **/
Redux::set_section(
	$opt_name,
	array(
        'icon' => 'zmdi zmdi-search-replace',
        'title' => esc_html__('Page 404', 'hara'),
        'fields' => array(
            array(
                'id'       => 'img_404',
                'type' => 'media',
                'title' => esc_html__('Upload Image 404', 'hara'),
                'subtitle' => esc_html__('Image File (.png or .gif)', 'hara'),
            ),
        )
	)
);