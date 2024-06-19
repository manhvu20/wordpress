<?php
    global $woocommerce;
    $_id = hara_tbay_random_key();

    extract($args);

    $data_dropdown = ( is_checkout() || is_cart() ) ? '' : 'data-bs-toggle="dropdown" data-bs-auto-close="outside"';
?>
<div class="tbay-topcart no-popup">
 <div id="cart-<?php echo esc_attr($_id); ?>" class="cart-dropdown cart-popup dropdown">
        <a class="dropdown-toggle mini-cart"  <?php echo $data_dropdown; ?> href="<?php echo ( is_checkout() ) ? wc_get_cart_url() : 'javascript:void(0);'; ?>" title="<?php esc_attr_e('View your shopping cart', 'hara'); ?>">
			<?php  hara_tbay_minicart_button($icon_mini_cart, $show_title_mini_cart, $title_mini_cart, $price_mini_cart); ?>
        </a>    
        
        <?php if( !is_checkout() && !is_cart() ) : ?>
            <div class="dropdown-menu">
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>    