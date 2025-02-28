<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Hara
 * @since Hara 1.0
 */
if (function_exists('dokan_is_store_page') && dokan_is_store_page() && dokan_get_option('enable_theme_store_sidebar', 'dokan_appearance', 'off') !== 'off') {
    return;
}

if (has_nav_menu('primary') || is_active_sidebar('sidebar-1')) : ?>
	<div id="secondary" class="secondary">

		<?php if (has_nav_menu('primary')) : ?>
			<nav id="site-navigation" class="main-navigation">
				<?php
                    // Primary navigation menu.
                    wp_nav_menu(array(
                        'menu_class'     => 'nav-menu',
                        'theme_location' => 'primary',
                    ));
                ?>
			</nav><!-- .main-navigation -->
		<?php endif; ?>


		<?php if (is_active_sidebar('sidebar-1')) : ?>
			<div id="widget-area" class="widget-area" role="complementary">
				<?php dynamic_sidebar('sidebar-1'); ?>
			</div><!-- .widget-area -->
		<?php endif; ?>

	</div><!-- .secondary -->

<?php endif; ?>
