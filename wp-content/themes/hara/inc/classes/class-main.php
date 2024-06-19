<?php

/**
 * Class hara_setup_theme'
 */
class hara_setup_theme
{
    public function __construct()
    {
        add_action('after_setup_theme', array( $this, 'setup' ), 10);

        add_action('wp_enqueue_scripts', array( $this, 'load_fonts_url' ), 10);
        add_action('wp_enqueue_scripts', array( $this, 'add_scripts' ), 100);
        add_action('wp_footer', array( $this, 'footer_scripts' ), 20);
        add_action('widgets_init', array( $this, 'widgets_init' ));
        add_filter('frontpage_template', array( $this, 'front_page_template' ));

        /**Remove fonts scripts**/
        add_action('wp_enqueue_scripts', array( $this, 'remove_fonts_redux_url' ), 1000);

        add_action('admin_enqueue_scripts', array( $this, 'load_admin_styles' ), 1000);


        add_action('after_switch_theme', array( $this, 'add_cpt_support'), 10);

        add_filter('sbi_use_theme_templates', array( $this, 'instagram_use_theme_templates'), 10, 1);

        add_action('wcmp_frontend_enqueue_scripts', array( $this, 'add_wcmp_frontend_enqueue_scripts' ), 10);
    }

    /**
     * Enqueue scripts and styles.
     */
    public function add_scripts()
    {
        if (hara_is_remove_scripts()) {
            return;
        }
       
        $suffix = (hara_tbay_get_config('minified_js', false)) ? '.min' : HARA_MIN_JS;

        // load bootstrap style
        if (is_rtl()) {
            wp_enqueue_style('bootstrap', HARA_STYLES . '/bootstrap.rtl.css', array(), '5.1');
        } else {
            wp_enqueue_style('bootstrap', HARA_STYLES . '/bootstrap.css', array(), '5.1');
        }
        
        // Load our main stylesheet.
        if (is_rtl()) {
            $css_path =  HARA_STYLES . '/template.rtl.css';
        } else {
            $css_path =  HARA_STYLES . '/template.css';
        }

        $css_array = array();

        if (hara_elementor_activated()) {
            array_push($css_array, 'elementor-frontend');
        }
        wp_enqueue_style('hara-template', $css_path, $css_array, HARA_THEME_VERSION);
        wp_enqueue_style('hara-style', HARA_THEME_DIR . '/style.css', array(), HARA_THEME_VERSION);

        /*Put CSS elementor post to header*/
        hara_get_elementor_post_scripts(); 

        //load font awesome
        wp_enqueue_style('font-awesome', HARA_STYLES . '/font-awesome.css', array(), '5.10.2');

        //load font custom icon tbay
        wp_enqueue_style('hara-font-tbay-custom', HARA_STYLES . '/font-tbay-custom.css', array(), '1.0.0');

        //load simple-line-icons
        wp_enqueue_style('simple-line-icons', HARA_STYLES . '/simple-line-icons.css', array(), '2.4.0');

        //load material font icons
        wp_enqueue_style('material-design-iconic-font', HARA_STYLES . '/material-design-iconic-font.css', array(), '2.2.0');

        // load animate version 3.5.0
        wp_enqueue_style('animate', HARA_STYLES . '/animate.css', array(), '3.5.0');

        
        wp_enqueue_script('hara-skip-link-fix', HARA_SCRIPTS . '/skip-link-fix' . $suffix . '.js', array(), HARA_THEME_VERSION, true);

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }


        /*mmenu menu*/ 
        wp_register_script('jquery-mmenu', HARA_SCRIPTS . '/jquery.mmenu' . $suffix . '.js', array( 'underscore' ), '7.0.5', true);
     
        /*Treeview menu*/
        wp_enqueue_style('jquery-treeview', HARA_STYLES . '/jquery.treeview.css', array(), '1.0.0');
        
        wp_register_script('popper', HARA_SCRIPTS . '/popper' . $suffix . '.js', array(), '1.12.9', true);

        if (class_exists('WeDevs_Dokan')) {
            wp_dequeue_script('dokan-tooltip');
        }
         
        wp_enqueue_script('bootstrap', HARA_SCRIPTS . '/bootstrap' . $suffix . '.js', array('popper'), '5.1', true);

        wp_enqueue_script('waypoints', HARA_SCRIPTS . '/jquery.waypoints' . $suffix . '.js', array(), '4.0.0', true);

        wp_enqueue_script('js-cookie', HARA_SCRIPTS . '/js.cookie' . $suffix . '.js', array(), '2.1.4', true);
  
