<?php

class Categories_As_Folders_Admin_Meta{
	
	private static $document;
	
	private $nonce = 'caf_document_meta';
	
	public static function getDocument(){
		return self::$document;
	}
	
	// Register Custom Post Type
	static function document_post_type() {
		
		$labels = array(
			'name'                  => _x( 'Documento', 'Post Type General Name', 'categories_as_folders' ),
			'singular_name'         => _x( 'Documento', 'Post Type Singular Name', 'categories_as_folders' ),
			'menu_name'             => __( 'Documentos', 'categories_as_folders' ),
			'name_admin_bar'        => __( 'Documento', 'categories_as_folders' ),
			'archives'              => __( 'Item Archives', 'categories_as_folders' ),
			'attributes'            => __( 'Item Attributes', 'categories_as_folders' ),
			'parent_item_colon'     => __( 'Parent Item:', 'categories_as_folders' ),
			'all_items'             => __( 'Todos os documentos', 'categories_as_folders' ),
			'add_new_item'          => __( 'Novo Documento', 'categories_as_folders' ),
			'add_new'               => __( 'Novo', 'categories_as_folders' ),
			'new_item'              => __( 'New', 'categories_as_folders' ),
			'edit_item'             => __( 'Edit Documento', 'categories_as_folders' ),
			'update_item'           => __( 'Update', 'categories_as_folders' ),
			'view_item'             => __( 'Visualizar', 'categories_as_folders' ),
			'view_items'            => __( 'Visualizar Documento', 'categories_as_folders' ),
			'search_items'          => __( 'Pesquisar Documento', 'categories_as_folders' ),
			'not_found'             => __( 'Not Found', 'categories_as_folders' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'categories_as_folders' ),
			'featured_image'        => __( 'Featured Image', 'categories_as_folders' ),
			'set_featured_image'    => __( 'Set featured image', 'categories_as_folders' ),
			'remove_featured_image' => __( 'Remove featured image', 'categories_as_folders' ),
			'use_featured_image'    => __( 'Use as featured image', 'categories_as_folders' ),
			'insert_into_item'      => __( 'Insert into item', 'categories_as_folders' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'categories_as_folders' ),
			'items_list'            => __( 'Items list', 'categories_as_folders' ),
			'items_list_navigation' => __( 'Items list navigation', 'categories_as_folders' ),
			'filter_items_list'     => __( 'Filter items list', 'categories_as_folders' )
		);
		$args = array(
			'label'                 => __( 'Post Type', 'categories_as_folders' ),
			'description'           => __( 'Post Type Description', 'categories_as_folders' ),
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
	
	/**
	* Adds a metabox to the right side of the screen under the â€œPublishâ€ box
	*/
	public static function add_document_metaboxes() {
		
		global $post;
		
		try{
			//loads the meeting on editor page
			self::$document = new CAF\Document($post->ID);
			
			
		} catch(\Exception $e){
			echo '<script>console.log('.$e->getMessage().')</script>';
		}
		
		//date
		add_meta_box(
			'document_files',
			__('Document Files', 'categories_as_folders'),
			['Categories_As_Folders_Admin_Meta', 'document_files'],
			'document',
			'normal',
			'default'
		);
		
	}
	
	/**
	* Output the HTML for the metabox.
	*/
	public static function document_files() {
		global $post;
		// Nonce field to validate form request came from current site
		wp_nonce_field( plugin_basename( __FILE__ ), 'document_files_nonce' );
		
		$media_files = get_attached_media(null, $post->ID);
		
		echo '<table style="width: 100%;">
		<tr><th>'.__('File').'</th><th>'.__('Remove').'</th></tr>';
		foreach($media_files as $k => $mf){
			echo '<tr><td>
			<span class="dashicons dashicons-paperclip"></span> #'.$k.' – '.$mf->post_mime_type.': <a href="'.$mf->guid.'">'.$mf->post_title.'</a>
			</td>
			<td><input type="checkbox" name="document_files_remove[]" value="'.$mf->ID.'"></td>
			</tr>';
		}
		echo '
		<tr><th>'.__('Add').'<input type="file" id="document_files" multiple name="document_files[]" class="widefat" value=""></th></tr>
		</table>';
		
		// Output the field
		echo '';
	}

	public static function add_file($post_id, $file){

		// Make sure the file array isn't empty
		if(!empty($file['name'])) {
			
			// Setup the array of supported file types. In this case, it's just PDF.
			$supported_types = array('application/pdf', 'image/jpeg', 'image/png');
			
			// Get the file type of the upload
			$arr_file_type = wp_check_filetype(basename($file['name']));
			$uploaded_type = $arr_file_type['type'];
			
			// Check if the type is supported. If not, throw an error.
			if(in_array($uploaded_type, $supported_types)) {
				
				// Use the WordPress API to upload the file
				$upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));
				
				if(isset($upload['error']) && $upload['error'] != 0) {
					wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
				} else {
					
					// $filename should be the path to a file in the upload directory.
					$filename = $upload['file'];
					
					// The ID of the post this attachment is for.
					$parent_post_id = $post_id;
					
					// Check the type of file. We'll use this as the 'post_mime_type'.
					$filetype = wp_check_filetype( basename( $filename ), null );
					
					// Get the path to the upload directory.
					$wp_upload_dir = wp_upload_dir();
					
					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
					
					$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
					
					//	add_post_meta($id, 'document_files', $upload);
					//	update_post_meta($id, 'document_files', $upload);     
				} // end if/else
				
			} else {
				wp_die("The file type that you've uploaded is not a PDF.");
			} // end if/else
			
		} // end if
	}
	
