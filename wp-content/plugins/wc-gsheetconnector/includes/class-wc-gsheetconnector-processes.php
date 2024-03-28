<?php

/*
 * Process class for woocommerce google sheet connector pro
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * wc_gsheetconnector_Service class
 * @since 1.0
 */
class GS_Processes {

    public function __construct() {
	add_action( 'wp_ajax_verify_gs_woo_integation', array( $this, 'verify_gs_woo_integation' ) );

	//deactivate google sheet integration
	add_action( 'wp_ajax_deactivate_gs_woo_integation', array( $this, 'deactivate_gs_woo_integation' ) );

	// clear debug log data
	add_action( 'wp_ajax_gs_woo_clear_log', array( $this, 'gs_woo_clear_logs' ) );

	// get sheet name and tab name
	add_action( 'wp_ajax_sync_woo_google_account', array( $this, 'sync_woo_google_account' ) );

	// get sheet names
	add_action( 'wp_ajax_get_tab_list', array( $this, 'get_woo_tab_list_by_sheetname' ) );

    }

    /**
     * AJAX function - verifies the token
     *
     * @since 1.0
     */
    public function verify_gs_woo_integation($Code="") {
	// nonce checksave_gs_woo_settings
	check_ajax_referer( 'gs-ajax-nonce', 'security' );

		/* sanitize incoming data */
		$Code = sanitize_text_field( $_POST[ "code" ] );

		if ( ! empty( $Code ) ) {
		    update_option( 'gs_woo_access_code', $Code );
		} else {
		    return;
		}

		if ( get_option( 'gs_woo_access_code' ) != '' ) {
		    include_once( WC_GSHEETCONNECTOR_ROOT . '/lib/google-sheets.php');
		    GSCWOO_googlesheet::preauth( get_option( 'gs_woo_access_code' ) );
		    //update_option( 'gs_woo_verify', 'valid' );
		    wp_send_json_success();
		} else {
		    update_option( 'gs_woo_verify', 'invalid' );
		    wp_send_json_error();
		}
    }


    /**
     * AJAX function - verifies the token
     *
     * @since 1.0
     */
    public function verify_gs_woo_integation_new($Code="") {
		if ( ! empty( $Code ) ) {
			$Code = sanitize_text_field($_GET["code"]);
		    update_option( 'gs_woo_access_code', $Code );
		} else {
		    return;
		}

		if ( get_option( 'gs_woo_access_code' ) != '' ) {
		    include_once( WC_GSHEETCONNECTOR_ROOT . '/lib/google-sheets.php');
		    GSCWOO_googlesheet::preauth( get_option( 'gs_woo_access_code' ) );
		    //update_option( 'gs_woo_verify', 'valid' );
		} else {
		    update_option( 'gs_woo_verify', 'invalid' );
		}
    }

    /**
     * AJAX function - deactivate activation
     * @since 1.2
     */
    public function deactivate_gs_woo_integation() {
	// nonce check
	check_ajax_referer( 'gs-ajax-nonce', 'security' );

	if ( get_option( 'gs_woo_token' ) !== '' ) {
	    delete_option( 'gs_woo_feeds' );
	    delete_option( 'gs_woo_sheetId' );
	    delete_option( 'gs_woo_token' );
	    delete_option( 'gs_woo_access_code' );
	    delete_option( 'gs_woo_verify' );

	    wp_send_json_success();
	} else {
	    wp_send_json_error();
	}
    }

    /**
     * AJAX function - clear log file
     * @since 1.0
     */
    public function gs_woo_clear_logs() {
	  // nonce check
	  check_ajax_referer( 'gs-ajax-nonce', 'security' );

	  $wcexistDebugFile = get_option('wcfgs_debug_log_file');
      $clear_file_msg ='';
      // check if debug unique log file exist or not then exists to clear file
      if (!empty($wcexistDebugFile) && file_exists($wcexistDebugFile)) {
       
        $handle = fopen ( $wcexistDebugFile, 'w');
        
        fclose( $handle );
        $clear_file_msg ='Logs are cleared.';
       }
       else{
        $clear_file_msg = 'No log file exists to clear logs.';
       }
     
      
      wp_send_json_success($clear_file_msg);
   }

    /**
     * Function - sync with google account to fetch sheet and tab name
     * @since 1.0
     */
    public function sync_woo_google_account() {
	$return_ajax = false;

	if ( isset( $_POST[ 'isajax' ] ) && $_POST[ 'isajax' ] == 'yes' ) {
	    // nonce check
	    check_ajax_referer( 'gs-ajax-nonce', 'security' );
	    $init		 = sanitize_text_field( $_POST[ 'isinit' ] );
	    $return_ajax	 = true;
	}

	include_once( WC_GSHEETCONNECTOR_ROOT . '/lib/google-sheets.php');

	$doc		 = new GSCWOO_googlesheet();
	$doc->auth();
	// Get all spreadsheets
	$spreadsheetFeed = $doc->get_spreadsheets();

	foreach ( $spreadsheetFeed as $sheetfeeds ) {
	    $sheetId	 = $sheetfeeds[ 'id' ];
	    $sheetname	 = $sheetfeeds[ 'title' ];

	    $sheet_array[ $sheetId ] = array(
		"sheet_name"	 => $sheetname
	    );
	}
	update_option( 'gs_woo_sheet_feeds', $sheet_array );

	if ( $return_ajax == true ) {
	    if ( $init == 'yes' ) {
		wp_send_json_success( array( "success" => 'yes' ) );
	    } else {
		wp_send_json_success( array( "success" => 'no' ) );
	    }
	}
    }

    /**
     * AJAX function - Fetch tab list by sheet name
     * @since 1.0
     */
    public function get_woo_tab_list_by_sheetname() {
	// nonce check
	check_ajax_referer( 'gs-ajax-nonce', 'security' );

	$sheetname	 = sanitize_text_field( $_POST[ 'sheetname' ] );
	$sheet_data	 = get_option( 'gs_woo_feeds' );
	$html		 = "";
	$tablist	 = "";
	if ( ! empty( $sheet_data ) && array_key_exists( $sheetname, $sheet_data ) ) {
	    $tablist = $sheet_data[ $sheetname ];
	}

	if ( ! empty( $tablist ) ) {
	    $html = '<option value="">' . __( "Select", "gs-woocommerce" ) . '</option>';
	    foreach ( $tablist as $tab ) {
		$html .= '<option value="' . $tab . '">' . $tab . '</option>';
	    }
	}
	wp_send_json_success( htmlentities( $html ) );
    }

}

$gs_processes = new GS_Processes();