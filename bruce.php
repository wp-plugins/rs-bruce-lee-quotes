<?php
/**
 * Plugin Name:       RS Bruce Lee Quotes
 * Plugin URI:        http://www.rstandley.co.uk
 * Description:       A plugin for displaying a random quote by Bruce Lee, on your site via a widget
 * Version:           1.0.1
 * Author:            Rory Standley
 * Author URI:        http://www.rstandley.co.uk
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$bruce_db_version = "1.0.0";

require_once( plugin_dir_path( __FILE__ ) . 'public/class-bruce.php' );
add_action( 'plugins_loaded', array('Bruce', 'get_instance' ) );

register_activation_hook( __FILE__, array( 'Bruce', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Bruce', 'deactivate' ) );

require_once( plugin_dir_path( __FILE__ ) . 'public/class-bruce-widget.php' );
add_action('widgets_init', create_function('', 'return register_widget("Bruce_Widget");'));

if(is_admin()){
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-bruce-admin.php' );
	add_action( 'plugins_loaded', array( 'Bruce_Admin', 'get_instance' ) );

}