<?php
    $location = 'mobile-menu';
    $tbay_location  = '';
    if (has_nav_menu($location)) {
        $tbay_location = $location;
    }

    
    $mmenu_langue           = hara_tbay_get_config('enable_mmenu_langue', false);
    $mmenu_currency         = hara_tbay_get_config('enable_mmenu_currency', false);

    $menu_mobile_select    =  hara_tbay_get_config('menu_mobile_select');

?>
  
<div id="tbay-mobile-smartmenu" data-title="<?php esc_attr_e('Menu', 'hara'); ?>" class="tbay-mmenu d-xl-none"> 


    <div class="tbay-offcanvas-body">
        
        <div id="mmenu-close">
            <button type="button" class="btn btn-toggle-canvas" data-toggle="offcanvas">
                <i class="tb-icon tb-icon-close-01"></i>
            </button>
        </div>

        <?php 
        if ( empty($menu_mobile_select) ) {
            $theme_locations = get_nav_menu_locations();
            $menu_obj = get_term( $theme_locations[$tbay_location], 'nav_menu' );
            $menu_name = hara_get_transliterate($menu_obj->name);
        } else {
            $menu_obj = wp_get_nav_menu_object($menu_mobile_select);
            $menu_name = hara_get_transliterate($menu_obj->slug);
        }
        ?>
        <nav id="tbay-mobile-menu-navbar" class="menu navbar navbar-offcanvas navbar-static" data-id="menu-<?php echo esc_attr($menu_name); ?>" >
            <?php

                $args = array(
                    'fallback_cb' => '',
                );

                if (empty($menu_mobile_select)) {
                    $args['theme_location']     = $tbay_location;
                } else {
                    $args['menu']               = $menu_mobile_select;
                }

                $args['container_id']       =   'main-mobile-menu-mmenu'; 
                $args['menu_id']            =   'main-mobile-menu-mmenu-wrapper';
                $args['items_wrap']         =   '<ul id="%1$s" class="%2$s" data-id="'. $menu_name .'">%3$s</ul>';

                if( class_exists('Hara_Megamenu_Walker') ) {
                    $args['walker']             =   new Hara_Megamenu_Walker();
                } else { 
                    $args['walker']             =   new Walker_Nav_Menu();
                }

                wp_nav_menu($args);


            ?>
        </nav>


    </div>
    <?php if ($mmenu_langue || $mmenu_currency) {
                ?>
         <div id="mm-tbay-bottom">  
    
            <div class="mm-bottom-track-wrapper">

                <?php
                    ?>
                    <div class="mm-bottom-langue-currency ">
                        <?php if ($mmenu_langue): ?>
                            <div class="mm-bottom-langue">
                                <?php do_action('hara_tbay_header_custom_language'); ?>
                            </div>
                        <?php endif; ?>
                
                        <?php if ($mmenu_currency && class_exists('WooCommerce') && class_exists('WOOCS')): ?>
                            <div class="mm-bottom-currency">
                                <div class="tbay-currency">
                                <?php echo do_shortcode('[woocs txt_type = "desc"]'); ?> 
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    <?php
                ?>
            </div>


        </div>
        <?php
            }
    ?>
   
</div>