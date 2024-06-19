<?php 
    if ( has_nav_menu( 'primary' ) ) {
        $tbay_location = 'primary';
        $locations  = get_nav_menu_locations();
        $menu_id    = $locations[ $tbay_location ] ;
        $menu_obj   = wp_get_nav_menu_object( $menu_id );
        $menu_name  = hara_get_transliterate($menu_obj->slug);
    } else {
        $tbay_location = $menu_name = '';
    }
?>
<nav data-duration="400" class="hidden-xs hidden-sm tbay-megamenu slide animate navbar tbay-horizontal-default" data-id="'. $menu_name .'">
<?php
    $args = array(
        'theme_location' => 'primary',
        'menu_class' => 'nav navbar-nav megamenu',
        'fallback_cb' => '',
        'menu_id' => 'primary-menu', 
        'menu_class'  => 'nav navbar-nav megamenu menu',
    );

    $args['walker']             =   new Hara_Megamenu_Walker();

    wp_nav_menu($args);
?>
</nav>