        /*slick jquery*/
        wp_register_script('slick', HARA_SCRIPTS . '/slick' . $suffix . '.js', array(), '1.0.0', true);
        wp_register_script('hara-custom-slick', HARA_SCRIPTS . '/custom-slick' . $suffix . '.js', array(), HARA_THEME_VERSION, true);
  
        // Add before after image
        wp_register_script( 'before-after-image', HARA_SCRIPTS . '/cndk.beforeafter' . $suffix . '.js', array('hara-script' ), '0.0.2', true ); 
        wp_register_style( 'before-after-image', HARA_STYLES . '/cndk.beforeafter.css', array(), '0.0.2' );

        // Add js Sumoselect version 3.0.2
        wp_register_style('sumoselect', HARA_STYLES . '/sumoselect.css', array(), '1.0.0', 'all');
        wp_register_script('jquery-sumoselect', HARA_SCRIPTS . '/jquery.sumoselect' . $suffix . '.js', array( ), '3.0.2', true);

        wp_register_script('jquery-autocomplete', HARA_SCRIPTS . '/jquery.autocomplete' . $suffix . '.js', array('hara-script' ), '1.0.0', true);
        wp_enqueue_script('jquery-autocomplete');

        wp_register_style('magnific-popup', HARA_STYLES . '/magnific-popup.css', array(), '1.1.0');
        wp_register_script('jquery-magnific-popup', HARA_SCRIPTS . '/jquery.magnific-popup' . $suffix . '.js', array( ), '1.1.0', true);

        wp_register_script('jquery-countdowntimer', HARA_SCRIPTS . '/jquery.countdowntimer' . $suffix . '.js', array( ), '20150315', true);
 
        wp_enqueue_script('jquery-countdowntimer'); 

        wp_enqueue_script('hara-script', HARA_SCRIPTS . '/functions' . $suffix . '.js', array('jquery-core', 'js-cookie'), HARA_THEME_VERSION, true);
       
        if (hara_tbay_get_config('header_js') != "") {
            wp_add_inline_script('hara-script', hara_tbay_get_config('header_js'));
        }
  
        $config = hara_localize_translate();

