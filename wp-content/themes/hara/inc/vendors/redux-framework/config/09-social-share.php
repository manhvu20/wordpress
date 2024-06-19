<?php
/**
 * Redux Framework checkbox config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;


/** Social Share Settings **/
Redux::set_section(
	$opt_name,
	array(
        'icon' => 'zmdi zmdi-share',
        'title' => esc_html__('Social Share', 'hara'),
        'fields' => array(
            array(
                'id' => 'enable_code_share',
                'type' => 'switch',
                'title' => esc_html__('Enable Code Share', 'hara'),
                'default' => true
            ),
            array(
                'id'       => 'sortable_sharing',
                'type'     => 'sortable', 
                'mode'     => 'checkbox',
                'required' => array('enable_code_share','=',1),
                'title'    => esc_html__('Sortable Sharing', 'hara'),
                'options'  => array(
                    'facebook'      => 'Facebook',
                    'twitter'       => 'Twitter',
                    'linkedin'      => 'Linkedin',
                    'pinterest'     => 'Pinterest',
                    'whatsapp'      => 'Whatsapp',
                    'email'         => 'Email',
                ),
                'default'   => array(
                    'facebook'  => true,
                    'twitter'   => true,
                    'linkedin'  => true,
                    'pinterest' => false,
                    'whatsapp'  => false,
                    'email'     => true,
                )
            ),
        )
	)
);