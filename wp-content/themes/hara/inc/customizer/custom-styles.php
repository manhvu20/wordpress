<?php


//convert hex to rgb
if (!function_exists('hara_tbay_getbowtied_hex2rgb')) {
    function hara_tbay_getbowtied_hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);
        
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        return implode(",", $rgb); // returns the rgb values separated by commas
        //return $rgb; // returns an array with the rgb values
    }
}


if (!function_exists('hara_tbay_color_lightens_darkens')) {
    /**
     * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
     * @param str $hex Colour as hexadecimal (with or without hash);
     * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
     * @return str Lightened/Darkend colour as hexadecimal (with hash);
     */
    function hara_tbay_color_lightens_darkens($hex, $percent)
    {
		if( empty($hex) ) return $hex;
        
       	// validate hex string
		
		$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$new_hex = '#';
		
		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}
		
		// convert to decimal and change luminosity
		for ($i = 0; $i < 3; $i++) {
			$dec = substr( $hex, $i*2, 2 );
			$dec = intval( $dec, 16 );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 ); 
			$new_hex .= str_pad( sprintf("%02x", $dec) , 2, 0, STR_PAD_LEFT );
		}		
		
		return $new_hex;
    }
}




if (!function_exists('hara_tbay_default_theme_primary_color')) {
    function hara_tbay_default_theme_primary_color()
    {
		$theme_variable = array();

		$theme_variable['main_color'] 			= '#007D71'; 
		$theme_variable['header_mobile_bg'] 	= '#fff';
		$theme_variable['header_mobile_color'] 	= '#000';
		$theme_variable['bg_back_to_top'] 	= '#fff';
		$theme_variable['hover_bg_back_to_top'] 	= '#C4743F';
		$theme_variable['color_back_to_top'] 	= '#000';
		$theme_variable['hover_color_back_to_top'] 	= '#fff';

        return apply_filters('hara_get_default_theme_color', $theme_variable);
    }
}

if ( !function_exists ('hara_tbay_default_theme_primary_fonts') ) {
	function hara_tbay_default_theme_primary_fonts() {

		$theme_variable = array();

		$theme_variable['main_font'] 			= 'Inter, sans-serif';

		$theme_variable['main_font_second'] 	= 'Cormorant Garamond, sans-serif';
		$theme_variable['font_second_enable'] 	= true; 

		return apply_filters( 'hara_get_default_theme_fonts', $theme_variable);
	}
}

if (!function_exists('hara_tbay_check_empty_customize')) {
    function hara_tbay_check_empty_customize($option, $default){
	 	echo !empty($option) ? esc_html($option) : esc_html($default);
	}
}


if (!function_exists('hara_tbay_theme_primary_color')) {
    function hara_tbay_theme_primary_color()
    {
		$default_color 		= hara_tbay_default_theme_primary_color(); 

		$main_color   = hara_tbay_get_config(('main_color'),$default_color['main_color']);
		$header_mobile_bg   = hara_tbay_get_config(('header_mobile_bg'),$default_color['header_mobile_bg']);
		$bg_back_to_top   = hara_tbay_get_config(('bg_back_to_top'),$default_color['bg_back_to_top']);
		$hover_bg_back_to_top   = hara_tbay_get_config(('hover_bg_back_to_top'),$default_color['hover_bg_back_to_top']);
		$color_back_to_top   = hara_tbay_get_config(('color_back_to_top'),$default_color['color_back_to_top']);
		$hover_color_back_to_top   = hara_tbay_get_config(('hover_color_back_to_top'),$default_color['hover_color_back_to_top']);
		$header_mobile_color   = hara_tbay_get_config(('header_mobile_color'),$default_color['header_mobile_color']);

		/*Theme Color*/
		?>
		:root {
			--tb-theme-color: <?php hara_tbay_check_empty_customize( $main_color, $default_color['main_color']) ?>;
			--tb-theme-color-hover: <?php hara_tbay_check_empty_customize( hara_tbay_color_lightens_darkens($main_color, -0.05), hara_tbay_color_lightens_darkens($default_color['main_color'], -0.05) );  ?>;
			--tb-header-mobile-bg: <?php hara_tbay_check_empty_customize($header_mobile_bg, $default_color['header_mobile_bg']) ?>;
			--tb-back-to-top-bg: <?php hara_tbay_check_empty_customize($bg_back_to_top, $default_color['bg_back_to_top']) ?>;
			--tb-back-to-top-bg-hover: <?php hara_tbay_check_empty_customize($hover_bg_back_to_top, $default_color['hover_bg_back_to_top']) ?>;
			--tb-back-to-top-color: <?php hara_tbay_check_empty_customize($color_back_to_top, $default_color['color_back_to_top']) ?>;
			--tb-back-to-top-color-hover: <?php hara_tbay_check_empty_customize($hover_color_back_to_top, $default_color['hover_color_back_to_top']) ?>;
			--tb-header-mobile-color: <?php hara_tbay_check_empty_customize($header_mobile_color, $default_color['header_mobile_color'] )?>;
		} 
		<?php
    }
}
 
