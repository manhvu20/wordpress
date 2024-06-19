<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Hara
 * @since Hara 1.0
 */


$sidebar = tbay_get_sidebar_dokan();

if(!isset($sidebar['id']) || empty($sidebar['id'])) return;

?> <div class="tbay-sidebar-vendor sidebar"><?php dynamic_sidebar( $sidebar['id'] ); ?></div>

