<?php
/**
 * Templates Name: Elementor
 * Widget: Product Flash Sales
 */

extract($settings);

$this->settings_layout();

?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
	
	<?php $this->render_element_heading_2();
    ?>
	
    <?php $this->render_content_product_count_down(); ?>
	<?php
        if ($settings['show_all'] === 'yes' && $settings['position_show_all'] === 'bottom' && (!empty($settings['text_show_all']) || !empty($settings['icon_show_all']['value']))) {
            $this->render_item_button();
        }
    ?>

</div>