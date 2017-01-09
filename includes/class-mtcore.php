<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://minimalthemes.net/
 * @since      1.0.0
 *
 * @package    MT_Core
 * @subpackage MT_Core/includes
 */

 // Exit if accessed directly
 if ( !defined( 'ABSPATH' ) ) exit;

/**
 * The core plugin class.
 *
 * This is used to define theme features, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MT_Core
 * @subpackage MT_Core/includes
 * @author     Minimal Themes <support@minimalthemes.net>
 */
class MT_Core {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Get all theme features
	 * by deafult the active theme has this features
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      mixed 			array theme fature pairing with theme relative path
	 */
	public $features = array();

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = MT_SLUG;
		$this->version = MT_VERSION;

    $this->features = get_option( 'mtcore_features', array() );

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		add_action( 'plugins_loaded', array( $this, 'set_locale' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MT_Core_Admin. Defines all hooks for the admin area.
	 * - MT_Core_Public. Defines all hooks for the public side of the site.
	 * - MT_Core_Feature. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once MT_PATH . 'admin/class-mtcore-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once MT_PATH . 'public/class-mtcore-public.php';

    /**
		 * Helper class method to cleanup and whitelabeling WordPress site
     * use this with caution, this is the part of one theme fetaures
     * if cleanup features enabled, you dont need to call this class method anymore!
     *
     * USE WITH CAUTION!!! THIS MAY BREAK YOUR WORDPRESS SITE!!!
		 */
		require_once MT_INC . 'class-mtcore-cleanup.php';

		/**
	   * Defining all theme features
	   * enabled by defaults, except cleanup features.
     *
     * set of features from option
	   */
		$opts_temp = array();

		if ( !empty( $this->features ) ) {
			foreach ( $this->features as $feature ) {

				$class_file_helper = sprintf( '%sclass-mtcore-%s.php', MT_INC, $feature );
				$class_name_helper = file_to_classname( $class_file_helper );

				if ( !file_exists( $class_file_helper ) ) {
					continue;
				}
				require_once $class_file_helper;
        if ( class_exists( $class_name_helper ) ) {
          $this->{$feature} = $class_name_helper::get_instance();
        }
			}
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MT_Core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function set_locale() {
		load_plugin_textdomain( 'mtcore', false, MT_DIR . 'languages' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		if ( ! is_admin() ) {
			return;
		}
		$plugin_admin = new MT_Core_Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ), 99 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new MT_Core_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ), 1 );

	}

  /**
	 * Public get and check single feature object.
	 *
	 * @since     1.0.0
	 * @return    boolean
	 */
	public function get( $feature = '' ) {
		if ( isset( $this->$feature ) && in_array( $feature, $this->features ) ) {
      return $this->$feature;
    }
    return false;
	}

	/**
	 * Public set enabled theme features.
   * Inside your theme get $MT_Core global value, use set() method then
   * with variable array value theme features you want to enable.
   *
   * Example :
   * global $MT_Core;
   * $MT_Core->set( array( 'metabox', 'builder' ) );
	 *
	 * @since     1.0.0
   * @param     $new_features   mixed theme features
	 * @return    boolean
	 */
	public function set( $new_features = array() ) {
		$features_opt = 'mtcore_features';
		$option = ( get_option( $features_opt ) !== false ) ? get_option( $features_opt ) : array();
		if ( $option !== false && $option !== $new_features ) {
			update_option( $features_opt, $new_features );
			wp_cache_delete( 'alloptions', 'options' );
			return true;
		}
		return false;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the plugin absolute folder path.
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public function get_path() {
		return MT_PATH;
	}

	/**
	 * Retrieve the plugin absolute URL.
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public function get_uri() {
		return MT_URI;
	}

}
$GLOBALS['MT_Core'] = MT_Core::get_instance();
