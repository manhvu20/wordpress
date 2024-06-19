
<?php
/**
 * hara_woocommerce_before_quick_view hook
 */
do_action('hara_woocommerce_before_quick_view');  
?>
<div id="tbay-quick-view-body" class="woocommerce single-product">
    <div id="tbay-quick-view-content"> 
        <div id="product-<?php the_ID(); ?>" <?php post_class('product '); ?>>
        <?php 
            if ( class_exists('YITH_WFBT_Frontend') ) {
                remove_action( 'woocommerce_after_single_product_summary', array( YITH_WFBT_Frontend::get_instance(), 'add_bought_together_form' ), 1 );
            }

            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
            wc_get_template_part( 'content', 'single-product' ); 
            add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

            if ( class_exists('YITH_WFBT_Frontend') ) {
                add_action( 'woocommerce_after_single_product_summary', array( YITH_WFBT_Frontend::get_instance(), 'add_bought_together_form' ), 1 );
            }
        
        ?>
        </div>
    </div>
</div>
<?php
/** 
 * hara_woocommerce_before_quick_view hook
 */
do_action('hara_woocommerce_after_quick_view');
