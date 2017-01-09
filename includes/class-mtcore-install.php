<?php
/**
 * WordPress CleanUp & White Labeling Loader Class
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
 *  MT_Core_Clean - Minimal Themes Framework
 *
 * @author  Minimal Themes
 * @since 	1.0.0
 */
class MT_Core_Install {

	/**
	 * Active var of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var      boolean
	 */
	protected $active = false;

  /**
	 * Fire it up
	 *
	 * @since  1.0.0
	 */
	public function __construct( $method = 'activate' ) {
		if ( 'deactivate' === $method ) {
			$this->deactivate();
		} elseif ( 'activate' === $method ) {
			$this->activate();
		}
	}

	/**
	 * Activate plugin Check.
	 *
	 * @since     1.0.0
	 *
	 * @return    boolean
	 */
	public function is_active() {
		$network_active = false;
		$plugin = 'mt-framework/mt-framework.php';
		if ( is_multisite() ) {
			$plugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $plugins[ $plugin ] ) ) {
				$network_active = true;
			}
		}
		return in_array( $plugin, get_option( 'active_plugins' ) ) || $network_active;
	}

  /**
	 * Activate function.
	 *
	 * @since     1.0.0
	 *
	 * @return    void
	 */
	public function activate() {
		// create plugin temp dir
		$this->mkdir();
    // clean all transient
		$this->delete_transient();
		// set globals value
		$GLOBALS['MT_Core'] = null;
		// add default features to option
		add_option( 'mtcore_features', array(
		 'shortcode', 'widget', 'metabox', 'builder', 'options', 'object', 'cleanup'
		) );
		// flush option cache
		wp_cache_delete( 'alloptions', 'options' );
		// flush rewrite rules always
		flush_rewrite_rules();
	}
  /**
	 * Deactivate function.
	 *
	 * @since     1.0.0
	 *
	 * @return    void
	 */
	public function deactivate() {
		// delete shortcode js
		$this->clean_temp();
		// clean all transient
		$this->delete_transient();
		// remove globals value
		unset( $GLOBALS['MT_Core'] );
		// delete features option
		delete_option( 'mtcore_features' );
		// flush option cache
		wp_cache_delete( 'alloptions', 'options' );
		// flush rewrite rules always
		flush_rewrite_rules();
	}

  /**
	 * Create new dir for this plugin.
	 *
	 * @since     1.0.0
	 *
	 * @return    boolean
	 */
	public function mkdir() {

		$upload_dir = wp_upload_dir();
		$upload_path = trailingslashit( $upload_dir['basedir'] );
		if ( $upload_dir['error'] || is_dir( $upload_path.'mt-framework' ) )
			return false;

		if ( wp_mkdir_p( $upload_path.'mt-framework' ) ) {
			$sc_temp = file_get_contents( MT_PATH.'admin/js/shortcodes.js' );
			file_put_contents( $upload_path.'mt-framework/shortcodes.js', $sc_temp );
		}
		return true;
	}

	/**
	 * Remove shortcode temp for this plugin.
	 *
	 * @since     1.0.0
	 *
	 * @return    boolean
	 */
	public function clean_temp() {
		$upload_dir = wp_upload_dir();
		$upload_path = trailingslashit( $upload_dir['basedir'] );
		if ( !$upload_dir['error'] && is_dir( $upload_path.'mt-framework' ) ) {
			$sc_temp = file_get_contents( MT_PATH.'admin/js/shortcodes.js' );
			file_put_contents( $upload_path.'mt-framework/shortcodes.js', $sc_temp );
			return true;
		}
		return false;
	}

	/**
	 * Remove all transient create by plugin.
	 *
	 * @since     1.0.0
	 *
	 * @return    boolean
	 */
	public function delete_transient() {

		global $wpdb;
		$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE ('\_transient%\_MT\_%')");

		delete_transient( 'spb_part_glob' );
		delete_transient( 'spb_part_details' );
	}

} //MT_Core_Install
