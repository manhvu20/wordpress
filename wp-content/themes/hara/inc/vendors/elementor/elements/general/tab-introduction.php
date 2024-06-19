<?php

if (! defined('ABSPATH') || function_exists('Hara_Elementor_Tab_Introduction')) {
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
class Hara_Elementor_Tab_Introduction extends Hara_Elementor_Widget_Base
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
        return 'tbay-tab-introduction';
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
        return esc_html__('Hara Tab Introduction', 'hara');
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
        return 'eicon-tabs';
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

        $repeater = $this->register_tab_repeater();

        $this->add_control(
            'tabs',
            [
                'label' => esc_html__('Tab Items', 'hara'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => $this->register_set_tabs_default(),
            ]
        );

        $this->end_controls_section();
    }

    private function register_tab_repeater()
    {
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
            'tab_name',
            [
                'label' => esc_html__('Title', 'hara'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'tab_content',
            [
                'label' => esc_html__('Content', 'hara'),
                'type' => Controls_Manager::WYSIWYG,
            ]
        );

        return $repeater;
    }

    private function register_set_tabs_default()
    {
        $defaults = [
            [
                'tab_name' => esc_html__('Tab name 1', 'hara'),
                'tab_content' => esc_html__('Lorem ipsum dolor sit amet, in mel unum delicatissimi conclusionemque', 'hara'),
            ],
            [
                'tab_name' => esc_html__('Tab name 2', 'hara'),
                'tab_content' => esc_html__('Lorem ipsum dolor sit amet, in mel unum delicatissimi conclusionemque 2', 'hara'),
            ],
            [
                'tab_name' => esc_html__('Tab name 3', 'hara'),
                'tab_content' => esc_html__('Lorem ipsum dolor sit amet, in mel unum delicatissimi conclusionemque 3', 'hara'),
            ],
        ];

        return $defaults;
    }

    protected function render_tabs_title($tabs, $id) {
        $count = 0;
        ?>
        <div class="nav nav-tabs nav-introduction-title" id="tab-nav-introduction-<?php echo esc_attr( $id ); ?>" role="tablist">
            <?php foreach ($tabs as $key) : ?>
                <?php 
                    $class_active = ( $count === 0 ) ? 'active' : '';
                    $selected = ( $count === 0 ) ? 'true' : 'false';
                ?>

                <button class="nav-link <?php echo esc_attr( $class_active ); ?>" id="nav-tab-<?php echo esc_attr( $id ); ?>-<?php echo esc_attr($key['_id']); ?>" data-bs-toggle="tab" data-bs-target="#nav-<?php echo esc_attr( $id ); ?>-<?php echo esc_attr($key['_id']); ?>" type="button" role="tab" aria-controls="nav-<?php echo esc_attr( $id ); ?>-<?php echo esc_attr($key['_id']); ?>" aria-selected="<?php echo esc_attr( $selected ) ?>"><span class="count"><?php echo trim($count + 1); ?></span><span class="name"><?php echo trim($key['tab_name']); ?></span></button>

                <?php $count++; ?>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function render_tabs_content($tabs, $id) {
        $count = 0;
        ?>
        <div class="tab-content nav-introduction-content" id="nav-content-introduction-<?php echo esc_attr( $id ); ?>">
            <?php foreach ($tabs as $key) : ?>
            <?php 
                $class_active = ( $count === 0 ) ? 'show active' : '';
            ?>

            <div class="tab-pane fade <?php echo esc_attr( $class_active ); ?>" id="nav-<?php echo esc_attr( $id ); ?>-<?php echo esc_attr($key['_id']); ?>" role="tabpanel" aria-labelledby="nav-tab-<?php echo esc_attr( $id ); ?>-<?php echo esc_attr($key['_id']); ?>">
                <?php 
                    echo '<h4 class="content-title">'. trim( $key['tab_name'] ) .'</h4>';
                    echo '<div class="content">'. trim( $key['tab_content'] ) .'</div>';
                ?>
            </div>

            <?php $count++; ?>

            <?php endforeach; ?>
        </div>
        <?php
    }
}
$widgets_manager->register(new Hara_Elementor_Tab_Introduction());