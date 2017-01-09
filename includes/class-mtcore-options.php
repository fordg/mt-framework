<?php
/**
 * WordPress Theme Options Loader Class
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
 *  MT_Core_Options - Minimal Themes Framework
 *
 * @author  Minimal Themes
 * @since 	1.0.0
 */
class MT_Core_Options {

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Hold vendor instance.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object
	 */
	private $options;

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
   * @var      boolean
   */
  protected $active = false;

  /**
	 * Get relative path to load section options directory.
	 *
	 * @var string
	 */
	protected $rel_path = 'includes/options';

	/**
	 * Holds theme objects.
	 *
	 * @var mixed
	 */
	public $theme;

	/**
	 * Holds key name.
	 *
	 * @var mixed
	 */
	public $key = '';

	/**
	 * Holds theme options loaded in.
	 *
	 * @var mixed
	 */
	public $args = array();

	/**
	 * Holds theme options tabs.
	 *
	 * @var mixed
	 */
	public $tabs = array();

	/**
   * Fire it up
   *
   * @since  1.0.0
   */
  public function __construct() {
    $this->version = MT_VERSION;
		if ( false !== ( $opts = get_option( 'mtcore_features' ) ) && in_array( 'options', $opts ) ) {
			if ( !in_array( 'metabox', $opts ) || !defined( 'CMB2_LOADED' ) ) {
				add_admin_notice( 'error', 'features', array( 'options', 'metabox' ) );
			} else {
				require_once MT_INC.'vendor/options/class-metatabs-options.php';
		    $this->active = true;
		    $this->init();
			}
		}
  }

	/**
   * Hook init.
   *
   * @since     1.0.0
   * @access   private
   */
  private function init() {
		add_action( 'after_setup_theme', array( $this, 'load_options' ), 17 );
	}

	/**
	 * Initialize options configurations.
	 *
	 * @since     1.0.0
	 * @access   public
	 */
	public function load_options() {

		if ( ! class_exists( 'Cmb2_Metatabs_Options' ) ) {
      return;
    }

    /**
     * ---> SET VARIABLES
     * All the variable for Theme Options.
     * */
		$this->theme = wp_get_theme();
 		$this->key   = $this->theme->template.'_options';
    $key_name 	 = apply_filters( 'mtcore_features_options_key',  $this->key );

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments.
     * */
    $this->args = apply_filters( 'mtcore_features_options_args', array(
      'key'             => $key_name,
      'title'    				=> $this->theme->get('Name'),
      'jsuri'           => MT_URI.'includes/vendor/options/metatabs-options.js',
      'savetxt'         => __( 'Save Options', 'mtcore' ),
      'class'           => 'mtcore-options-wrap',
      'topmenu'         => 'themes.php',
      'cols'            => 1,
      'menuargs'        => array(
        'page_title'    => sprintf( __( '%s Theme Options', 'mtcore' ), $this->theme->get('Name') ),
        'menu_title'    => __( 'Theme Options', 'mtcore' ),
        'capability'    => 'edit_theme_options',
        'menu_slug'     => $key_name,
        'network'       => false
      ),
      'boxes'           => array(),
      'tabs'            => array()
    ), $key_name, $this->theme );

		add_action( 'cmb2_admin_init', array( $this, 'register_options' ) );
    add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 999 );
	}

	/**
	 * Load all boxes and tabs then start the instance.
	 * Each file will hold one tab to register.
	 *
	 * @since     1.0.0
	 * @access   public
	 */
	public function register_options() {

    /**
     * ---> LOAD SECTIONS
     * Load dynamically section settings from file.
     **/
		$rel_path = apply_filters( 'mtcore_features_options_path', $this->rel_path );

		//Check Child Theme Shortcodes
		if ( is_child_theme() && is_dir( MT_CHILD . $rel_path ) ) {
			$this->load_tabs( MT_CHILD . $rel_path );
		}

		//Check Theme Shortcode Overrides;
		if ( is_dir( MT_THEME . $rel_path ) ) {
			$this->load_tabs( MT_THEME . $rel_path );
		}

		//Load Plugin Shortcodes;
		//Fix folder path - no filter applied
		if ( is_dir( MT_INC . 'options' ) ) {
			$this->load_tabs( MT_INC . 'options' );
		}

		$this->args['tabs'] = array_values( $this->tabs );
 		$this->options = new Cmb2_Metatabs_Options( $this->args );
	}

	/**
	 * Function to add tabs to theme options.
	 *
	 * @since     1.0.0
	 * @access   public
	 */
	public function load_tabs( $dir ) {

		$tabs = $this->args['tabs'];
		$dir  = glob( $dir.'/section*.php', GLOB_NOSORT );

    if ( ! is_array( $dir ) || empty( $dir ) ) {
      return;
    }

		foreach ( $dir as $file ) {
			// get file slug
			$name = basename( $file, '.php' );
			$sec_name = str_replace( 'section-', '', $name );
			if ( !array_key_exists( $sec_name, $tabs ) && file_exists( $file ) ) {
				$tab_args = include_once( $file );
				if ( !empty( $tab_args ) && is_array( $tab_args ) && isset( $tab_args['id'] ) ) {
					if ( isset( $tab_args['boxes'] ) && !empty( $tab_args['boxes'] ) ) {
						$this->add_boxes( $tab_args['boxes'] );
						$tab_args['boxes'] = array_keys( $tab_args['boxes'] );
					}
					$this->tabs[ $sec_name ] = $tab_args;
				}
			}
		}
	}

  public function add_boxes( $boxes ) {

		if ( empty( $boxes ) ) return;

		$show_on = array(
  		'key'   => 'options-page',
  		'value' => array( $this->key ),
  	);

		foreach ( $boxes as $key => $box ) {
			if ( !array_key_exists( 'id', $box ) ) continue;
			if ( !array_key_exists( 'fields', $box ) ) continue;
			$cmb_args = array(
				'id'	  	=> $box['id'],
				'title' 	=> $box['title'],
				'show_on'	=> $show_on
			);
			if ( !empty( $box['desc'] ) ) $cmb_args['desc'] = $box['desc'];
			if ( isset( $box['closed'] ) ) $cmb_args['closed'] = true;
			$cmb = new_cmb2_box( $cmb_args );
			foreach ( $box['fields'] as $field ) {
				$cmb->add_field( $field );
			}
			$cmb->object_type( 'options-page' );
			$this->args['boxes'][] = $cmb;
		}

  }

	/**
	 * Add footer copyright.
	 *
	 * @since     1.0.0
	 *
	 * @return    string
	 */
	public function admin_footer_text( $text ) {
		global $current_screen;
		if ( 'appearance_page_'.$this->theme->template.'_options' === $current_screen->id ) {
			$text = sprintf(
				__( '&copy; %s %s. All Right Reserved.', 'mtcore' ),
				date('Y'),
				'<a href="http://minimalthemes.net/" target="_blank">Minimal Themes</a>'
			);
		}
		return $text;
	}

	public function get() {
		return $this->options;
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
	 * Set this feature active.
	 *
	 * @since     1.0.0
	 */
	public function is_active() {
		return $this->active;
	}

}
