<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://www.desenvolvedormatteus.com.br
* @since      1.0.0
*
* @package    Categories_As_Folders
* @subpackage Categories_As_Folders/public
*/

/**
* The public-facing functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the public-facing stylesheet and JavaScript.
*
* @package    Categories_As_Folders
* @subpackage Categories_As_Folders/public
* @author     Matteus <contato@desenvolvedormatteus.com.br>
*/
class Categories_As_Folders_Public {
	
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
	* @param      string    $plugin_name       The name of the plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}
	
	/**
	* Register the stylesheets for the public-facing side of the site.
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
		
		wp_enqueue_style( $this->plugin_name.'-bootstrap-grid', plugin_dir_url( __FILE__ ) . 'css/bootstrap-grid.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/categories-as-folders-public.css', array(), $this->version, 'all' );
		
	}
	
	/**
	* Register the JavaScript for the public-facing side of the site.
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
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/categories-as-folders-public.js', array( 'jquery' ), $this->version, false );
		
	}

	public static function process_query($query){
		
		if(isset($query['document_id'])){		
			return Categories_As_Folders_Public::shortcode_document(['document_id' => $query['document_id']]);
		} else {
			return Categories_As_Folders_Public::shortcode_folder(['category_id' => $query['category_id'], 'group_by' => $query['group_by']]);
		}
	}

	public static function shortcode_main($atts = []) {

		if(empty($atts)){
			$atts = [];
		}

		$query = shortcode_atts(
			['document_id' => null, 'category_id' => null, 'form' => null, 'group_by' => null],
			array_merge($atts, $_GET));
	

		return Categories_As_Folders_Public::process_query($query);
	}
	
	public static function shortcode_document($atts = []) {

		$document_id = $atts['document_id'];

		$post = get_post($document_id);
		setup_postdata( $post ); 

		ob_start();
		$document = new CAF\Document($document_id);
		require plugin_dir_path( __FILE__ ).'templates/breadcrumb.php'; 
		echo $document->view();
		?> 
		
		<?php
		return ob_get_clean();
	}
	
	public static function shortcode_folder($atts = []) {

		global $categories_as_folders_plugin;
		
		$category_id = !empty($atts) ? $atts['category_id'] : $_GET['category_id'];
		
		ob_start();
		$domain_class = "\CAF\\Domain\\Folder";
		if(class_exists($domain_class)){
			$folder = new $domain_class($category_id);
		} else {
			$folder = new CAF\Folder($category_id);
		}			
		
		require plugin_dir_path( __FILE__ ).'templates/breadcrumb.php';
		
		echo $folder->view(!empty($atts['group_by']) ? $atts['group_by'] : null);

		?> 
		
		<?php
		return ob_get_clean();
	}	
	
	public function shortcodes_load(){
		
		add_shortcode( 'catsfolders', ['Categories_As_Folders_Public', 'shortcode_main'] );
		add_shortcode( 'catsfolders_folder', ['Categories_As_Folders_Public', 'shortcode_folder'] );
		add_shortcode( 'catsfolders_document', ['Categories_As_Folders_Public', 'shortcode_document'] );
		
	}
	
	public static function caf_register_query_vars( $vars ) {
		$vars[] = 'category_id';
		$vars[] = 'document_id';
		$vars[] = 'group_by';
		return $vars;
	}
	
}

add_filter( 'query_vars', ['Categories_As_Folders_Public', 'caf_register_query_vars'] );
