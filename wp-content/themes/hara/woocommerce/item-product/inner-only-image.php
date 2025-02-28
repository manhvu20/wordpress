<?php
global $product;


$class = array();

?>
<div <?php hara_tbay_product_class($class); ?> data-product-id="<?php echo esc_attr($product->get_id()); ?>">
	<div class="product-content">
		<div class="block-inner">
			<figure class="image <?php hara_product_block_image_class(); ?>">
				<a title="<?php the_title_attribute(); ?>" href="<?php echo the_permalink(); ?>" class="product-image">
					<?php
                        /**
                        * woocommerce_before_shop_loop_item_title hook
                        *
                        * @hooked woocommerce_template_loop_product_thumbnail - 10
                        */
                        do_action('woocommerce_before_shop_loop_item_title');
                    ?>
				</a>
				
				<?php
                    /**
                    * hara_tbay_after_shop_loop_item_title hook
                    *
                    * @hooked hara_tbay_add_slider_image - 10
                    */
                    do_action('hara_tbay_after_shop_loop_item_title');
                ?>
				
			</figure>
			
		</div>
    </div>
    
</div>
