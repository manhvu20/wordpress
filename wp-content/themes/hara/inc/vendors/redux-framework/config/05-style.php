<?php
/**
 * Redux Framework checkbox config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;


$default_color = hara_tbay_default_theme_primary_color();
$default_fonts = hara_tbay_default_theme_primary_fonts();

if ( !isset($default_color['main_color_second']) ) {
	$default_color['main_color_second'] = '';
}

if ( !isset($default_fonts['main_font_second']) ) {
	$default_fonts['main_font_second'] = '';
}

/** Style Settings **/
Redux::set_section(
	$opt_name,
	array(
        'icon' => 'zmdi zmdi-format-color-text',
        'title' => esc_html__('Style', 'hara'),
	)
);


// Style main color settings
Redux::set_section(
	$opt_name,
	array(
		'title' => esc_html__('Main', 'hara'),
		'subsection' => true,
		'fields' => array(
			array(
				'id'       => 'boby_bg',
				'type'     => 'background',
				'output'   => array( 'body' ),
				'title'    => esc_html__('Body Background', 'hara'),
				'subtitle' => esc_html__('Body background with image, color, etc.', 'hara'),
			),
		   
			array(
				'title' => esc_html__('Theme Main Color', 'hara'),
				'id' => 'main_color',
				'type' => 'color',
				'transparent' => false,
				'default' => $default_color['main_color'],
			),
			
		)
	)
);


// Style Typography settings
Redux::set_section(
	$opt_name,
	array(
		'subsection' => true,
		'title' => esc_html__('Typography', 'hara'),
		'fields' => array(
			array(
				'id' => 'show_typography',
				'type' => 'switch',
				'title' => esc_html__('Edit Typography', 'hara'),
				'default' => false
			),
			array(
				'title'    => esc_html__('Font Source', 'hara'),
				'id'       => 'font_source',
				'type'     => 'radio',
				'required' => array('show_typography','=', true),
				'options'  => array(
					'1' => 'Standard + Google Webfonts',
					'2' => 'Google Custom',
					'3' => 'Custom Fonts'
				),
				'default' => '1'
			),
			array(
				'id'=>'font_google_code',
				'type' => 'text',
				'title' => esc_html__('Google Link', 'hara'),
				'subtitle' => '<em>'.esc_html__('Paste the provided Google Code', 'hara').'</em>',
				'default' => '',
				'desc' => esc_html__('e.g.: https://fonts.googleapis.com/css?family=Open+Sans', 'hara'),
				'required' => array('font_source','=','2')
			),

			array(
				'id' => 'main_custom_font_info',
				'icon' => true,
				'type' => 'info',
				'raw' => '<h3 style="margin: 0;">'. sprintf(
					'%1$s <a href="%2$s">%3$s</a>',
					esc_html__('Video guide custom font in ', 'hara'),
					esc_url('https://www.youtube.com/watch?v=ljXAxueAQUc'),
					esc_html__('here', 'hara')
				) .'</h3>',
				'required' => array('font_source','=','3')
			),

			array(
				'id' => 'main_font_info',
				'icon' => true,
				'type' => 'info',
				'raw' => '<h3 style="margin: 0;"> '.esc_html__('Main Font', 'hara').'</h3>',
				'required' => array('show_typography','=', true),
			),

			// Standard + Google Webfonts
			array(
				'title' => esc_html__('Font Face', 'hara'),
				'id' => 'main_font',
				'type' => 'typography',
				'line-height' => false,
				'text-align' => false,
				'font-style' => false,
				'font-weight' => false,
				'all_styles'=> true,
				'font-size' => false,
				'color' => false,
				'default' => array(
					'font-family' => '',
					'subsets' => '',
				),
				'required' => array(
					array('font_source','=','1'),
					array('show_typography','=', true)
				)
			),
			
			// Google Custom
			array(
				'title' => esc_html__('Google Font Face', 'hara'),
				'subtitle' => '<em>'.esc_html__('Enter your Google Font Name for the theme\'s Main Typography', 'hara').'</em>',
				'desc' => esc_html__('e.g.: &#39;Open Sans&#39;', 'hara'),
				'id' => 'main_google_font_face',
				'type' => 'text',
				'default' => '',
				'required' => array(
					array('font_source','=','2'),
					array('show_typography','=', true)
				)
			),

			// main Custom fonts
			array(
				'title' => esc_html__('Main custom Font Face', 'hara'),
				'subtitle' => '<em>'.esc_html__('Enter your Custom Font Name for the theme\'s Main Typography', 'hara').'</em>',
				'desc' => esc_html__('e.g.: &#39;Open Sans&#39;', 'hara'),
				'id' => 'main_custom_font_face',
				'type' => 'text',
				'default' => '',
				'required' => array(
					array('font_source','=','3'),
					array('show_typography','=', true)
				)
			),

			array (
				'id' => 'main_font_second_info',
				'icon' => true,
				'type' => 'info',
				'raw' => '<h3 style="margin: 0;"> '.esc_html__('Font Second', 'hara').'</h3>',
				'required' => array( 
					array('show_typography','=', true),
					array('show_typography','=', $default_fonts['main_font_second']),
				)
			),

			// Standard + Google Webfonts
			array (
				'title' => esc_html__('Font Face Second', 'hara'),
				'id' => 'main_font_second',
				'type' => 'typography',
				'line-height' => false,
				'text-align' => false,
				'font-style' => false,
				'font-weight' => false,
				'all_styles'=> true,
				'font-size' => false,
				'color' => false,
				'default' => array (
					'font-family' => '',
					'subsets' => '',
				),
				'required' => array( 
					array('font_source','=','1'), 
					array('show_typography','=', true),
					array('show_typography','=', $default_fonts['font_second_enable']),
				)
			),

			// Google Custom                        
			array (
				'title' => esc_html__('Google Font Face Second', 'hara'),
				'subtitle' => '<em>'.esc_html__('Enter your Google Font Name for the theme\'s Main Typography', 'hara').'</em>',
				'desc' => esc_html__('e.g.: &#39;Open Sans&#39;, sans-serif', 'hara'),
				'id' => 'main_second_google_font_face',
				'type' => 'text',
				'default' => '',
				'required' => array( 
					array('font_source','=','2'), 
					array('show_typography','=', true),
					array('show_typography','=', $default_fonts['font_second_enable']),
				)
			),                    

			// main Custom fonts                      
			array (
				'title' => esc_html__('Custom Font Face Second', 'hara'),
				'subtitle' => '<em>'.esc_html__('Enter your Custom Font Name for the theme\'s Main Typography', 'hara').'</em>',
				'desc' => esc_html__('e.g.: &#39;Open Sans&#39;, sans-serif', 'hara'),
				'id' => 'main_second_custom_font_face',
				'type' => 'text',
				'default' => '',
				'required' => array( 
					array('font_source','=','3'), 
					array('show_typography','=', true),
					array('show_typography','=', $default_fonts['font_second_enable']),
				)
			),
		)
	)
);


// Style Header Mobile settings
Redux::set_section(
	$opt_name,
	array(
		'title' => esc_html__('Header Mobile', 'hara'),
		'subsection' => true,
		'fields' => array(

			array(
				'title' => esc_html__('Background Color', 'hara'),
				'id' => 'header_mobile_bg',
				'type' => 'color',
				'transparent' => false,
			),

			array(
				'title' => esc_html__('Header Color', 'hara'),
				'id' => 'header_mobile_color',
				'type' => 'color',
				'transparent' => false,
			),
		)
	)
);