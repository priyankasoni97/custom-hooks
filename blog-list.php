<?php
/**
 * The plugin main file
 *
 * @link              https://github.com/priyankasoni97/
 * @since             1.0.0
 * @package           Blog_List
 *
 * @wordpress-plugin
 * Plugin Name:       Blog List
 * Plugin URI:        https://github.com/priyankasoni97/
 * Description:       This plugin is responsible for all the custom functionalities.
 * Version:           1.0.0
 * Author:            Priyanka Soni
 * Author URI:        https://github.com/priyankasoni97/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blog-list
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CF_PLUGIN_VERSION', '1.0.0' );

// Plugin path.
if ( ! defined( 'CF_PLUGIN_PATH' ) ) {
	define( 'CF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

// Plugin URL.
if ( ! defined( 'CF_PLUGIN_URL' ) ) {
	define( 'CF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * This code runs during the plugin activation.
 * This code is documented in includes/class-cf-core-functions-activator.php
 */
function activate_core_functions() {
	require 'includes/class-cf-core-functions-activator.php';
	Cf_Core_Functions_Activator::run();
}

register_activation_hook( __FILE__, 'activate_core_functions' );

/**
 * This code runs during the plugin deactivation.
 * This code is documented in includes/class-cf-core-functions-deactivator.php
 */
function deactivate_core_functions() {
	require 'includes/class-cf-core-functions-deactivator.php';
	Cf_Core_Functions_Deactivator::run();
}

register_deactivation_hook( __FILE__, 'deactivate_core_functions' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_core_funcitons() {
	require_once 'includes/cf-core-functions.php';

	// The core plugin class that is used to define internationalization and admin-specific hooks.
	require_once 'includes/class-cf-core-functions-admin.php';
	new Cf_Core_Functions_Admin();

	// The core plugin class that is used to define internationalization and public-specific hooks.
	require_once 'includes/class-cf-core-functions-public.php';
	new Cf_Core_Functions_Public();
}

/**
 * This initiates the plugin.
 * Checks for the required plugins to be installed and active.
 */
function cf_plugins_loaded_callback() {
	run_core_funcitons();
}

add_action( 'plugins_loaded', 'cf_plugins_loaded_callback' );

/**
 * Debugger function which shall be removed in production.
 */
if ( ! function_exists( 'debug' ) ) {
	/**
	 * Debug function definition.
	 *
	 * @param string $params Holds the variable name.
	 */
	function debug( $params ) {
		echo '<pre>';
		print_r( $params ); // phpcs:ignore
		echo '</pre>';
	}
}
