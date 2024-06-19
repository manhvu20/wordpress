<?php
/**
 * Templates Name: Elementor
 * Widget: Testimonials
 */
extract($settings);
if (empty($testimonials) || !is_array($testimonials)) {
    return;
}

$this->add_render_attribute('item', 'class', 'item');
$this->add_render_attribute('wrapper', 'class', $layout_style);
$this->settings_layout();
?>

<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>

    <?php $this->render_element_heading(); ?>

    <div <?php echo $this->get_render_attribute_string('row'); ?>>
        <?php foreach ($testimonials as $item) : ?>
            <div <?php echo $this->get_render_attribute_string('item'); ?>>
                <?php 
                    switch ($layout_style) {
                        case 'style1':
                            $this->render_item_style1($item);
                            break;

                        case 'style2':
                            $this->render_item_style2($item);
                            break;

                        case 'style3':
                            $this->render_item_style3($item);
                            break;
                        
                        default:
                            $this->render_item_style1($item);
                            break;
                    }
                ?>
            </div>

        <?php endforeach; ?>
    </div>
</div>