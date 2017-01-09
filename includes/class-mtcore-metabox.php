<?php
/**
 * WordPress Custom Metabox Loader Class
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
 *  MT_Core_Metabox - Minimal Themes Framework
 *
 * @author  Minimal Themes
 * @since 	1.0.0
 */
 class MT_Core_Metabox {

 	/**
 	 * Instance of this class.
 	 *
 	 * @since    1.0.0
 	 *
 	 * @var      object
 	 */
 	protected static $instance = null;

 	/**
 	 * Active var of this class.
 	 *
 	 * @since    1.0.0
 	 *
 	 * @var      object
 	 */
 	protected $active = false;

  /**
	 * Current version number
	 * @var   string
	 * @since 1.5
	 */
	private $version;

	/**
	 * Current version number
	 * @var   string
	 * @since 1.0.0
	 */
	const VERSION = '2.2.2.1';

	/**
	 * Current version hook priority.
	 * Will decrement with each release
	 *
	 * @var   int
	 * @since 2.0.0
	 */
	const PRIORITY = 9986;

  /**
	 * Holds registered metabox files.
	 *
	 * @var array
	 */
	public $metaboxes = null;

 	/**
 	 * Fire it up
 	 *
 	 * @since  1.0.0
 	 */
 	public function __construct() {
		$has_plugin = defined( 'CMB2_LOADED' );
		if ( ! $has_plugin &&
		! class_exists( 'CMB2_Bootstrap_2221', false ) &&
		false !== ( $opts = get_option( 'mtcore_features' ) ) &&
		in_array( 'metabox', $opts )
		) {
			define( 'CMB2_LOADED', self::PRIORITY );
			add_action( 'init', array( $this, 'include_cmb' ), 12 );
			add_action( 'plugins_loaded', array( $this, 'include_cmb_extra' ) );
      $this->version = self::VERSION;
			$this->active = true;
		}
		if ( $has_plugin ) {
			add_admin_notice( 'error', 'plugin', array( 'metabox', 'CMB2' ) );
			$this->instance = null;
		}
 	}

	/**
	 * A final check if CMB2 exists before kicking off our CMB2 loading.
	 * CMB2_VERSION and CMB2_DIR constants are set at this point.
	 *
	 * @since  2.0.0
	 */
	public function include_cmb() {
		if ( class_exists( 'CMB2', false ) ) {
			return;
		}

		if ( ! defined( 'CMB2_VERSION' ) ) {
			define( 'CMB2_VERSION', self::VERSION );
		}

		if ( ! defined( 'CMB2_DIR' ) ) {
			define( 'CMB2_DIR', trailingslashit( MT_INC .'vendor/metabox' ) );
		}

    $this->l10ni18n();

		// Include helper functions
		require_once CMB2_DIR . 'includes/CMB2.php';
		require_once CMB2_DIR . 'includes/helper-functions.php';

		// Now kick off the class autoloader
		spl_autoload_register( 'cmb2_autoload_classes' );

		// Kick the whole thing off
		require_once CMB2_DIR . 'bootstrap.php';
		cmb2_bootstrap();
	}

	/**
 	 * Include all extra CMB2 plugins.
 	 *
 	 * @since     1.0.0
 	 */
	public function include_cmb_extra() {

		$dir = trailingslashit( MT_INC .'vendor/metabox/includes/extra' );

		if ( ! class_exists( 'CMB2_Conditionals' ) ) {
			require_once $dir.'cmb2-conditionals.php';
			$cmb2_conditionals = new CMB2_Conditionals();
		} else {
			add_admin_notice( 'error', 'plugin', array( 'metabox', 'CMB2 Conditionals' ) );
		}

		if ( ! defined( 'MKDO_LPFC_ROOT' ) ) {
			require_once $dir.'cmb2-link-picker.php';
			$cmb2_conditionals = new CMB2_Link_Picker();
		} else {
			add_admin_notice( 'error', 'plugin', array( 'metabox', 'Link Picker for CMB2' ) );
		}
	}

  /**
   * Registers CMB2 text domain path
   *
   * @since  1.0.0
   */
  public function l10ni18n() {

    load_plugin_textdomain( 'cmb2', false, CMB2_DIR . 'languages' );

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
 	 * Check if this feature activated.
 	 *
 	 * @since     1.0.0
 	 *
 	 * @return    boolean
 	 */
 	public function is_active() {
 		return $this->active;
 	}

 }
