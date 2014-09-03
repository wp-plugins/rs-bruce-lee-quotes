<?php
class Bruce_Widget extends WP_Widget {

	const VERSION = '1.0.0';
	protected $plugin_slug = 'bruce-lee-quotes';

	// constructor
	function __construct() {
		parent::WP_Widget(false, $name = __('RS Bruce Lee Quotes', 'Bruce_Widget') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	// widget form creation
	function form($instance) {	
		// Nothing for the admin to enter
	}

	// widget update
	function update($new_instance, $old_instance) {
		// Nothing to update
	}

	// widget display
	function widget($args, $instance) {
		// I am going to include the public view here
		include("views/public.php");
		wp_print_scripts($this->plugin_slug . '-plugin-script');
	}

	function enqueue_styles(){
		// wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	function enqueue_scripts(){
		// wp_register_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION ); 
	}

}