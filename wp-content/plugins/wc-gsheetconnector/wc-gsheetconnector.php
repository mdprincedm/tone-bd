<?php
/**
 * Plugin Name: WooCommerce GSheetConnector
 * Plugin URI: https://wordpress.org/plugins/wc-gsheetconnector/
 * Description: Send your WooCommerce data to your Google Sheets spreadsheet.
 * Author: GSheetConnector
 * Author URI: https://www.gsheetconnector.com/
 * Version: 1.3.13
 * Text Domain: wc-gsheetconnector
 * Domain Path:  /languages
 * WooCommerce requires at least: 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(wc_gsheetconnector_Init::gscwoo_is_pugin_active('wc_gsheetconnector_Init_Pro')){
    return;
}


/*freemius*/
if (function_exists('is_plugin_active') && is_plugin_active('wc-gsheetconnector/wc-gsheetconnector.php')) {
if ( ! function_exists( 'gs_woofree' ) ) {
    // Create a helper function for easy SDK access.
    function gs_woofree() {
        global $gs_woofree;

        if ( ! isset( $gs_woofree ) ) {
            // Activate multisite network integration.
            if ( ! defined( 'WP_FS__PRODUCT_9480_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_9480_MULTISITE', true );
            }

            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $gs_woofree = fs_dynamic_init( array(
                'id'                  => '9480',
                'slug'                => 'wc-gsheetconnector',
                'type'                => 'plugin',
                'public_key'          => 'pk_487f703ba4a974974c9d344111193',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'wc-gsheetconnector-config',
                    'first-path'     => ( ! is_multisite() ? 'admin.php?page=wc-gsheetconnector-config' : 'plugins.php' ),
                    'account'        => false,
                ),
            ) );
        }

        return $gs_woofree;
    }

    // Init Freemius.
    gs_woofree();
    // Signal that SDK was initiated.
    do_action( 'gs_woofree_loaded' );
}
}
/*freemius*/


