<?php
class CategoriesAsFoldersSettingsPage
{
    /**
    * Holds the values to be used in the fields callbacks
    */
    private $options;
    /**
    * Start up
    */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
    /**
    * Add options page
    */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Categories as Folders', 
            'manage_options', 
            'categories-as-folders-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }
    /**
    * Options page callback
    */
    public function create_admin_page()
    {
        // Set class property
        $this->options = unserialize(get_option( 'caf_settings'));
        ?>
        <div class="wrap">
        <h1><?php _e('Basic information setup', 'categories_as_folders'); ?></h1>
        <form method="post" action="options.php">
        <?php
        // This prints out all hidden setting fields
        settings_fields( 'caf_group' );
        do_settings_sections( 'categories-as-folders-setting-admin' );
        submit_button();
        ?>
        </form>
        </div>
        <?php
    }
    /**
    * Register and add settings
    */
    public function page_init()
    {        
        register_setting(
            'caf_group', // Option group
            'caf_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );
        add_settings_section(
            'caf_default_section', // ID
            __('Categories as Folders', 'categories_as_folders'), // Title
            array( $this, 'print_section_info' ), // Callback
            'categories-as-folders-setting-admin' // Page
        );  
        add_settings_field(
            'display_folders', // ID
            __('Display folders from view', 'categories_as_folders'), // Title 
            array( $this, 'display_folders_callback' ), // Callback
            'categories-as-folders-setting-admin', // Page
            'caf_default_section' // Section           
        );   
        add_settings_field(
            'display_documents', // ID
            __('Display documents from view', 'categories_as_folders'), // Title 
            array( $this, 'display_documents_callback' ), // Callback
            'categories-as-folders-setting-admin', // Page
            'caf_default_section' // Section           
        );   
        add_settings_field(
            'display_media', // ID
            __('Display media from view', 'categories_as_folders'), // Title 
            array( $this, 'display_media_callback' ), // Callback
            'categories-as-folders-setting-admin', // Page
            'caf_default_section' // Section           
        );   
    }
    /**
    * Sanitize each setting field as needed
    *
    * @param array $input Contains all settings fields as array keys
    */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['display_folders'] ) )
        $new_input['display_folders'] = sanitize_text_field( $input['display_folders'] );
        if( isset( $input['display_documents'] ) )
        $new_input['display_documents'] = sanitize_text_field( $input['display_documents'] );
        if( isset( $input['display_media'] ) )
        $new_input['display_media'] = sanitize_text_field( $input['display_media'] );
        return $new_input;
    }
    /** 
    * Print the Section text
    */
    public function print_section_info()
    {
    }
    /* Get the settings option array and print one of its values
    */
    public function display_folders_callback()
    {
        $atts_display = null;
        $atts_ignore = null;
        if(isset( $this->options['display_folders'] ) && esc_attr( $this->options['display_folders']) == 1){
            $atts_display = 'checked';
        } else {
            $atts_ignore = 'checked';
        }
        echo '<label><input type="radio" id="display_folders_display" name="caf_settings[display_folders]" value="1" '.$atts_display.'/>'.__('Display', 'categories_as_folders').'</label>
        <label><input type="radio" id="display_folders_ignore" name="caf_settings[display_folders]" value="0" '.$atts_ignore.' />'.__('Ignore', 'categories_as_folders').'</label>';
    }
    /* Get the settings option array and print one of its values
    */
    public function display_documents_callback()
    {
        $atts_display = null;
        $atts_ignore = null;
        if(isset( $this->options['display_documents'] ) && esc_attr( $this->options['display_documents']) == 1){
            $atts_display = 'checked';
        } else {
            $atts_ignore = 'checked';
        }
        echo '<label><input type="radio" id="display_documents_display" name="caf_settings[display_documents]" value="1" '.$atts_display.'/>'.__('Display', 'categories_as_folders').'</label>
        <label><input type="radio" id="display_documents_ignore" name="caf_settings[display_documents]" value="0" '.$atts_ignore.' />'.__('Ignore', 'categories_as_folders').'</label>';
    }
    /* Get the settings option array and print one of its values
    */
    public function display_media_callback()
    {
        $atts_display = null;
        $atts_ignore = null;
        if(isset( $this->options['display_media'] ) && esc_attr( $this->options['display_media']) == 1){
            $atts_display = 'checked';
        } else {
            $atts_ignore = 'checked';
        }
        echo '<label><input type="radio" id="display_media_display" name="caf_settings[display_media]" value="1" '.$atts_display.'/>'.__('Display', 'categories_as_folders').'</label>
        <label><input type="radio" id="display_media_ignore" name="caf_settings[display_media]" value="0" '.$atts_ignore.' />'.__('Ignore', 'categories_as_folders').'</label>';
    }
}
if( is_admin() )
$caf_settings_page = new CategoriesAsFoldersSettingsPage();