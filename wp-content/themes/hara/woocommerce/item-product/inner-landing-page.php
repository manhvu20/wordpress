<?php
global $product;


do_action('hara_woocommerce_before_product_block_grid');

$flash_sales 	= isset($flash_sales) ? $flash_sales : false;
$end_date 		= isset($end_date) ? $end_date : '';

$countdown_title 		= isset($countdown_title) ? $countdown_title : '';

$countdown 		= isset($countdown) ? $countdown : false;
$class = array();
$class_flash_sale = hara_tbay_class_flash_sale($flash_sales);
array_push($class, $class_flash_sale);


?>
<div class="product-block product landing-page v1" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
	<?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
    ?>
	<div class="product-content">
		
		<div class="block-inner">
			<figure class="image <?php hara_product_block_image_class(); ?>">
				<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" class="product-image">
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
                    */
                    do_action('hara_tbay_after_shop_loop_item_title');
                ?>
			
			<?php hara_tbay_item_deal_ended_flash_sale($flash_sales, $end_date); ?>
			</figure>
			
		</div>
		<?php hara_tbay_stock_flash_sale($flash_sales); ?>
		<?php
            /**
            * tbay_woocommerce_before_content_product hook
            *
            * @hooked woocommerce_show_product_loop_sale_flash - 10
            */
            do_action('tbay_woocommerce_before_content_product');
        ?>
		
		<?php hara_woo_product_time_countdown($countdown, $countdown_title); ?>
		
		<div class="caption">
			<?php
                do_action('hara_woo_before_shop_loop_item_caption');
            ?>
			<?php
                /**
                * hara_woo_show_brands hook
                *
                * @hooked the_brands_the_name - 10
                */
                do_action('hara_woo_show_brands');
            ?>
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
                * hara_woocommerce_loop_item_rating hook
                *
                * @hooked woocommerce_template_loop_rating - 10
                */
                do_action('hara_woocommerce_loop_item_rating');
            ?>
			<?php
                /**
                * woocommerce_after_shop_loop_item_title hook
                *
                * @hooked woocommerce_template_loop_price - 10
                */
                do_action('woocommerce_after_shop_loop_item_title');
            ?>
			
			

			<?php
                do_action('hara_woo_after_shop_loop_item_caption');
            ?>
			<div class="add-to-cart-landing-page">	
				<?php
                    /**
                    * hara_add_to_cart_landing_page hook
                    *
                    * @hooked woocommerce_template_loop_add_to_cart - 10
                    */
                    do_action('hara_add_to_cart_landing_page', $product->get_id());
                ?>
		    </div>
		</div>

		
		<?php
            do_action('hara_woocommerce_after_product_block_grid');
        ?>
    </div>
    
	<?php
        /**
        * Hook: woocommerce_after_shop_loop_item.
        */
        do_action('woocommerce_after_shop_loop_item');
    ?>
</div>
