<?php

/**
 * @link              http://minimalthemes.net/
 * @since             1.0.0
 * @package           MT_Core
 *
 * @wordpress-plugin
 * Plugin Name:       Minimal Themes Framework
 * Plugin URI:        http://minimalthemes.net/
 * Description:       Core framework for all themes by Minimal Themes. Use this plugin to take all theme features e.g. shortcode, custom widget and integration with other plugins.
 * Version:           1.0.0
 * Author:            Minimal Themes
 * Author URI:        http://minimalthemes.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mtcore
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MT_VERSION', '1.0.0' );
define( 'MT_CORE', plugin_basename( __FILE__ ) );
define( 'MT_SLUG', basename( MT_CORE, '.php' ) );
define( 'MT_DIR', trailingslashit( dirname( MT_CORE ) ) );
define( 'MT_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'MT_URI', trailingslashit( plugins_url( MT_DIR ) ) );
define( 'MT_INC', trailingslashit( MT_PATH . 'includes' ) );

define( 'MT_THEME', trailingslashit( get_template_directory() ) );
define( 'MT_CHILD', trailingslashit( get_stylesheet_directory() ) );

/**
 * Helpel function Minimal Themes Framework
 * Use this can be as static function and callable.
 */
require MT_INC . 'functions-mtcore.php';

/**
 * The core plugin class that is used to define theme fetures,
 * admin-specific hooks, and public-facing site hooks.
 */
require MT_INC . 'class-mtcore.php';

register_activation_hook( __FILE__, 'activate_mtcore' );
register_deactivation_hook( __FILE__, 'deactivate_mtcore' );
