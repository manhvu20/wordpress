<?php
global $product;

?>
<div class="product-block list" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
	<?php
        /**
        * Hook: hara_woocommerce_before_shop_list_item.
        *
        * @hooked hara_remove_add_to_cart_list_product - 10
        */
        do_action('hara_woocommerce_before_shop_list_item');
    ?>
	<div class="product-content row">
		<div class="block-inner col-lg-4 col-4">
			<?php
                /**
                * Hook: woocommerce_before_shop_loop_item.
                *
                * @hooked woocommerce_template_loop_product_link_open - 10
                */
                do_action('woocommerce_before_shop_loop_item');
            ?>
			<figure class="image <?php hara_product_block_image_class(); ?>">
				<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" class="product-image">
					<?php
                        /**
                        * woocommerce_before_shop_loop_item_title hook
                        *
                        * @hooked woocommerce_show_product_loop_sale_flash - 10
                        * @hooked woocommerce_template_loop_product_thumbnail - 10
                        */
                        do_action('woocommerce_before_shop_loop_item_title');
                    ?>
				</a>

				<?php
                    /**
                    * hara_tbay_after_shop_loop_item_title hook
                    *
                    */
                    do_action('hara_tbay_after_shop_loop_item_title');
                ?>
				<?php
                    /**
                    * tbay_woocommerce_before_content_product hook
                    *
                    * @hooked woocommerce_show_product_loop_sale_flash - 10
                    */
                    do_action('tbay_woocommerce_before_content_product');
                ?>
				
			</figure>
		</div>
		<div class="caption col-lg-8 col-8">
            <div class="caption-right">
                
                <div class="group-content">
                    <?php
                        do_action('hara_woo_before_shop_list_caption');
                    ?>

                    <?php   
                        /**
                        * hara_woo_list_rating hook
                        *
                        * @hooked woocommerce_template_loop_rating - 5
                        */
                        do_action('hara_woo_list_rating');
                    ?>
                </div>

                <?php hara_the_product_name(); ?>
                <?php
                    /**
                    * hara_after_title_tbay_subtitle hook
                    *
                    * @hooked hara_woo_get_subtitle - 0
                    */
                    do_action('hara_after_title_tbay_subtitle');
                ?>

                <?php   
                    /**
                    * hara_woo_list_price hook
                    *
                    * @hooked woocommerce_template_loop_price - 5
                    */
                    do_action('hara_woo_list_price');
                ?>
				
                <?php
                    /**
                    * Hook: hara_shop_list_sort_description.
                    *
                    * @hooked woocommerce_template_single_excerpt - 5
                    */
                    do_action( 'hara_shop_list_sort_description' );
                ?>
				
				<?php
                    /**
                    * hara_woo_list_after_short_description hook
                    *
                    * @hooked the_woocommerce_variable - 5
                    * @hooked list_variable_swatches_pro - 5
                    * @hooked hara_tbay_total_sales - 15
                    */
                    do_action('hara_woo_list_after_short_description');
                ?>


                <?php
                    /**
                    * Hook: woocommerce_after_shop_loop_item.
                    */
                    do_action('woocommerce_after_shop_loop_item');
                ?>
				
				<div class="group-buttons clearfix">	
					<?php
                        /**
                        * hara_tbay_after_shop_loop_item_title hook
                        *
                        * @hooked hara_the_yith_wishlist - 20
                        * @hooked hara_the_quick_view - 30
                        * @hooked hara_the_yith_compare - 40
                        */
                        do_action('hara_woocommerce_group_buttons', $product->get_id());
                    ?>
				</div>
            </div>
	    </div>
	</div>
	<?php
        do_action('hara_woocommerce_after_shop_list_item');
    ?>
</div>


