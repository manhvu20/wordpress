<?php
/**
 * Redux Framework checkbox config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

/** Footer Settings **/
Redux::set_section(
	$opt_name,
	array(
        'icon' => 'zmdi zmdi-border-bottom',
        'title' => esc_html__('Footer', 'hara'),
        'fields' => array(
            array(
                'id' => 'footer_type',
                'type' => 'select',
                'title' => esc_html__('Select Footer Layout', 'hara'),
                'options' => hara_tbay_get_footer_layouts(),
                'default' => 'footer_default'
            ),
            array(
                'id' => 'copyright_text',
                'type' => 'editor',
                'title' => esc_html__('Copyright Text', 'hara'),
                'default' => esc_html__('Copyright  &#64; 2023 Hara Designed by ThemBay. All Rights Reserved.', 'hara'),
                'required' => array('footer_type','=','footer_default')
            ),
            array(
                'id' => 'back_to_top',
                'type' => 'switch',
                'title' => esc_html__('Enable "Back to Top" Button', 'hara'),
                'default' => true,
            ),
            array(
                'id' => 'bg_type_back_to_top',
                'type' => 'select',
                'options' => array (
                    'type_color' => esc_html__('Color','hara'),
                    'type_image' => esc_html__('Image','hara'),
                ),
                'required' => array('back_to_top','=',true),
                'title' => esc_html__('Background Type', 'hara'),
                'default' => 'type_image', 
            ),
            array(
                'id' => 'bg_back_to_top',
                'type' => 'color', 
				'transparent' => false,  
                'required' => array(
					array('back_to_top','=',true),
					array('bg_type_back_to_top','=', 'type_color')
				),
                'title' => esc_html__('Background Color', 'hara'),
            ),
            array(
                'id' => 'hover_bg_back_to_top',
                'type' => 'color', 
				'transparent' => false,  
                'required' => array(
					array('back_to_top','=',true),
					array('bg_type_back_to_top','=', 'type_color')
				),
                'title' => esc_html__('Hover Background Color', 'hara'),
            ),
            array(
                'id' => 'bg_img_back_to_top',
                'type' => 'media', 
                'required' =>  array(
					array('back_to_top','=',true),
					array('bg_type_back_to_top','=', 'type_image') 
				),
                'title' => esc_html__('Background Image', 'hara'),
                'subtitle' => esc_html__('Image File (.png or .gif)', 'hara'), 
            ), 
            array(
                'id' => 'color_back_to_top',
                'type' => 'color',
				'transparent' => false,
                'required' => array('back_to_top','=',true),
                'title' => esc_html__('Color', 'hara'),
            ),
            array(
                'id' => 'hover_color_back_to_top', 
                'type' => 'color',
				'transparent' => false,
                'required' => array('back_to_top','=',true),
                'title' => esc_html__('Hover Color', 'hara'),
            ),
        )

	)
);