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

class Tbay_PostType_Custom_Post {

	/**
	 * Instance of Tbay_PostType_Custom_Post
	 *
	 * @var Tbay_PostType_Custom_Post
	 */
	private static $_instance = null;

	/**
	 * Instance of Tbay_PostType_Custom_Post
	 *
	 * @return Tbay_PostType_Custom_Post Instance of Tbay_PostType_Custom_Post
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

		add_action( 'add_meta_boxes', [ $this, 'register_metabox' ] );
		add_action( 'save_post', [ $this, 'save_meta' ] );

		add_filter( 'manage_tbay_custom_post_posts_columns', [ $this, 'set_shortcode_columns' ] );
		add_action( 'manage_tbay_custom_post_posts_custom_column', [ $this, 'render_shortcode_column' ], 10, 2 );


		
		add_action( 'pre_get_posts', [ $this, 'alter_query' ], 10, 1 );
		add_filter( 'query_vars', [ $this, 'custom_query_vars_filter'], 10, 1 );
		add_action( 'restrict_manage_posts', [ $this, 'admin_posts_filter_restrict_manage_posts'], 10, 1 );

		if ( is_admin() ) {
			add_action( 'manage_tbay_custom_post_posts_custom_column', [ $this, 'column_content' ], 10, 2 );
			add_filter( 'manage_tbay_custom_post_posts_columns', [ $this, 'column_headings' ] );

			add_filter( 'manage_edit-tbay_custom_post_sortable_columns', [ $this, 'set_custom_sortable_columns' ] );
		}
  	} 
	  
  	public static function register_post_type() {
	    $labels = array(
			'name'               => esc_html__( 'Thembay Blocks', 'wpthembay' ),
			'singular_name'      => esc_html__( 'Thembay', 'wpthembay' ),
			'menu_name'          => esc_html__( 'Thembay Blocks', 'wpthembay' ),
			'name_admin_bar'     => esc_html__( 'Thembay', 'wpthembay' ),
			'add_new'            => esc_html__( 'Add New', 'wpthembay' ),
			'add_new_item'       => esc_html__( 'Add New Header, Footer or Block', 'wpthembay' ),
			'new_item'           => esc_html__( 'New Thembay Block', 'wpthembay' ),
			'edit_item'          => esc_html__( 'Edit Thembay Block', 'wpthembay' ),
			'view_item'          => esc_html__( 'View Thembay Block', 'wpthembay' ),
			'all_items'          => esc_html__( 'All Thembay', 'wpthembay' ),
			'search_items'       => esc_html__( 'Search Thembay Blocks', 'wpthembay' ),
			'parent_item_colon'  => esc_html__( 'Parent Thembay Blocks:', 'wpthembay' ),
			'not_found'          => esc_html__( 'No Thembay Blocks found.', 'wpthembay' ),
			'not_found_in_trash' => esc_html__( 'No Thembay Blocks found in Trash.', 'wpthembay' ),
	    ); 

	    $type = 'tbay_custom_post';
 
	    register_post_type( $type,
	      	array(
		        'labels'            => apply_filters( 'tbay_postype_custom_post_labels' , $labels ),
		        'supports'          => array( 'title', 'editor' ),
		        'public'            => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
		        'has_archive'       => false,
				'exclude_from_search' => false,
		        'menu_icon' 		=> 'dashicons-layout',
		        'menu_position'     => 51,
				'capability_type'   => array($type, "{$type}s"),
				'map_meta_cap'      => true,	      	
			)
	    );

  	}

  	public static function add_role_caps() {
 
		 // Add the roles you'd like to administer the custom post types
		 $roles = array('administrator');

		 $type  = 'tbay_custom_post';
		 
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
			$role->add_cap( "delete_{$type}s" );
			$role->add_cap( "delete_others_{$type}s" );
			$role->add_cap( "delete_private_{$type}s" ); 
			$role->add_cap( "delete_published_{$type}s" );
		 
		 }
	}

	/**
	 * Adds the custom list table column content.
	 *
	 * @since 1.2.0
	 * @param array $column Name of column.
	 * @param int   $post_id Post id.
	 * @return void
	 */
	public function column_content( $column, $post_id ) {

		if ( 'tbay_display_rules' == $column ) {

			$type = get_post_meta( $post_id, 'tbay_block_type', true );


			if ( isset( $type ) ) { 
				echo '<div class="ast-advanced-headers-location-wrap" style="margin-bottom: 5px;">';
				echo '<strong>Type: '. $this->get_title_column_content($type) .'</strong>';
				echo '</div>';
			}
		}
	}

