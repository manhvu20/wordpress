<?php

if (! defined('ABSPATH') || function_exists('Hara_Elementor_List_Custom_Fonts')) {
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
class Hara_Elementor_List_Custom_Fonts extends Hara_Elementor_Carousel_Base
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
        return 'tbay-list-custom-fonts';
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
        return esc_html__('Hara List Icons Tbay Custom Fonts', 'hara');
    }

    public function get_script_depends()
    {
        return [ '' ];
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
        return 'eicon-icon-box';
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
                'label' => esc_html__('Layout Type', 'hara'),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'grid',
            ]
        );
        $this->end_controls_section();

        $this->add_control_responsive();
    }

    protected function render_element_content()
    {
        $url = HARA_THEME_DIR . '/inc/vendors/elementor/icons/json/tbay-custom.json';
        $request = wp_remote_get($url);

        if (is_wp_error($request)) {
            return false; // Bail early
        }

        $body   = wp_remote_retrieve_body($request);
        $data   = json_decode($body);

        $this->add_render_attribute('row', 'class', 'list-icons'); ?> 
            <div class="list-tbay-custom-fonts-body"> 
                <div class="quick-search">
                    <input id="quick-search" placeholder="Search..." type="text">
                    <i class="icon-magnifier"></i>
                </div> 
                <div class="text-center font-size-changer">
                    <a href="#" class="small-icons"><i class="icon-info"></i> <?php esc_html_e('Small', 'hara'); ?></a>
                    <a href="#" class="medium-icons active"><i class="icon-info"></i> <?php esc_html_e('Medium', 'hara'); ?></a>
                    <a href="#" class="large-icons"><i class="icon-info"></i> <?php esc_html_e('Large', 'hara'); ?></a>
                </div>

                <div <?php echo $this->get_render_attribute_string('row'); ?>>
                    <?php
                        foreach ($data->icons as $key) {
                            $this->render_item($key);
                        } ?>
                </div>
            </div>
        <?php
    }
    
    protected function render_item($icon)
    {
        ?>
        <div class="icon-preview-box">
            <div class="preview">
            <a href="#" class="show-code" title="<?php esc_attr_e('click to show css class name', 'hara'); ?>"><i class="tb-icon tb-icon-<?php echo esc_attr($icon); ?>"></i><span class="name"><?php echo trim(str_replace('-', ' ', $icon)); ?></span> <code class="code-preview" style="display: none;">tb-icon tb-icon-<?php echo trim($icon); ?></code></a>
            </div>
        </div>
        <?php
    }
}
$widgets_manager->register(new Hara_Elementor_List_Custom_Fonts());
