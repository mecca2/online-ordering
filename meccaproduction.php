<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              meccaproduction.com
 * @since             1.0.0
 * @package           Meccaproduction
 *
 * @wordpress-plugin
 * Plugin Name:       Mecca Production
 * Plugin URI:        meccaproduction.com
 * Description:       A plugin for Mecca Production customizations
 * Version:           1.0.0
 * Author:            Mecca Production
 * Author URI:        meccaproduction.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       meccaproduction
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include('meccaproduction_filters.php');
include('meccaproduction_shortcodes.php' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-meccaproduction-activator.php
 */
function activate_meccaproduction() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-meccaproduction-activator.php';
	Meccaproduction_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-meccaproduction-deactivator.php
 */
function deactivate_meccaproduction() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-meccaproduction-deactivator.php';
	Meccaproduction_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_meccaproduction' );
register_deactivation_hook( __FILE__, 'deactivate_meccaproduction' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-meccaproduction.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_meccaproduction() {

	$plugin = new Meccaproduction();
	$plugin->run();

}
run_meccaproduction();
