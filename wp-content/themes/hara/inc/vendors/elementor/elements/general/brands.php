<?php

if (! defined('ABSPATH') || function_exists('Hara_Elementor_Brands')) {
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
class Hara_Elementor_Brands extends Hara_Elementor_Carousel_Base
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
        return 'tbay-brands';
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
        return esc_html__('Hara Brands', 'hara');
    }

    public function get_script_depends()
    {
        return [ 'hara-custom-slick', 'slick' ];
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
        return 'eicon-meta-data';
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
        $this->register_controls_heading();

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'hara'),
            ]
        );
 
        $this->add_control(
            'layout_type',
            [
                'label'     => esc_html__('Layout Type', 'hara'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'grid',
                'options'   => [
                    'grid'      => esc_html__('Grid', 'hara'),
                    'carousel'  => esc_html__('Carousel', 'hara'),
                ],
            ]
        );
        $this->add_control(
            'brands_align',
            [
                'label' => esc_html__('Align', 'hara'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'hara'),
                        'icon' => 'fas fa-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'hara'),
                        'icon' => 'fas fa-align-center'
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'hara'),
                        'icon' => 'fas fa-align-right'
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .item .inner,.tbay-element-brands .row.grid > div' => 'justify-content: {{VALUE}} !important',
                ]
            ]
        );
        $brands = new \Elementor\Repeater();

        $brands->add_control(
            'brand_image',
            [
                'label' => esc_html__('Choose Image', 'hara'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $brands->add_control(
            'brand_link',
            [
                'label' => esc_html__('Link to', 'hara'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'hara'),
            ]
        );

        $this->add_control(
            'brands',
            [
                'label' => esc_html__('Brand Items', 'hara'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $brands->get_controls()
            ]
        );

        

        $this->end_controls_section();

        $this->add_control_responsive();
        $this->add_control_carousel(['layout_type' => 'carousel']);
    }

    protected function render_item($item)
    {
        extract($item); ?> 
        <div class="inner"> 
           <?php
                $image_id           = $brand_image['id'];
        $link               = $brand_link['url'];
        $is_external        = $brand_link['is_external'];
        $nofollow           = $brand_link['nofollow'];

        $attribute = '';
        if ($is_external === 'on') {
            $attribute .= ' target="_blank"';
        }

        if ($nofollow === 'on') {
            $attribute .= ' rel="nofollow"';
        } ?>

           <?php if (isset($link) && !empty($link)) : ?>
                <a href="<?php echo esc_url($link); ?>" <?php echo trim($attribute); ?>>
                    <?php echo wp_get_attachment_image($image_id, 'full'); ?>
                </a>
            <?php else: ?>
                <?php echo wp_get_attachment_image($image_id, 'full'); ?>
            <?php endif; ?>

        </div>
        <?php
    }
}
$widgets_manager->register(new Hara_Elementor_Brands());
