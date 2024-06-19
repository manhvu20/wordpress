<?php
if ( !hara_woocommerce_activated() ) return;

// breadcrumb for woocommerce page
if (!function_exists('hara_tbay_woocommerce_breadcrumb_defaults')) {
    function hara_tbay_woocommerce_breadcrumb_defaults($args)
    {
        if( hara_checkout_optimized() ) return;
        
        global $post;

        if( is_product() ) {
            $breadcrumb_img = hara_tbay_get_config('woo_single_breadcrumb_image');
            $breadcrumb_color = hara_tbay_get_config('woo_single_breadcrumb_color');
            $breadcrumbs_layout = hara_tbay_get_config('single_product_breadcrumb_layout', 'color');
        } else {
            $breadcrumb_img = hara_tbay_get_config('woo_breadcrumb_image');
            $breadcrumb_color = hara_tbay_get_config('woo_breadcrumb_color');
            $breadcrumbs_layout = hara_tbay_get_config('product_breadcrumb_layout', 'color');
        }

        $style = array();
        $img = '';

        $sidebar_configs = hara_tbay_get_woocommerce_layout_configs();



        if (isset($_GET['breadcrumbs_layout'])) {
            $breadcrumbs_layout = $_GET['breadcrumbs_layout'];
        }

        $class_container = '';
        if (isset($sidebar_configs['container_full']) &&  $sidebar_configs['container_full']) {
            $class_container = 'container-full';
        }

        switch ($breadcrumbs_layout) {
            case 'image':
                $breadcrumbs_class = ' breadcrumbs-image';
                break;
            case 'color':
                $breadcrumbs_class = ' breadcrumbs-color';
                break;
            case 'text':
                $breadcrumbs_class = ' breadcrumbs-text';
                break;
            default:
                $breadcrumbs_class  = ' breadcrumbs-text';
        }


        
        $current_page = true;

        switch ($current_page) {
            case is_shop():
                $page_id = wc_get_page_id('shop');
                break;
            case is_checkout():
            case is_order_received_page():
                $page_id = wc_get_page_id('checkout');
                break;
            case is_edit_account_page():
            case is_add_payment_method_page():
            case is_lost_password_page():
            case is_account_page():
            case is_view_order_page():
                $page_id = wc_get_page_id('myaccount');
                break;
            
            case is_page():
                $page_id = $post->ID;
                break;
            
            default:
                $page_id = get_queried_object()->term_id;
                break; 
        }


        if (isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) && $breadcrumbs_layout !=='color' && $breadcrumbs_layout !=='text') {
            $img = '<img src="'.esc_url($breadcrumb_img['url']).'" alt="'.esc_attr__('breadcrumb-img','hara') .'" >';
        }

        if ($breadcrumb_color && $breadcrumbs_layout !== 'image') {
            $style[] = 'background-color:'.$breadcrumb_color; 
        }

        $estyle = (!empty($style) && $breadcrumbs_layout !=='text') ? ' style="'.implode(";", $style).'"':"";

        $title = $nav = '';

        if ($breadcrumbs_layout == 'image') {
            if (is_single() || is_shop() ) { 
                $title = '<h1 class="page-title">'. get_the_title($page_id) .'</h1>';
            } elseif (is_archive()) {
                $title = '<h1 class="page-title">'. single_cat_title('', false) .'</h1>'; 
            }  
        } else { 
            if( is_single() ) {
                $nav = hara_woo_product_nav_icon();
                $breadcrumbs_class .= ' active-nav-icon';
            } 
            
        }

        $args['wrap_before'] = '<section id="tbay-breadcrumb" '.$estyle.' class="tbay-breadcrumb '.esc_attr($breadcrumbs_class).'">'.$img.'<div class="container '.$class_container.'"><div class="breadscrumb-inner">'. $title .'<ol class="tbay-woocommerce-breadcrumb breadcrumb">';
        $args['wrap_after'] = '</ol>'. $nav .'</div></div></section>';

        return $args;
    }
}