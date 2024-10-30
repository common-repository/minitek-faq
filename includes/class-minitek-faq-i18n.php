<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/includes
 */

class MFaq_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    	1.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'minitek-faq',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
