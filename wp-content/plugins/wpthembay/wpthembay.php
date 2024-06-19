<?php
/**
 * WPthembay Core Plugin
 *
 * A simple, truly extensible and fully responsive options framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     WPthembay Core
 * Plugin URI:      https://themeforest.net/user/thembay
 * Description:     WPthembay Core. A plugin required to activate the functionality in the themes.
 * Author:          Thembay Team
 * Author URI:      https://themeforest.net/user/thembay/
 * Version:         1.0.7
 * Text Domain:     wpthembay
 * License:         GPL3+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     languages
 */

define( 'WPTHEMBAY_ELEMENTOR_VERSION', '1.0.7');
define( 'WPTHEMBAY_ELEMENTOR_URL', plugin_dir_url( __FILE__ ) ); 
define( 'WPTHEMBAY_ELEMENTOR_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPTHEMBAY_CONTENT_TYPES_LIB', dirname(__FILE__) . '/lib/');
define( 'WPTHEMBAY_ELEMENTOR_CLASSES', dirname(__FILE__) . '/classes/');

require_once( WPTHEMBAY_ELEMENTOR_DIR . 'plugin-update-checker/plugin-update-checker.php' );
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://plugins.thembay.com/update/wpthembay/plugin.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'wpthembay'
);

if( class_exists( 'RWMB_Loader' ) ) {
	require_once( WPTHEMBAY_ELEMENTOR_DIR . 'metabox-addons/group/meta-box-group.php' );
	require_once( WPTHEMBAY_ELEMENTOR_DIR . 'metabox-addons/conditional-logic/meta-box-conditional-logic.php' );
}

class WPthembayClass {
    function __construct() {
		// Init plugins
		$this->loadInit();

		load_plugin_textdomain('wpthembay', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		add_filter( 'wp_calculate_image_srcset', array( $this, 'disable_image_srcset' ), 10, 1 );

		add_action( 'widgets_init', array( $this, 'register_post_types' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'tbay_enqueue_scripts' ), 10 );
		if( class_exists('WooCommerce') ) {
			add_action( 'add_meta_boxes', array( $this, 'product_add_metaboxes' ), 50 );

			if (class_exists('YITH_WFBT')) {
				add_action( 'wp_enqueue_scripts', array( $this, 'remove_scripts_yith_wfbt' ), 20 );
			}
		}

		add_shortcode( 'tbay_block', [ $this, 'render_template' ] );

    }

	public function tbay_enqueue_scripts() {
		wp_enqueue_script( 'wpthembay-admin', WPTHEMBAY_ELEMENTOR_URL . 'assets/admin.js', array( 'jquery'  ), '20131022', true );
        wp_enqueue_style( 'wpthembay-admin', WPTHEMBAY_ELEMENTOR_URL . 'assets/backend.css' );
    }

	public function loadInit() { 
		require WPTHEMBAY_ELEMENTOR_DIR . 'functions-helper.php';
		require WPTHEMBAY_ELEMENTOR_DIR . 'classes/class-tbay-widgets.php';
		$this->load_file(WPTHEMBAY_ELEMENTOR_CLASSES);
	
		add_action( 'widgets_init', array( $this, 'register_widgets' ), 10 );
    }

	public function load_file($path){
		$files = array_diff(scandir($path), array('..', '.'));
		if(count($files)>0){
			foreach ($files as  $file) {
				if (strpos($file, '.php') !== false)
					require_once($path . $file);
			}
		}		
	}

    public function product_add_metaboxes() {
        if( function_exists( 'tbay_swatch_attribute_template' ) ) {
            add_meta_box( 'woocommerce-product-swatch-attribute', esc_html__( 'Swatch attribute to display', 'wpthembay' ), 'tbay_swatch_attribute_template', 'product', 'side' );    
        }    

        if( function_exists( 'tbay_single_select_single_layout_template' ) ) {
            add_meta_box( 'woocommerce-product-single-layout', esc_html__( 'Select Single Product Layout', 'wpthembay' ), 'tbay_single_select_single_layout_template', 'product', 'side' );  
        }
    }

	public function disable_image_srcset( $media_item ) {
		return false;	
	}

	public function register_post_types() {

        $types = array('custom-post');

		if( defined('TBAY_DISCOUNT_CAMPAIGN') && TBAY_DISCOUNT_CAMPAIGN && class_exists('WooCommerce') ) {
			$types[] = 'discount-campaign';
		}

        $post_types = apply_filters( 'wpthembay_register_post_types', $types);
        if ( !empty($post_types) ) {
            foreach ($post_types as $post_type) {
                if ( file_exists( WPTHEMBAY_ELEMENTOR_CLASSES . 'post-types/'.$post_type.'.php' ) ) {
                    require WPTHEMBAY_ELEMENTOR_CLASSES . 'post-types/'.$post_type.'.php';
                }
            }
        }
    }

	public function register_widgets() {

		$widgets = array(
			'Tbay_Widget_Popup_Newsletter',
			'Tbay_Widget_Recent_Post',
			'Tbay_Widget_Socials',
			'Tbay_Widget_Template_Custom_Block',
		);  

		$widgets = apply_filters( 'wpthembay_register_widgets_theme', $widgets);

		foreach ($widgets as $widget) {
			if(class_exists($widget)) {
				
				register_widget( $widget );
			}   
		}
					
	}

	function remove_scripts_yith_wfbt() {
		wp_deregister_script('yith-wfbt-query-dialog');
		wp_deregister_style('yith-wfbt-query-dialog-style');
	}

	/**
	 * Callback to shortcode.
	 *
	 * @param array $atts attributes for shortcode.
	 */
	public function render_template( $atts ) {
		$atts = shortcode_atts(
			[
				'id' => '',
			],
			$atts,
			'tbay_block'
		);

		$slug = ! empty( $atts['id'] ) ? apply_filters( 'tbay_render_template_id', $atts['id'] ) : '';

		

		if ( empty( $slug ) ) {
			return '';
		}

		if ( $post = get_page_by_path( $slug, OBJECT, 'tbay_custom_post' ) ) {
			$id = $post->ID;
		} else {
			return false;
		}

		if ( function_exists('elementor_load_plugin_textdomain') && Elementor\Plugin::instance()->documents->get( $id )->is_built_with_elementor() ) {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( $id );
			} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
				// Load elementor styles.
				$css_file = new \Elementor\Post_CSS_File( $id );
			}

			$css_file->enqueue();

			return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
		} else {
			$content_post = get_post($id);
			$content = $content_post->post_content;
			return $content;
		}

	}

}

// Finally initialize code
new WPthembayClass();