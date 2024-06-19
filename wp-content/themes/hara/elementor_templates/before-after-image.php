<?php
/**
 * Templates Name: Elementor
 * Widget: Before after image
 */
extract($settings);
?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php $this->render_item(); ?>
</div>