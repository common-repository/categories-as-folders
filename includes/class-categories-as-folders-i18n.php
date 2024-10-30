<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.desenvolvedormatteus.com.br
 * @since      1.0.0
 *
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 * @author     Matteus <contato@desenvolvedormatteus.com.br>
 */
class Categories_As_Folders_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'categories-as-folders',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
