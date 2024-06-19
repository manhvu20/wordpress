<?php

if (! defined('ABSPATH') || function_exists('Hara_ElementorBefore_After_Image')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Hara_Elementor_Before_After_Image extends Hara_Elementor_Widget_Base
{
    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'tbay-before-after-image';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Hara Before After Image', 'hara');
    }

    public function get_script_depends()
    {
        return [ 'before-after-image' ];
    }
    
    public function get_style_depends() {
        return [ 'before-after-image' ];
    } 
 
    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-sync';
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'hara'),
            ]
        );

        $this->add_control(
            'heading_before',
            [
                'label' => esc_html__('Before', 'hara'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'before_label',
            [
                'label'     => esc_html__('Label', 'hara'),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'Before'
            ]
        );

        $this->add_control(
            'image_before',
            [
                'label' => esc_html__('Image', 'hara'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'heading_after',
            [
                'label' => esc_html__('After', 'hara'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'after_label',
            [
                'label' => esc_html__('Label', 'hara'),
                'type' => Controls_Manager::TEXT,
                'default' => 'After'
            ]
        );

        $this->add_control(
            'image_after',
            [
                'label' => esc_html__('Image After', 'hara'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->end_controls_section();
    } 

    protected function render_item()
    {
        $settings = $this->get_settings_for_display();
        extract($settings); 
        ?>
        <div class="beforeafterdefault tbay-before-after-image">
            <div data-type="data-type-image">
                <div data-type="before" data-title="<?php echo esc_attr($before_label); ?>"><?php echo wp_get_attachment_image($image_before['id'], 'full'); ?></div>
                <div data-type="after" data-title="<?php echo esc_attr($after_label); ?>"><?php echo wp_get_attachment_image($image_after['id'], 'full'); ?></div>
            </div>
        </div>
        <?php
    }
    
}
$widgets_manager->register(new Hara_Elementor_Before_After_Image());
