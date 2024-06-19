<?php
/**
 * Templates Name: Elementor
 * Widget: Instagram Feed
 */
extract($settings);

$_id = hara_tbay_random_key();
$this->settings_layout();

$this->add_render_attribute('item', 'class', 'item');

$this->add_render_attribute('row', 'data-layout', $layout_type);

$this->add_render_attribute('row', 'data-title', $heading_title);

$this->add_render_attribute('row', 'data-subtitle', $heading_subtitle);

$row = $this->get_render_attribute_string('row');
?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
    <?php echo do_shortcode('[instagram-feed feed="'. $select_feeds. '" tb-atts="yes" '. $row .' ]'); ?>
</div>