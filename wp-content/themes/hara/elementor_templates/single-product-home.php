<?php
/**
 * Templates Name: Single Product Home Page
 * Widget: Products
 */

extract($settings);
?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>

    <?php $this->render_item_content(); ?>
    
</div>