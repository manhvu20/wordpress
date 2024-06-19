<?php

if (! defined('ABSPATH') || function_exists('Hara_Elementor_Testimonials')) {
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
class Hara_Elementor_Testimonials extends Hara_Elementor_Carousel_Base
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
        return 'tbay-testimonials';
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
        return esc_html__('Hara Testimonials', 'hara');
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
        return 'eicon-testimonial';
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
            'layout_style',
            [
                'label'     => esc_html__('Layout Style', 'hara'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'style1',
                'options'   => [
                    'style1'    => esc_html__('Style 1', 'hara'),
                    'style2'    => esc_html__('Style 2', 'hara'),
                    'style3'    => esc_html__('Style 3', 'hara'),
                ],
            ]
        );

        $repeater = $this->register_testimonials_repeater();

        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__('Testimonials Items', 'hara'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => $this->register_set_testimonial_default(),
                'testimonials_field' => '{{{ testimonials_image }}}',
            ]
        );

        $this->end_controls_section();

        $this->add_control_responsive();
        $this->add_control_carousel(['layout_type' => 'carousel']);
    }

    private function register_testimonials_repeater()
    {
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'testimonial_image',
            [
                'label' => esc_html__('Choose Image: Avatar', 'hara'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $repeater->add_control(
            'title_excerpt',
            [
                'label' => esc_html__('Excerpt', 'hara'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $repeater->add_control(
            'testimonial_excerpt',
            [
                'label' => esc_html__('Description', 'hara'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );
        $repeater->add_control(
            'testimonial_name',
            [
                'label' => esc_html__('Name', 'hara'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'testimonial_sub_name',
            [
                'label' => esc_html__('Sub Name', 'hara'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        return $repeater;
    }

    private function register_set_testimonial_default()
    {
        $defaults = [
            [
                
                'testimonial_image' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                
                'testimonial_name' => esc_html__('Name 1', 'hara'),
                'testimonial_sub_name' => esc_html__('Sub name 1', 'hara'),
                'testimonial_excerpt' => esc_html__('Lorem ipsum dolor sit amet, in mel unum delicatissimi conclusionemque', 'hara'),
            ],
            [
                'testimonial_image' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                
                'testimonial_name' => esc_html__('Name 2', 'hara'),
                'testimonial_sub_name' => esc_html__('Sub name 2', 'hara'),
                'testimonial_excerpt' => esc_html__('Lorem ipsum dolor sit amet, in mel unum delicatissimi conclusionemque', 'hara'),
            ],
            [
                'testimonial_image' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                
                'testimonial_name' => esc_html__('Name 3', 'hara'),
                'testimonial_sub_name' => esc_html__('Sub name 3', 'hara'),
                'testimonial_excerpt' => esc_html__('Lorem ipsum dolor sit amet, in mel unum delicatissimi conclusionemque', 'hara'),
            ],
            [
                'testimonial_image' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                
                'testimonial_name' => esc_html__('Name 4', 'hara'),
                'testimonial_sub_name' => esc_html__('Sub name 4', 'hara'),
                'testimonial_excerpt' => 'Lorem ipsum dolor sit amet, in mel unum delicatissimi conclusionemque',
            ],
        ];

        return $defaults;
    }

    protected function render_item_style1($item)
    {
        ?> 
        <div class="testimonials-body"> 
                <div class="testimonial-meta">
                    <i class="tb-icon tb-icon-quote-left"></i>
                    <div class="testimonial-rating"></div>
                    <?php $this->render_item_excerpt($item); ?>
                    <div class="testimonials-info d-flex">
                        <div class="flex-shrink-0">
                            <?php echo $this->get_widget_field_img($item['testimonial_image']); ?>
                        </div>
                        <div class="testimonials-info-right">
                            <?php  
                                $this->render_item_name($item); 
                                $this->render_item_sub_name($item);
                            ?>
                        </div>
                    </div>
                    
                </div>
                
                <?php
                ?>
                <?php
            ?>
        </div>
        <?php
    }
    

    protected function render_item_style2($item)
    {
        ?> 
        <div class="testimonials-body"> 
            <div class="testimonial-meta">
                <div class="testimonials-info-wrapper">
                    <div class="testimonials-info">
                        <?php echo $this->get_widget_field_img($item['testimonial_image']); ?>
                    </div>
                    <?php $this->render_item_excerpt($item); ?>

                    <div class="testimonial-rating"></div>

                    <div class="testimonials-name-sub">
                        <?php  
                            $this->render_item_name($item); 
                            $this->render_item_sub_name($item);
                        ?>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }

    protected function render_item_style3($item)
    {
        ?> 
        <div class="testimonials-body"> 
            <div class="testimonial-meta">
                <div class="testimonials-info-wrapper">
                    <div class="testimonials-info d-flex">
                        <div class="flex-shrink-0">
                            <?php echo $this->get_widget_field_img($item['testimonial_image']); ?>
                        </div>
                        <div class="testimonials-info-right flex-grow-1 ms-3">
                            <?php  
                                $this->render_item_name($item); 
                                $this->render_item_sub_name($item);
                            ?>
                        </div>
                    </div>

                    <div class="testimonial-rating"></div>
                </div>
                <?php $this->render_item_excerpt($item); ?>
            </div>
        </div>
        <?php
    }
    

    private function render_item_name($item)
    {
        $testimonial_name  = $item['testimonial_name'];
        if (isset($testimonial_name) && !empty($testimonial_name)) {
            ?>
                <span class="name"><?php echo trim($testimonial_name); ?></span>
            <?php
        }
    }
    private function render_item_sub_name($item)
    {
        $testimonial_sub_name  = $item['testimonial_sub_name'];

        if (isset($testimonial_sub_name) && !empty($testimonial_sub_name)) {
            ?>
                <span class="sub-name"><?php echo trim($testimonial_sub_name) ?></span>
            <?php
        }
    }
    private function render_item_excerpt($item)
    {
        $testimonial_excerpt  = $item['testimonial_excerpt'];

        if ( isset($testimonial_excerpt) && !empty($testimonial_excerpt) ) {
            ?>  
                <div class="excerpt"><?php echo trim($testimonial_excerpt) ?></div>
            <?php
        }
    }
}
$widgets_manager->register(new Hara_Elementor_Testimonials());
