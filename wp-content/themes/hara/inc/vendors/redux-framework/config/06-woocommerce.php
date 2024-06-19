<?php
/**
 * Redux Framework checkbox config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

$columns            = hara_settings_columns();
$aspect_ratio       = hara_settings_aspect_ratio();

/** WooCommerce Settings **/
Redux::set_section(
	$opt_name,
	array(
        'icon' => 'zmdi zmdi-shopping-cart',
        'title' => esc_html__('WooCommerce Theme', 'hara'),
        'fields' => array(
            array(
                'title'    => esc_html__('Label Sale Format', 'hara'),
                'id'       => 'sale_tags',
                'type'     => 'radio',
                'options'  => array(
                    'Sale!' => esc_html__('Sale!', 'hara'),
                    'Save {percent-diff}%' => esc_html__('Save {percent-diff}% (e.g "Save 50%")', 'hara'),
                    'Save {symbol}{price-diff}' => esc_html__('Save {symbol}{price-diff} (e.g "Save $50")', 'hara'),
                    'custom' => esc_html__('Custom Format (e.g -50%, -$50)', 'hara')
                ),
                'default' => 'custom'
            ),
            array(
                'id'        => 'sale_tag_custom',
                'type'      => 'text',
                'title'     => esc_html__('Custom Format', 'hara'),
                'desc'      => esc_html__('{price-diff} inserts the dollar amount off.', 'hara'). '</br>'.
                               esc_html__('{percent-diff} inserts the percent reduction (rounded).', 'hara'). '</br>'.
                               esc_html__('{symbol} inserts the Default currency symbol.', 'hara'),
                'required'  => array('sale_tags','=', 'custom'),
                'default'   => '-{percent-diff}%'
            ),
            array(
                'id' => 'enable_label_featured',
                'type' => 'switch',
                'title' => esc_html__('Enable "Featured" Label', 'hara'),
                'default' => true
            ),
            array(
                'id'        => 'custom_label_featured',
                'type'      => 'text',
                'title'     => esc_html__('"Featured Label" Custom Text', 'hara'),
                'required'  => array('enable_label_featured','=', true),
                'default'   => esc_html__('Featured', 'hara')
            ),
            
            array(
                'id' => 'enable_brand',
                'type' => 'switch',
                'title' => esc_html__('Enable Brand Name', 'hara'),
                'subtitle' => esc_html__('Enable/Disable brand name on HomePage and Shop Page', 'hara'),
                'default' => false
            ),
            array(
                'id' => 'enable_hide_sub_title_product',
                'type' => 'switch',
                'title' => esc_html__('Hide sub title product', 'hara'),
                'default' => false
            ),

            array(
                'id' => 'enable_text_time_coutdown',
                'type' => 'switch',
                'title' => esc_html__('Enable the text of Time Countdown', 'hara'),
                'default' => false
            ),
            
            array(
                'id'   => 'opt-divide',
                'class' => 'big-divide',
                'type' => 'divide'
            ),
            array(
                'id' => 'product_display_image_mode',
                'type' => 'image_select',
                'title' => esc_html__('Product Image Display Mode', 'hara'),
                'options' => array(
                    'one' => array(
                        'title' => esc_html__('Single Image', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/image_mode/single-image.png'
                    ),
                    'two' => array(
                        'title' => esc_html__('Double Images (Hover)', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/image_mode/display-hover.gif'
                    ),
                                                                        
                ),
                'default' => 'two'
            ),
            array(
                'id' => 'enable_quickview',
                'type' => 'switch',
                'title' => esc_html__('Enable Quick View', 'hara'),
                'default' => 1
            ),
            array(
                'id' => 'enable_woocommerce_catalog_mode',
                'type' => 'switch',
                'title' => esc_html__('Show WooCommerce Catalog Mode', 'hara'),
                'default' => false
            ),
            array(
                'id' => 'enable_woocommerce_quantity_mode',
                'type' => 'switch',
                'title' => esc_html__('Enable WooCommerce Quantity Mode', 'hara'),
                'subtitle' => esc_html__('Enable/Disable show quantity on Home Page and Shop Page', 'hara'),
                'default' => false
            ),
            array(
                'id' => 'ajax_update_quantity',
                'type' => 'switch',
                'title' => esc_html__('Quantity Ajax Auto-update', 'hara'),
                'subtitle' => esc_html__('Enable/Disable quantity ajax auto-update on page Cart', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'enable_variation_swatch',
                'type' => 'switch',
                'title' => esc_html__('Enable Product Variation Swatch', 'hara'),
                'subtitle' => esc_html__('Enable/Disable Product Variation Swatch on HomePage and Shop page', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'variation_swatch',
                'type' => 'select',
                'title' => esc_html__('Product Attribute', 'hara'),
                'options' => hara_tbay_get_variation_swatchs(),
                'default' => 'pa_size'
            ),
        )
	)
);

// woocommerce Search settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Search Products', 'hara'),
        'fields' => array(
            array(
                'id' => 'search_query_in',
                'type' => 'button_set',
                'title' => esc_html__('Search Query', 'hara'),
                'options' => array(
                    'title' => esc_html__('Only Title', 'hara'), 
                    'all' => esc_html__('All (Title, Content, Sku)', 'hara'), 
                ),
                'default' => 'title'
            ),
            array(
                'id' => 'search_sku_ajax',
                'type' => 'switch',
                'title' => esc_html__('Show SKU on AJAX results', 'hara'),
                'required' => array('search_query_in','=', 'all'),
                'default' => true
            ),
        )
	)
);



// woocommerce Cart settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Cart', 'hara'),
        'fields' => array(
            array(
                'id' => 'show_cart_free_shipping',
                'type' => 'switch',
                'title' => esc_html__('Enable Free Shipping on Cart and Mini-Cart', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'show_mini_cart_qty',
                'type' => 'switch',
                'title' => esc_html__('Enable Quantity on Mini-Cart', 'hara'),
                'default' => true
            ), 
             array(
                'id' => 'woo_mini_cart_position',
                'type' => 'select',
                'title' => esc_html__('Mini-Cart Position', 'hara'),
                'options' => array(
                    'left'       => esc_html__('Left', 'hara'),
                    'right'      => esc_html__('Right', 'hara'),
                    'popup'      => esc_html__('Popup', 'hara'),
                    'no-popup'   => esc_html__('None Popup', 'hara')
                ),
                'default' => 'popup'
            ),
        )
	)
);


// woocommerce Breadcrumb settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Breadcrumb Shop', 'hara'),
        'fields' => array(
            array(
                'id' => 'show_product_breadcrumb',
                'type' => 'switch',
                'title' => esc_html__('Enable Breadcrumb', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'product_breadcrumb_layout',
                'type' => 'image_select',
                'class'     => 'image-two',
                'compiler' => true,
                'title' => esc_html__('Breadcrumb Layout', 'hara'),
                'required' => array('show_product_breadcrumb','=',1),
                'options' => array(
                    'image' => array(
                        'title' => esc_html__('Background Image', 'hara'),
                        'img'   => HARA_ASSETS_IMAGES . '/breadcrumbs/image.jpg'
                    ),
                    'color' => array(
                        'title' => esc_html__('Background color', 'hara'),
                        'img'   => HARA_ASSETS_IMAGES . '/breadcrumbs/color.jpg'
                    ),
                    'text'=> array(
                        'title' => esc_html__('Text Only', 'hara'),
                        'img'   => HARA_ASSETS_IMAGES . '/breadcrumbs/text_only.jpg'
                    ),
                ),
                'default' => 'color'
            ),
            array(
                'title' => esc_html__('Breadcrumb Background Color', 'hara'),
                'subtitle' => '<em>'.esc_html__('The Breadcrumb background color of the site.', 'hara').'</em>',
                'id' => 'woo_breadcrumb_color',
                'required' => array('product_breadcrumb_layout','=',array('default','color')),
                'type' => 'color',
                'default' => '#f4f9fc',
                'transparent' => false,
            ),
            array(
                'id' => 'woo_breadcrumb_image',
                'type' => 'media',
                'title' => esc_html__('Breadcrumb Background', 'hara'),
                'subtitle' => esc_html__('Upload a .jpg or .png image that will be your Breadcrumb.', 'hara'),
                'required' => array('product_breadcrumb_layout','=','image'),
                'default'  => array(
                    'url'=> HARA_IMAGES .'/breadcrumbs-woo.jpg'
                ),
            ),
        )
	)
);