	public static function add_files($post_id){



		if(!empty($_FILES)){
			
			$items = [];
			$idx = 0;

			$files = $_FILES['document_files'];

			//multiple file
			if(is_array($files['name'])){

				foreach($_FILES['document_files'] as $att => $info_arr){
					foreach($info_arr as $idx => $info){
						$items[$idx][$att] = $info;
					}
				}
			} else {
				$items[0] = $_FILES['document_files'];
			}
			
			foreach($items as $k => $file){
			
				self::add_file($post_id, $file);
			}
		}


	}
	
	public static function remove_file($id){
		
	}
	
	/**
	* Save post metadata when a post is saved.
	*
	* @param int $post_id The post ID.
	* @param post $post The post object.
	* @param bool $update Whether this is an existing post being updated or not.
	*/
	public static function save_custom_meta_data($id, $post = null, $update = null ) {
		/*
		* In production code, $slug should be set only once in the plugin,
		* preferably as a class property, rather than in each function that needs it.
		*/
		$post_type = get_post_type($id);
		
		// If this isn't a 'book' post, don't update it.
		if ( "document" != $post_type ) return;
		
		/* --- security verification --- */
		if(!wp_verify_nonce($_POST['document_files_nonce'], plugin_basename(__FILE__))) {
			return $id;
		} // end if

	
		if(!empty($_POST['document_files_remove'])){
			if(is_array($_POST['document_files_remove'])){
				foreach($_POST['document_files_remove'] as $doc_id){
					wp_delete_attachment(filter_var($doc_id, FILTER_SANITIZE_NUMBER_INT));
				} 
			} else {
				wp_delete_attachment(filter_var($_POST['document_files_remove'], FILTER_SANITIZE_NUMBER_INT));
			}		
		}
		
		if(!empty($_FILES)) {
			self::add_files($id); 
		}
			
		
	}
	
	public static function add_post_enctype() {
		echo ' enctype="multipart/form-data"';
	}


}


add_action('post_edit_form_tag', ['Categories_As_Folders_Admin_Meta', 'add_post_enctype']);

add_action( 'save_post', ['Categories_As_Folders_Admin_Meta', 'save_custom_meta_data']);