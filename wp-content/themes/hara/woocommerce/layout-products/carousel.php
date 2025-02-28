<?php

wp_enqueue_script('slick');
wp_enqueue_script('hara-custom-slick');
          
$inner = 'inner-v1';

$product_item = isset($product_item) ? $product_item : $inner;

$columns 		= isset($columns) ? $columns : 4;
$show_des 		= isset($show_des) ? $show_des : false;
$countdown 		= isset($countdown) ? $countdown : false;
$flash_sales 	= isset($flash_sales) ? $flash_sales : false;
$end_date 		= isset($end_date) ? $end_date : '';

$countdown_title 		= isset($countdown_title) ? $countdown_title : '';

$rows_count 	= isset($rows) ? $rows : 1;
$data_auto		= (!empty($data_auto)) ? $data_auto : 'no';
$data_loop		= (!empty($data_loop)) ? $data_loop : 'no';
$data_autospeed	= (!empty($data_autospeed)) ? $data_autospeed : 500;


$screen_desktop          	=      isset($screen_desktop) ? $screen_desktop : 4;
$screen_desktopsmall     	=      isset($screen_desktopsmall) ? $screen_desktopsmall : 3;
$screen_tablet           	=      isset($screen_tablet) ? $screen_tablet : 3;
$screen_landscape_mobile    =      isset($screen_landscape_mobile) ? $screen_landscape_mobile : 2;
$screen_mobile           	=      isset($screen_mobile) ? $screen_mobile : 1;


$data_carousel = hara_tbay_data_carousel($rows, $nav_type, $pagi_type, $loop_type, $auto_type, $autospeed_type, $disable_mobile);
$responsive_carousel  = hara_tbay_check_data_responsive_carousel($columns, $screen_desktop, $screen_desktopsmall, $screen_tablet, $screen_landscape_mobile, $screen_mobile);

$class_item = ($rows_count != 1) ? 'row-no-one' : '';

$classes = array('products-grid', 'product');
?>
<div class="owl-carousel products rows-<?php echo esc_attr($rows_count); ?> <?php echo esc_attr($class_item); ?> <?php hara_slick_carousel_product_block_image_class(); ?>" <?php echo $responsive_carousel; ?>  <?php echo $data_carousel; ?>>
    <?php while ($loop->have_posts()): $loop->the_post(); global $product; ?>
		
		<div class="item">
			<div <?php wc_product_class($classes, $product); ?>>
				<div class="products-grid product">
					<?php 
						$post_object = get_post( get_the_ID() );
						setup_postdata( $GLOBALS['post'] =& $post_object );
						
						wc_get_template('item-product/'.$product_item.'.php', array('show_des' => $show_des, 'countdown' => $countdown, 'countdown_title' => $countdown_title, 'flash_sales' => $flash_sales, 'end_date' => $end_date )); 
					?>
				</div>
			</div>
	
		</div>
		
    <?php endwhile; ?>

	<?php wp_reset_postdata(); ?>
</div> 