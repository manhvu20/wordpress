<?php

if (! defined('ABSPATH') || function_exists('Hara_Elementor_Products')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Hara_Elementor_Single_Product_Home extends Hara_Elementor_Widget_Base
{
    public function get_name()
    {
        return 'tbay-single-product-home';
    }

    public function get_title()
    {
        return esc_html__('Hara Single Product Home Page', 'hara');
    }

    public function get_categories()
    {
        return [ 'hara-elements', 'woocommerce-elements'];
    }

    public function get_icon()
    {
        return 'eicon-products';
    }

    public function get_keywords()
    {
        return [ 'woocommerce', 'product', 'products', 'single product' ];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'general',
            [
                'label' => esc_html__('General', 'hara'),
            ]
        );

        if( hara_elementor_pro_activated() ) {
            $this->add_control(
                'product_id',
                [
                    'label' => esc_html__( 'Product ID', 'hara' ),
                    'type' => ElementorPro\Modules\QueryControl\Module::QUERY_CONTROL_ID,
                    'options' => [],
                    'label_block' => true,
                    'autocomplete' => [
                        'object' => ElementorPro\Modules\QueryControl\Module::QUERY_OBJECT_POST,
                        'query' => [
                            'post_type' => [ 'product' ],
                        ],
                    ],
                ]
            );
              
        } else {
            $this->add_control(
                'product_id',
                array(
                    'label'       => esc_html__( 'Product ID', 'hara' ),
                    'type'        => Controls_Manager::NUMBER,
                    'input_type'  => 'text',
                    'placeholder' => '123',
                )
            );
        }


        $this->add_control(
            'show_image',
            [
                'label'     => esc_html__('Show Image', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'     => esc_html__('Show Title', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label'     => esc_html__('Show Price', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_rating',
            [
                'label'     => esc_html__('Show Rating', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );

        $this->add_control(
            'show_short_description',
            [
                'label'     => esc_html__('Show Short Description', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_form_cart',
            [
                'label'     => esc_html__('Show Form Cart', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label'     => esc_html__('Show Product Meta', 'hara'),
                'type'      => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );

        $this->end_controls_section();
        $this->register_section_style_content();
    }

    private function register_section_style_content() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Product', 'hara'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style_content_title',
            [
                'label' => esc_html__('Title', 'hara'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'style_content_title_typography',
                'selector' => '{{WRAPPER}} .product_title',
            ]
        ); 

        $this->add_responsive_control(
            'style_content_title_margin',
            [
                'label'     => esc_html__('Margin', 'hara'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product_title'        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
            ]
        );

        $this->add_control(
            'style_content_price',
            [
                'label' => esc_html__('Price', 'hara'),
                'separator'    => 'before',
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'style_content_price_typography',
                'selector' => '{{WRAPPER}} .price .woocommerce-Price-amount',
            ]
        ); 

        $this->add_responsive_control(
            'style_content_price_margin',
            [
                'label'     => esc_html__('Margin', 'hara'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .price'        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
            ]
        );

        $this->add_control(
            'style_content_rating',
            [
                'label' => esc_html__('Rating', 'hara'),
                'separator'    => 'before',
                'type' => Controls_Manager::HEADING,
            ]
        );

        
        $this->add_responsive_control(
            'style_content_rating_margin',
            [
                'label'     => esc_html__('Margin', 'hara'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-product-rating'        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
            ]
        );

        $this->add_control(
            'style_content_short_description',
            [
                'label' => esc_html__('Short Description', 'hara'),
                'separator'    => 'before',
                'type' => Controls_Manager::HEADING,
            ]
        );

        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'style_content_short_description_typography',
                'selector' => '{{WRAPPER}} .short-description',
            ]
        ); 

        
        $this->add_responsive_control(
            'style_content_short_description_margin',
            [
                'label'     => esc_html__('Margin', 'hara'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .short-description'        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
            ]
        );

        $this->add_control(
            'style_content_form_cart',
            [
                'label' => esc_html__('Form Cart', 'hara'),
                'separator'    => 'before',
                'type' => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_responsive_control(
            'style_content_form_cart_margin',
            [
                'label'     => esc_html__('Margin', 'hara'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} form.cart'        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
            ]
        );

        $this->add_control(
            'style_content_product_meta',
            [
                'label' => esc_html__('Product Meta', 'hara'),
                'separator'    => 'before',
                'type' => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_responsive_control(
            'style_content_product_meta_margin',
            [
                'label'     => esc_html__('Margin', 'hara'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product_meta'        => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render_item_content() {
        $settings = $this->get_settings_for_display();
        extract($settings);

        $product = wc_get_product( $product_id );

        if( !is_object($product) || !$product ) return;

        $image = wp_get_attachment_image_src( $product->get_image_id(), 'woocommerce_single' );

        if( $show_image === 'yes' ) {
            $class_information = 'col-md-6';
        } else {
            $class_information = 'col-md-12';
        }

        if( $show_title !== 'yes' && $show_price !== 'yes' && $show_short_description !== 'yes' && $show_form_cart !== 'yes' && $show_meta !== 'yes' ) {
            $class_image = 'col-md-12';
        } else {
            $class_image = 'col-md-6';
        }

        ?>
        <div class="row">

            <?php if( $show_image === 'yes' ) : ?>
            <div class="image-mains <?php echo esc_attr($class_image); ?>">
                <img src="<?php echo esc_url($image[0]); ?>" data-id="<?php echo esc_attr($product_id); ?>" />
            </div> 
            <?php endif; ?>

            <div class="information <?php echo esc_attr($class_information); ?>">

                <?php 
                   $this->the_product_name( $product );
                   $this->the_price( $product );
                   $this->the_single_rating( $product_id );
                   $this->the_short_description( $product );
                   $this->the_add_to_cart( $product_id );
                   $this->the_single_meta( $product_id );
                ?>

            </div>
        </div>
        <?php
    }

    private function the_product_name( $product ) {
        $settings = $this->get_settings_for_display();
        if( $settings['show_title'] !== 'yes' ) return;

        if( !empty( $product->get_name() ) ) {
            echo '<h2 class="product_title">'. $product->get_name() .'</h2>';
        }
    }

    private function the_price( $product ) {
        $settings = $this->get_settings_for_display();
        if( $settings['show_price'] !== 'yes' ) return;

        if( !empty( $product->get_price() ) ) {
            echo '<p class="price">'. $product->get_price_html() .'</p>';
        }
    }

    
    private function the_single_rating( $product_id ) {
        $settings = $this->get_settings_for_display();
        if( $settings['show_meta'] !== 'yes' ) return;
        
        $post = get_post( $product_id, OBJECT ); 
        setup_postdata( $post );

        woocommerce_template_single_rating();

        wp_reset_postdata();
    }

    private function the_short_description( $product ) {
        $settings = $this->get_settings_for_display();
        if( $settings['show_short_description'] !== 'yes' ) return;

        if( !empty( $product->get_short_description() ) ) {
            echo '<div class="short-description">'. $product->get_short_description() .'</div>';
        }
    }

    private function the_add_to_cart( $product_id ) {
        $settings = $this->get_settings_for_display();
        if( $settings['show_form_cart'] !== 'yes' ) return;

        $post = get_post( $product_id, OBJECT ); 
        setup_postdata( $post );

        remove_action('woocommerce_after_add_to_cart_button', array( Hara_Single_WooCommerce::getInstance(), 'product_group_buttons'), 20);

        woocommerce_template_single_add_to_cart();

        add_action('woocommerce_after_add_to_cart_button', array( Hara_Single_WooCommerce::getInstance(), 'product_group_buttons'), 20);
        
        wp_reset_postdata();
    }

    private function the_single_meta( $product_id ) {
        $settings = $this->get_settings_for_display();
        if( $settings['show_meta'] !== 'yes' ) return;
        
        $post = get_post( $product_id, OBJECT ); 
        setup_postdata( $post );

        woocommerce_template_single_meta();

        wp_reset_postdata();
    }
    
}
$widgets_manager->register(new Hara_Elementor_Single_Product_Home());
