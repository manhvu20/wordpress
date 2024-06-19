<?php

if (! defined('ABSPATH') || function_exists('Hara_Elementor_Product_Categories_Tabs')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Hara_Elementor_Product_Categories_Tabs extends Hara_Elementor_Carousel_Base
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
        return 'tbay-product-categories-tabs';
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
        return esc_html__('Hara Product Categories Tabs', 'hara');
    }
    public function get_categories()
    {
        return [ 'hara-elements', 'woocommerce-elements'];
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
        return 'eicon-product-tabs';
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    public function get_script_depends()
    {
        return ['slick', 'hara-custom-slick'];
    }

    public function get_keywords()
    {
        return [ 'woocommerce-elements', 'product-categories' ];
    }

    protected function register_controls()
    {
        $this->register_controls_heading();
        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('Product Categories', 'hara'),
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => esc_html__('Number of products', 'hara'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Number of products to show ( -1 = all )', 'hara'),
                'default' => 6,
                'min'  => -1,
            ]
        );

        $this->add_control(
            'advanced',
            [
                'label' => esc_html__('Advanced', 'hara'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->register_woocommerce_order();
        $this->add_control(
            'product_type',
            [
                'label'   => esc_html__('Product Type', 'hara'),
                'type'     => Controls_Manager::SELECT,
                'options' => $this->get_product_type(),
                'default' => 'newest'
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
            'style_tabs',
            [
                'label' => esc_html__( 'Categories Tabs Style', 'hara' ),
                'type' => Controls_Manager::SELECT,
                'default'   => 'style1',
                'options'   => [
                    'style1'      => esc_html__('Style 1', 'hara'),
                    'style2'  => esc_html__('Style 2', 'hara'),
                ],
            ]
        ); 

        
        $this->add_control(
            'ajax_tabs',
            [
                'label' => esc_html__( 'Ajax Categories Tabs', 'hara' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => esc_html__( 'Show/hidden Ajax Categories Tabs', 'hara' ), 
            ]
        );

        $this->add_control(
            'icon_after_title',
            [
                'label' => esc_html__( 'Show Icon After Title', 'hara' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => esc_html__( 'Show/hidden Icon After Title', 'hara' ), 
                'condition' => [
                    'style_tabs' => 'style1'
                ],
                'prefix_class' => 'icon-after-title-'
            ]
        );

        $repeater = $this->register_category_repeater();
        $this->add_control(
            'categories_tabs',
            [
                'label' => esc_html__('Categories Items', 'hara'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'categories_field' => '{{{ categories }}}',
            ]
        );

        $this->add_control(
            'product_style',
            [
                'label' => esc_html__('Product Style', 'hara'),
                'type' => Controls_Manager::SELECT,
                'default' => 'v1',
                'options' => $this->get_template_product(),
                'prefix_class' => 'elementor-product-'
            ]
        );

        
        $this->register_button();
        $this->end_controls_section();
        $this->add_control_responsive();
        $this->add_control_carousel(['layout_type' => 'carousel']);
        $this->register_style_heading();
        $this->register_style_tabs_icon();
    }

    private function register_category_repeater()
    {
        $repeater = new \Elementor\Repeater();
        $categories = $this->get_product_categories();
        $repeater->add_control(
            'category',
            [
                'label' => esc_html__('Select Category', 'hara'),
                'type'      => Controls_Manager::SELECT,
                'default'   => array_keys($categories)[0],
                'label_block' => true,
                'options'   => $categories,
            ]
        );
        $repeater->add_control(
            'icon_category',
            [
                'label' => esc_html__('Select Icon', 'hara'),
                'type'      => Controls_Manager::ICONS,
            ]
        );

        return $repeater;
    }

    protected function register_style_heading()
    {
        $this->start_controls_section(
            'section_style_heading_categories_tab',
            [
                'label' => esc_html__('Heading Product Categories Tabs', 'hara'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'style_heading_tab',
            [
                'label' => esc_html__('Style Heading Tab', 'hara'),
                'description' => esc_html__('Only working on screen > 992px', 'hara'),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'style-inline' => esc_html__('Inline', 'hara'),
                    'style-block' => esc_html__('Block', 'hara'),
                ],
                'default' => 'style-block',
                'prefix_class' => 'heading-tab-'
            ]
        );

        $this->add_responsive_control(
            'heading_categories_tab_align',
            [
                'label' => esc_html__('Alignment', 'hara'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'style_heading_tab' => 'style-block'
                ],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'hara'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'hara'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'hara'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .wrapper-heading-tab .tabs-list, {{WRAPPER}} .wrapper-heading-tab .heading-tbay-title' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
     
        $this->add_responsive_control(
            'heading_categories_tab_padding',
            [
                'label'      => esc_html__('Padding', 'hara'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '12',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wrapper-heading-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_categories_tab_margin',
            [
                'label'      => esc_html__('Margin', 'hara'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '6',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors'  => [
                    '{{WRAPPER}} .wrapper-heading-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'border_heading_categories_tab',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .wrapper-heading-tab',
                'separator'   => 'before',
            ]
        );


        $this->end_controls_section();
    }

    protected function register_style_tabs_icon(){
        $this->start_controls_section(
            'section_style_tabs_icon',
            [
                'label' => esc_html__( 'Tabs Icon', 'hara' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'tabs_icon_size',
            [
                'label' => esc_html__('Font Size', 'hara'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a > i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tabs_icon_line_height',
            [
                'label' => esc_html__('Line Height', 'hara'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a > i' => 'line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'hara' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ], 
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a > i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );    

        $this->add_responsive_control(
            'tabs_icon_size_with_height',
            [
                'label' => esc_html__( 'Size width/height', 'hara' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 300,
                    ], 
                ], 
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a > i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );    

        $this->start_controls_tabs( 'categories_tabs_icon_tabs' );

        $this->start_controls_tab(
            'categories_tabs_icon_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'hara' ),
            ]
        );

        $this->add_control(
            'categories_tabs_icon_bg',
            [
                'label' => esc_html__( 'Background', 'hara' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a > i' => 'background: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'categories_tabs_icon_color',
            [
                'label' => esc_html__( 'Color', 'hara' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a > i' => 'color: {{VALUE}};',
                ],
                
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'categories_tabs_icon_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'hara' ),
            ]
        );

        $this->add_control(
            'categories_tabs_icon_bg_hover',
            [
                'label' => esc_html__( 'Background', 'hara' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a:hover > i,
                    {{WRAPPER}} .tabs-list > li > a.active > i' => 'background: {{VALUE}};',
                ],
                
            ]
        );

        $this->add_control(
            'categories_tabs_icon_color_hover',
            [
                'label' => esc_html__( 'Hover Color', 'hara' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .tabs-list > li > a:hover > i,
                    {{WRAPPER}} .tabs-list > li > a.active > i' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_tab();
    }

    protected function register_button()
    {
        $this->add_control(
            'show_more',
            [
                'label'     => esc_html__('Display Show All', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control(
            'text_button',
            [
                'label'     => esc_html__('Text Button', 'hara'),
                'default'   => esc_html__('Show all', 'hara'),
                'type'      => Controls_Manager::TEXT,
                'condition' => [
                    'show_more' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'icon_button',
            [
                'label'     => esc_html__('Icon Button', 'hara'),
                'type'      => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'tb-icon tb-icon-arrow-right',
                    'library' => 'tbay-custom',
                ],
                'condition' => [
                    'show_more' => 'yes'
                ]
            ]
        );
    }
   

    public function get_template_product()
    {
        return apply_filters('hara_get_template_product', 'v1');
    }

    public function render_tabs_title($categories_tabs, $random_id)
    {
        $settings = $this->get_settings_for_display();
        extract($settings);


        if ($ajax_tabs === 'yes') {
            $this->add_render_attribute('row', 'class', ['products']);
            $attr_row = $this->get_render_attribute_string('row'); 
    
            $json = array(
                'product_type'          => $product_type,
                'cat_operator'          => '',
                'limit'                 => $limit,
                'orderby'               => $orderby,
                'order'                 => $order,
                'product_style'         => $product_style,
                'attr_row'              => $attr_row,
            ); 
    
            $encoded_settings  = wp_json_encode( $json );
            $tabs_data = 'data-atts="'. esc_attr( $encoded_settings ) .'"';
        } else {
            $tabs_data = '';
        }
        ?>
            
            <?php
                if (!empty($title_cat_tab) || !empty($sub_title_cat_tab)) {
                    ?>
                    <h3 class="heading-tbay-title">
                        <?php if (!empty($title_cat_tab)) : ?>
                            <span class="title"><?php echo trim($title_cat_tab); ?></span>
                        <?php endif; ?>	    	
                        <?php if (!empty($sub_title_cat_tab)) : ?>
                            <span class="subtitle"><?php echo trim($sub_title_cat_tab); ?></span>
                        <?php endif; ?>
                    </h3>
                    <?php
                } ?>

            <ul class="product-categories-tabs-title tabs-list nav nav-tabs" <?php echo trim($tabs_data); ?>>
                <?php $_count = 0; ?>
                <?php foreach ($categories_tabs as $item) : ?>
                    <?php $this->render_product_tab($item['category'], $item['_id'], $_count, $random_id, $item['icon_category']); ?>
                    <?php $_count++; ?>
                <?php endforeach; ?>
                
            </ul>
            
        <?php
    }
    public function render_product_tab($item, $_id, $_count, $random_id,$icon)
    {
        $active     = ($_count == 0) ? 'active' : '';
        $obj_cat    = get_term_by('slug', $item, 'product_cat');
        
        if ( !is_object($obj_cat) ) return;

        $title = $obj_cat->name; ?>
        <li >  
            <a href="javascript:void(0)" data-bs-toggle="pill" data-bs-target="#<?php echo esc_attr($item.'-'. $random_id .'-'.$_id); ?>" class="<?php echo esc_attr($active); ?>" data-value="<?php echo esc_attr($item); ?>"> <?php $this->render_item_icon($icon); ?><span><?php echo trim($title); ?></span></a>
        </li>

       <?php
    }
    public function render_product_tabs_content($categories_tabs, $random_id)
    {
        $settings = $this->get_settings_for_display();
        ?>
            <div class="content-product-category-tab">
                <div class="tbay-addon-content tab-content woocommerce">
                 <?php
                    $_count = 0;
                    foreach ($categories_tabs as $key) {
                        if( is_object(get_term_by('slug', $key['category'], 'product_cat')) ) {

                            $tab_active = ($_count == 0) ? ' active active-content current' : ''; 
                            ?> 
                            <div class="tab-pane <?php echo esc_attr($tab_active); ?>" id="<?php echo esc_attr($key['category'].'-'. $random_id .'-'.$key['_id']); ?>"> 
                            <?php
                            if( $_count === 0 || $settings['ajax_tabs'] !== 'yes' ) {
                                $this->render_content_tab($key['category'], $tab_active, $key['_id'], $random_id);
                            }
                            $_count++; 
                            ?>
                            </div>
                            <?php
                        } 
                    } 
                ?>
                </div>
            </div>
        <?php
    }
    private function render_content_tab($key, $tab_active, $_id, $random_id)
    {
        $settings = $this->get_settings_for_display();
        $cat_operator = $product_type = $limit = $orderby = $order = '';
        extract($settings);
        
        /** Get Query Products */
        $loop = hara_get_query_products($key, $cat_operator, $product_type, $limit, $orderby, $order);

        $this->add_render_attribute('row', 'class', ['products']);

        $attr_row = $this->get_render_attribute_string('row'); ?>
        
        <?php wc_get_template('layout-products/layout-products.php', array( 'loop' => $loop, 'product_style' => $product_style, 'attr_row' => $attr_row)); ?>
        <?php
    }
    
    public function render_item_button()
    {
        $settings = $this->get_settings_for_display();
        extract($settings);
        $url_category =  get_permalink(wc_get_page_id('shop'));
        if (isset($text_button) && !empty($text_button)) {?>
            <div class="readmore-wrapper"><a href="<?php echo esc_url($url_category)?>" class="btn show-all"><?php echo trim($text_button) ?>
                <?php
                    $this->render_item_icon($icon_button);
                ?>
            </a></div>
            <?php
        }
    }
}
$widgets_manager->register(new Hara_Elementor_Product_Categories_Tabs());