// woocommerce Breadcrumb settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Breadcrumb Single Product', 'hara'),
        'fields' => array(
            array(
                'id' => 'show_single_product_breadcrumb',
                'type' => 'switch',
                'title' => esc_html__('Enable Breadcrumb', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'show_product_nav',
                'type' => 'switch', 
                'title' => esc_html__('Enable Product Navigator', 'hara'),
                'required' => array('show_single_product_breadcrumb','=',1),
                'default' => true
            ),    
            array(
                'id' => 'single_product_breadcrumb_layout',
                'type' => 'image_select',
                'class'     => 'image-two',
                'compiler' => true,
                'title' => esc_html__('Breadcrumb Layout', 'hara'),
                'required' => array('show_single_product_breadcrumb','=',1),
                'options' => array(
                    'image' => array(
                        'title' => esc_html__('Background Image', 'hara'),
                        'img'   => HARA_ASSETS_IMAGES . '/breadcrumbs/image.jpg'
                    ),
                    'color' => array(
                        'title' => esc_html__('Background color', 'hara'),
                        'img'   => HARA_ASSETS_IMAGES . '/breadcrumbs/color.jpg'
                    ),
                    'text'=> array(
                        'title' => esc_html__('Text Only', 'hara'),
                        'img'   => HARA_ASSETS_IMAGES . '/breadcrumbs/text_only.jpg'
                    ),
                ),
                'default' => 'color'
            ),
            array(
                'title' => esc_html__('Breadcrumb Background Color', 'hara'),
                'subtitle' => '<em>'.esc_html__('The Breadcrumb background color of the site.', 'hara').'</em>',
                'id' => 'woo_single_breadcrumb_color',
                'required' => array('single_product_breadcrumb_layout','=',array('default','color')),
                'type' => 'color',
                'default' => '#f4f9fc',
                'transparent' => false,
            ),
            array(
                'id' => 'woo_single_breadcrumb_image',
                'type' => 'media',
                'title' => esc_html__('Breadcrumb Background', 'hara'),
                'subtitle' => esc_html__('Upload a .jpg or .png image that will be your Breadcrumb.', 'hara'),
                'required' => array('single_product_breadcrumb_layout','=','image'),
                'default'  => array(
                    'url'=> HARA_IMAGES .'/breadcrumbs-woo.jpg'
                ),
            ),
        )
	)
);


