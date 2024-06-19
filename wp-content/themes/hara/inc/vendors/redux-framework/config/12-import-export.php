<?php
/**
 * Redux Framework checkbox config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;


/** Import / Export Settings **/
Redux::set_section(
	$opt_name,
	array(
        'title' => esc_html__('Import / Export', 'hara'),
        'desc' => esc_html__('Import and Export your Redux Framework settings from file, text or URL.', 'hara'),
        'icon' => 'zmdi zmdi-download',
        'fields' => array(
            array(
                'id' => 'opt-import-export',
                'type' => 'import_export',
                'title' => 'Import Export',
                'subtitle' => 'Save and restore your Redux options',
                'full_width' => false,
            ),
        ),
	)
);