<?php
/**
 * widget base for WPthembay Core
 *
 * @package    wpthembay
 * @author     Thembay Teams <thembayteam@gmail.com \>
 * @license    GNU General Public License, version 3
 * @copyright  2021-2022 WPthembay Core
 */

abstract class Tbay_Widget extends WP_Widget {
	
	public $template;
	abstract function getTemplate();

	public function display( $args, $instance ) {
		$this->getTemplate();
		extract($args);
		extract($instance);
		echo $before_widget;
			require wpthembay_get_widget_locate( $this->template );
		echo $after_widget;
	}
}