// WooCommerce Archive settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Shop', 'hara'),
        'fields' => array(
            array(
                'id' => 'product_archive_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Shop Layout', 'hara'),
                'options' => array(
                    'shop-left' => array(
                        'title' => esc_html__('Left Sidebar', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/product_archives/shop_left_sidebar.jpg'
                    ),
                    'shop-right' => array(
                        'title' => esc_html__('Right Sidebar', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/product_archives/shop_right_sidebar.jpg'
                    ),
                    'full-width' => array(
                        'title' => esc_html__('No Sidebar', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/product_archives/shop_no_sidebar.jpg'
                    ),
                ),
                'default' => 'shop-left'
            ),
            array(
                'id' => 'product_archive_sidebar',
                'type' => 'select',
                'title' => esc_html__('Archive Sidebar', 'hara'),
                'data'      => 'sidebars',
                'default' => 'product-archive'
            ),
            array(
                'id' => 'enable_display_mode',
                'type' => 'switch',
                'title' => esc_html__('Enable Products Display Mode', 'hara'),
                'subtitle' => esc_html__('Enable/Disable Display Mode', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'product_display_mode',
                'type' => 'button_set',
                'title' => esc_html__('Products Display Mode', 'hara'),
                'required' => array('enable_display_mode','=',1),
                'options' => array(
                    'grid' => esc_html__('Grid', 'hara'),
                    'list' => esc_html__('List', 'hara')
                ),
                'default' => 'grid'
            ),
            array(
                'id' => 'title_product_archives',
                'type' => 'switch',
                'title' => esc_html__('Show Title of Categories', 'hara'),
                'default' => false
            ),
            array(
                'id' => 'pro_des_image_product_archives',
                'type' => 'switch',
                'title' => esc_html__('Show Description, Image of Categories', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'number_products_per_page',
                'type' => 'slider',
                'title' => esc_html__('Number of Products Per Page', 'hara'),
                'default' => 12,
                'min' => 1,
                'step' => 1,
                'max' => 100,
            ),
            array(
                'id' => 'product_columns',
                'type' => 'select',
                'title' => esc_html__('Product Columns', 'hara'),
                'options' => $columns,
                'default' => 4
            ),
        )
	)
);


// WooCommerce Single Product settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Single Product', 'hara'),
        'fields' => array(
            array(
                'id' => 'product_single_layout',
                'type' => 'image_select',
                'compiler' => true,
                'title' => esc_html__('Select Single Product Layout', 'hara'),
                'options' => array(
                    'vertical' => array(
                        'title' => esc_html__('Image Vertical', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/product_single/verical_thumbnail.jpg'
                    ),
                    'horizontal' => array(
                        'title' => esc_html__('Image Horizontal', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/product_single/horizontal_thumbnail.jpg'
                    ),
                    'left-main' => array(
                        'title' => esc_html__('Left - Main Sidebar', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/product_single/left_main_sidebar.jpg'
                    ),
                    'main-right' => array(
                        'title' => esc_html__('Main - Right Sidebar', 'hara'),
                        'img' => HARA_ASSETS_IMAGES . '/product_single/main_right_sidebar.jpg'
                    ),
                ),
                'default' => 'horizontal'
            ),
            array(
                'id' => 'product_single_sidebar',
                'type' => 'select',
                'required' => array('product_single_layout','=',array('left-main','main-right')),
                'title' => esc_html__('Single Product Sidebar', 'hara'),
                'data'      => 'sidebars',
                'default' => 'product-single'
            ),
        )
	)
);


// WooCommerce Single Product Advanced Options settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Single Product Advanced Options', 'hara'),
        'fields' => array(
            array(
                'id' => 'enable_ajax_single_add_to_cart',
                'type' => 'switch',
                'title' => esc_html__('Enable/Disable Ajax add to cart', 'hara'),
                'subtitle' => esc_html__('Only simple variable products are supported ajax', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'enable_total_sales',
                'type' => 'switch',
                'title' => esc_html__('Enable Total Sales', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'enable_buy_now',
                'type' => 'switch',
                'title' => esc_html__('Enable Buy Now', 'hara'),
                'default' => false
            ),
            array(
                'id' => 'redirect_buy_now',
                'required' => array('enable_buy_now','=',true),
                'type' => 'button_set',
                'title' => esc_html__('Redirect to page after Buy Now', 'hara'),
                'options' => array(
                        'cart'          => 'Page Cart',
                        'checkout'      => 'Page CheckOut',
                ),
                'default' => 'cart'
            ),
            array(
                'id'   => 'opt-divide',
                'class' => 'big-divide',
                'type' => 'divide'
            ),
            array(
                'id' => 'style_single_tabs_style',
                'type' => 'button_set',
                'title' => esc_html__('Tab Mode', 'hara'),
                'options' => array(
                        'fulltext'          => 'Full Text',
                        'tabs'          => 'Tabs',
                        'accordion'        => 'Accordion',
                ),
                'default' => 'fulltext'
            ),
            array(
                'id'   => 'opt-divide',
                'class' => 'big-divide',
                'type' => 'divide'
            ),
            array(
                'id'   => 'opt-divide',
                'class' => 'big-divide',
                'type' => 'divide'
            ),  
            array(
                'id'   => 'opt-divide',
                'class' => 'big-divide',
                'type' => 'divide'
            ),
            array(
                'id' => 'enable_sticky_menu_bar',
                'type' => 'switch',
                'title' => esc_html__('Sticky Menu Bar', 'hara'),
                'subtitle' => esc_html__('Enable/disable Sticky Menu Bar', 'hara'),
                'default' => false
            ),
            array(
                'id' => 'enable_zoom_image',
                'type' => 'switch',
                'title' => esc_html__('Zoom inner image', 'hara'),
                'subtitle' => esc_html__('Enable/disable Zoom inner Image', 'hara'),
                'default' => false
            ),
            array(
                'id'   => 'opt-divide',
                'class' => 'big-divide',
                'type' => 'divide'
            ),
            array(
                'id' => 'video_aspect_ratio',
                'type' => 'select',
                'title' => esc_html__('Featured Video Aspect Ratio', 'hara'),
                'subtitle' => esc_html__('Choose the aspect ratio for your video', 'hara'),
                'options' => $aspect_ratio,
                'default' => '16_9'
            ),
            array(
                'id'      => 'video_position',
                'title'    => esc_html__('Featured Video Position', 'hara'),
                'type'    => 'select',
                'default' => 'last',
                'options' => array(
                    'last' => esc_html__('The last product gallery', 'hara'),
                    'first' => esc_html__('The first product gallery', 'hara'),
                ),
            ),
            array(
                'id'   => 'opt-divide',
                'class' => 'big-divide',
                'type' => 'divide'
            ),
            array(
                'id' => 'enable_product_social_share',
                'type' => 'switch',
                'title' => esc_html__('Social Share', 'hara'),
                'subtitle' => esc_html__('Enable/disable Social Share', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'enable_product_review_tab',
                'type' => 'switch',
                'title' => esc_html__('Product Review Tab', 'hara'),
                'subtitle' => esc_html__('Enable/disable Review Tab', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'enable_product_releated',
                'type' => 'switch',
                'title' => esc_html__('Products Releated', 'hara'),
                'subtitle' => esc_html__('Enable/disable Products Releated', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'enable_product_upsells',
                'type' => 'switch',
                'title' => esc_html__('Products upsells', 'hara'),
                'subtitle' => esc_html__('Enable/disable Products upsells', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'enable_product_countdown',
                'type' => 'switch',
                'title' => esc_html__('Display Countdown time ', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'number_product_thumbnail',
                'type'  => 'slider',
                'title' => esc_html__('Number Images Thumbnail to show', 'hara'),
                'default' => 4,
                'min'   => 2,
                'step'  => 1,
                'max'   => 5,
            ),
            array(
                'id' => 'number_product_releated',
                'type' => 'slider',
                'title' => esc_html__('Number of related products to show', 'hara'),
                'default' => 8,
                'min' => 1,
                'step' => 1,
                'max' => 20,
            ),
            array(
                'id' => 'releated_product_columns',
                'type' => 'select',
                'title' => esc_html__('Releated Products Columns', 'hara'),
                'options' => $columns,
                'default' => 4
            ),
            array(
                'id'       => 'html_before_add_to_cart_btn',
                'type'     => 'textarea',
                'title'    => esc_html__('HTML before Add To Cart button (Global)', 'hara'),
                'desc'     => esc_html__('Enter HTML and shortcodes that will show before Add to cart selections.', 'hara'),
            ),
            array(
                'id'       => 'html_after_add_to_cart_btn',
                'type'     => 'textarea',
                'title'    => esc_html__('HTML after Add To Cart button (Global)', 'hara'),
                'desc'     => esc_html__('Enter HTML and shortcodes that will show after Add to cart button.', 'hara'),
            ),
            array(
                'id'       => 'html_before_inner_product_summary',
                'type'     => 'textarea',
                'title'    => esc_html__('HTML before Inner Product Summary', 'hara'),
                'desc'     => esc_html__('Enter HTML and shortcodes that will show before Product Summary selections.', 'hara'),
            ),
            array(
                'id'       => 'html_after_inner_product_summary',
                'type'     => 'textarea',
                'title'    => esc_html__('HTML after Inner Product Summary', 'hara'),
                'desc'     => esc_html__('Enter HTML and shortcodes that will show after Product Summary selections.', 'hara'),
            ),
        )
	)
);


// woocommerce Other Page settings
Redux::set_section(
	$opt_name,
	array(
        'subsection' => true,
        'title' => esc_html__('Other page', 'hara'),
        'fields' => array(
            array(
                'id' => 'show_woocommerce_password_strength',
                'type' => 'switch',
                'title' => esc_html__('Show Password Strength Meter', 'hara'),
                'subtitle' => esc_html__('Enable or disable in page My Account', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'show_checkout_image',
                'type' => 'switch',
                'title' => esc_html__('Show Image Product', 'hara'),
                'subtitle'  => esc_html__('Enable or disable "Image Product" in page Checkout', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'show_checkout_quantity',
                'type' => 'switch',
                'title' => esc_html__('Show Quantity Product', 'hara'),
                'subtitle'  => esc_html__('Enable or disable "Quantity Product" on Review Order on page Checkout', 'hara'),
                'default' => true
            ),
            array(
                'id' => 'show_checkout_optimized',
                'type' => 'switch',
                'title' => esc_html__('Checkout Optimized', 'hara'),
                'subtitle'  => esc_html__('Remove "Header" and "Footer" in page Checkout', 'hara'),
                'default' => false
            ),
            array(
                'id' => 'checkout_logo',
                'type' => 'media',
                'required' => array('show_checkout_optimized','=', true),
                'title' => esc_html__('Upload Logo in page Checkout', 'hara'),
                'subtitle' => esc_html__('Image File (.png or .gif)', 'hara'),
            ),
            array(
                'id'        => 'checkout_img_width',
                'type'      => 'slider',
                'required' => array('show_checkout_optimized','=', true),
                'title'     => esc_html__('Logo maximum width (px)', 'hara'),
                "default"   => 120,
                "min"       => 50,
                "step"      => 1,
                "max"       => 600,
            ),
        )
	)
);

if (!function_exists('hara_settings_multi_vendor_fields')) {
    function hara_settings_multi_vendor_fields( $columns )
    {
        $wcmp_array = $fields_dokan = array();

        if (class_exists('WCMp')) {
            $wcmp_array = array(
                'id'        => 'show_vendor_name_wcmp',
                'type'      => 'info',
                'title'     => esc_html__('Enable Vendor Name Only WCMP Vendor', 'hara'),
                'subtitle'  => sprintf(__('Go to the <a href="%s" target="_blank">Setting</a> Enable "Sold by" for WCMP Vendor', 'hara'), admin_url('admin.php?page=wcmp-setting-admin')),
            );
        }

        $fields = array(
            array(
                'id' => 'show_vendor_name',
                'type' => 'switch',
                'title' => esc_html__('Enable Vendor Name', 'hara'),
                'subtitle' => esc_html__('Enable/Disable Vendor Name on HomePage and Shop page only works for Dokan, WCMP Vendor', 'hara'),
                'default' => true
            ),
            $wcmp_array
        );


        if (class_exists('WeDevs_Dokan')) {
            $fields_dokan = array(
                array(
                    'id'   => 'divide_vendor_1',
                    'class' => 'big-divide',
                    'type' => 'divide'
                ),
                array(
                    'id' => 'show_info_vendor_tab',
                    'type' => 'switch',
                    'title' => esc_html__('Enable Tab Info Vendor Dokan', 'hara'),
                    'subtitle' => esc_html__('Enable/Disable tab Info Vendor on Product Detail Dokan', 'hara'),
                    'default' => true
                ),
                array(
                    'id'        => 'show_seller_tab',
                    'type'      => 'info',
                    'title'     => esc_html__('Enable/Disable Tab Products Seller', 'hara'),
                    'subtitle'  => sprintf(__('Go to the <a href="%s" target="_blank">Setting</a> of each Seller to Enable/Disable this tab of Dokan Vendor.', 'hara'), home_url('dashboard/settings/store/')),
                ),
                array(
                    'id' => 'seller_tab_per_page',
                    'type' => 'slider',
                    'title' => esc_html__('Dokan Number of Products Seller Tab', 'hara'),
                    'default' => 4,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                ),
                array(
                    'id' => 'seller_tab_columns',
                    'type' => 'select',
                    'title' => esc_html__('Dokan Product Columns Seller Tab', 'hara'),
                    'options' => $columns,
                    'default' => 4
                ),
            );
        }
        

        $fields = array_merge($fields, $fields_dokan);

        return $fields;
    }
}

if( hara_woo_is_active_vendor() ) {
    Redux::set_section(
        $opt_name,
        array(
            'subsection' => true,
            'title' => esc_html__('Multi-vendor', 'hara'),
            'fields' => hara_settings_multi_vendor_fields($columns)
        )
    );
}