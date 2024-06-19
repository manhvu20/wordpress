<?php
if (! defined('ABSPATH') || !hara_woocommerce_activated()) {
    exit;
}

if (! class_exists('Hara_WooCommerce')) :


    class Hara_WooCommerce
    {
        public static $instance;

        public static function getInstance()
        {
            if (! isset(self::$instance) && ! (self::$instance instanceof Hara_WooCommerce)) {
                self::$instance = new Hara_WooCommerce();
            }

            return self::$instance;
        }

        /**
         * Setup class.
         *
         * @since 1.0
         *
         */
        public function __construct()
        {
            $this->includes();
            $this->init_hooks();
        }

        public function includes()
        {
            require_once(get_parent_theme_file_path(HARA_VENDORS . '/woocommerce/classes/class-wc-shop.php'));
            require_once(get_parent_theme_file_path(HARA_VENDORS . '/woocommerce/classes/class-wc-single.php'));
            require_once(get_parent_theme_file_path(HARA_VENDORS . '/woocommerce/classes/class-wc-cart.php'));
        }

        private function init_hooks()
        {
            add_action('after_setup_theme', array( $this, 'setup' ), 10);
            add_action('after_setup_theme', array( $this, 'setup_size_image' ), 10);

            if( hara_redux_framework_activated() ) {
				add_action('widgets_init', array( $this, 'widgets_init'), 10);
			}

            if (hara_tbay_get_global_config('config_media', false)) {
                remove_action('after_setup_theme', array( $this, 'setup_size_image' ), 10);
            }

            add_filter('hara_woo_pro_des_image', array( $this, 'shop_des_image_active'), 10, 1);

            /*Body Class*/
            add_filter('body_class', array( $this, 'body_class' ), 30, 1);
            
            add_action('wp_enqueue_scripts', array( $this, 'woocommerce_scripts' ), 20);

            /*YITH Compare*/
            add_action('wp_print_styles', array( $this, 'compare_styles'), 200);

            /*Quick view*/
            add_action('wp_enqueue_scripts', array( $this, 'quick_view_scripts'), 101);
            add_action( 'hara_woocommerce_after_product_thumbnails', array( $this, 'quick_view_view_details_btn'), 10 );

            add_filter('hara_tbay_woocommerce_content_class', array( $this, 'woocommerce_content_class'), 10);

            /*YITH Wishlist*/
            if ( class_exists('YITH_WCWL') && apply_filters( 'tbay_yith_wcwl_remove_text', true )) {

                $icon = get_option( 'yith_wcwl_add_to_wishlist_icon' );
                if ( 'custom' === $icon ) {

                    $custom_icon       = get_option( 'yith_wcwl_add_to_wishlist_custom_icon' );

                    if( empty( $custom_icon ) ) {
                        add_filter( 'yith_wcwl_add_to_wishlist_icon_html', array( $this, 'custom_wishlist_icon_html' ), 10, 1 );
                    }

                    $added_icon       = get_option( 'yith_wcwl_added_to_wishlist_custom_icon' );

                    if ('custom' === $added_icon) {
                        $added_custom_icon       = get_option( 'yith_wcwl_added_to_wishlist_custom_icon' );
    
                        if( empty( $added_custom_icon ) ) {
                            add_filter( 'yith_wcwl_add_to_wishlist_heading_icon_html', array( $this, 'custom_wishlist_icon_html' ), 10, 1 );
                        }
                    }
                } 

                add_filter('yith_wcwl_product_already_in_wishlist_text_button', array( $this, 'remove_wishlist_text'), 10, 1);
                add_filter('yith_wcwl_product_added_to_wishlist_message_button', array( $this, 'remove_wishlist_text'), 10, 1);
            }

            add_filter('post_class', array( $this, 'post_class'), 21);

            add_filter('body_class', array( $this, 'body_classes_product_number_mobile'), 10, 1);


            /*Catalog mode*/
            add_filter('body_class', array( $this, 'body_class_woocommerce_catalog_mod'), 10, 1);
            add_action('woocommerce_before_single_product_summary', array( $this, 'catalog_mode_remove_single_hook'), 10);
            add_action('hara_woocommerce_before_quick_view', array( $this, 'catalog_mode_remove_single_hook'), 10);
            add_action('hara_tbay_after_shop_loop_item_title', array( $this, 'catalog_mode_remove_shop_loop_item_hook'), 10);
            add_action('yith_wcqv_product_image', array( $this, 'catalog_mode_remove_yith_wcqv_hook'), 10);
            add_action('wp', array( $this, 'catalog_mode_redirect_page'), 10);
             
            /**Fix customize compare style**/
            add_action('wp_print_styles', array( $this, 'add_compare_styles'), 200);

            /*Hide Variation Selector on HomePage and Shop page*/
            add_filter('hara_enable_variation_selector', array( $this, 'enable_variation_selector'), 10);
            add_filter('body_class', array( $this, 'body_classes_enable_variation_selector'), 10);


            /*Show Quantity on mobile*/
            add_filter('hara_show_quantity_mobile', array( $this, 'show_quantity_mobile'), 10, 1);
            add_filter('body_class', array( $this, 'body_classes_show_quantity_mobile'), 10, 1);

            /*Remove password strength check.*/
            add_action('wp_print_scripts', array( $this, 'remove_password_strength'), 10);


            if (defined('YITH_WCWL')) {
                /**  Add yith wishlist to page my account **/
                add_filter('woocommerce_account_menu_items', array( $this, 'yith_add_wcwl_link_my_account' ), 10, 1);
            }


            /*Change sale flash*/
            add_filter('woocommerce_sale_flash', array( $this, 'show_product_loop_sale_flash_label'), 10, 3);
            add_action('tbay_woocommerce_before_content_product', 'woocommerce_show_product_loop_sale_flash', 10);

            

            // add only feature product
            add_action('tbay_woocommerce_before_content_product', array( $this,'only_feature_product_label'), 10);
            add_action('woocommerce_before_single_product_summary', array( $this,'only_feature_product_label'), 15);
            

            add_filter('gwp_affiliate_id', array( $this, 'affiliate_id'), 10);

            /*change single product */
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            add_action('hara_top_single_product', 'woocommerce_template_single_title', 10);
            add_action('hara_top_single_product', 'hara_woo_get_subtitle', 15);
            add_action('hara_top_single_product', 'woocommerce_template_single_rating', 20);
            add_action('hara_top_single_product', 'woocommerce_template_single_price', 5);
                        

            add_action('init', array( $this, 'wvs_theme_support'), 99);
            add_action('woocommerce_register_form_end', array( $this, 'social_nextend_social_register'), 10);
            add_action('woocommerce_login_form_end', array( $this, 'social_nextend_social_login'), 10);


            add_action('woocommerce_before_shop_loop_item_title', array( $this, 'show_product_outstock_flash_html'), 20);


            /*Page check out*/
            add_filter('woocommerce_paypal_icon', array( $this, 'check_out_paypal_icon'), 10, 1);

            if ( hara_nextend_social_login_activated() ) {
                add_action('woocommerce_login_form_start', array( $this, 'login_social_form_buttons'), 10);

                if (class_exists('WCMp')) {
                    add_action('wcmp_vendor_register_form', array( $this, 'login_social_form_buttons'), 10);
                }
            }


            add_filter('woocommerce_product_thumbnails_columns', array( $this, 'product_thumbnails_columns'), 10, 1);

            add_filter('hara_get_filter_title_mobile', array( $this, 'get_title_mobile'), 10, 1);

            /*The avatar in page my account on mobile*/
            add_action('woocommerce_account_navigation', array( $this, 'the_my_account_avatar'), 5);
        }

        public function setup()
        {
            add_theme_support("woocommerce");
        }

        public function woocommerce_scripts()
        {
            $suffix = (hara_tbay_get_config('minified_js', false)) ? '.min' : HARA_MIN_JS;

            wp_enqueue_script('hara-woocommerce', HARA_SCRIPTS . '/woocommerce' . $suffix . '.js', array( 'hara-script' ), HARA_THEME_VERSION, true);

            wp_register_script('jquery-onepagenav', HARA_SCRIPTS . '/jquery.onepagenav' . $suffix . '.js', array( 'hara-script' ), '3.0.0', true);
        }

        public function compare_styles()
        {
            if (! class_exists('YITH_Woocompare')) {
                return;
            }

            $view_action = 'yith-woocompare-view-table';
            if ((! defined('DOING_AJAX') || ! DOING_AJAX) && (! isset($_REQUEST['action']) || $_REQUEST['action'] != $view_action)) {
                return;
            }

            wp_enqueue_style('hara-font-tbay-custom');

            wp_enqueue_style('hara-template');

            add_filter('body_class', array( $this, 'body_classes_compare'), 30, 1);
        }
        
        public function body_classes_compare($classes)
        {
            $class = 'tbay-body-compare';

            $classes[] = trim($class);

            return $classes;
        }

        public function body_class($classes)
        {
            $class  =  (is_cart() && hara_tbay_get_config('ajax_update_quantity', false)) ? 'tbay-ajax-update-quantity' : '';

            if( is_cart() || is_checkout() ) {
                if( WC()->cart->is_empty() ) {
                    $class  = hara_add_cssclass('tbay-cart-empty', $class);
                }
            }
            
            $class  = hara_add_cssclass('woocommerce', $class);
 
            if (is_product_category()) {
                $class  = hara_add_cssclass('tbay-product-category', $class);
            }

            if (is_cart() && WC()->cart->is_empty()) {
                $class = hara_add_cssclass('empty-cart', $class);
            }
            
            if (class_exists('Woo_Variation_Swatches')) {
                if (!(class_exists('Woo_Variation_Swatches_Pro') && function_exists('wvs_pro_archive_variation_template'))) {
                    $class = hara_add_cssclass('tbay-variation-free', $class);
                }
            }

            $classes[] = trim($class);

            return $classes;
        }

        public function widgets_init()
        {
            register_sidebar(array(
                'name'          => esc_html__('Product Archive Sidebar Top', 'hara'),
                'id'            => 'product-top-archive',
                'description'   => esc_html__('Add widgets here to appear in only shop page.', 'hara'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
            register_sidebar(array(
                'name'          => esc_html__('Product Archive Sidebar Bottom', 'hara'),
                'id'            => 'product-bottom-archive',
                'description'   => esc_html__('Add widgets here to appear in bottom product archive.', 'hara'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
            register_sidebar(array(
                'name'          => esc_html__('Product Archive Sidebar', 'hara'),
                'id'            => 'product-archive',
                'description'   => esc_html__('Add widgets here to appear in Product archive left, right sidebar.', 'hara'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
            register_sidebar(array(
                'name'          => esc_html__('Product Single Sidebar', 'hara'),
                'id'            => 'product-single',
                'description'   => esc_html__('Add widgets here to appear in Product single left, right sidebar.', 'hara'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
        }

        public function setup_size_image()
        {
            $thumbnail_width = 406;
            $main_image_width = 800;
            $cropping_custom_width = 1;
            $cropping_custom_height = 1;

            // Image sizes
            update_option('woocommerce_thumbnail_image_width', $thumbnail_width);
            update_option('woocommerce_single_image_width', $main_image_width);

            update_option('woocommerce_thumbnail_cropping', 'custom');
            update_option('woocommerce_thumbnail_cropping_custom_width', $cropping_custom_width);
            update_option('woocommerce_thumbnail_cropping_custom_height', $cropping_custom_height);
        }

        public function woocommerce_content_class($class)
        {
            $page = 'archive';
            if (is_singular('product')) {
                $page = 'single';
            }

            if (!isset($_GET['product_'.$page.'_layout'])) {
                $class .= ' '.hara_tbay_get_config('product_'.$page.'_layout');
            } else {
                $class .= ' '.$_GET['product_'.$page.'_layout'];
            }

            return $class;
        }

        public function custom_wishlist_icon_html( )
        {
            return '<i class="tb-icon tb-icon-heart2"></i>';
        }

        public function remove_wishlist_text()
        {
            return '';
        }

        public function post_class($classes)
        {
            if ('product' == get_post_type()) {
                $classes = array_diff($classes, array( 'first', 'last' ));
            }
            return $classes;
        }

        public function body_classes_product_number_mobile($classes)
        {
            $columns = hara_tbay_get_config('mobile_product_number', 'two');

            if (isset($columns)) {
                $class = 'tbay-body-mobile-product-'.$columns;
            }

            $classes[] = trim($class);

            return $classes;
        }

        public function body_class_woocommerce_catalog_mod($classes)
        {
            $class = '';
            $active = hara_catalog_mode_active();
            if (isset($active) && $active) {
                $class = 'tbay-body-woocommerce-catalog-mod';
            }

            $classes[] = trim($class);

            return $classes;
        }

        public function catalog_mode_remove_single_hook()
        {
            $active = hara_catalog_mode_active();

            if (isset($active) && $active) {
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            }
        }

        public function catalog_mode_remove_shop_loop_item_hook()
        {
            $active = hara_catalog_mode_active();

            if (isset($active) && $active) {
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            }
        }

        public function catalog_mode_remove_yith_wcqv_hook()
        {
            $active = hara_catalog_mode_active();

            if (isset($active) && $active) {
                if (defined('YITH_WCQV') && YITH_WCQV) {
                    remove_action('yith_wcqv_product_summary', 'woocommerce_template_single_add_to_cart', 25);
                }
            }
        }

        public function catalog_mode_redirect_page()
        {
            $active = hara_catalog_mode_active();
            if (isset($active) && $active) {
                $cart     = is_page(wc_get_page_id('cart'));
                $checkout = is_page(wc_get_page_id('checkout'));

                if ($cart || $checkout) {
                    wp_redirect(home_url());
                    exit;
                }
            }
        }

        public function add_compare_styles()
        {
            if( ! class_exists( 'YITH_Woocompare' ) ) return;
            $view_action = 'yith-woocompare-view-table';
            if ( ( ! defined('DOING_AJAX') || ! DOING_AJAX ) && ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $view_action ) ) return;
            wp_enqueue_style( 'font-awesome' );
            wp_enqueue_style( 'simple-line-icons' );
            wp_enqueue_style( 'hara-style' );
        }

        public function enable_variation_selector()
        {
            $active = hara_tbay_get_config('enable_variation_swatch', false);

            $active = (isset($_GET['variation-selector'])) ? $_GET['variation-selector'] : $active;

            if (class_exists('Woo_Variation_Swatches_Pro') && function_exists('wvs_pro_archive_variation_template')) {
                $active = false;
            }

            return $active;
        }

        public function body_classes_enable_variation_selector($classes)
        {
            $class = '';
            $active = apply_filters('hara_enable_variation_selector', 10, 2);
            if (!(isset($active) && $active)) {
                $class = 'tbay-hide-variation-selector';
            }

            $classes[] = trim($class);

            return $classes;
        }

        public function show_quantity_mobile()
        {
            $active = hara_tbay_get_config('enable_quantity_mobile', false);

            $active = (isset($_GET['quantity_mobile'])) ? $_GET['quantity_mobile'] : $active;

            return $active;
        }

        public function body_classes_show_quantity_mobile($classes)
        {
            $class = '';
            $active = apply_filters('hara_show_quantity_mobile', 10, 2);
            if (isset($active) && $active) {
                $class = 'tbay-show-quantity-mobile';
            }

            $classes[] = trim($class);

            return $classes;
        }

        public function remove_password_strength()
        {
            $active = hara_tbay_get_config('show_woocommerce_password_strength', true);

            if (isset($active) && !$active) {
                wp_dequeue_script('wc-password-strength-meter');
            }
        }

        public function yith_add_wcwl_link_my_account($items)
        {
            if (!class_exists('YITH_WCWL')) {
                return $items;
            }

            $wishlist_page_id = yith_wcwl_object_id(get_option('yith_wcwl_wishlist_page_id'));
            $slug = get_post_field('post_name', $wishlist_page_id);
            
            unset($items['edit-address']);
            unset($items['customer-logout']);
            unset($items['payment-methods']);
            unset($items['edit-account']);
            
            $items[$slug]                       =   esc_html__('My Wishlist', 'hara');
            $items['edit-address']              =   esc_html__('Addresses', 'hara');
            $items['payment-methods']           =   esc_html__('Payment methods', 'hara');
            $items['edit-account']              =   esc_html__('Account details', 'hara');
            $items['customer-logout']           =   esc_html__('Logout', 'hara');
    
            return $items;
        }

        public function show_product_loop_sale_flash_label($original, $post, $product)
        {
            $format                 =  hara_tbay_get_config('sale_tags', 'custom');
            $enable_label_featured  =  hara_tbay_get_config('enable_label_featured', true);

            if ($format == 'custom') {
                $format = hara_tbay_get_config('sale_tag_custom', '-{percent-diff}%');
            }

            $priceDiff = 0;
            $percentDiff = 0;
            $regularPrice = '';
            $salePrice = $percentage = $return_content = '';

            $decimals   =  wc_get_price_decimals();
            $symbol   =  get_woocommerce_currency_symbol();

            $_product_sale   = $product->is_on_sale();
            $featured        = $product->is_featured();

            if ($featured && $enable_label_featured) {
                $return_content  = '<span class="featured">'. hara_tbay_get_config('custom_label_featured', esc_html__('Hot', 'hara')) .'</span>';
            }


            if (!empty($product) && $product->is_type('variable')) {
                $default_attributes = hara_get_default_attributes($product);
                $variation_id = hara_find_matching_product_variation($product, $default_attributes);

                if (!empty($variation_id)) {
                    $variation      = wc_get_product($variation_id);

                    $_product_sale  = $variation->is_on_sale();
    
                    if ($_product_sale) {
                        $regularPrice   = get_post_meta($variation_id, '_regular_price', true);
                        $salePrice      = get_post_meta($variation_id, '_price', true);
                    }
                } else {
                    $percentage = '<span class="saled">'. esc_html__('Sale', 'hara') . '</span>';
                }
            } elseif ($product->is_type('grouped')) {
                $percentage = '<span class="saled">'. esc_html__('Sale', 'hara') . '</span>';
            } else {
                $salePrice = get_post_meta($product->get_id(), '_price', true);
                $regularPrice = get_post_meta($product->get_id(), '_regular_price', true);
            }


            if (!empty($regularPrice) && !empty($salePrice) && $regularPrice > $salePrice) {
                $priceDiff = $regularPrice - $salePrice;
                $percentDiff = round($priceDiff / $regularPrice * 100);
                
                $parsed = str_replace('{price-diff}', number_format((float)$priceDiff, $decimals, '.', ''), $format);
                $parsed = str_replace('{symbol}', $symbol, $parsed);
                $parsed = str_replace('{percent-diff}', $percentDiff, $parsed);
                $percentage = '<span class="saled">'. $parsed .'</span>';
            }

            if (!empty($_product_sale)) {
                $percentage .= $return_content;
            } else {
                $percentage = '<span class="saled">'. esc_html__('Sale', 'hara') . '</span>';
                $percentage .= $return_content;
            }
            if ($featured) {
                echo '<span class="wrapper-onsale-featured onsale">'. trim($percentage) . '</span>';
            } else {
                echo '<span class="onsale">'. trim($percentage) . '</span>';
            }
        }
        public function only_feature_product_label()
        {
            global $product;

            if ($product->is_on_sale()) {
                return;
            }
            $featured               = $product->is_featured();
            $return_content = '';
            if ($featured) {
                $enable_label_featured  =  hara_tbay_get_config('enable_label_featured', true);

                if ($featured && $enable_label_featured) {
                    $return_content  .= '<span class="only-featured onsale"><span class="featured">'. hara_tbay_get_config('custom_label_featured', esc_html__('Hot', 'hara')) .'</span></span>';

                    echo trim($return_content);
                }
            }
        }

        public function quick_view_view_details_btn( ) {
			global $product;
			$permalink = $product->get_permalink(); 
			echo '<div class="details-btn-wrapper"><a class="view-details-btn" href="'. esc_url($permalink) .'">'. esc_html__('View details', 'hara') .'</a></div>';
        }
        

        public function quick_view_scripts()
        {
            if (!hara_tbay_get_config('enable_quickview', true)) {
                return; 
            }

            wp_enqueue_script('jquery-magnific-popup');
            wp_enqueue_style('magnific-popup');
            wp_enqueue_script('wc-add-to-cart-variation'); 
            wp_enqueue_script('wc-single-product');
            wp_enqueue_script('slick'); 
            wp_enqueue_script('hara-custom-slick');
        }

        public function affiliate_id()
        {
            return 2403;
        }

        public function wvs_theme_support()
        {
            if (class_exists('Woo_Variation_Swatches_Pro')) {
                remove_action('woocommerce_after_shop_loop_item', 'wvs_pro_archive_variation_template', 30);
                remove_action('woocommerce_after_shop_loop_item', 'wvs_pro_archive_variation_template', 7);

                add_filter('woo_variation_swatches_archive_product_wrapper', function () {
                    return '.product-block';
                });
                
                add_filter('woo_variation_swatches_archive_add_to_cart_text', function () {
                    return '<i class="tb-icon tb-icon-shopping-cart"></i><span class="title-cart">' . esc_html__('Add to cart', 'hara'). '</span>';
                });

                add_filter('woo_variation_swatches_archive_add_to_cart_select_options', function () {
                    return '<i class="tb-icon tb-icon-shopping-cart"></i><span class="title-cart">' . esc_html__('Select options', 'hara') . '</span>';
                });
            }
        }

        public function social_nextend_social_register()
        {
            if ( hara_nextend_social_login_activated() ) {
                echo '<div class="social-log"><span>'. esc_html__('Or connect with', 'hara') .'</span></div>';
            }
        }

        public function social_nextend_social_login()
        {
            if ( hara_nextend_social_login_activated() ) {
                echo '<div class="social-log"><span>'. esc_html__('Or login with', 'hara') .'</span></div>';
            }
        }

        public function show_product_outstock_flash_html($html)
        {
            global $product;
            $return_content = '';

            if ($product->is_type('simple')) {
                if ($product->is_on_sale() &&  ! $product->is_in_stock()) {
                    $return_content .= '<span class="out-stock out-stock-sale"><span>'. esc_html__('Out of stock', 'hara') .'</span></span>';
                } elseif (! $product->is_in_stock()) {
                    $return_content .= '<span class="out-stock"><span>' . esc_html__('Out of stock', 'hara') .'</span></span>';
                }
            } else if ( ( ! $product->is_type('variable') && ! $product->is_in_stock()  ) 
            || ( $product->is_type('variable') && hara_is_variable_product_out_of_stock( $product ) ) ) {
                $return_content .= '<span class="out-stock"><span>' . esc_html__('Out of stock', 'hara') .'</span></span>';
            }


            echo trim($return_content);
        }

        public function check_out_paypal_icon()
        {
            return HARA_IMAGES. '/paypal.png';
        }

        public function login_social_form_buttons()
        {
            add_action('woocommerce_login_form_end', 'NextendSocialLogin::addLoginFormButtons');
            add_action('woocommerce_register_form_end', 'NextendSocialLogin::addLoginFormButtons');
        }

        public function product_thumbnails_columns()
        {
            $columns = hara_tbay_get_config('number_product_thumbnail', 4);

            if (isset($_GET['number_product_thumbnail']) && !empty($_GET['number_product_thumbnail']) && is_numeric($_GET['number_product_thumbnail'])) {
                $columns = $_GET['number_product_thumbnail'];
            } else {
                $columns = hara_tbay_get_config('number_product_thumbnail', 4);
            }

            return $columns;
        }

        public function remove_result_count_loadmore()
        {
            $pagination_style = (isset($_GET['pagination_style'])) ? $_GET['pagination_style'] : hara_tbay_get_config('product_pagination_style', 'number');

            if (isset($pagination_style) && ($pagination_style == 'loadmore')) {
                remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
            }
        }

        public function get_title_mobile($title)
        {
            if (is_author()) {
                return $title;
            } elseif (is_account_page() && is_user_logged_in()) {
                $current_user   =  wp_get_current_user();
                return $current_user->display_name;
            } elseif (is_product_tag()) {
                $title = esc_html__('Tagged: "', 'hara'). single_tag_title('', false) . '"';
            } elseif (is_product_category()) {
                $title = '';
                $_id = hara_tbay_random_key();
                $args = array(
                    'id' => 'product-cat-'.$_id,
                    'hide_empty'         => 0,
                    'show_option_none' => '',
                );
                echo '<form method="get" class="woocommerce-fillter">';
                wc_product_dropdown_categories($args);
                echo '</form>';
            } elseif (is_shop()) {
                $post_id = wc_get_page_id('shop');
                if (isset($post_id) && !empty($post_id)) {
                    $title = get_the_title($post_id);
                } else {
                    $title = esc_html__('shop', 'hara');
                }
            } elseif (is_product()) {
                $title = get_the_title();
            } elseif ( is_archive() && !empty(single_cat_title("", false)) ) {
                $title = single_cat_title("", false);
            }

            return $title;
        }

        public function the_my_account_avatar()
        {
            if (is_account_page() && is_user_logged_in() && wp_is_mobile()) {
                $current_user   =  wp_get_current_user();
                $output = '<div class="tbay-my-account-avatar">';
                $output .= '<div class="tbay-avatar">';
                $output .= get_avatar($current_user->user_email, 70, '', $current_user->display_name);
                $output .= '</div>';
                $output .= '</div>';

                echo  trim($output);
            }
        }

        public function shop_des_image_active($active)
        {
            $active = hara_tbay_get_config('pro_des_image_product_archives', false);
    
            $active = (isset($_GET['pro_des_image'])) ? (boolean)$_GET['pro_des_image'] : (boolean)$active;
    
            return $active;
        }
    }
endif;

function Hara_WooCommerce()
{
    return Hara_WooCommerce::getInstance();
}

// Global for backwards compatibility.
$GLOBALS['Hara_WooCommerce'] = Hara_WooCommerce();
