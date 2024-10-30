<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://www.desenvolvedormatteus.com.br
 * @since      1.0.0
 *
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Categories_As_Folders
 * @subpackage Categories_As_Folders/includes
 * @author     Matteus <contato@desenvolvedormatteus.com.br>
 */
class Categories_As_Folders_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		if ( !post_type_exists( 'document' ) ) {
			add_action( 'init', ['Categories_As_Folders_Loader', 'document_post_type'], 0 );
		}

	}

		// Register Custom Post Type
		static function document_post_type() {

			$labels = array(
				'name'                  => _x( 'Documento', 'Post Type General Name', 'text_domain' ),
				'singular_name'         => _x( 'Documento', 'Post Type Singular Name', 'text_domain' ),
				'menu_name'             => __( 'Documentos', 'text_domain' ),
				'name_admin_bar'        => __( 'Documento', 'text_domain' ),
				'archives'              => __( 'Item Archives', 'text_domain' ),
				'attributes'            => __( 'Item Attributes', 'text_domain' ),
				'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
				'all_items'             => __( 'Todos os documentos', 'text_domain' ),
				'add_new_item'          => __( 'Novo Documento', 'text_domain' ),
				'add_new'               => __( 'Novo', 'text_domain' ),
				'new_item'              => __( 'New', 'text_domain' ),
				'edit_item'             => __( 'Edit Documento', 'text_domain' ),
				'update_item'           => __( 'Update', 'text_domain' ),
				'view_item'             => __( 'Visualizar', 'text_domain' ),
				'view_items'            => __( 'Visualizar Documento', 'text_domain' ),
				'search_items'          => __( 'Pesquisar Documento', 'text_domain' ),
				'not_found'             => __( 'Not Found', 'text_domain' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
				'featured_image'        => __( 'Featured Image', 'text_domain' ),
				'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
				'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
				'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
				'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
				'items_list'            => __( 'Items list', 'text_domain' ),
				'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
				'filter_items_list'     => __( 'Filter items list', 'text_domain' )
			);
			$args = array(
				'label'                 => __( 'Post Type', 'text_domain' ),
				'description'           => __( 'Post Type Description', 'text_domain' ),
				'labels'                => $labels,
				'taxonomies'			=> ['category'],
				'supports'              => ['title', 'editor', 'excerpt', 'thumbnail'],
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'post',
				'menu_icon' 			=> 'dashicons-format-status' 
			);

			register_post_type( 'document', $args );
		
		}


}
