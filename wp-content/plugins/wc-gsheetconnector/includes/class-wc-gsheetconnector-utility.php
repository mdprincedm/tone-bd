<?php

/*
 * Utilities class for woocommerce google sheet connector pro
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * wc_gsheetconnector_utility class - singleton class
 * @since 1.0
 */
class wc_gsheetconnector_utility {

    private function __construct() {
	// Do Nothing
    }

    /**
     * Get the singleton instance of the wc_gsheetconnector_utility class
     *
     * @return singleton instance of wc_gsheetconnector_utility
     */
    public static function instance() {

	static $instance = NULL;
	if ( is_null( $instance ) ) {
	    $instance = new wc_gsheetconnector_utility();
	}
	return $instance;
    }

    /**
     * Prints message (string or array) in the debug.log file
     *
     * @param mixed $message
     */
    public function logger( $message ) {
	if ( WP_DEBUG === true ) {
	    if ( is_array( $message ) || is_object( $message ) ) {
		error_log( print_r( $messagerror_loge, true ) );
	    } else {
		error_log( $message );
	    }
	}
    }

    /**
     * Display error or success message in the admin section
     *
     * @param array $data containing type and message
     * @return string with html containing the error message
     * 
     * @since 1.0 initial version
     */
    public function admin_notice( $data = array() ) {
	// extract message and type from the $data array
	$message	 = isset( $data[ 'message' ] ) ? $data[ 'message' ] : "";
	$message_type	 = isset( $data[ 'type' ] ) ? $data[ 'type' ] : "";
	switch ( $message_type ) {
	    case 'error':
		$admin_notice	 = '<div id="message" class="error notice is-dismissible">';
		break;
	    case 'update':
		$admin_notice	 = '<div id="message" class="updated notice is-dismissible">';
		break;
	    case 'update-nag':
		$admin_notice	 = '<div id="message" class="update-nag">';
		break;
	    case 'upgrade':
		$admin_notice	 = '<div id="message" class="error notice wpforms-gs-upgrade is-dismissible">';
		break;
	    default:
		$message	 = __( 'There\'s something wrong with your code...', 'wc-gsheetconnector' );
		$admin_notice	 = "<div id=\"message\" class=\"error\">\n";
		break;
	}

	$admin_notice	 .= "    <p>" . __( $message, 'wc-gsheetconnector' ) . "</p>\n";
	$admin_notice	 .= "</div>\n";
	return $admin_notice;
    }

    /**
     * Utility function to get the current user's role
     *
     * @since 1.0
     */
    public function get_current_user_role() {
	global $wp_roles;
	foreach ( $wp_roles->role_names as $role => $name ) :
	    if ( current_user_can( $role ) )
		return $role;
	endforeach;
    }

    public static function gs_debug_log($error){
		try{	
			if( ! is_dir( WC_GSHEETCONNECTOR_PATH.'logs' ) ){
				mkdir( WC_GSHEETCONNECTOR_PATH . 'logs', 0755, true );
			}
		} catch (Exception $e) {

		}
		try{
         // check if debug log file exists or not
        $wclogFilePathToDelete = WC_GSHEETCONNECTOR_PATH . "logs/log.txt";
        // Check if the log file exists before attempting to delete
        if (file_exists($wclogFilePathToDelete)) {
            unlink($wclogFilePathToDelete);
        }
         // check if debug unique log file exists or not
         $wcexistDebugFile = get_option('wcfgs_debug_log_file');
         if (!empty($wcexistDebugFile) && file_exists($wcexistDebugFile)) {
         $wclog = fopen( $wcexistDebugFile , 'a');
         if ( is_array( $error ) ) {
            fwrite($wclog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion(), TRUE));
            fwrite( $wclog, print_r($error, TRUE));   
         } else {
         $result = fwrite($wclog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion()." \t $error \r\n", TRUE));
         }
         fclose( $wclog );
            }
        else{
        // if unique log file not exists then create new file code
        // Your log content (you can customize this)
        $wc_unique_log_content = "Log created at " . date('Y-m-d H:i:s');
        // Create the log file
          $wclogfileName = 'log-' . uniqid() . '.txt';
        // Define the file path
          $wclogUniqueFile = WC_GSHEETCONNECTOR_PATH . "logs/".$wclogfileName;
       if (file_put_contents($wclogUniqueFile, $wc_unique_log_content)) {
         // save debug unique file in table
         update_option('wcfgs_debug_log_file', $wclogUniqueFile);
        // Success message
        // echo "Log file created successfully: " . $logUniqueFile;
        $wclog = fopen( $wclogUniqueFile , 'a');
         if ( is_array( $error ) ) {
            fwrite($wclog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion(), TRUE));
            fwrite( $wclog, print_r($error, TRUE));   
         } else {
         $result = fwrite($wclog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion()." \t $error \r\n", TRUE));
         }
         fclose( $wclog );

       } else {
        // Error message
        echo "Error - Not able to create Log File.";
          }
        }
        
		} catch (Exception $e) {
			
		}
    }

    /**
     * 
     * @param string $setting_name
     * @param array $selected_roles
     */
    public function gs_woocommerce_checkbox_roles_multi( $setting_name, $selected_roles ) {
		$selected_row	 = '';
		$checked	 = '';
		$roles		 = array();
		$system_roles	 = $this->get_system_roles();

		if ( ! empty( $selected_roles ) ) {
			foreach ( $selected_roles as $role => $display_name ) {
			array_push( $roles, $role );
			}
		}

       // changes checkbox convert to toggle
		$selected_row	 .= "<label class='toggle-role'> <input type='checkbox' class='woforms-gs-checkbox' disabled='disabled' checked='checked'/>";
		$selected_row	 .= "<span class='slider-role'></span>";
		$selected_row	 .= "</label>";
		$selected_row	 .= "<label style='margin-left:10px;'>";
        $selected_row	 .= __( "Administrator", "wc-gsheetconnector" );
        $selected_row	 .= "</label>";
        $selected_row	 .= "</br>";
		foreach ( $system_roles as $role => $display_name ) {
			if ( $role === "administrator" ) {
			continue;
			}
			if ( ! empty( $roles ) && is_array( $roles ) && in_array( esc_attr( $role ), $roles ) ) { // preselect specified role
			$checked = " ' checked='checked' ";
			} else {
			$checked = '';
			}

           
		   $selected_row	 .= "<label class='toggle-role'> <input type='checkbox' class='gs-checkbox'
			  name='" . $setting_name . "' value='" . esc_attr( $role ) . "'/>";
			$selected_row	 .= "<span class='slider-role'></span>";
			$selected_row	 .= "</label>";
			$selected_row	 .= "<label style='margin-left:10px;'>";
			$selected_row	 .= __( $display_name, "wc-gsheetconnector" );
			$selected_row	 .= "</label>";
			$selected_row	 .= "</br>";
		}
		echo $selected_row;
    }

    

    /*
     * Get all editable roles except for subscriber role
     * @return array
     * @since 1.1
     */

    public function get_system_roles() {
		$participating_roles	 = array();
		$editable_roles		 = get_editable_roles();

		foreach ( $editable_roles as $role => $details ) {
			$participating_roles[ $role ] = $details[ 'name' ];
		}
		return $participating_roles;
    }

       
}
