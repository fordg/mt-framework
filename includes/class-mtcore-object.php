<?php
/**
 * WordPress Custom Post Type and Taxonomy Loader Class
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
 *  MT_Core_Object - Minimal Themes Framework
 *
 * @author  Minimal Themes
 * @since 	1.0.0
 */
class MT_Core_Object {

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

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
	 * Get relative path to load shortcode directory.
	 *
	 * @var string
	 */
	protected $rel_path = 'includes/objects';

	/**
	 * Holds post_type and taxonomy loaded in.
	 *
	 * @var array
	 */
	public $objects =  array();

	/**
   * Fire it up
   *
   * @since  1.0.0
   */
  public function __construct() {
    $this->version = MT_VERSION;
		if ( false !== ( $opts = get_option( 'mtcore_features' ) ) && in_array( 'object', $opts ) ) {
	    $this->active = true;
	    $this->init();
		}
  }

	/**
   * Hook init.
   *
   * @since     1.0.0
   * @access   private
   */
  private function init() {
		add_action( 'after_setup_theme', array( $this, 'register_objects' ), 11 );
	}

	/**
	 * Load all custom post type and taxonomy.
	 *
	 * @since     1.0.0
	 * @access   public
	 */
	public function register_objects() {

		$rel_path = apply_filters( 'mtcore_features_object_path', $this->rel_path, self::$instance );

		//Check Child Theme Shortcodes
		if ( is_child_theme() && is_dir( MT_CHILD . $rel_path ) ) {
			$this->load_objects( MT_CHILD . $rel_path );
		}

		//Check Theme Shortcode Overrides;
		if ( is_dir( MT_THEME . $rel_path ) ) {
			$this->load_objects( MT_THEME . $rel_path );
		}

		//Load Plugin Shortcodes;
		//Fix folder path - no filter applied
		if ( is_dir( MT_INC . 'objects' ) ) {
			$this->load_objects( MT_INC . 'objects' );
		}

	}

	/**
	 * Include post file as variable for register post and taxonomy.
	 *
	 * @since     1.0.0
	 * @access   public
	 */
	public function load_objects( $directory ) {

		$directory = glob( $directory.'/*.php', GLOB_NOSORT );

    if ( !is_array( $directory ) || empty( $directory ) ) {
      return;
    }

		foreach ($directory as $key => $file) {
			// get file slug
			$name = basename( $file, '.php' );
			// register post type
			if ( false !== strpos( $name, 'post-' ) ) {
				$post_name = str_replace( 'post-', '', $name );
				if ( !array_key_exists( $post_name, $this->objects ) && file_exists( $file ) ) {
          $this->objects[ $post_name ] = array();
					$args = include_once( $file );
					register_post_type( $post_name, $args );
				}
			}
			// register taxonomy
			elseif ( false !== strpos( $file, 'taxonomy-' ) ) {
				$tax_obj = str_replace( 'taxonomy-', '', $name );
				$tax_obj = explode( '-', $tax_obj );
				if ( array_key_exists( $tax_obj[0], $this->objects ) && file_exists( $file ) ) {
          if ( ! in_array( $tax_obj[1], $this->objects[ $tax_obj[0] ] ) ) {
            $this->objects[ $tax_obj[0] ][] = $tax_obj[1];
  					$args = include_once( $file );
  					register_taxonomy( $tax_obj[1], $tax_obj[0], $args );
          }
				}
			}
		}
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
