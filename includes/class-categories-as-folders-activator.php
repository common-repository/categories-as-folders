<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.desenvolvedormatteus.com.br
 * @since      1.0.0
 *
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 * @author     Matteus <contato@desenvolvedormatteus.com.br>
 */
class Categories_As_Folders_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {	


		$options = ['display_folders' => 1,
		'display_documents' => 1,
		'display_media' => 1];
		
		update_option('caf_settings', serialize($options));
		
	}

	
}