	private function get_title_column_content( $type ) {
		switch ($type) {
			case 'type_header':
				return '<a href="'. admin_url( 'edit.php?post_type=tbay_custom_post&tbay_block_type='. $type ) .'">'. esc_html__('Header', 'wpthembay') .'</a>';
				break;

			case 'type_footer':
				return '<a href="'. admin_url( 'edit.php?post_type=tbay_custom_post&tbay_block_type='. $type ) .'">'. esc_html__('Footer', 'wpthembay') .'</a>';
				break;

			case 'type_megamenu':
				return '<a href="'. admin_url( 'edit.php?post_type=tbay_custom_post&tbay_block_type='. $type ) .'">'. esc_html__('Mega Menu', 'wpthembay') .'</a>';
				break;

			default:
				return '<a href="'. admin_url( 'edit.php?post_type=tbay_custom_post&tbay_block_type='. $type ) .'">'. esc_html__('Custom Block', 'wpthembay') .'</a>';
				break;
		}
	}

	/**
	 * Set shortcode column for template list.
	 *
	 * @param array $columns template list columns.
	 */
	function set_shortcode_columns( $columns ) {
		$date_column = $columns['date'];

		unset( $columns['date'] );

		$columns['shortcode'] = esc_html__( 'Shortcode', 'wpthembay' );
		$columns['date']      = $date_column;

		return $columns;
	}

	/**
	 * Display shortcode in block list column.
	 *
	 * @param array $column block list column.
	 * @param int   $post_id post id.
	 */
	function render_shortcode_column( $column, $post_id ) {

		$slug = get_post_field( 'post_name', $post_id );
		switch ( $column ) {
			case 'shortcode':
				ob_start(); 
				?>
				<span class="tbay-shortcode-col-wrap">
					<input type="text" onfocus="this.select();" readonly="readonly" value='[tbay_block id="<?php echo esc_attr( $slug ); ?>"]' class="tbay-large-text code">
				</span>

				<?php

				ob_get_contents();
				break;
		}
	}

	function custom_query_vars_filter( $vars ) {
		$vars[] .= 'tbay_block_type';
		return $vars;
	}

	function alter_query( $query ) {

		if ( !is_admin() || 'tbay_custom_post' != $query->query['post_type'] )
			return;

		if ( !empty($query->query_vars['tbay_block_type']) ) {
			$query->set( 'meta_key', 'tbay_block_type' );
			$query->set( 'meta_value', $query->query_vars['tbay_block_type'] );
		}
	}

	function admin_posts_filter_restrict_manage_posts( $post_type ){
		if( 'tbay_custom_post' !== $post_type ){
			return;
		}
	
		$values = array(
			'Custom' => 'custom', 
			'Header' => 'type_header',
			'Footer' => 'type_footer',
			'Megamenu' => 'type_megamenu',
		);
		?>
		<select name="tbay_block_type">
		<option value=""><?php esc_html_e('Display Type ', 'wpthembay'); ?></option>
		<?php
			$current_v = isset($_GET['tbay_block_type'])? $_GET['tbay_block_type']:'';
			foreach ($values as $label => $value) {
				printf
					(
						'<option value="%s"%s>%s</option>',
						$value,
						$value == $current_v? ' selected="selected"':'',
						$label
					);
				}
		?>
		</select>
		<?php
	}

	
	/**
	 * Adds or removes list table column headings.
	 *
	 * @param array $columns Array of columns.
	 * @return array
	 */
	public function column_headings( $columns ) {
		unset( $columns['date'] );

		$columns['tbay_display_rules'] 		= esc_html__( 'Display Type', 'wpthembay' );
		$columns['date']                    = esc_html__( 'Date', 'wpthembay' );

		return $columns;
	}

