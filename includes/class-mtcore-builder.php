<?php
/**
 * WordPress Page Builder Loader Class
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
 *  MT_Core_Builder - Minimal Themes Framework
 *
 * @author  Minimal Themes
 * @since 	1.0.0
 */
class MT_Core_Builder {

	/**
	 * Current version number
	 * @var   string
	 * @since 1.5
	 */
	private $version = '1.6';

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
	 * Relative path to builder files part.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected $rel_path = 'includes/builders';

	/**
	 * Builder object.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	public $builders = null;

	/**
	 * Fire it up
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

    $opts = get_option( 'mtcore_features', array() );
		$has_plugin = class_exists( 'WDS_Simple_Page_Builder' );
    if ( in_array( 'metabox', $opts ) && defined( 'CMB2_LOADED' ) && !$has_plugin ) {
			define( 'PAGEBUILDER_VERSION', $this->version );
			define( 'PAGEBUILDER_VERSION_PATH', MT_INC.'vendor/builder/' );
      include_once( PAGEBUILDER_VERSION_PATH . 'index.php' );
      $this->builders = new WDS_Simple_Page_Builder;
      $this->active = true;
			$this->init();
    }
		else {
			if ( $has_plugin ) {
				add_admin_notice( 'error', 'plugin', array( 'builder', 'WDS Simple Page Builder' ) );
			}
			if ( !defined( 'CMB2_LOADED' ) ) {
				add_admin_notice( 'error', 'features', array( 'builder', 'metabox' ) );
			}
			self::$instance = null;
		}

	}

  /**
   * Hook init.
   *
   * @since     1.0.0
   * @access   private
   */
  private function init() {
    add_action( 'init', array( $this, 'register_options' ), 14 );
		add_action( 'spb_init', array( $this, 'register_areas' ), 14 );
    add_action( 'spb_init', array( $this, 'register_fields' ), 22 );
		add_action( 'spb_init', array( $this, 'locate_templates' ), 98 );
  }

  /**
   * Hook all into spb_init.
   *
   * @since     1.0.0
   * @access   public
   */
	public function locate_templates() {
		add_filter( 'spb_get_template_stack', array( $this, 'builder_dir' ) );
		add_filter( 'page_builder_get_theme_compat_dir', '__return_empty_string', 999 );
		add_filter( 'page_builder_get_plugin_compat_dir', '__return_empty_string', 999 );
		add_filter( 'wds_page_builder_fields_saved-layout', array( $this, 'saved_layout_fields' ), 1 );
	}

  /**
   * Add default page builder directory.
   *
   * @since     1.0.0
   * @access   public
   */
	public function builder_dir( $locations ) {

		// child first load all builer parts
		if ( is_child_theme() && is_dir( MT_CHILD . $this->rel_path ) )
			$locations[] = trailingslashit( MT_CHILD . $this->rel_path );

		// parent theme builder parts
		if ( is_dir( MT_THEME . $this->rel_path ) )
			$locations[] = trailingslashit( MT_THEME . $this->rel_path );

		// plugin builder parts
		if ( is_dir( MT_INC . 'builders' ) )
			$locations[] = trailingslashit( MT_INC . 'builders' );

		return $locations;
	}

	/**
   * Add default page builder areas.
   *
   * @since     1.0.0
   * @access   public
   */
  public function register_areas() {
		// Register after content area builder
		register_page_builder_area(
			'page_builder_default',
			array(
				'name'				=> esc_html__( 'After Content', 'mtcore' ),
				'description'	=> ''
			)
		);
	}

  /**
   * Set certain Page Builder options.
   *
   * @since     1.0.0
   * @access   public
   */
  public function register_options() {
    if ( current_theme_supports( 'wds-simple-page-builder' ) ) {
      return;
    }
    add_theme_support( 'wds-simple-page-builder' );

		// Register builder options
    $options = apply_filters( 'mtcore_builder_options', array(
        'hide_options'    => true,
				'use_wrap'        => 'on',
        'parts_dir'       => $this->rel_path,
				'container'				=> 'section',
				'container_class'	=> 'section-container clearfix',
				'post_types'			=> array( 'post', 'page' )
    ) );
    if ( function_exists( 'wds_register_page_builder_options' ) )
      wds_register_page_builder_options( $options );
  }

  /**
   * Set certain Page Builder fields options.
   *
   * @since     1.0.0
   * @access   public
   */
  public function register_fields() {

    $parts = $this->builders->options->get_parts();

    if ( !is_array( $parts ) || empty( $parts ) ) {
      return;
    }
    foreach ($parts as $part_key => $part_args) {
      $dir  = dirname( $part_args['path'] );
      $base = basename( $part_args['path'] );
      $opts = $dir.'/options/'.$base;
      if ( is_file( $opts ) && file_exists( $opts ) ) {
        add_filter( 'wds_page_builder_fields_'.$part_key, function( $default ) use ( $opts ) {
          $fields = include_once( $opts );
          return ( !empty( $fields ) ? array_merge( $default, $fields ) : $default );
        } );
      }
    }
  }

	/**
	 * Builder part saved layout option field.
	 *
	 * @since     1.0.0
	 * @return    mixed
	 */
	public function saved_layout_fields() {
		return array(
			array(
				'name'    => __( 'Choose Saved Layouts', 'mtcore' ),
				'id'      => 'template_saved_layout',
				'type'    => 'select',
				'default' => '',
		    'show_option_none' => true,
		    'options' => mtcore_get_saved_layouts()
			)
		);
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

  /**
	 * Get builder object.
	 *
	 * @since     1.0.0
	 *
	 * @return    object or null
	 */
	public function get() {
		return $this->builders;
	}

}
