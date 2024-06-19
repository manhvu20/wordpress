<?php
/**
 * Footer manager for WPthembay Core
 *
 * @package    WPThembay
 * @author     Thembay Teams <thembayteam@gmail.com >
 * @license    GNU General Public License, version 3
 * @copyright  2021-2022 WPthembay Core
 */
 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Tbay_PostType_Discount_Campaign {

	/**
	 * Instance of Tbay_PostType_Discount_Campaign
	 *
	 * @var Tbay_PostType_Discount_Campaign
	 */
	private static $_instance = null;

	/**
	 * Instance of Tbay_PostType_Discount_Campaign
	 *
	 * @return Tbay_PostType_Discount_Campaign Instance of Tbay_PostType_Discount_Campaign
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	/**
	 * Constructor
	 */
	private function __construct() {
    	add_action( 'init', array( $this, 'register_post_type' ) );
    	add_action( 'admin_init', array( $this, 'add_role_caps' ) );
  	} 
	  
  	public static function register_post_type() {
	    $labels = array(
			'name'               => esc_html__( 'Thembay Discount campaign', 'wpthembay' ),
			'singular_name'      => esc_html__( 'campaign', 'wpthembay' ),
			'menu_name'          => esc_html__( 'Thembay Discount campaign', 'wpthembay' ),
			'name_admin_bar'     => esc_html__( 'Thembay Discount campaign', 'wpthembay' ),
			'add_new'            => esc_html__( 'Add New', 'wpthembay' ),
			'add_new_item'       => esc_html__( 'Add New Discount Campaign', 'wpthembay' ),
			'new_item'           => esc_html__( 'New Discount Campaign', 'wpthembay' ),
			'edit_item'          => esc_html__( 'Edit Discount Campaign', 'wpthembay' ),
			'view_item'          => esc_html__( 'View Discount Campaign', 'wpthembay' ),
			'all_items'          => esc_html__( 'All Discount Campaign', 'wpthembay' ),
			'search_items'       => esc_html__( 'Search discount campaign', 'wpthembay' ),
			'parent_item_colon'  => esc_html__( 'Parent discount campaign:', 'wpthembay' ),
			'not_found'          => esc_html__( 'No discount campaign found.', 'wpthembay' ),
			'not_found_in_trash' => esc_html__( 'No discount campaign found in Trash.', 'wpthembay' ),
	    ); 

	    $type = 'tb_discount_campaign';
 
	    register_post_type( $type,
	      	array(
		        'labels'            => apply_filters( 'tbay_postype_discount_campaign_labels' , $labels ),
		        'supports'          => array( 'title', 'editor' ),
		        'public'            => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
		        'has_archive'       => false,
				'exclude_from_search' => false,
		        'menu_icon' 		=> 'dashicons-list-view',
		        'menu_position'     => 51,
				'capability_type'   => array($type, "{$type}s"),
				'map_meta_cap'      => true,	      	
			)
	    );

  	}

  	public static function add_role_caps() {
 
		 // Add the roles you'd like to administer the custom post types
		 $roles = array('administrator');

		 $type  = 'tb_discount_campaign';
		 
		 // Loop through each role and assign capabilities
		 foreach($roles as $the_role) { 
		 
		    $role = get_role($the_role);
		 
			$role->add_cap( "read" );
			$role->add_cap( "read_{$type}");
			$role->add_cap( "read_private_{$type}s" );
			$role->add_cap( "edit_{$type}" );
			$role->add_cap( "edit_{$type}s" );
			$role->add_cap( "edit_others_{$type}s" );
			$role->add_cap( "edit_published_{$type}s" );
			$role->add_cap( "publish_{$type}s" );
			$role->add_cap( "delete_others_{$type}s" );
			$role->add_cap( "delete_private_{$type}s" ); 
			$role->add_cap( "delete_published_{$type}s" );
		 
		 }
	}
}

Tbay_PostType_Discount_Campaign::instance();