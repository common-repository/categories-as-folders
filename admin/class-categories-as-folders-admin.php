<?php

require 'class-categories-as-folders-admin-meta.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.desenvolvedormatteus.com.br
 * @since      1.0.0
 *
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/admin
 * @author     Matteus <contato@desenvolvedormatteus.com.br>
 */
class Categories_As_Folders_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Categories_As_Folders_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Categories_As_Folders_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/categories-as-folders-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Categories_As_Folders_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Categories_As_Folders_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/categories-as-folders-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function run(){
		if ( !post_type_exists( 'document' ) ) {
			//class-tryst-admin-meta.php
			add_action( 'init', ['Categories_As_Folders_Admin_Meta', 'document_post_type'], 0 );
			add_action( 'add_meta_boxes', ['Categories_As_Folders_Admin_Meta', 'add_document_metaboxes']);

	}
}

}
