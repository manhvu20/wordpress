<?php

defined( 'ABSPATH' ) || exit();


class Hara_Megamenu {

	private $menu_items  = [];

	public function __construct() {
		$this->includes_core();
		$this->includes();

		if( hara_elementor_activated() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		add_filter( 'hara_nav_menu_args', [ $this, 'set_menu_args' ], 99999 );
	}

	public function set_menu_args( $args ) {
		$args['walker'] = new Hara_Megamenu_Walker();

		return $args;
	}

	public function enqueue_scripts() {
		foreach ( $this->menu_items as $id ) {
			Elementor\Core\Files\CSS\Post::create( $id )->enqueue();
		}
	}

	private function includes_core(){
		if ( is_admin() ) {
			include_once get_template_directory() . '/inc/vendors/megamenu/includes/admin/class-admin.php';
		}
		include_once get_template_directory() . '/inc/vendors/megamenu/includes/core-functions.php';
	}

	private function includes() { 
		include_once get_template_directory() . '/inc/vendors/megamenu/includes/hook-functions.php';
		include_once get_template_directory() . '/inc/vendors/megamenu/includes/class-menu-walker.php';
	}
}

return new Hara_Megamenu();
