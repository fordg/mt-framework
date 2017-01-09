<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_Simple_Page_Builder' ) ) {

	class WDS_Simple_Page_Builder {

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$this->basename       = MT_CORE;
			$this->directory_path = MT_INC . 'vendor/builder/';
			$this->directory_url  = MT_URI . 'includes/vendor/builder/';

			// Include any required files
			require_once( $this->directory_path . 'inc/class-wds-page-builder-options.php' );
			require_once( $this->directory_path . 'inc/class-wds-page-builder-admin.php' );
			require_once( $this->directory_path . 'inc/class-wds-page-builder-areas.php' );
			require_once( $this->directory_path . 'inc/class-wds-page-builder-data.php' );
			require_once( $this->directory_path . 'inc/class-wds-page-builder-functions.php' );
			require_once( $this->directory_path . 'inc/class-wds-page-builder-layouts.php' );
			require_once( $this->directory_path . 'inc/template-tags.php' );
			require_once( $this->directory_path . 'inc/deprecated-functions.php' );

			$this->plugin_classes();
			$this->hooks();
		}

		/**
		 * Attach other plugin classes to the base plugin class.
		 *
		 * @since 0.1.0
		 * @return  null
		 */
		function plugin_classes() {
			$this->admin = new WDS_Page_Builder_Admin( $this );
			$this->options = new WDS_Page_Builder_Options( $this );
			$this->functions = new WDS_Page_Builder_Functions( $this );
			$this->areas = new WDS_Page_Builder_Areas( $this );
			$this->layouts = new WDS_Page_Builder_Layouts( $this );
			$this->data = new WDS_Page_Builder_Data( $this );
		}

		/**
		 * Add hooks and filters
		 *
		 * @return null
		 */
		public function hooks() {
			add_action( 'init', array( $this, 'init' ) );
			// Run our options hooks
			$this->options->hooks();
			// Run our admin hooks
			$this->admin->hooks();
			// Run layouts hooks
			$this->layouts->hooks();
		}

		/**
		 * Init hooks
		 *
		 * @since  0.1.0
		 * @return null
		 */
		public function init() {
			do_action('spb_init');
		}

	}
}

/**
 * Public wrapper function
 *
 * @since 	1.0.0
 * @return	object
 */
if ( !function_exists('wds_page_builder') ):
function wds_page_builder() {
	global $MT_Core;
	if ( $builder = $MT_Core->get('builder') ) {
		return $builder->get();
	}
	return null;
}
endif;
