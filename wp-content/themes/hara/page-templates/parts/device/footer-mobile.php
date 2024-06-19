<?php
    if( hara_checkout_optimized() ) return;
    /**
     * hara_before_topbar_mobile hook
     */
    do_action('hara_before_footer_mobile');
    $mobile_footer_slides = hara_tbay_get_config('mobile_footer_slides');
?>



<?php
    if ($mobile_footer_slides && !empty($mobile_footer_slides)) {
        ?>
            <div class="footer-device-mobile d-xl-none clearfix">
            <?php
                /**
                * hara_before_footer_mobile hook
                */
                do_action('hara_before_footer_mobile');

        /**
        * Hook: hara_footer_mobile_content.
        *
        * @hooked hara_the_custom_list_menu_icon - 10
        */

        do_action('hara_footer_mobile_content');

        /**
        * hara_after_footer_mobile hook
        */
        do_action('hara_after_footer_mobile'); ?>
            </div>
        <?php
    }
?>

