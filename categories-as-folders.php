<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.desenvolvedormatteus.com.br
 * @since             1.0.0
 * @package           Categories_As_Folders
 *
 * @wordpress-plugin
 * Plugin Name:       Categories as Folders
 * Plugin URI:        https://www.desenvolvedormatteus.com.br/categories-as-folders
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Matteus Barbosa
 * Author URI:        https://www.desenvolvedormatteus.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       categories-as-folders
 * Domain Path:       /languages
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
define( 'CAF_VERSION', '1.0.0' );


//loads composer and dependencies
require __DIR__ . '/vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-categories-as-folders-activator.php
 */
function activate_categories_as_folders() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-categories-as-folders-activator.php';
	Categories_As_Folders_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-categories-as-folders-deactivator.php
 */
function deactivate_categories_as_folders() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-categories-as-folders-deactivator.php';
	Categories_As_Folders_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_categories_as_folders' );
register_deactivation_hook( __FILE__, 'deactivate_categories_as_folders' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-categories-as-folders.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_categories_as_folders() {

	global $categories_as_folders_plugin;
	$categories_as_folders_plugin = new Categories_As_Folders();
	$categories_as_folders_plugin->run();

}

run_categories_as_folders();