		/**
	 * Register meta box(es).
	 */
	function register_metabox() {
		add_meta_box(
			'wpthembay-meta-box',
			esc_html__( 'Thembay Blocks Options', 'wpthembay' ),
			[
				$this,
				'tbay_metabox_render',
			],
			'tbay_custom_post',
			'normal',
			'high'
		);
	}

	
	/**
	 * Render Meta field.
	 *
	 * @param  POST $post Currennt post object which is being displayed.
	 */
	public function tbay_metabox_render( $post ) {
		$values            = get_post_custom( $post->ID );
		$template_type     = isset( $values['tbay_block_type'] ) ? esc_attr( $values['tbay_block_type'][0] ) : '';

		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'tbay_meta_nounce', 'tbay_meta_nounce' );

		$slug = get_post_field( 'post_name', $post->ID );
		?>
		<table class="tbay-options-table widefat">
			<tbody>
				<tr class="tbay-options-row type-of-template">
					<td class="tbay-options-row-heading">
						<label for="tbay_block_type"><?php _e( 'Type of Template', 'wpthembay' ); ?></label>
					</td>
					<td class="tbay-options-row-content">
						<select name="tbay_block_type" id="tbay_block_type">
							<option value="custom" <?php selected( $template_type, 'custom' ); ?>><?php _e( 'Custom Block', 'wpthembay' ); ?></option>
							<option value="type_header" <?php selected( $template_type, 'type_header' ); ?>><?php _e( 'Header', 'wpthembay' ); ?></option>
							<option value="type_footer" <?php selected( $template_type, 'type_footer' ); ?>><?php _e( 'Footer', 'wpthembay' ); ?></option>
							<option value="type_megamenu" <?php selected( $template_type, 'type_megamenu' ); ?>><?php _e( 'Mega Menu', 'wpthembay' ); ?></option>
						</select>
					</td>
				</tr> 

				<tr class="tbay-options-row tbay-shortcode">
					<td class="tbay-options-row-heading">
						<label for="tbay_block_type"><?php _e( 'Shortcode', 'wpthembay' ); ?></label>
						<i class="tbay-options-row-heading-help dashicons dashicons-editor-help" title="<?php _e( 'Copy this shortcode and paste it into your post, page, or text widget content.', 'wpthembay' ); ?>">
						</i>
					</td>
					<td class="tbay-options-row-content">
						<span class="tbay-shortcode-col-wrap">
							<input type="text" onfocus="this.select();" readonly="readonly" value='[tbay_block id="<?php echo esc_attr( $slug ); ?>"]' class="tbay-large-text code">
						</span>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Save meta field.
	 *
	 * @param  POST $post_id Currennt post object which is being displayed.
	 *
	 * @return Void
	 */
	public function save_meta( $post_id ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// if our nonce isn't there, or we can't verify it, bail.
		if ( ! isset( $_POST['tbay_meta_nounce'] ) || ! wp_verify_nonce( $_POST['tbay_meta_nounce'], 'tbay_meta_nounce' ) ) {
			return;
		}

		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if ( isset( $_POST['tbay_block_type'] ) ) {
			update_post_meta( $post_id, 'tbay_block_type', esc_attr( $_POST['tbay_block_type'] ) );
			update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
		}
	}

	function set_custom_sortable_columns( $columns ) {
		$columns['shortcode'] = 'shortcode'; 
		$columns['tbay_display_rules'] = 'tbay_display_rules'; 
	  
		return $columns;
	}
}

Tbay_PostType_Custom_Post::instance();