<div class="tbay-offcanvas-cart sidebar-right offcanvas offcanvas-end" id="cart-offcanvas-right">
	<div class="offcanvas-header widget-header-cart">
		<div class="header-cart-content">
			<h3 class="widget-title heading-title"><?php esc_html_e('Shopping cart', 'hara') ?></h3>
			<a href="javascript:;" class="offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="tb-icon tb-icon-cross"></i></a>
		</div> 
	</div>
	<div class="offcanvas-body widget_shopping_cart_content">
	<?php woocommerce_mini_cart(); ?>
	</div>
</div>