<?php

use appforge\coreex\includes\models\WPCore;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.app-forge.net
 * @since      1.0.0
 *
 * @package    Coreex
 * @subpackage Coreex/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Coreex
 * @subpackage Coreex/admin
 * @author     App-Forge <App-forge>
 */
class Coreex_Admin {

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
		 * defined in Coreex_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Coreex_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/coreex-admin.css', array(), $this->version, 'all' );
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
		 * defined in Coreex_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Coreex_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/coreex-admin.js', array( 'jquery' ), $this->version, false );
    }

    public function enqueue_styles_test()
    {
        global $enqueued_styles;
        var_dump(json_encode($enqueued_styles));
        // var_dump($enqueued_styles);
    }
    
    public function register_route()
    {
        //Code Generator
        add_rewrite_endpoint( 'coge', EP_PERMALINK );
        add_rewrite_endpoint( 'coge/model', EP_PERMALINK );
    }

    public function template_redirect()
    {
        if( get_query_var( 'coge', false ) !== false ) 
		{
			//Check theme directory first
			$newTemplate = locate_template( array( 'coreex-admin-index.php' ) ); 
			if( '' != $newTemplate )
				return $newTemplate;

			//Check plugin directory next
			$newTemplate = plugin_dir_path( __FILE__ ) . 'partials/coreex-admin-index.php';
			if( file_exists( $newTemplate ) )
				return $newTemplate;
        }
        else if( get_query_var( 'coge/model', false ) !== false ) 
		{
            
            
			//Check theme directory first
			$newTemplate = locate_template( array( 'coreex-admin-model.php' ) );
			if( '' != $newTemplate )
				return $newTemplate;

			//Check plugin directory next
			$newTemplate = plugin_dir_path( __FILE__ ) . 'partials/coreex-admin-model.php'; 
			if( file_exists( $newTemplate ) )
				return $newTemplate;
		} 
    }

    public function rest_api_init()
    {
        register_rest_route( 'coge/v2', '/dbtable/(?<dbtable>\w+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'rest_api_table'),

          ) );
    }

    /**
     * @var WP_REST_Request $request
     */
    public function rest_api_table($request)
    {
        $param = $request->get_params();
        //var_dump(explode('=',$param['dbtable']));
        //return json_encode($request->get_params()['dbtables']);

        //WPCore::$app->db->
        $sql = "show tables;";
        $results = WPCore::$app->db->wpdb->get_results($sql); 
        $items = [];
        $searchStr = $param['dbtable'];
        foreach($results as $result)
        {
            if(strpos($result->Tables_in_wp_frieg, $searchStr) !== false)
                $items[] = $result->Tables_in_wp_frieg;
        }
        
        return $items;
    }

    /**
     * 
     */
    public function plugin_action_links( $links )
    {
        $links['coge'] = '<a href="/?coge/model" target="_blank">'.__( 'Gode Generator', 'coreex').'</a>';

        return $links;
    }

    /**
     * 
     */
    public function plugin_row_meta($plugin_meta, $plugin_file)
    {
        if($plugin_file == COREEX_PLUGIN_BASE)
        {
            $plugin_meta['docs_faq'] = '<a href="https://www.app-forge.net/wordpress/docs_faq" target="_blank">'.__( 'Docs & FAQ', 'coreex').'</a>';
        }

        return $plugin_meta;
    }

}
