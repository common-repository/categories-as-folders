<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.desenvolvedormatteus.com.br
 * @since      1.0.0
 *
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 * @author     Matteus <contato@desenvolvedormatteus.com.br>
 */
class Categories_As_Folders_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$options = ['display_folders' => 0,
		'display_documents' => 0,
		'display_media' => 0];
		
		update_option('caf_settings', serialize($options));
	}

}
