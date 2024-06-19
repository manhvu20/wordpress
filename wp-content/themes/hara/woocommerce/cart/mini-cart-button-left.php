<?php
    global $woocommerce;
    $_id = hara_tbay_random_key();
    
    extract($args);

    $data_dropdown = ( is_checkout() || is_cart() ) ? '' : 'data-bs-toggle="offcanvas" data-bs-target="#cart-offcanvas-left" aria-controls="cart-offcanvas-left"';
?>
<div class="tbay-topcart left-right">
 	<div id="cart-<?php echo esc_attr($_id); ?>" class="cart-dropdown dropdown">
        <a class="dropdown-toggle mini-cart v2" <?php echo $data_dropdown; ?>  href="<?php echo ( is_checkout() ) ? wc_get_cart_url() : 'javascript:void(0);'; ?>">
			<?php  hara_tbay_minicart_button($icon_mini_cart, $show_title_mini_cart, $title_mini_cart, $price_mini_cart); ?>
        </a>    
    </div> 
	<?php 
    if( !is_checkout() && !is_cart() ) {
        hara_tbay_get_page_templates_parts('offcanvas-cart', 'left'); 
    }
    ?>
</div>    

  