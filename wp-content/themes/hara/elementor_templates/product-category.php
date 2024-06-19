<?php
/**
 * Templates Name: Elementor
 * Widget: Products Category
 */
$category =  $cat_operator = $product_type = $limit = $orderby = $order = '';
extract($settings);

if (empty($settings['category'])) {
    return;
}

$layout_type = $settings['layout_type'];
$this->settings_layout();
 
/** Get Query Products */
$loop = hara_get_query_products($category, $cat_operator, $product_type, $limit, $orderby, $order);

$this->add_render_attribute('row', 'class', ['products']);

$attr_row = $this->get_render_attribute_string('row');

?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php $this->render_element_heading_2(); ?>
	<?php wc_get_template('layout-products/layout-products.php', array( 'loop' => $loop, 'product_style' => $product_style, 'attr_row' => $attr_row)); ?>
	<?php
        if ($settings['show_all'] === 'yes' && $settings['position_show_all'] === 'bottom' && (!empty($settings['text_show_all']) || !empty($settings['icon_show_all']['value']))) {
            $this->render_item_button();
        }
    ?>
</div>