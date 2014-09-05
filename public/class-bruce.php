<?php
class Bruce {

	const VERSION = '1.0.0';
	protected $plugin_slug = 'buce-lee-quotes';
	protected static $instance = null;

	private function __construct() {

		global $wpdb;
		global $bruce_db_version;

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'display_plugin_view_page'));

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'bruce', array( $this, 'action_method_name' ) );
		add_filter( 'bruce', array( $this, 'filter_method_name' ) );


	}

	public static function display_plugin_view_page(){
		// include("views/public.php");
	}

	/**
	 * Return the plugin slug.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if(null == self::$instance){
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 * $network_wide boolean for WPMU
	 */
	public static function activate($network_wide) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if($network_wide){

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 * $network_wide boolean for WPMU
	 */
	public static function deactivate($network_wide){

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 */
	public function activate_new_site($blog_id){

		if (1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	private static function single_activate() {
		// We need to install the table 
		global $bruce_db_version;
		global $wpdb;
   		$table_name = $wpdb->prefix . "rs_bruce_table";
      
   		$sql = "CREATE TABLE $table_name (
  		`bruce_id` int(11) NOT NULL AUTO_INCREMENT,
  		`bruce_quote` text NOT NULL,
  		PRIMARY KEY (`bruce_id`)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql = "
			INSERT INTO $table_name (bruce_quote)VALUES
			('The key to immortality is first living a life worth remembering.'),
			('Mistakes are always forgivable, if one has the courage to admit them.'),
			('Be happy but never satisfied.'),
			('If you don\'t want to slip up tomorrow, speak the truth today'),
			('Learning is never cumulative, it is a movement of knowing which has no beginning and no end.'),
			('Defeat is a state of mind. No one is ever defeated until defeat has been accepted as a reality.'),
			('It\'s not the daily increase but daily decrease. Hack away at the unessential.'),
			('Do not pray for an easy life. Pray for the strength to endure a difficult one.'),
			('I fear not the man who has practiced 10,000 kicks once, but I fear the man who has practiced one kick 10,000 times.'),
			('Make at least one definite move daily towards your goal.'),
			('Man, the living creature, the creating individual, is always more important than any established style or system.'),
			('Knowledge will give you power, but character respect.'),
			('Cease negative mental chattering. If you think a thing is impossible, you\'ll make it impossible.'),
			('Running water never grows stale. So you just have to keep on flowing.'),
			('A wise man can learn more from a foolish question than a fool can learn from a wise answer.'),
			('Real living is living for others.'),
			('The happiness that is derived from excitement is like a brilliant fire. Soon it will go out.'),
			('Empty your cup so that it may be filled. Become devoid to gain totality.'),
			('Obey the principles without being bound by them.'),
			('If you love life, don\'t waste time, for time is what life is made up of.'),
			('Don\'t fear failure. Not failure, but low aim, is the crime. In great attempts, it is glorious even to fail.'),
			('There are no limits. There are plateaus, but you must not stay there, you must go beyond them.'),
			('Adapt what is useful, reject what is useless and add what is specifically your own.'),
			('Reality is apparent when one ceases to compare.'),
			('Absorb what is useful, discard what is not, add what is uniquely your own.'),
			('Knowing is not enough. We must apply. Willing is not enough. We must do.'),
			('Concentration is the root of all the higher abilities in man.'),
			('To hell with circumstances. I create opportunities.'),
			('If you always put limits on everything you do, physical or anything else, it will spread into your work and into your life. There are no limits. There are only plateaus, and you must not stay there, you must go beyond them.'),
			('Ultimatley the greatest help is self-help.'),
			('For it is easy to criticise and break down the spirit of others, but to know yourself takes a lifetime.'),
			('Showing off is the fool\'s idea of glory.'),
			('Always be yourself, express yourself, have faith in yourself, do not go out and look for a successful personality and duplicate it.'),
			('A quick temper will make a fool of you soon enough.'),
			('As you think, so you become.'),
			('A goal is not always meant to be reached, it often serves simply as something to aim at.'),
			('What IS, is more important that what should be.'),
			('If you spend to much time thinking about a thing, you\'ll never get it done.'),
			('Those who are unaware they are walking in darkness will never seek the light.');
		";

		dbDelta( $sql );

		add_option( "bruce_db_version", $bruce_db_version );
	}

	private static function single_deactivate() {
		// We need to remove any tables here
		global $wpdb;
		$table_name = $wpdb->prefix."rs_bruce_table";
		//Delete any options that's stored also?
		delete_option('bruce_db_version');
		$wpdb->query("DROP TABLE IF EXISTS $table_name");
	}

	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// Define your filter hook callback here
	}

}