if (!function_exists('hara_tbay_custom_styles')) {
    function hara_tbay_custom_styles()
    {
		ob_start();

		hara_tbay_theme_primary_color();

		$default_fonts 		= hara_tbay_default_theme_primary_fonts();

		if ( !hara_redux_framework_activated() ) {
			?>
			:root { 
				--tb-text-primary-font: <?php echo trim($default_fonts['main_font']); ?>; 
				--tb-text-second-font: <?php echo trim($default_fonts['main_font_second']); ?>;
			} 
			<?php
        }

		/*End Theme Color*/
		if (hara_redux_framework_activated()) {
			$logo_img_width        		= hara_tbay_get_config('logo_img_width');
			$logo_padding        		= hara_tbay_get_config('logo_padding');

			$logo_img_width_mobile 		= hara_tbay_get_config('logo_img_width_mobile');
			$logo_mobile_padding 		= hara_tbay_get_config('logo_mobile_padding');

			$checkout_img_width 		= hara_tbay_get_config('checkout_img_width');

			$custom_css 			= hara_tbay_get_config('custom_css');
			$css_desktop 			= hara_tbay_get_config('css_desktop');
			$css_tablet 			= hara_tbay_get_config('css_tablet');
			$css_wide_mobile 		= hara_tbay_get_config('css_wide_mobile');
			$css_mobile         	= hara_tbay_get_config('css_mobile');

			$primary_font  =  $default_fonts['main_font'];
			$second_font  =  $default_fonts['main_font_second'];


			$show_typography        = (bool) hara_tbay_get_config('show_typography', false);
			
			if ($show_typography) {
                $font_source 			= hara_tbay_get_config('font_source');

				if ( !empty(hara_tbay_get_config('main_font')['font-family']) ) {
					$primary_font 			= hara_tbay_get_config('main_font')['font-family'];
				}
				if ( !empty(hara_tbay_get_config('main_font_second')['font-family']) ) {
					$second_font					= hara_tbay_get_config('main_font_second')['font-family'];
				}
                $main_google_font_face = hara_tbay_get_config('main_google_font_face');
                $main_custom_font_face = hara_tbay_get_config('main_custom_font_face');
                $main_second_google_font_face 	= hara_tbay_get_config('main_second_google_font_face');
                $main_second_custom_font_face 	= hara_tbay_get_config('main_second_custom_font_face');
 
                if ($font_source  == "2" && $main_google_font_face) {
                    $primary_font = $main_google_font_face;
                    $second_font = $main_second_google_font_face;
                } elseif ($font_source  == "3" && $main_custom_font_face) {
                    $primary_font = $main_custom_font_face;
                    $second_font = $main_second_custom_font_face;
                } ?>
				:root {
					--tb-text-primary-font: <?php echo trim($primary_font); ?>;

					<?php if ($default_fonts['font_second_enable']) : ?> 
						--tb-text-second-font: <?php echo trim($second_font); ?>;  
					<?php endif; ?>
				}  
				<?php 
            } else {
				?>
					:root { 
						--tb-text-primary-font: <?php echo trim($default_fonts['main_font']); ?>;
						<?php if ($default_fonts['font_second_enable']) : ?>
							--tb-text-second-font: <?php echo trim($default_fonts['main_font_second']); ?>;
						<?php endif; ?>
					}
				<?php
			}

			?>
			/* Theme Options Styles */
			

					<?php if ($logo_img_width != "") : ?>
					.site-header .logo img {
						max-width: <?php echo esc_html($logo_img_width); ?>px;
					} 
					<?php endif; ?>

					<?php if ($checkout_img_width != "") : ?>
					.checkout-logo img {
						max-width: <?php echo esc_html($checkout_img_width); ?>px;
					} 
					<?php endif; ?>

					<?php if ($logo_padding != "") : ?>
					.site-header .logo img {

						<?php if (!empty($logo_padding['padding-top'])) : ?>
							padding-top: <?php echo esc_html($logo_padding['padding-top']); ?>;
						<?php endif; ?>

						<?php if (!empty($logo_padding['padding-right'])) : ?>
							padding-right: <?php echo esc_html($logo_padding['padding-right']); ?>;
						<?php endif; ?>
						
						<?php if (!empty($logo_padding['padding-bottom'])) : ?>
							padding-bottom: <?php echo esc_html($logo_padding['padding-bottom']); ?>;
						<?php endif; ?>

						<?php if (!empty($logo_padding['padding-left'])) : ?>
							padding-left: <?php echo esc_html($logo_padding['padding-left']); ?>;
						<?php endif; ?>

					}
					<?php endif; ?> 

					@media (max-width: 1199px) {

						<?php if ($logo_img_width_mobile != "") : ?>
						/* Limit logo image height for mobile according to mobile header height */
						.mobile-logo a img {
							width: <?php echo esc_html($logo_img_width_mobile); ?>px;
						}     
						<?php endif; ?>       

						<?php if ($logo_mobile_padding['padding-top'] != "" || $logo_mobile_padding['padding-right'] || $logo_mobile_padding['padding-bottom'] || $logo_mobile_padding['padding-left']) : ?>
						.mobile-logo a img {

							<?php if (!empty($logo_mobile_padding['padding-top'])) : ?>
								padding-top: <?php echo esc_html($logo_mobile_padding['padding-top']); ?>;
							<?php endif; ?>

							<?php if (!empty($logo_mobile_padding['padding-right'])) : ?>
								padding-right: <?php echo esc_html($logo_mobile_padding['padding-right']); ?>;
							<?php endif; ?>

							<?php if (!empty($logo_mobile_padding['padding-bottom'])) : ?>
								padding-bottom: <?php echo esc_html($logo_mobile_padding['padding-bottom']); ?>;
							<?php endif; ?>

							<?php if (!empty($logo_mobile_padding['padding-left'])) : ?>
								padding-left: <?php echo esc_html($logo_mobile_padding['padding-left']); ?>;
							<?php endif; ?>
						
						}
						<?php endif; ?>
					}

					@media screen and (max-width: 782px) {
						html body.admin-bar{
							top: -46px !important;
							position: relative;
						}
					}

				/* Custom CSS */
				<?php
				if ($custom_css != '') {
					echo trim($custom_css);
				}
			if ($css_desktop != '') {
				echo '@media (min-width: 1024px) { ' . trim($css_desktop) . ' }';
			}
			if ($css_tablet != '') {
				echo '@media (min-width: 768px) and (max-width: 1023px) {' . trim($css_tablet) . ' }';
			}
			if ($css_wide_mobile != '') {
				echo '@media (min-width: 481px) and (max-width: 767px) { ' . trim($css_wide_mobile) . ' }';
			}
			if ($css_mobile != '') {
				echo '@media (max-width: 480px) { ' . trim($css_mobile) . ' }';
			}
		}

        
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}

		$custom_css = implode($new_lines);

		wp_enqueue_style('hara-style', HARA_THEME_DIR . '/style.css', array(), '1.0');

		wp_add_inline_style('hara-style', $custom_css);
		
    }

    add_action('wp_enqueue_scripts', 'hara_tbay_custom_styles', 200);
}
