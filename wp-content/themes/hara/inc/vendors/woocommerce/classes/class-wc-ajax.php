<?php
/**
 * Since 1.6.5
 */
defined('ABSPATH') or die(); // Exit if accessed directly

if ( class_exists('WC_AJAX') ) :
    class HARA_WC_AJAX extends WC_AJAX
    {

        /**
         * Hook in ajax handlers.
         */
        public static function hara_init()
        {
            add_action('init', array(__CLASS__, 'define_ajax'), 0);
            add_action('template_redirect', array(__CLASS__, 'do_wc_ajax'), 0);
            
            self::hara_add_ajax_events();
        }

        /**
         * Hook in methods - uses WordPress ajax handlers (admin-ajax).
         */
        public static function hara_add_ajax_events()
        {
            /**
             * Register ajax event
             */
            $ajax_events = array(
                'hara_quantity_mini_cart',
                'hara_product_remove',
                'hara_products_categories_tab_shortcode',
                'hara_products_tab_shortcode',
                'hara_products_list_ajax',
                'hara_products_grid_ajax', 
                'hara_single_add_to_cart', 
                'hara_popup_variation_name', 
            );

            if ( defined('YITH_WCWL') ) {
                $ajax_events[] = 'hara_update_wishlist_count';
            }

            if ( hara_tbay_get_config('enable_quickview', true) ) {
                $ajax_events[] = 'hara_quickview_product';
            }

               
            foreach ($ajax_events as $ajax_event) {
                add_action('wp_ajax_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));
                add_action('wp_ajax_nopriv_woocommerce_' . $ajax_event, array(__CLASS__, $ajax_event));

                // WC AJAX can be used for frontend ajax requests.
                add_action('wc_ajax_' . $ajax_event, array(__CLASS__, $ajax_event));
            }

        }

        public static function hara_quantity_mini_cart() { 
            check_ajax_referer( 'hara-minicartquantity-nonce', 'security' );
             
            // Set item key as the hash found in input.qty's name
            $cart_item_key = $_REQUEST['hash'];

            // Get the array of values owned by the product we're updating
            $product_values = WC()->cart->get_cart_item($cart_item_key);

            // Get the quantity of the item in the cart
            $product_quantity = apply_filters('woocommerce_stock_amount_cart_item', apply_filters('woocommerce_stock_amount', preg_replace("/[^0-9\.]/", '', filter_var($_REQUEST['quantity'], FILTER_SANITIZE_NUMBER_INT))), $cart_item_key);

            // Update cart validation
            $passed_validation  = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $product_values, $product_quantity);    

            // Update the quantity of the item in the cart
            if ($passed_validation) {
                WC()->cart->set_quantity($cart_item_key, $product_quantity, true);
            } 

            // Return fragments
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();

            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                    )
                ), 
                'cart_hash' => apply_filters('woocommerce_cart_hash', WC()->cart->get_cart_for_session() ? md5(json_encode(WC()->cart->get_cart_for_session())) : '', WC()->cart->get_cart_for_session())
            );   
        
            wp_send_json($data);   

            die();
        }

        public static function hara_product_remove() {
            check_ajax_referer( 'hara-productremove-nonce', 'security' );

            // Get mini cart
            ob_start();
    
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                if ($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key']) {
                    WC()->cart->remove_cart_item($cart_item_key);
                }
            }
    
            WC()->cart->calculate_totals();
            WC()->cart->maybe_set_cart_cookies();
    
            woocommerce_mini_cart();
    
            $mini_cart = ob_get_clean();
    
            // Fragments and mini cart are returned
            $data = array(
                'fragments' => apply_filters(
                    'woocommerce_add_to_cart_fragments',
                    array(
                        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                    )
                ),
                'cart_hash' => apply_filters('woocommerce_cart_hash', WC()->cart->get_cart_for_session() ? md5(json_encode(WC()->cart->get_cart_for_session())) : '', WC()->cart->get_cart_for_session())
            );
    
            wp_send_json($data); 

            die();
        }   

        public static function hara_update_wishlist_count() {  
            check_ajax_referer( 'hara-wishlistcount-nonce', 'security' );

            ob_start();

            $wishlist_count = YITH_WCWL()->count_products();

            $data = array(
                'count' => $wishlist_count
            );     

            wp_send_json($data);

            die();
        }

        public static function hara_products_categories_tab_shortcode() {
            if ( ! empty( $_POST['atts'] ) ) {
                check_ajax_referer( 'hara-productscategoriestab-nonce', 'security' );

                ob_start();

                $atts               = hara_clean( $_POST['atts'] );  
                $categories         = hara_clean( $_POST['value'] );
                $atts['categories'] = $categories;
    
                $data = hara_elementor_products_ajax_template( $atts );

                wp_send_json_success( $data ); 

                die();
            } 
        }

        public static function hara_products_tab_shortcode() {
            if ( ! empty( $_POST['atts'] ) ) {
                check_ajax_referer( 'hara-productstab-nonce', 'security' );

                ob_start();

                $atts                   = hara_clean( $_POST['atts'] );
                $product_type           = hara_clean( $_POST['value'] );
                $atts['product_type']   = $product_type;
    
                $data = hara_elementor_products_ajax_template( $atts );
 
                wp_send_json_success( $data );

                die();
            } 
        }   

        public static function hara_products_grid_ajax() {
            check_ajax_referer( 'hara-productsgrid-nonce', 'security' );

            // prepare our arguments for the query
            $args = json_decode(stripslashes($_POST['query']), true);
            
            hara_order_by_query($args['orderby'], $args['order']);
         
            // it is always better to use WP_Query but not here
            query_posts($args);

            $list = 'grid';
          
            ob_start();

            if (have_posts()) :
         
                while (have_posts()): the_post();

                    wc_get_template('content-product.php', array('list' => $list));
                     
                endwhile;
            endif; 

            wc_reset_loop();  

            $content = ob_get_clean();

            wp_send_json($content);

            die();
        }

        public static function hara_products_list_ajax() {
            check_ajax_referer( 'hara-productslist-nonce', 'security' );

            // prepare our arguments for the query
            $args = json_decode(stripslashes($_POST['query']), true);

            hara_order_by_query($args['orderby'], $args['order']);

            if (isset($_GET['paged'])) {
                $args['paged'] = intval($_GET['paged']);
            }
            
            query_posts($args);

            $list = 'list';

            ob_start();
         
            if (have_posts()) :
         
                while (have_posts()): the_post();
         
                wc_get_template('content-product.php', array('list' => $list));
         
                endwhile;
         
            endif;     

             

            $content = ob_get_clean();

            wp_send_json($content);

            die();
        }  
         
        public static function hara_quickview_product()
        {     
            if (!empty($_GET['product_id'])) {

                $args = array(  
                    'post_type' => 'product',
                    'post__in' => array($_GET['product_id'])
                );
                $loop = new WP_Query($args);

                ob_start(); 
                if ($loop->have_posts()) {
                    while ($loop->have_posts()): $loop->the_post();
                    wc_get_template_part('content', 'product-quickview');
                    endwhile;
                } 
                 
                $content = ob_get_clean();
                  
                wp_send_json($content);
            }  
            die;
        }

        /**
         * validate variation
         */
        protected static function validate_variation($product, $variation_id, $variation, $quantity) {
            if (empty($variation_id) || empty($product)) {
                return array('validate' => false);
            }

            $missing_attributes = array();
            $variations         = array();
            $attributes         = $product->get_attributes();
            $variation_data     = wc_get_product_variation_attributes($variation_id);

            foreach ($attributes as $attribute) {
                if (!$attribute['is_variation']) {
                    continue;
                }

                $taxonomy = 'attribute_' . sanitize_title($attribute['name']);

                if (isset($variation[$taxonomy])) {
                    // Get value from post data
                    if ($attribute['is_taxonomy']) {
                        // Don't use wc_clean as it destroys sanitized characters
                        $value = sanitize_title(stripslashes($variation[$taxonomy]));
                    } else {
                        $value = wc_clean(stripslashes($variation[$taxonomy]));
                    }
                    
                    if (trim($value) == '') {
                        $missing_attributes[] = wc_attribute_label($attribute['name']);
                    } else {
                        // Get valid value from variation
                        $valid_value = isset($variation_data[$taxonomy]) ? $variation_data[$taxonomy] : '';

                        // Allow if valid or show error.
                        if ($valid_value === $value || (in_array($value, $attribute->get_slugs()))) {
                            $variations[$taxonomy] = $value;
                        } else {
                            return array('validate' => false);
                        }
                    }
                } else {
                    $missing_attributes[] = wc_attribute_label($attribute['name']);
                }
            }
            
            if (!empty($missing_attributes)) {
                return array(
                    'validate' => false,
                    'missing_attributes' => $missing_attributes
                );
            }

            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product->get_id(), $quantity, $variation_id, $variations);

            return array(
                'validate' => $passed_validation
            );
        }

        public static function hara_single_add_to_cart() {
            /**
             * Clear Old Notices
             */
            wc_clear_notices();
            
            /**
             * Add to cart in single
             */
            if (isset($_REQUEST['add-to-cart']) && is_numeric(wp_unslash($_REQUEST['add-to-cart']))) {
                $error = (0 === wc_notice_count('error')) ? false : true;
                $product_id = wp_unslash($_REQUEST['add-to-cart']);
                
                /**
                 * Error Add to Cart
                 */
                if ($error) {
                    $data = array(
                        'error' => $error,
                        'message' => wc_print_notices(true),
                        'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
                    );
                }
                
                /**
                 * Added product success
                 */
                else {
                    // Return fragments
                    ob_start();
                    woocommerce_mini_cart();
                    $mini_cart = ob_get_clean();
                    
                    $woo_mess = wc_print_notices(true);
                    $woo_mess = empty($woo_mess) ? '<div class="woocommerce-message text-center" role="alert">' . esc_html__('Product added to cart successfully!', 'hara') . '</div>' : $woo_mess;

                    // Fragments and mini cart are returned
                    $data = array(
                        'fragments' => apply_filters(
                            'woocommerce_add_to_cart_fragments',
                            array(
                                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
                                '.woocommerce-message' => $woo_mess
                            )
                        ),
                        'cart_hash' => WC()->cart->get_cart_hash()
                    );
                }
                
                wp_send_json($data);
            }
            
            /**
             * Add to cart in Loop
             */
            else {
                if (!isset($_REQUEST['product_id']) || !is_numeric(wp_unslash($_REQUEST['product_id']))){
                    wc_add_notice(esc_html__('Sorry, Product is not existing.', 'hara'), 'error');
                    wp_send_json(array(
                        'error' => true,
                        'message' => wc_print_notices(true)
                    ));

                    wp_die();
                }

                $error      = false;
                $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['product_id']));
                $quantity   = empty($_REQUEST['quantity']) ? 1 : wc_stock_amount($_REQUEST['quantity']);
                $type       = (!isset($_REQUEST['product_type']) || !in_array($_REQUEST['product_type'], array('simple', 'variation', 'variable'))) ? false : $_REQUEST['product_type'];
                
                if (!$type) {
                    wc_add_notice(esc_html__('Sorry, Product is not existing.', 'hara'), 'error');
                    wp_send_json(array(
                        'error' => true,
                        'message' => wc_print_notices(true)
                    ));

                    wp_die();
                }

                $variation = isset($_REQUEST['variation']) ? $_REQUEST['variation'] : array();
                $validate_attr = array('validate' => true);
                if ($type == 'variation') {
                    if (!isset($_REQUEST['variation_id']) || !$_REQUEST['variation_id']) {
                        $variation_id = $product_id;
                        $product_id = wp_get_post_parent_id($product_id);
                        $type = 'variable';
                    } else {
                        $variation_id = (int) $_REQUEST['variation_id'];
                    }
                }

                $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
                $product_status    = get_post_status($product_id);

                $product = wc_get_product((int) $product_id);
                $product_type = false;

                if (!$product) {
                    $error = true;
                } else {
                    $product_type = $product->get_type();
                    if ((!$variation || !$variation_id) && $product_type == 'variable'){
                        $error = true;
                    }

                    if (!$error && $product_type == 'variable') {
                        $validate_attr = self::validate_variation($product, $variation_id, $variation, $quantity);
                    }
                }

                if (!$error && $validate_attr['validate'] && $passed_validation && 'publish' === $product_status && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation)) {

                    do_action('woocommerce_ajax_added_to_cart', $product_id);

                    if ('yes' !== get_option('woocommerce_cart_redirect_after_add')) {
                        // Return fragments
                        ob_start();
                        woocommerce_mini_cart();
                        $mini_cart = ob_get_clean();

                        // Fragments and mini cart are returned
                        $data = array(
                            'fragments' => apply_filters(
                                'woocommerce_add_to_cart_fragments',
                                array(
                                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                                )
                            ),
                            'cart_hash' => WC()->cart->get_cart_hash()
                        );
                    } else {
                        wc_add_to_cart_message(array($product_id => $quantity), true);
                        $data = array(
                            'redirect' => wc_get_cart_url()
                        );
                    }

                    wp_send_json($data);
                } else {
                    // If there was an error adding to the cart, redirect to the product page to show any errors
                    if (isset($validate_attr['missing_attributes'])) {
                        $number = count($validate_attr['missing_attributes']);
                        wc_add_notice( _n('%s is a required field', '%s are required fields', $number, 'hara'), 'error');
                    } else {
                        wc_add_notice(esc_html__('Sorry, Maybe product empty in stock.', 'hara'), 'error');
                    }

                    $data = array(
                        'error' => true,
                        'message' => wc_print_notices(true),
                        'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
                    );

                    wp_send_json($data);
                }
            }
        } 

        public static function hara_popup_variation_name() {
            if (!empty($_POST['variation_id'])) {    
                check_ajax_referer( 'hara-popupvariationname-nonce', 'security' );

                $variation_id = $_POST['variation_id'];
  
                $variation = wc_get_product($variation_id);
                $data = $variation->get_name();
                  
                wp_send_json($data);
            }  
            die; 
        }

    }

    /**
     * Init HARA WC AJAX
     */
    if (isset($_REQUEST['wc-ajax'])) {
        add_action('init', 'hara_init_wc_ajax');
        if (!function_exists('hara_init_wc_ajax')) :
            function hara_init_wc_ajax() {
                HARA_WC_AJAX::hara_init();
            }
        endif;
    }
endif;