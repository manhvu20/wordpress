<?php

defined( 'ABSPATH' ) || exit();
/**
 * @param $menu_id
 *
 * @return array menu settings data
 */
if (! function_exists('hara_megamenu_get_item_data')) {
	function hara_megamenu_get_item_data($menu_id = false)
	{
		return get_post_meta($menu_id, 'hara_megamenu_item_data', true);
	}
}

/**
 * update item data
 *
 * @param $menu_id
 * @param $data
 */
if (! function_exists('hara_megamenu_update_item_data')) {
	function hara_megamenu_update_item_data($menu_id = false, $data = array())
	{
		update_post_meta($menu_id, 'hara_megamenu_item_data', $data);
		do_action('hara_menu_item_updated', $menu_id, $data);
	}
}

/**
 * delete menu item settings data
 *
 * @param int $menu_id
 */
if (! function_exists('hara_megamenu_delete_item_data')) {
	function hara_megamenu_delete_item_data($menu_id = false)
	{
		delete_post_meta($menu_id, 'hara_megamenu_item_data');
		do_action('hara_megamenu_item_deleted', $menu_id);
	}
}

/**
 * get elementor post id as menu item id
 *
 * @param int $menu_id
 *
 * @return boolean
 */
if (! function_exists('hara_megamenu_get_post_related_menu')) {
	function hara_megamenu_get_post_related_menu($menu_id = false)
	{
		$post_id = get_post_meta($menu_id, 'hara_elementor_post_id', true);

		return apply_filters('hara_post_related_menu_post_id', $post_id, $menu_id);
	}
}

if (! function_exists('hara_get_name_menu_item')) {
    function hara_get_name_menu_item($menu_id = false)
    {
		$object_id        	= get_post_meta( $menu_id, '_menu_item_object_id', true );
		$object_type        = get_post_meta( $menu_id, '_menu_item_type', true );
		
		switch ( $object_type ) {
			case 'taxonomy':
				$term = get_term($object_id);
				$menu_name = $term->name;
			break;

			default:
				$menu_name = get_the_title($object_id);
				break;
		}

		return $menu_name;
    }
}

/**
 * create releated post menu id
 *
 * @param $menu_id
 */
if (! function_exists('hara_megamenu_create_related_post')) {
	function hara_megamenu_create_related_post($menu_id = false)
	{
		$menu_name = hara_get_name_menu_item( $menu_id );

		$name 	= 'Thembay Megamenu ' . $menu_name;
		$slug 	= 'thembay-megamenu-' . $menu_id;

		$args = apply_filters('hara_megamenu_create_related_post_args', array(
			'post_type'   => 'tbay_custom_post',
			'post_title'  => $name,
			'post_name'   => $slug,
			'post_status' => 'publish',
			'meta_input'  => array(
				'_wp_page_template' => 'elementor_canvas',
				'tbay_block_type' => 'type_megamenu',
			)
		)); 

		$post_related_id = wp_insert_post($args);
		// save elementor_post_id meta value
		update_post_meta($menu_id, 'hara_elementor_post_id', $post_related_id);
		update_post_meta($menu_id, 'hara_elementor_post_name', $slug);

		// trigger events
		do_action('hara_megamenu_releated_post_created', $post_related_id, $args);

		return apply_filters('hara_megamenu_create_releated_post', $post_related_id);
	}
}

/**
 * get menu icon html
 *
 * @param $icon
 *
 * @return string html
 */
if (! function_exists('hara_megamenu_get_icon_html')) {
	function hara_megamenu_get_icon_html(string $icon, $data)
	{
		$style = '';
		if (isset($data['icon_color']) && $data['icon_color']) {
			$style .= 'style="color:' . $data['icon_color'] . '"';
		}

		return apply_filters('hara_menu_icon_html', '<i class="menu-icon ' . $icon . '" ' . $style . '></i>');
	}
}


if (! function_exists('hara_megamenu_get_icons')) {
	function hara_megamenu_get_icons() {
		$jsonfile = get_theme_file_uri('/inc/vendors/elementor/icons/json/tbay-custom.json');
		$request  = wp_remote_get($jsonfile, array('sslverify' => FALSE) );
		$response = wp_remote_retrieve_body($request);

		$json = json_decode($response, true);

		return $json['icons'];
	}
}