        wp_localize_script('hara-script', 'hara_settings', $config);
    }

    public function footer_scripts()
    {
        if (hara_tbay_get_config('footer_js') != "") {
            $footer_js = hara_tbay_get_config('footer_js');
            echo trim($footer_js);
        }
    }

    public function remove_fonts_redux_url()
    {
        $show_typography  = hara_tbay_get_config('show_typography', false);
        if (!$show_typography) {
            wp_dequeue_style('redux-google-fonts-hara_tbay_theme_options');
        }
    }

    public function load_admin_styles()
    {
        wp_enqueue_style('material-design-iconic-font', HARA_STYLES . '/material-design-iconic-font.css', false, '2.2.0');
        wp_enqueue_style('hara-custom-admin', HARA_STYLES . '/admin/custom-admin.css', false, '1.0.0');

        $suffix = (hara_tbay_get_config('minified_js', false)) ? '.min' : HARA_MIN_JS;
        wp_enqueue_script('hara-admin', HARA_SCRIPTS . '/admin/admin' . $suffix . '.js', array( ), HARA_THEME_VERSION, true);
    }

    public function add_wcmp_frontend_enqueue_scripts($is_vendor_dashboard)
    {
        if (!hara_is_remove_scripts()) {
            return;
        }

        wp_enqueue_style('hara-vendor', HARA_STYLES . '/admin/custom-vendor.css', array(), '1.0');
    }

    /**
     * Register widget area.
     *
     * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
     */
    public function widgets_init()
    {
        register_sidebar(array(
            'name'          => esc_html__('Sidebar Default', 'hara'),
            'id'            => 'sidebar-default',
            'description'   => esc_html__('Add widgets here to appear in your Sidebar.', 'hara'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        

        /* Check WPML */
        if (function_exists('icl_object_id')) {
            register_sidebar(array(
                'name'          => esc_html__('WPML Sidebar', 'hara'),
                'id'            => 'wpml-sidebar',
                'description'   => esc_html__('Add widgets here to appear.', 'hara'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
        }
        /* End check WPML */

        register_sidebar(array(
            'name'          => esc_html__('Footer', 'hara'),
            'id'            => 'footer',
            'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'hara'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
    }

    public function add_cpt_support()
    {
        $cpt_support = ['tbay_custom_post', 'post', 'page', 'product'];
        update_option('elementor_cpt_support', $cpt_support);

        update_option('elementor_disable_color_schemes', 'yes');
        update_option('elementor_disable_typography_schemes', 'yes');
        update_option('elementor_container_width', '1200');
        update_option('elementor_viewport_lg', '1200');
        update_option('elementor_space_between_widgets', '0');
        update_option('elementor_load_fa4_shim', 'yes');

        // update_option('sb_instagram_custom_template', 'yes');
    }

    public function edit_post_show_excerpt($user_login, $user)
    {
        update_user_meta($user->ID, 'metaboxhidden_post', true);
    }

    public function instagram_use_theme_templates()
    {
        if (apply_filters('hara_sb_instagram_custom_template', true)) {
            $active = true;
        } else {
            $active = false;
        }

        return $active;
    }
    

    /**
     * Use front-page.php when Front page displays is set to a static page.
     *
     * @param string $template front-page.php.
     *
     * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
     */
    public function front_page_template($template)
    {
        return is_home() ? '' : $template;
    }

    public function setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on hara, use a find and replace
         * to change 'hara' to the name of your theme in all the template files
         */
        load_theme_textdomain('hara', HARA_THEMEROOT . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        add_theme_support("post-thumbnails");

        add_image_size('hara_avatar_post_carousel', 100, 100, true);

        // This theme styles the visual editor with editor-style.css to match the theme style.
        $font_source = hara_tbay_get_config('show_typography', false);
        if (!$font_source) {
            add_editor_style(array( 'css/editor-style.css', $this->fonts_url() ));
        }

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');


        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption','search-canvas'
        ));

        
        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside', 'image', 'video', 'gallery', 'audio'
        ));

        $color_scheme  = hara_tbay_get_color_scheme();
        $default_color = trim($color_scheme[0], '#');

        // Setup the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('hara_custom_background_args', array(
            'default-color'      => $default_color,
            'default-attachment' => 'fixed',
        )));

        add_action('wp_login', array( $this, 'edit_post_show_excerpt'), 10, 2);

        if( apply_filters('hara_remove_widgets_block_editor', true) ) {
            remove_theme_support( 'block-templates' );
            remove_theme_support( 'widgets-block-editor' );

            /*Remove extendify--spacing--larg CSS*/
            update_option('use_extendify_templates', '');
        }

        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(array(
            'primary'           => esc_html__('Primary Menu', 'hara'),
            'mobile-menu'       => esc_html__('Mobile Menu', 'hara'),
            'nav-category-menu'  => esc_html__('Nav Category Menu', 'hara'),
            'track-order'  => esc_html__('Tracking Order Menu', 'hara'),
        ));

        update_option('page_template', 'elementor_header_footer');
    }

    public function load_fonts_url()
    {
        $protocol         = is_ssl() ? 'https:' : 'http:';
        $show_typography  = hara_tbay_get_config('show_typography', false);
        $font_source      = hara_tbay_get_config('font_source', "1");
        $font_google_code = hara_tbay_get_config('font_google_code');
        if (!$show_typography) {
            wp_enqueue_style('hara-theme-fonts', $this->fonts_url(), array(), null);
        } elseif ($font_source == "2" && !empty($font_google_code)) {
            wp_enqueue_style('hara-theme-fonts', $font_google_code, array(), null);
        }
    }

    public function fonts_url()
    {
        /**
         * Load Google Front
         */

        $fonts_url = '';

        /* Translators: If there are characters in your language that are not
        * supported by Montserrat, translate this to 'off'. Do not translate
        * into your own language.
        */
        $Inter       = _x('on', 'Inter font: on or off', 'hara');

        $Cormorant_Garamond       = _x('on', 'Cormorant Garamond font: on or off', 'hara');

     
        if ('off' !== $Inter) {
            $font_families = array();
     
            if ('off' !== $Inter) {
                $font_families[] = 'Inter:400,500,600,700';
            }

            if ( 'off' !== $Cormorant_Garamond ) {
                $font_families[] = 'Cormorant Garamond:400,500,600,700';
            } 

            $query_args = array(
                'family' => rawurlencode(implode('|', $font_families)),
                'subset' => urlencode('latin,latin-ext'),
                'display' => urlencode('swap'),
            );
            
            $protocol = is_ssl() ? 'https:' : 'http:';
            $fonts_url = add_query_arg($query_args, $protocol .'//fonts.googleapis.com/css');
        }
     
        return esc_url_raw($fonts_url);
    }
}

return new hara_setup_theme();
