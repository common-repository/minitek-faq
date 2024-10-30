<?php

/**
 * @package           	Minitek-Faq
 * @since								1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:       	Minitek FAQ
 * Plugin URI:        	https://www.minitek.gr/wordpress/plugins/minitek-faq
 * Description:       	Allow users to quickly find answers to the most common questions.
 * Version:           	1.0.2
 * Author:            	Minitek.gr
 * Author URI:        	https://www.minitek.gr/
 * License:           	GPL3
 * License URI:       	https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       	minitek-faq
 * Domain Path:       	/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MFAQ__ADMIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ).'admin/' );
define( 'MFAQ__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_mfaq() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-minitek-faq-activator.php';
	MFaq_Activator::activate();

	// Set default capabilities for administrators
	add_action( 'admin_init', MFaq_Activator::mf_add_default_caps() );

}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_mfaq() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-minitek-faq-deactivator.php';
	MFaq_Deactivator::deactivate();

}

register_activation_hook( __FILE__, 'activate_mfaq' );
register_deactivation_hook( __FILE__, 'deactivate_mfaq' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-minitek-faq.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_mfaq() {

	$plugin = new MFaq();
	$plugin->run();

}

run_mfaq();