// Declare some global constants
define( 'WC_GSHEETCONNECTOR_VERSION', '1.3.13' );
define( 'WC_GSHEETCONNECTOR_DB_VERSION', '1.3.13' );
define( 'WC_GSHEETCONNECTOR_ROOT', dirname( __FILE__ ) );
define( 'WC_GSHEETCONNECTOR_URL', plugins_url( '/', __FILE__ ) );
define( 'WC_GSHEETCONNECTOR_BASE_FILE', basename( dirname( __FILE__ ) ) . '/wc-gsheetconnector.php' );
define( 'WC_GSHEETCONNECTOR_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'WC_GSHEETCONNECTOR_PATH', plugin_dir_path( __FILE__ ) ); //use for include files to other files
define( 'WC_GSHEETCONNECTOR_CURRENT_THEME', get_stylesheet_directory() );

load_plugin_textdomain( 'wc-gsheetconnector', false, basename( dirname( __FILE__ ) ) . '/languages' );

/*
 * include utility classes
 */
if ( ! class_exists( 'wc_gsheetconnector_utility' ) ) {
    include(WC_GSHEETCONNECTOR_ROOT . '/includes/class-wc-gsheetconnector-utility.php' );
}
//Include Library Files
require_once WC_GSHEETCONNECTOR_ROOT . '/lib/vendor/autoload.php';

include_once( WC_GSHEETCONNECTOR_ROOT . '/lib/google-sheets.php');

if ( ! class_exists( 'wc_gsheetconnector_Service' ) ) {
    include_once( WC_GSHEETCONNECTOR_PATH . 'includes/class-wc-gsheetconnector-services.php' );
}

class wc_gsheetconnector_Init {

    /**
     *  Set things up.
     *  @since 1.0
     */
    public function __construct() {

	//run on activation of plugin
	register_activation_hook( __FILE__, array( $this, 'wc_gsheetconnector_activate' ) );

	//run on deactivation of plugin
	register_deactivation_hook( __FILE__, array( $this, 'wc_gsheetconnector_deactivate' ) );

	//run on uninstall
	register_uninstall_hook( __FILE__, array('wc_gsheetconnector_Init', 'gs_wocommerce_free_uninstall' ) );

    // clear debug logs method using ajax for system status tab
    add_action('wp_ajax_wc_clear_debug_logs', array($this, 'wc_clear_debug_logs'));

	// validate is woocommerce plugin exist
	add_action('admin_init', array( $this, 'validate_parent_plugin_exists'));

	// register admin menu under "Contact" > "Integration"
	add_action('admin_menu', array( $this, 'register_gs_menu_pages' ), 70 );

	// load the js and css files
	add_action('init', array( $this, 'load_css_and_js_files' ) );

	// load the classes
	add_action('init', array( $this, 'load_all_classes' ) );
    }


     public static function gs_wocommerce_free_uninstall(){
        // Not like register_uninstall_hook(), you do NOT have to use a static function.
        // gs_woofree()->add_action('after_uninstall', 'gs_woofree_uninstall_cleanup');
        if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
            exit();
        }

        delete_site_option('WC_GS_info');
    }

    /**
     * Do things on plugin activation
     * @since 1.0
     */
    public function wc_gsheetconnector_activate( $network_wide ) {
	global $wpdb;
	$this->run_on_activation();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	    // check if it is a network activation - if so, run the activation function for each blog id
	    if ( $network_wide ) {
		// Get all blog ids
		$blogids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->base_prefix}blogs" );
		foreach ( $blogids as $blog_id ) {
		    switch_to_blog( $blog_id );
		    $this->run_for_site();
		    restore_current_blog();
		}
		return;
	    }
	}

	// for non-network sites only
	$this->run_for_site();
    }

     /**
    * Called on activation.
    * Creates the site_options (required for all the sites in a multi-site setup)
    * If the current version doesn't match the new version, runs the upgrade
    * @since 1.0
    */
   private function run_on_activation() {
       try{
          $plugin_options = get_site_option('WC_GS_info');
          if (false === $plugin_options) {
             $Wc_GS_info = array(
                'version' => WC_GSHEETCONNECTOR_VERSION,
                'db_version' => WC_GSHEETCONNECTOR_DB_VERSION
             );
             update_site_option('WC_GS_info', $Wc_GS_info);
          } else if (WC_GSHEETCONNECTOR_DB_VERSION != $plugin_options['version']) {
             $this->run_on_upgrade();
          }
      //echo "activate";
      //exit;
        } catch (Exception $e) {
         wc_gsheetconnector_utility::gs_debug_log("Something Wrong : - " . $e->getMessage());
      }
   }

   /**
    * called on upgrade. 
    * checks the current version and applies the necessary upgrades from that version onwards
    * @since 1.0
    */
   public function run_on_upgrade() {
      $plugin_options = get_site_option('WC_GS_info');

      // update the version value
      $google_sheet_info = array(
         'version' => WC_GSHEETCONNECTOR_ROOT,
         'db_version' => WC_GSHEETCONNECTOR_DB_VERSION
      );
      // check if debug log file exists or not
      $wclogFilePathToDelete = WC_GSHEETCONNECTOR_PATH . "logs/log.txt";
        // Check if the log file exists before attempting to delete
        if (file_exists($wclogFilePathToDelete)) {
            unlink($wclogFilePathToDelete);
        }
      update_site_option('WC_GS_info', $google_sheet_info);
   }

    /**
    * AJAX function - clear log file for system status tab
    * @since 2.1
    */
    public function wc_clear_debug_logs() {
        // nonce check
        check_ajax_referer('gs-ajax-nonce', 'security');
        $handle = fopen(WP_CONTENT_DIR . '/debug.log', 'w');
        fclose($handle);
        wp_send_json_success();
    }


    /**
     * deactivate the plugin
     * @since 1.0
     */
    public function wc_gsheetconnector_deactivate( $network_wide ) {
	
    }

    /**
     *  Runs on plugin uninstall.
     *  a static class method or function can be used in an uninstall hook
     *
     *  @since 1.0
     */
    public static function gs_connector_free_uninstall() {
	global $wpdb;
	wc_gsheetconnector_Init::run_on_uninstall();
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	    //Get all blog ids; foreach of them call the uninstall procedure
	    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->base_prefix}blogs" );

	    //Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
	    foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		wc_gsheetconnector_Init::delete_for_site();
		restore_current_blog();
	    }
	    return;
	}
	wc_gsheetconnector_Init::delete_for_site();
    }

    /**
     * Called on uninstall - deletes site_options
     *
     * @since 1.5
     */
    private static function run_on_uninstall() {
	if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	    exit();

	delete_site_option( 'google_sheet_info' );
    }

    /**
     * Called on uninstall - deletes site specific options
     *
     * @since 1.0
     */
    private static function delete_for_site() {

	delete_option( 'gs_woo_access_code' );
	delete_option( 'gs_woo_verify' );
	delete_option( 'gs_woo_token' );
	delete_option( 'gs_woo_feeds' );
	delete_option( 'gs_woo_sheetId' );
	delete_post_meta_by_key( 'gs_woo_settings' );
    }

    /**
     * Validate parent Plugin WooCommerce exist and activated
     * @access public
     * @since 1.0
     */
    public function validate_parent_plugin_exists() {

	$plugin = plugin_basename( __FILE__ );

	if ((!is_plugin_active('woocommerce/woocommerce.php' )) || (!file_exists(plugin_dir_path( __DIR__ ) . 'woocommerce/woocommerce.php' ) ) ) {
        // exit('vcnvc');
	    add_action('admin_notices', array($this, 'wc_gsheetconnector_missing_notice'),10,2);
	    add_action('network_admin_notices', array($this, 'wc_gsheetconnector_missing_notice'),10,3);
	    deactivate_plugins($plugin);
	    if (isset($_GET['activate'])) {
		// Do not sanitize it because we are destroying the variables from URL
		 unset($_GET['activate']);
	    }
        // Redirect to the plugins page
        // wp_redirect(admin_url('plugins.php'));
        // exit; // Ensure that WordPress redirects immediately
	}
    }

    /**
     * If WooCommerce plugin is not installed or activated then throw the error
     *
     * @access public
     * @return mixed error_message, an array containing the error message
     *
     * @since 1.0 initial version
     */
    public function wc_gsheetconnector_missing_notice() {
	$plugin_error = wc_gsheetconnector_utility::instance()->admin_notice(array(
	    'type'		 => 'error',
	    'message'	 => __( 'GSheetConnector WooCommerce Add-on requires WooCommerce plugin to be installed and activated.', 'wc-gsheetconnector' )
	) );
		
		echo $plugin_error;
    }

    /**
     * Called on activation.
     * Creates the options and DB (required by per site)
     * @since 1.0
     */
    private function run_for_site() {
    	if ( ! get_option( 'gs_woo_access_code' ) ) {
    	    update_option( 'gs_woo_access_code', '' );
    	}
    	if ( ! get_option( 'gs_woo_verify' ) ) {
    	    update_option( 'gs_woo_verify', 'invalid' );
    	}
    	if ( ! get_option( 'gs_woo_token' ) ) {
    	    update_option( 'gs_woo_token', '' );
    	}
    	if ( ! get_option( 'gs_woo_feeds' ) ) {
    	    update_option( 'gs_woo_feeds', '' );
    	}
    	if ( ! get_option( 'gs_woo_sheetId' ) ) {
    	    update_option( 'gs_woo_sheetId', '' );
    	}
    	if ( ! get_option( 'gs_woo_settings' ) ) {
    	    update_option( 'gs_woo_settings', '' );
    	}
    	if ( ! get_option( 'gs_woo_checkbox_settings' ) ) {
    	    update_option( 'gs_woo_checkbox_settings', array() );
        }   
        if (!get_option('gs_woo_tab_roles_setting')) {
           update_option("gs_woo_tab_roles_setting", array());
	    }
    }

    public function load_css_and_js_files() {
	add_action( 'admin_print_styles', array( $this, 'add_css_files' ) );
	add_action( 'admin_print_scripts', array( $this, 'add_js_files' ) );
    }

    /**
     * enqueue CSS files
     * @since 1.0
     */
    public function add_css_files() {
	if ( is_admin() && ( isset( $_GET[ 'page' ] ) && ( $_GET[ 'page' ] == 'wc-gsheetconnector-config' ) ) ) {
	    wp_enqueue_style( 'gs-woocommerce-connector-css', WC_GSHEETCONNECTOR_URL . 'assets/css/gs-woocommerce-connector.css', WC_GSHEETCONNECTOR_VERSION, true );
	}
    }

    /**
     * enqueue JS files
     * @since 1.0
     */
    public function add_js_files() {
	if ( is_admin() && ( isset( $_GET[ 'page' ] ) && ( $_GET[ 'page' ] == 'wc-gsheetconnector-config' ) ) ) {
	    wp_enqueue_script( 'gs-connector-js', WC_GSHEETCONNECTOR_URL . 'assets/js/gs-connector.js', WC_GSHEETCONNECTOR_VERSION, true );
	}

	// if ( is_admin() ) {
	//     wp_enqueue_script( 'gs-connector-notice-css', WC_GSHEETCONNECTOR_URL . 'assets/js/gs-connector-notice.js', WC_GSHEETCONNECTOR_VERSION, true );
	// }
    }

    /**
     * Create/Register menu items for the plugin.
     * @since 1.0
     */
    public function register_gs_menu_pages() {
        $current_role = wc_gsheetconnector_utility::instance()->get_current_user_role();
        $gs_woo_roles = get_option('gs_woo_page_roles_setting');

        if($current_role === "administrator" || is_super_admin()) {
        // if (( is_array($gs_woo_roles) && array_key_exists($current_role, $gs_woo_roles) ) || $current_role === "administrator" || is_super_admin()) {
    	add_submenu_page( 'woocommerce', 'Google Sheets', 'Google Sheets', 'manage_options', 'wc-gsheetconnector-config', array( $this, 'google_sheet_configuration' ) );
        }

    }

    /**
     * Google Sheets page action.
     * This method is called when the menu item "Google Sheets" is clicked.
     * @since 1.0
     */
    public function google_sheet_configuration() {
	include( WC_GSHEETCONNECTOR_PATH . "includes/pages/google-sheet-settings.php" );
    }

    /**
     * Load all the classes - as part of init action hook
     * @since 1.0
     */
    public function load_all_classes() {
		if ( ! class_exists( 'GS_Processes' ) ) {
		    include( WC_GSHEETCONNECTOR_PATH . 'includes/class-wc-gsheetconnector-processes.php' );
		}
        if ( ! class_exists( 'wc_gsheetconnector_role_settings_free' ) ) {
            include( WC_GSHEETCONNECTOR_PATH . 'includes/class-wc-gsheetconnector-role-settings-free.php' );
        }
    }

    /**
    * Add custom link for the plugin beside activate/deactivate links
    * @param array $links Array of links to display below our plugin listing.
    * @return array Amended array of links.    * 
    * @since 1.5
    */
   public function wc_gsheet_setting_link($links) {
      // We shouldn't encourage editing our plugin directly.
      unset($links['edit']);

      // Add our custom links to the returned array value.
      return array_merge(array(
          '<a href="' . admin_url('admin.php?page=wc-gsheetconnector-config') . '">' . __('Settings', 'wc-gsheetconnector') . '</a>'
              ), $links);
   }

   /**
    * Add function to check plugins is Activate or not
    * @param string $class of plugins main class .
    * @return true/false    * 
    * @since 2.0.2
    */
   public static function gscwoo_is_pugin_active($class) {
        if ( class_exists( $class ) ) {
            return true;
        }
        return false;
    }

    /**
    * Build System Information String
    * @global object $wpdb
    * @return string
    * @since 1.2
    */
    public function get_wcfree_system_info() {

        global $wpdb;

        // Get WordPress version
        $wp_version = get_bloginfo('version');

        // Get theme info
        $theme_data = wp_get_theme();
        $theme_name_version = $theme_data->get('Name') . ' ' . $theme_data->get('Version');
        $parent_theme = $theme_data->get('Template');

        if (!empty($parent_theme)) {
            $parent_theme_data = wp_get_theme($parent_theme);
            $parent_theme_name_version = $parent_theme_data->get('Name') . ' ' . $parent_theme_data->get('Version');
        } else {
            $parent_theme_name_version = 'N/A';
        }

        
        // Check plugin version and subscription plan
        $plugin_version = defined('WC_GSHEETCONNECTOR_VERSION') ? WC_GSHEETCONNECTOR_VERSION : 'N/A';
        $subscription_plan = 'FREE';

        // Check Google Account Authentication
        // $api_token = get_option('gs_token');
        // $google_sheet = new CF7GSC_googlesheet_PRO();
        // $email_account = $google_sheet->gsheet_print_google_account_email();

        $api_token_auto = get_option('gs_woo_token');

        if (!empty($api_token_auto)) {
            // The user is authenticated through the auto method
            $google_sheet_auto = new GSCWOO_googlesheet();
            $email_account_auto = $google_sheet_auto->gsheet_print_google_account_email();
            $connected_email = !empty($email_account_auto) ? esc_html($email_account_auto) : 'Not Auth';
        } else {
            // Auto authentication is the only method available
            $connected_email = 'Not Auth';
        }


        // $google_sheet = new CF7GSC_googlesheet_PRO();
        // $email_account = $google_sheet->gsheet_print_google_account_email_manual(); 
        //$api_status = empty($api_token) ? 'Not Authenticated' : 'Authenticated';

        // Check Google Permission
        $gs_verify_status = get_option('gs_woo_verify');
        $search_permission = ($gs_verify_status === 'valid') ? 'Given' : 'Not Given';

    
        // Create the system info HTML
        $system_info = '<div class="system-statuswc">';
        $system_info .= '<h4><button id="show-info-button" class="info-button">GSheetConnector<span class="dashicons dashicons-arrow-down"></span></h4>';
        $system_info .= '<div id="info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>GSheetConnector</h3>';
        $system_info .= '<table>';
        $system_info .= '<tr><td>Plugin Version</td><td>' . esc_html($plugin_version) . '</td></tr>';
        $system_info .= '<tr><td>Plugin Subscription Plan</td><td>' . esc_html($subscription_plan) . '</td></tr>';
        $system_info .= '<tr><td>Connected Email Account</td><td>' . $connected_email . '</td></tr>';
        $system_info .= '<tr><td>Google Drive Permission</td><td>' . esc_html($search_permission) . '</td></tr>';
        $system_info .= '<tr><td>Google Sheet Permission</td><td>' . esc_html($search_permission) . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';
         // Add WordPress info
        // Create a button for WordPress info
        $system_info .= '<h2><button id="show-wordpress-info-button" class="info-button">WordPress Info<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="wordpress-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>WordPress Info</h3>';
        $system_info .= '<table>';
        $system_info .= '<tr><td>Version</td><td>' . get_bloginfo('version') . '</td></tr>';
        $system_info .= '<tr><td>Site Language</td><td>' . get_bloginfo('language') . '</td></tr>';
        $system_info .= '<tr><td>Debug Mode</td><td>' . (WP_DEBUG ? 'Enabled' : 'Disabled') . '</td></tr>';
        $system_info .= '<tr><td>Home URL</td><td>' . get_home_url() . '</td></tr>';
        $system_info .= '<tr><td>Site URL</td><td>' . get_site_url() . '</td></tr>';
        $system_info .= '<tr><td>Permalink structure</td><td>' . get_option('permalink_structure') . '</td></tr>';
        $system_info .= '<tr><td>Is this site using HTTPS?</td><td>' . (is_ssl() ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Is this a multisite?</td><td>' . (is_multisite() ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Can anyone register on this site?</td><td>' . (get_option('users_can_register') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Is this site discouraging search engines?</td><td>' . (get_option('blog_public') ? 'No' : 'Yes') . '</td></tr>';
        $system_info .= '<tr><td>Default comment status</td><td>' . get_option('default_comment_status') . '</td></tr>';

        $server_ip = $_SERVER['REMOTE_ADDR'];
        if ($server_ip == '127.0.0.1' || $server_ip == '::1') {
            $environment_type = 'localhost';
        } else {
            $environment_type = 'production';
        }
        $system_info .= '<tr><td>Environment type</td><td>' . esc_html($environment_type) . '</td></tr>';

        $user_count = count_users();
        $total_users = $user_count['total_users'];
        $system_info .= '<tr><td>User Count</td><td>' . esc_html($total_users) . '</td></tr>';

        $system_info .= '<tr><td>Communication with WordPress.org</td><td>' . (get_option('blog_publicize') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // info about active theme
        $active_theme = wp_get_theme();

        $system_info .= '<h2><button id="show-active-info-button" class="info-button">Active Theme<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="active-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Active Theme</h3>';
        $system_info .= '<table>';
        $system_info .= '<tr><td>Name</td><td>' . $active_theme->get('Name') .'</td></tr>';
        $system_info .= '<tr><td>Version</td><td>' . $active_theme->get('Version') .'</td></tr>';
        $system_info .= '<tr><td>Author</td><td>' . $active_theme->get('Author') .'</td></tr>';
        $system_info .= '<tr><td>Author website</td><td>' . $active_theme->get('AuthorURI') .'</td></tr>';
        $system_info .= '<tr><td>Theme directory location</td><td>' . $active_theme->get_template_directory() .'</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // Get a list of other plugins you want to check compatibility with
        $other_plugins = array(
            'plugin-folder/plugin-file.php', // Replace with the actual plugin slug
            // Add more plugins as needed
        );

        // Network Active Plugins
        if (is_multisite()) {
           $network_active_plugins = get_site_option('active_sitewide_plugins', array());
           if (!empty($network_active_plugins)) {
               $system_info .= '<h2><button id="show-netplug-info-button" class="info-button">Network Active plugins<span class="dashicons dashicons-arrow-down"></span></h2>';
               $system_info .= '<div id="netplug-info-container" class="info-content" style="display:none;">';
               $system_info .= '<h3>Network Active plugins</h3>';
               $system_info .= '<table>';
               foreach ($network_active_plugins as $plugin => $plugin_data) {
                   $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
                   $system_info .= '<tr><td>' . $plugin_data['Name'] . '</td><td>' . $plugin_data['Version'] . '</td></tr>';
               }
               // Add more network active plugin statuses here...
                $system_info .= '</table>';
                $system_info .= '</div>';
           }
        }
        // Active plugins
        $system_info .= '<h2><button id="show-acplug-info-button" class="info-button">Active plugins<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="acplug-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Active plugins</h3>';
        $system_info .= '<table>';

        // Retrieve all active plugins data
        $active_plugins_data = array();
        $active_plugins = get_option('active_plugins', array());
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $active_plugins_data[$plugin] = array(
                'name'    => $plugin_data['Name'],
                'version' => $plugin_data['Version'],
                'count'   => 0, // Initialize the count to zero
            );
        }

        // Count the number of active installations for each plugin
        $all_plugins = get_plugins();
        foreach ($all_plugins as $plugin_file => $plugin_data) {
            if (array_key_exists($plugin_file, $active_plugins_data)) {
                $active_plugins_data[$plugin_file]['count']++;
            }
        }

        // Sort plugins based on the number of active installations (descending order)
        uasort($active_plugins_data, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Display the top 5 most used plugins
        $counter = 0;
        foreach ($active_plugins_data as $plugin_data) {
            $system_info .= '<tr><td>' . $plugin_data['name'] . '</td><td>' . $plugin_data['version'] . '</td></tr>';
            // $counter++;
            // if ($counter >= 5) {
            //     break;
            // }
        }
        $system_info .= '</table>';
        $system_info .= '</div>';
        // Webserver Configuration
        $system_info .= '<h2><button id="show-server-info-button" class="info-button">Server<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="server-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Server</h3>';
        $system_info .= '<table>';
        $system_info .= '<p>The options shown below relate to your server setup. If changes are required, you may need your web host’s assistance.</p>';
        // Add Server information
        $system_info .= '<tr><td>Server Architecture</td><td>' . esc_html(php_uname('s')) . '</td></tr>';
        $system_info .= '<tr><td>Web Server</td><td>' . esc_html($_SERVER['SERVER_SOFTWARE']) . '</td></tr>';
        $system_info .= '<tr><td>PHP Version</td><td>' . esc_html(phpversion()) . '</td></tr>';
        $system_info .= '<tr><td>PHP SAPI</td><td>' . esc_html(php_sapi_name()) . '</td></tr>';
        $system_info .= '<tr><td>PHP Max Input Variables</td><td>' . esc_html(ini_get('max_input_vars')) . '</td></tr>';
        $system_info .= '<tr><td>PHP Time Limit</td><td>' . esc_html(ini_get('max_execution_time')) . ' seconds</td></tr>';
        $system_info .= '<tr><td>PHP Memory Limit</td><td>' . esc_html(ini_get('memory_limit')) . '</td></tr>';
        $system_info .= '<tr><td>Max Input Time</td><td>' . esc_html(ini_get('max_input_time')) . ' seconds</td></tr>';
        $system_info .= '<tr><td>Upload Max Filesize</td><td>' . esc_html(ini_get('upload_max_filesize')) . '</td></tr>';
        $system_info .= '<tr><td>PHP Post Max Size</td><td>' . esc_html(ini_get('post_max_size')) . '</td></tr>';
        $system_info .= '<tr><td>cURL Version</td><td>' . esc_html(curl_version()['version']) . '</td></tr>';
        $system_info .= '<tr><td>Is SUHOSIN Installed?</td><td>' . (extension_loaded('suhosin') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Is the Imagick Library Available?</td><td>' . (extension_loaded('imagick') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>Are Pretty Permalinks Supported?</td><td>' . (get_option('permalink_structure') ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>.htaccess Rules</td><td>' . esc_html(is_writable('.htaccess') ? 'Writable' : 'Non Writable') . '</td></tr>';
        $system_info .= '<tr><td>Current Time</td><td>' . esc_html(current_time('mysql')) . '</td></tr>';
        $system_info .= '<tr><td>Current UTC Time</td><td>' . esc_html(current_time('mysql', true)) . '</td></tr>';
        $system_info .= '<tr><td>Current Server Time</td><td>' . esc_html(date('Y-m-d H:i:s')) . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // Database Configuration
        $system_info .= '<h2><button id="show-database-info-button" class="info-button">Database<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="database-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Database</h3>';
        $system_info .= '<table>';
        $database_extension = 'mysqli';
        $database_server_version = $wpdb->get_var("SELECT VERSION() as version");
        $database_client_version = $wpdb->db_version();
        $database_username = DB_USER;
        $database_host = DB_HOST;
        $database_name = DB_NAME;
        $table_prefix = $wpdb->prefix;
        $database_charset = $wpdb->charset;
        $database_collation = $wpdb->collate;
        $max_allowed_packet_size = $wpdb->get_var("SHOW VARIABLES LIKE 'max_allowed_packet'");
        $max_connections_number = $wpdb->get_var("SHOW VARIABLES LIKE 'max_connections'");

        $system_info .= '<tr><td>Extension</td><td>' . esc_html($database_extension) . '</td></tr>';
        $system_info .= '<tr><td>Server Version</td><td>' . esc_html($database_server_version) . '</td></tr>';
        $system_info .= '<tr><td>Client Version</td><td>' . esc_html($database_client_version) . '</td></tr>';
        $system_info .= '<tr><td>Database Username</td><td>' . esc_html($database_username) . '</td></tr>';
        $system_info .= '<tr><td>Database Host</td><td>' . esc_html($database_host) . '</td></tr>';
        $system_info .= '<tr><td>Database Name</td><td>' . esc_html($database_name) . '</td></tr>';
        $system_info .= '<tr><td>Table Prefix</td><td>' . esc_html($table_prefix) . '</td></tr>';
        $system_info .= '<tr><td>Database Charset</td><td>' . esc_html($database_charset) . '</td></tr>';
        $system_info .= '<tr><td>Database Collation</td><td>' . esc_html($database_collation) . '</td></tr>';
        $system_info .= '<tr><td>Max Allowed Packet Size</td><td>' . esc_html($max_allowed_packet_size) . '</td></tr>';
        $system_info .= '<tr><td>Max Connections Number</td><td>' . esc_html($max_connections_number) . '</td></tr>';
        $system_info .= '</table>';
        $system_info .= '</div>';

        // wordpress constants
        $system_info .= '<h2><button id="show-wrcons-info-button" class="info-button">WordPress Constants<span class="dashicons dashicons-arrow-down"></span></h2>';
        $system_info .= '<div id="wrcons-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>WordPress Constants</h3>';
        $system_info .= '<table>';
        // Add WordPress Constants information
        $system_info .= '<tr><td>ABSPATH</td><td>' . esc_html(ABSPATH) . '</td></tr>';
        $system_info .= '<tr><td>WP_HOME</td><td>' . esc_html(home_url()) . '</td></tr>';
        $system_info .= '<tr><td>WP_SITEURL</td><td>' . esc_html(site_url()) . '</td></tr>';
        $system_info .= '<tr><td>WP_CONTENT_DIR</td><td>' . esc_html(WP_CONTENT_DIR) . '</td></tr>';
        $system_info .= '<tr><td>WP_PLUGIN_DIR</td><td>' . esc_html(WP_PLUGIN_DIR) . '</td></tr>';
        $system_info .= '<tr><td>WP_MEMORY_LIMIT</td><td>' . esc_html(WP_MEMORY_LIMIT) . '</td></tr>';
        $system_info .= '<tr><td>WP_MAX_MEMORY_LIMIT</td><td>' . esc_html(WP_MAX_MEMORY_LIMIT) . '</td></tr>';
        $system_info .= '<tr><td>WP_DEBUG</td><td>' . (defined('WP_DEBUG') && WP_DEBUG ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>WP_DEBUG_DISPLAY</td><td>' . (defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>SCRIPT_DEBUG</td><td>' . (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>WP_CACHE</td><td>' . (defined('WP_CACHE') && WP_CACHE ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>CONCATENATE_SCRIPTS</td><td>' . (defined('CONCATENATE_SCRIPTS') && CONCATENATE_SCRIPTS ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>COMPRESS_SCRIPTS</td><td>' . (defined('COMPRESS_SCRIPTS') && COMPRESS_SCRIPTS ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>COMPRESS_CSS</td><td>' . (defined('COMPRESS_CSS') && COMPRESS_CSS ? 'Yes' : 'No') . '</td></tr>';
        // Manually define the environment type (example values: 'development', 'staging', 'production')
        $environment_type = 'development';

        // Display the environment type
        $system_info .= '<tr><td>WP_ENVIRONMENT_TYPE</td><td>' . esc_html($environment_type) . '</td></tr>';

        $system_info .= '<tr><td>WP_DEVELOPMENT_MODE</td><td>' . (defined('WP_DEVELOPMENT_MODE') && WP_DEVELOPMENT_MODE ? 'Yes' : 'No') . '</td></tr>';
        $system_info .= '<tr><td>DB_CHARSET</td><td>' . esc_html(DB_CHARSET) . '</td></tr>';
        $system_info .= '<tr><td>DB_COLLATE</td><td>' . esc_html(DB_COLLATE) . '</td></tr>';

        $system_info .= '</table>';
        $system_info .= '</div>';

        // Filesystem Permission
        $system_info .= '<h2><button id="show-ftps-info-button" class="info-button">Filesystem Permission <span class="dashicons dashicons-arrow-down"></span></button></h2>';
        $system_info .= '<div id="ftps-info-container" class="info-content" style="display:none;">';
        $system_info .= '<h3>Filesystem Permission</h3>';
        $system_info .= '<p>Shows whether WordPress is able to write to the directories it needs access to.</p>';
        $system_info .= '<table>';
        // Filesystem Permission information
        $system_info .= '<tr><td>The main WordPress directory</td><td>' . esc_html(ABSPATH) . '</td><td>' . (is_writable(ABSPATH) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The wp-content directory</td><td>' . esc_html(WP_CONTENT_DIR) . '</td><td>' . (is_writable(WP_CONTENT_DIR) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The uploads directory</td><td>' . esc_html(wp_upload_dir()['basedir']) . '</td><td>' . (is_writable(wp_upload_dir()['basedir']) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The plugins directory</td><td>' . esc_html(WP_PLUGIN_DIR) . '</td><td>' . (is_writable(WP_PLUGIN_DIR) ? 'Writable' : 'Not Writable') . '</td></tr>';
        $system_info .= '<tr><td>The themes directory</td><td>' . esc_html(get_theme_root()) . '</td><td>' . (is_writable(get_theme_root()) ? 'Writable' : 'Not Writable') . '</td></tr>';

        $system_info .= '</table>';
        $system_info .= '</div>';

        return $system_info;
    }
    
    public function display_error_log() {
        // Define the path to your debug log file
        $debug_log_file = WP_CONTENT_DIR . '/debug.log';

        // Check if the debug log file exists
        if (file_exists($debug_log_file)) {
            // Read the contents of the debug log file
            $debug_log_contents = file_get_contents($debug_log_file);

            // Split the log content into an array of lines
            $log_lines = explode("\n", $debug_log_contents);

            // Get the last 100 lines in reversed order
            $last_100_lines = array_slice(array_reverse($log_lines), 0, 100);

            // Join the lines back together with line breaks
            $last_100_log = implode("\n", $last_100_lines);

            // Output the last 100 lines in reversed order in a textarea
            ?>
            <textarea class="errorlog" rows="20" cols="80"><?php echo esc_textarea($last_100_log); ?></textarea>
            <?php
        } else {
            echo 'Debug log file not found.';
        }
    }

}

// Initialize the google sheet connector class
$init = new wc_gsheetconnector_Init();

add_filter('plugin_action_links_' . WC_GSHEETCONNECTOR_BASE_NAME, array($init, 'wc_gsheet_setting_link'));