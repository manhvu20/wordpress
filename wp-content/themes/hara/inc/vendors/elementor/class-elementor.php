<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Hara_Elementor_Addons
{
    public function __construct()
    {
        $this->include_control_customize_widgets();
        $this->include_render_customize_widgets();

        add_action('elementor/elements/categories_registered', array( $this, 'add_category' ));

        add_action('elementor/widgets/register', array( $this, 'include_widgets' ));

        add_action('wp', [ $this, 'regeister_scripts_frontend' ]);

        // frontend
        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [ $this, 'frontend_after_register_scripts' ]);
        add_action('elementor/frontend/after_enqueue_scripts', [ $this, 'frontend_after_enqueue_scripts' ]);

        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_icons'], 99);

        // editor
        add_action('elementor/editor/after_register_scripts', [ $this, 'editor_after_register_scripts' ]);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'editor_after_enqueue_scripts']);

    
        add_action('widgets_init', array( $this, 'register_wp_widgets' ));
    }

    public function editor_after_register_scripts()
    {
        if (hara_is_remove_scripts()) {
            return;
        }

        $suffix = (hara_tbay_get_config('minified_js', false)) ? '.min' : HARA_MIN_JS;
        // /*slick jquery*/
        wp_register_script('slick', HARA_SCRIPTS . '/slick' . $suffix . '.js', array(), '1.0.0', true);
        wp_register_script('hara-custom-slick', HARA_SCRIPTS . '/custom-slick' . $suffix . '.js', array( ), HARA_THEME_VERSION, true);

        wp_register_script('hara-script', HARA_SCRIPTS . '/functions' . $suffix . '.js', array(), HARA_THEME_VERSION, true);
    
        wp_register_script('popper', HARA_SCRIPTS . '/popper' . $suffix . '.js', array( ), '1.12.9', true);
        wp_register_script('bootstrap', HARA_SCRIPTS . '/bootstrap' . $suffix . '.js', array( 'popper' ), '5.1', true);
          
        // Add before after image
        wp_register_script( 'before-after-image', HARA_SCRIPTS . '/cndk.beforeafter' . $suffix . '.js', array('hara-script' ), '0.0.2', true ); 
        wp_register_style( 'before-after-image', HARA_STYLES . '/cndk.beforeafter.css', array(), '0.0.2' );

        /*Treeview menu*/
        wp_register_script('jquery-treeview', HARA_SCRIPTS . '/jquery.treeview' . $suffix . '.js', array( ), '1.4.0', true);

        wp_enqueue_script('waypoints', HARA_SCRIPTS . '/jquery.waypoints' . $suffix . '.js', array(), '4.0.0', true);
       
        // Add js Sumoselect version 3.0.2
        wp_register_style('sumoselect', HARA_STYLES . '/sumoselect.css', array(), '1.0.0', 'all');
        wp_register_script('jquery-sumoselect', HARA_SCRIPTS . '/jquery.sumoselect' . $suffix . '.js', array(), '3.0.2', true);
    }

    public function frontend_after_enqueue_scripts()
    {
    }

    public function editor_after_enqueue_scripts()
    {
    }

    public function enqueue_editor_icons()
    {
        wp_enqueue_style('font-awesome', HARA_STYLES . '/font-awesome.css', array(), '5.10.2');
        wp_enqueue_style('simple-line-icons', HARA_STYLES . '/simple-line-icons.css', array(), '2.4.0');
        wp_enqueue_style('hara-font-tbay-custom', HARA_STYLES . '/font-tbay-custom.css', array(), '1.0.0');
        wp_enqueue_style('material-design-iconic-font', HARA_STYLES . '/material-design-iconic-font.css', array(), '2.2.0');

        if (hara_elementor_is_edit_mode() || hara_elementor_preview_page() || hara_elementor_preview_mode()) {
            wp_enqueue_style('hara-elementor-editor', HARA_STYLES . '/elementor-editor.css', array(), HARA_THEME_VERSION);
        }
    }


    /**
     * @internal Used as a callback
     */
    public function frontend_after_register_scripts()
    {
        $this->editor_after_register_scripts();
    }


    public function register_wp_widgets()
    {
    }

    public function regeister_scripts_frontend()
    {
    }


    public function add_category( $elements_manager )
    {
        $elements_manager->add_category(
            'hara-elements',
            array(
                'title' => esc_html__('Hara Elements', 'hara'),
                'icon'  => 'fa fa-plug',
            )
        );
    }

    /**
     * @param $widgets_manager Elementor\Widgets_Manager
     */
    public function include_widgets($widgets_manager)
    {
        $this->include_abstract_widgets($widgets_manager);
        $this->include_general_widgets($widgets_manager);
        $this->include_header_widgets($widgets_manager);
        $this->include_woocommerce_widgets($widgets_manager);
    }


    /**
     * Widgets General Theme
     */
    public function include_general_widgets($widgets_manager)
    {
        $elements = hara_elementor_general_widgets();

        foreach ($elements as $file) {
            $path   = HARA_ELEMENTOR .'/elements/general/' . $file . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Widgets WooComerce Theme
     */
    public function include_woocommerce_widgets($widgets_manager)
    {
        if (!hara_woocommerce_activated()) return;

        $elements = hara_elementor_woocommerce_widgets();

        foreach ($elements as $file) {
            $path   = HARA_ELEMENTOR .'/elements/woocommerce/' . $file . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Widgets Header Theme
     */
    public function include_header_widgets($widgets_manager)
    {
        $elements = hara_elementor_header_widgets();

        foreach ($elements as $file) {
            $path   = HARA_ELEMENTOR .'/elements/header/' . $file . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }


    /**
     * Widgets Abstract Theme
     */
    public function include_abstract_widgets($widgets_manager)
    {
        $abstracts = array(
            'image',
            'base',
            'responsive',
            'carousel',
        );

        $abstracts = apply_filters('hara_abstract_elements_array', $abstracts);

        foreach ($abstracts as $file) {
            $path   = HARA_ELEMENTOR .'/abstract/' . $file . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    public function include_control_customize_widgets()
    {
        $widgets = array(
            'sticky-header',
            'column',
            'settings-layout',
            'global-typography',
        );

        $widgets = apply_filters('hara_customize_elements_array', $widgets);
 
        foreach ($widgets as $file) {
            $control   = HARA_ELEMENTOR .'/elements/customize/controls/' . $file . '.php';
            if (file_exists($control)) {
                require_once $control;
            }
        }
    }

    public function include_render_customize_widgets()
    {
        $widgets = array(
            'sticky-header',
        );

        $widgets = apply_filters('hara_customize_elements_array', $widgets);
 
        foreach ($widgets as $file) {
            $render    = HARA_ELEMENTOR .'/elements/customize/render/' . $file . '.php';
            if (file_exists($render)) {
                require_once $render;
            }
        }
    }
}

new Hara_Elementor_Addons();
