<?php
/**
 * Templates Name: Elementor
 * Widget: Canvas Menu
 */

$active_ajax = false;
extract( $settings );

if( $ajax_menu_template === 'yes' ) {
    $this->add_render_attribute('wrapper', 'class', ['canvas-template-ajax', 'ajax-active']);
    $active_ajax = true;
} 
?>
<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php $this->render_canvas_menu(); ?>
</div>
    