<?php

/**
 * @link              https://wordherd.io
 * @since             1.0
 * @package           content_moover
 *
 * @wordpress-plugin
 * Plugin Name:       WordHerd Content Moover
 * Plugin URI:        https://gutenbergmoover.com
 * Description:       Migrate your WordPress content to Gutenberg.
 * Version:           1.0
 * Author:            WordHerd
 * Author URI:        https://wordherd.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       content-moover
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0 and use SemVer - https://semver.org
 */
define( 'CONTENT_MOOVER_VERSION', '1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-content-moover-activator.php
 */
function activate_content_moover() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-content-moover-activator.php';
	Content_Moover_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-content-moover-deactivator.php
 */
function deactivate_content_moover() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-content-moover-deactivator.php';
	Content_Moover_Deactivator::deactivate();
}

// register_activation_hook( __FILE__, 'activate_content_moover' );
// register_deactivation_hook( __FILE__, 'deactivate_content_moover' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-content-moover.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0
 */
function run_content_moover() {

	$plugin = new Content_Moover();
	$plugin->run();

}
run_content_moover();
