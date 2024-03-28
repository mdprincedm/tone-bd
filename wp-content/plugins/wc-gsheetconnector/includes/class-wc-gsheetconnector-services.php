<?php

/*
 * Service class for woocommerce google sheet connector pro
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * wc_gsheetconnector_Service class
 *
 * @since 1.0
 */
class wc_gsheetconnector_Service {

	public $class_name = 'wc_gsheetconnector_Service';
	public $status_and_sheets;
	public $sheet_headers;
	public $sheet_headers_pro;
	public $product_headers_pro;
	public $customer_headers_pro;
	public $product_variations_headers_pro;
	public $coupons_headers_pro;
	public $subscriptions_headers_pro;
	public $_gfgsc_googlesheet;

	public function __construct() {

		$this->status_and_sheets = array(
			'wc-pending'    => 'Pending Orders',
			'wc-processing' => 'Processing Orders',
			'wc-on-hold'    => 'On Hold Orders',
			'wc-failed'     => 'Failed Orders',
			'wc-completed'  => 'Completed Orders',
			'wc-cancelled'  => 'Cancelled Orders',
			'wc-refunded'   => 'Refunded Orders',
			'wc-trash'      => 'Trashed Orders',
		);

		$this->status_and_sheets = apply_filters( 'poolexpress_status_and_sheets', $this->status_and_sheets );

		$class_name       = $this->class_name;
		$order_class_name = 'WC_Order';

		$order_id_column_name = apply_filters( 'poolexpress_order_id_column_name', 'Order Id' );

		$this->sheet_headers = array(
			$order_id_column_name    => array(
				'class'         => $order_class_name,
				'function_name' => 'get_id',
			),
			'Status'                 => array(
				'class'         => $order_class_name,
				'function_name' => 'get_status',
			),
			'Product name(QTY)(SKU)' => array(
				'class'         => $class_name,
				'function_name' => 'extract_product_qty_sku',
			),
			'Tax Total'              => array(
				'class'         => $order_class_name,
				'function_name' => 'get_total_tax',
			),
			'Order Total'            => array(
				'class'         => $order_class_name,
				'function_name' => 'get_total',
			),
			'Payment Method'         => array(
				'class'         => $order_class_name,
				'function_name' => 'get_payment_method_title',
			),
			'Billing First name'     => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_first_name',
			),
			'Billing Last Name'      => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_last_name',
			),
			'Billing Address 1'      => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_address_1',
			),
			'Billing Address 2'      => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_address_2',
			),
			'Billing City'           => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_city',
			),
			'Billing State'          => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_state',
			),
			'Billing Postcode'       => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_postcode',
			),
			'Billing Country'        => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_country',
			),
			'Billing Company Name'   => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_company',
			),
			'Shipping First Name'    => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_first_name',
			),
			'Shipping Last Name'     => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_last_name',
			),
			'Shipping Address 1'     => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_address_1',
			),
			'Shipping Address 2'     => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_address_2',
			),
			'Shipping City'          => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_city',
			),
			'Shipping State'         => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_state',
			),
			'Shipping Postcode'      => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_postcode',
			),
			'Shipping Country'       => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_country',
			),
			'Shipping Method Title'  => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_to_display',
			),
			'Shipping Company Name'  => array(
				'class'         => $order_class_name,
				'function_name' => 'get_shipping_company',
			),
			'Coupons Codes'          => array(
				'class'         => $order_class_name,
				'function_name' => 'get_coupon_codes',
			),
			'Email'                  => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_email',
			),
			'Phone'                  => array(
				'class'         => $order_class_name,
				'function_name' => 'get_billing_phone',
			),
			'Customer Note'          => array(
				'class'         => $order_class_name,
				'function_name' => 'get_customer_note',
			),
			'Created Date'           => array(
				'class'         => $order_class_name,
				'function_name' => 'get_date_created',
			),

		);

		$this->sheet_headers = apply_filters( 'poolexpress_sheet_headers', $this->sheet_headers );

		$this->sheet_headers_pro    = array(
			'Currency',
			'Product ID',
			'Product Image',
			'Product Meta',
			'Product Variation',
			'SKU',
			'Product Base Price',
			'Product Total',
			'Product Categories',
			'Partial Refund Amount',
			'Order URL',
			'Status Updated Date',
			'Transaction ID',
			'Customer ID',
			'Order Completion Date',
			'Order Paid Date',
			'Order Notes',
			'Order Status',
			'Discount Total',
			'Discount Tax',
			'Shipping Total',
			'Shipping Tax',
			'Cart Tax',
		);
		$this->product_headers_pro  = array(
			'Product ID',
			'Product Name',
			'Product Status',
			'Product Short Description',
			'Product Description',
			'Product Slug',
			'Product Link',
			'Product Categories',
			'Product Type',
			'Product Image',
			'Product Tags',
			'Product Attribute',
			'Product Regular Price',
			'Product Sale Price Dates From',
			'Product Sale Price Dates To',
			'Product Stock',
			'Product Stock Status',
			'Product Weight',
			'Product Height',
			'Product Width',
			'Product Length',
			'Product Total Sale',
			'Product Purchase Note',
			'Product Dimensions',
			'Product Sold Individually',
			'Manage Product Stock',
			'Product Shipping Class',
			'Product Tax Status',
			'Product Tax Class',
			'Virtual Product',
			'Date Created',
			'Date Modified',
			'Product Downloadable',
			'Product Downloadable Files',
			'Product Downloadable File Names',
			'Product Download Limit',
			'Product Download Expiry',
			'product Allow Backorders',
			'Product Variation SKU',
			'Product Variation Image',
			'Product Variation Regular Price',
			'Product Variation Sale Price',
			'Product Variation Sale Price Dates From',
			'Product Variation Sale Price Dates To',
			'Product Variation Stock',
			'Product Variation Stock Status',
			'Product Variation Weight',
			'Product Variation Dimensions',
			'Product Variation Manage Product Stock',
			'Product Variation Shipping Class',
			'Virtual Product Variation',
			'Product Variation Description',
			'Downloadable Product Variation',
			'Product Variation Downloadable Files',
			'Product Variation Download Limit',
			'Product Variation Download Expiry',
			'Grouped Products Ids',
			'Product Variation Allow Backorders',
			'Product Variation Total Sale',
			'External Product Button Text',
			'External Product Link',
		);
		$this->customer_headers_pro = array(

			'Customer ID',
			'Customer Username',
			'Customer Role',
			'Customer FirstName',
			'Customer LastName',
			'Customer Nickname',
			'Customer Display Name',
			'Customer EmailID',
			'Customer Website',
			'Customer Biographical Info',
			'Customer Profile Image',
			'Billing FirstName',
			'Billing LastName',
			'Billing Company Name',
			'Billing Address1',
			'Billing Address2',
			'Billing City',
			'Billing Postcode / ZIP',
			'Billing Country',
			'Billing State',
			'Billing Phone Number',
			'Billing EmailID',
			'Shipping FirstName',
			'Shipping LastName',
			'Shipping Company Name',
			'Shipping Address1',
			'Shipping Address2',
			'Shipping City',
			'Shipping Postcode / ZIP',
			'Shipping Country ',
			'Shipping State',

		);
		$this->product_variations_headers_pro = array(

			'Product ID',
			'Product Variation Name',
			'Product Variation Sale Price',
			'Product Variation Stock Status',
			'Product Variation Shipping Class',
			'Product Variation Downloadable Files',
			'Product Variation Total Sale',
			'Variation ID',
			'Product Variation SKU',
			'Product Variation Sales Price Dates From',
			'Product Variation Weight',
			'Virtual Product Variation',
			'Product Variation Download Limit',
			'Product Name',
			'Product Variation Image',
			'Product Variation Sales Price Dates To',
			'Product Variation Dimensions',
			'Product Variation Description',
			'Product Variation Download Expiry',
			'Product Type',
			'Product Variation Regular Price',
			'Product Variation Stock',
			'Product Variation Manage Product Stock',
			'Downloadable Product Variation',
			'Product Variation Allow Backorders',
		);

		$this->coupons_headers_pro = array(
			'Coupon ID',
			'Coupon Code',
			'Description',
			'Discount Type',
			'Coupon Amount',
			'Status',
			'Allow Free Shipping',
			'Coupon Expiry Date',
			'Minimum Spend',
			'Maximum Spend',
			'Individual Use Only',
			'Exclude Sale Items',
			'Products',
			'Exclude Products',
			'Applied Product Categories',
			'Exclude Categories',
			'Allowed Emails',
			'Usage Limit Per Coupon',
			'Limit Usage To X Items',
			'Usage Limit Per User',

		);

		$this->subscriptions_headers_pro = array(
			'Subscription ID',
			'Status',
			'Customer ID',
			'Customer Name',
			'Start Date',
			'End Date',
			'Billing Period',
			'Billing Interval',
			'Trial End Date',
			'Next Payment Date',
			'Renewal Enabled',
			'Total Order Count',
			'Total Shipped Items',
			'Total Earned',
			'Total Refunded',
			'Tax Amount',
			'Coupon Amount',
			'Product Names',
			'Product IDs',
			'Variation Names',
			'Variation IDs',
			'Cancellation Reason',
			'Notes',
			'Payment Method',
			'Shipping Address',
			'Coupon Code',
			'Switched Subscriptions',
			'New Subscriptions',
			'Number of Resubscribes',
			'Number of Renewals',
			'Subscriptions Ended',
			'Cancellations',
			'Signup Totals',
			'Resubscribe Totals',
			'Renewal Totals',
			'Switch Totals',

		);

		add_filter( 'gscwoo_tab_headers', array( $this, 'add_status_header_in_all_orders' ), 10, 2 );
	}

	public function init() {
		try {
			add_action( 'admin_init', array( $this, 'execute_post_data' ) );
			add_action( 'woocommerce_order_status_changed', array( $this, 'woocommerce_order_status_changed' ), 10, 4 );
			add_action( 'woocommerce_process_shop_order_meta', array( $this, 'woocommerce_process_shop_order_meta' ), 1000, 2 );
			add_filter( 'gscwoo_row_values', array( $this, 'change_status_to_uppercase' ), 10, 2 );
			add_action( 'wp_trash_post', array( $this, 'wp_trash_post' ), 10, 1 );
			add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 10, 3 );
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function woocommerce_process_shop_order_meta( $order_id, $order ) {
		try {
			$order          = new WC_Order( $order_id );
			$current_status = $order->get_status();
			$this->woocommerce_order_status_changed( $order_id, $current_status, $current_status, $order );
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function transition_post_status( $new_status, $old_status, $post ) {
		try {

			global $post_type;
			if ( ( $post_type !== 'shop_order' ) || ( isset( $_REQUEST['action'] ) && sanitize_text_field( $_REQUEST['action'] != 'untrash' ) ) ) {
				return;
			}

			if ( $old_status == 'trash' || $old_status == 'wc-trash' ) {
				$order_id = $post->ID;
				$order    = wc_get_order( $order_id );

				$old_status = str_replace( 'wc-', '', $old_status );
				$new_status = str_replace( 'wc-', '', $new_status );

				$this->woocommerce_order_status_changed( $order_id, $old_status, $new_status, $order );
			}
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}
	public function wp_trash_post( $order_id ) {
		try {
			global $post_type;
			if ( $post_type !== 'shop_order' ) {
				return;
			}

			$order = wc_get_order( $order_id, $order );

			$new_status     = 'trash';
			$current_status = $order->get_status();

			$this->woocommerce_order_status_changed( $order_id, $current_status, $new_status, $order );
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function execute_post_data() {
		try {

			if ( isset( $_POST ['woo-save-btn'] ) ) {
				// Check if the nonce is set.
				if ( ! isset( $_POST['gs-woo-nonce'] ) ) {
					return;
				}

				// Verify the nonce.
				$nonce = sanitize_text_field( $_POST['gs-woo-nonce'] );

				if ( ! wp_verify_nonce( $nonce, 'gs-woo-nonce' ) ) {
					return;
				}


				// adminitrator or super admin check role.
				$current_role = wc_gsheetconnector_utility::instance()->get_current_user_role();
				if ( $current_role !== 'administrator' || ( ! is_super_admin() ) ) {
					return;
				}

				// Fetch dropdown fields
				$selected_sheet_id = isset( $_POST['gs-woo-sheet-id'] ) ? sanitize_text_field( $_POST['gs-woo-sheet-id'] ) : '';

				if ( $selected_sheet_id != '' ) {
					update_option( 'gs_woo_settings', $selected_sheet_id );

					// Get Spreadsheet name from id
					$sheet_data             = get_option( 'gs_woo_sheet_feeds' );
					$gscwoo_spreadsheetName = $sheet_data[ $selected_sheet_id ]['sheet_name'];

					// Get order states and save it to database
					$order_states = isset( $_POST['wc_order_state'] ) ? array_map( 'sanitize_text_field', $_POST['wc_order_state'] ) : array();

					update_option( 'gscwc_order_states', $order_states );

					if ( ! empty( $order_states ) ) {
						// Check for existing sheet tabs
						include_once WC_GSHEETCONNECTOR_ROOT . '/lib/google-sheets.php';
						$gscwoo_client = new GSCWOO_googlesheet();

						$gscwoo_client->auth();
						$gscwoo_client->setSpreadsheetId( $selected_sheet_id );

						// $gscwoo_client->ciu_tabs_and_headers( $selected_sheet_id, $gscwoo_spreadsheetName, $order_states );
						$this->create_remove_sheet_and_headers( $selected_sheet_id, $order_states );
					} else {
						add_action( 'admin_notices', array( $this, 'error_message' ) );
					}
				}
			} else {
				add_action( 'admin_notices', array( $this, 'error_message' ) );
			}
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function error_message() {
		if ( is_admin() && ( isset( $_GET['page'] ) && ( $_GET['page'] == 'woocommerce-gsheet-config' ) ) ) {
			if ( isset( $_POST ['woo-save-btn'] ) ) {
				$plugin_error = wc_gsheetconnector_utility::instance()->admin_notice(
					array(
						'type'    => 'error',
						'message' => __( 'Please select Google Sheet Name and Check Atleast on Google Sheet Tab !', 'wc-gsheetconnector' ),
					)
				);
				echo esc_attr( $plugin_error, 'wc-gsheetconnector' );
			}
		}
	}

	public function create_remove_sheet_and_headers( $spreadsheet_id, $order_states ) {

		try {
			$gscwoo_client = $this->get_googlesheet_object();
			$sheet_headers = $this->sheet_headers;

			$status_and_sheets = $this->status_and_sheets;

			$available_sheets = $gscwoo_client->get_sheet_tabs( $spreadsheet_id );

			$removable_sheets          = array();
			$add_sheets                = array();
			$working_order_states_data = array();

			foreach ( $status_and_sheets as $wc_status => $associated_tab ) {

				if ( in_array( $wc_status, $order_states ) ) {

					if ( ! in_array( $associated_tab, $available_sheets ) ) {
						$add_sheets[] = $associated_tab;
					}

					$working_order_states_data[ $wc_status ] = $associated_tab;
				} elseif ( in_array( $associated_tab, $available_sheets ) ) {
						$sheet_id                      = array_search( $associated_tab, $available_sheets );
						$removable_sheets[ $sheet_id ] = $associated_tab;
				}
			}

			$sheet_update_requests = array();

			if ( $add_sheets ) {
				foreach ( $add_sheets as $sheetName ) {
					$sheet_update_requests[] = array(
						'addSheet' => array(
							'properties' => array(
								'title' => $sheetName,
							),
						),
					);
				}
			}

			if ( $removable_sheets ) {
				foreach ( $removable_sheets as $sheet_id => $sheetName ) {
					$sheet_update_requests[] = array(
						'deleteSheet' => array(
							'sheetId' => (int) $sheet_id,
						),
					);
				}
			}

			if ( $sheet_update_requests ) {
				$gscwoo_client->perform_sheet_tab_updates( $spreadsheet_id, $sheet_update_requests );
			}

			/* NOW SET HEADERS */

			foreach ( $working_order_states_data as $wc_status => $associated_tab ) {
				$headers = apply_filters( 'gscwoo_tab_headers', $sheet_headers, $wc_status );

				$header_names = array_keys( $headers );

				// $gscwoo_client->add_row_to_sheet( $spreadsheet_id, $associated_tab, $header_names, $order, true );
				$gscwoo_client->add_row_to_sheet( $spreadsheet_id, $associated_tab, $header_names, '', true );
			}
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function add_status_header_in_all_orders( $headers, $wc_status ) {
		try {
			if ( $wc_status != 'all' ) {
				unset( $headers['Status'] );
			}

			return $headers;
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function change_status_to_uppercase( $header_value, $cell_name ) {

		try {
			if ( $cell_name == 'Status' ) {
				$header_value = ucwords( $header_value );
			}

			return $header_value;
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function create_save_array( $order, $header_cells, $custom_status = false ) {

		try {
			$create_value_array = array();
			$send_row_data      = array();

			$order_data = $order->get_data();
			$wc_status  = $order->get_status();

			if ( $custom_status ) {
				$wc_status = $custom_status;
			}

			$sheet_headers = $this->sheet_headers;
			$sheet_headers = apply_filters( 'gscwoo_tab_headers', $sheet_headers, $wc_status );

			foreach ( $sheet_headers as $header_name => $header_data ) {
				$class         = $header_data['class'];
				$function_name = $header_data['function_name'];

				$header_value = $function_name;
				if ( $class && class_exists( $class ) ) {
					if ( method_exists( $class, $function_name ) ) {

						if ( $class == 'WC_Order' ) {
							$header_value = $order->$function_name();
						} else {
							$header_value = $class::$function_name( $order, $order_data );
						}
					}
				} elseif ( function_exists( $function_name ) ) {
					$header_value = $function_name( $order, $order_data );
				}

				if ( is_array( $header_value ) ) {
					$header_value = implode( ', ', $header_value );
				}
				if ( is_a( $header_value, 'WC_DateTime' ) ) {
					$header_value = $header_value->date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
				}

				$create_value_array[ $header_name ] = apply_filters( 'gscwoo_row_values', $header_value, $header_name );

			}

			$entry_cells = $create_value_array;
			if ( $entry_cells && $header_cells ) {

				foreach ( $header_cells as $index => $cellName ) {

					if ( isset( $entry_cells[ $cellName ] ) ) {
						$send_row_data[ $index ] = $entry_cells[ $cellName ];
					}
				}

				foreach ( $header_cells as $index => $cellName ) {
					if ( ! isset( $send_row_data[ $index ] ) ) {
						$send_row_data[ $index ] = '';
					}
				}
			}

			return $send_row_data;

		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function woocommerce_order_status_changed( $order_id, $old_status, $new_status, $order ) {

		try {
			$new_wc_status = 'wc-' . $new_status;
			$old_wc_status = 'wc-' . $old_status;

			$adding_sheet   = '';
			$removing_sheet = '';

			$data_update_only = false;
			if ( $new_wc_status == $old_wc_status ) {
				$data_update_only = true;
			}

			if ( isset( $this->status_and_sheets[ $new_wc_status ] ) && $this->status_and_sheets[ $new_wc_status ] != '' ) {
				$adding_sheet = $this->status_and_sheets[ $new_wc_status ];
			}

			if ( isset( $this->status_and_sheets[ $old_wc_status ] ) && $this->status_and_sheets[ $old_wc_status ] != '' ) {
				$removing_sheet = $this->status_and_sheets[ $old_wc_status ];
			}

			$gscwoo_client  = $this->get_googlesheet_object();
			$spreadsheet_id = get_option( 'gs_woo_settings' );

			$header_row = $gscwoo_client->get_header_row( $spreadsheet_id, $adding_sheet );
			$insert_row = $this->create_save_array( $order, $header_row );

			if ( ! $data_update_only && $this->status_is_enabled( $new_wc_status ) ) {
				$gscwoo_client->add_row_to_sheet( $spreadsheet_id, $adding_sheet, $insert_row, $order );
			}

			if ( ! $data_update_only && $this->status_is_enabled( $old_wc_status ) ) {
				$header_removing_row  = $gscwoo_client->get_header_row( $spreadsheet_id, $removing_sheet );
				$order_id_column_name = apply_filters( 'poolexpress_order_id_column_name', 'Order Id' );
				$order_id_key         = array_search( $order_id_column_name, $header_removing_row );
				$gscwoo_client->remove_row_by_order_id( $spreadsheet_id, $removing_sheet, $order_id, $order_id_key );
			}

			if ( $data_update_only && $this->status_is_enabled( $new_wc_status ) ) {
				$header_removing_row  = $gscwoo_client->get_header_row( $spreadsheet_id, $adding_sheet );
				$order_id_column_name = apply_filters( 'poolexpress_order_id_column_name', 'Order Id' );
				$order_id_key         = array_search( $order_id_column_name, $header_removing_row );
				$gscwoo_client->update_row_by_order_id( $spreadsheet_id, $adding_sheet, $insert_row, $order_id, $order_id_key );
			}

			if ( $this->status_is_enabled( 'all' ) ) {
				$all_sheet            = $this->status_and_sheets['all'];
				$header_all_row       = $gscwoo_client->get_header_row( $spreadsheet_id, $all_sheet );
				$order_id_column_name = apply_filters( 'poolexpress_order_id_column_name', 'Order Id' );
				$order_id_key         = array_search( $order_id_column_name, $header_all_row );
				$insert_row           = $this->create_save_array( $order, $header_all_row, 'all' );
				$gscwoo_client->update_row_by_order_id( $spreadsheet_id, $all_sheet, $insert_row, $order_id, $order_id_key );
			}

			remove_action( 'woocommerce_process_shop_order_meta', array( $this, 'woocommerce_process_shop_order_meta' ), 1000, 2 );
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}

		// exit;
	}

	public function status_is_enabled( $wc_status ) {

		try {
			$selected_order_states = get_option( 'gscwc_order_states' );
			if ( in_array( $wc_status, $selected_order_states ) ) {
				return true;
			}

			return false;
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function get_googlesheet_object() {

		try {
			if ( $this->_gfgsc_googlesheet ) {
				return $this->_gfgsc_googlesheet;
			}

			$google_sheet = new GSCWOO_googlesheet();
			$google_sheet->auth();

			$this->_gfgsc_googlesheet = $google_sheet;
			return $google_sheet;
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function extract_product_qty_sku( $order, $order_data = false ) {

		try {

			$value       = array();
			$order_items = $order->get_items();

			foreach ( $order_items as $item ) {
				$sku          = '';
				$variation_id = $item->get_variation_id();
				$product_id   = $item->get_product_id();
				$product      = $item->get_product();
				if ( method_exists( $product, 'get_sku' ) ) {
					$sku = $product->get_sku();
				}

				$create_product_name  = $item->get_name();
				$create_product_name .= '(' . $item->get_quantity() . ')';
				$create_product_name .= $sku ? '(' . $sku . ')' : '';

				$value[] = $create_product_name;
			}

			$value = implode( ', ', $value );
			return $value;
		} catch ( Exception $e ) {
			$data['ERROR_MSG'] = $e->getMessage();
			$data['TRACE_STK'] = $e->getTraceAsString();
			wc_gsheetconnector_utility::gs_debug_log( $data );
		}
	}

	public function get_adding_extra_order_row() {
		$extra_rows = array();
		global $wpdb;
		$already_in_header = "'_billing_address_1','_billing_address_2','_billing_address_index','_billing_city','_billing_company','_billing_country','_billing_first_name','_billing_last_name','_billing_postcode','_billing_state','_cart_hash','_cart_discount_tax','_completed_date','_date_completed','_date_paid','_order_currency','_order_tax','_order_total','_paid_date','_payment_method','_pos','_shipping_address_1','_shipping_address_2','_shipping_address_index','_shipping_city','_shipping_company','_shipping_country','_shipping_first_name','_shipping_last_name','_shipping_postcode','_shipping_state','_wc'";

		$query = "SELECT DISTINCT(wpm.meta_key) 
		FROM {$wpdb->prefix}posts AS wp 
		INNER JOIN {$wpdb->prefix}postmeta AS wpm ON wp.ID = wpm.post_id
		WHERE post_type = 'shop_order' AND wpm.meta_key NOT IN ({$already_in_header}) ORDER BY wpm.meta_key";

		$all_extra_order_headers = $wpdb->get_results( $query, ARRAY_A );

		if ( ! empty( $all_extra_order_headers ) ) {
			$extra_rows = array_column( $all_extra_order_headers, 'meta_key' );
		}

		return $extra_rows;
	}

	public function get_adding_extra_product_item_row() {
		$extra_rows = array();
		global $wpdb;

		$already_in_header = "'_product_id','_variation_id','_qty','_line_subtotal','_line_subtotal_tax','_line_total'";

		$query1                   = "SELECT DISTINCT(woim.meta_key) 
		FROM {$wpdb->prefix}woocommerce_order_items AS woi 
		INNER JOIN {$wpdb->prefix}posts AS wp ON wp.ID = woi.order_id
		INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
		WHERE order_item_type='line_item' AND woim.meta_key NOT IN ({$already_in_header})";
		$all_extra_order_itemmeta = $wpdb->get_results( $query1, ARRAY_A );

		if ( ! empty( $all_extra_order_itemmeta ) ) {
			$extra_rows = array_column( $all_extra_order_itemmeta, 'meta_key' );
		}

		/** compatible thirt-party plugins related to product meta */
		$allProductQry  = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'product'";
		$allProductArr  = $wpdb->get_results( $allProductQry, ARRAY_A );
		$allProductClmn = array_column( $allProductArr, 'ID' );
		$allProductIds  = implode( ',', $allProductClmn );

		$query2 = "SELECT DISTINCT(pm.meta_key) FROM `{$wpdb->prefix}postmeta` AS pm WHERE pm.post_id IN ($allProductIds) AND pm.meta_key  NOT IN ($already_in_header)";

		$all_extra_post_itemmeta = $wpdb->get_results( $query2, ARRAY_A );

		if ( ! empty( $all_extra_post_itemmeta ) ) {
			$extra_rows_new = array_column( $all_extra_post_itemmeta, 'meta_key' );
			$extra_rows     = array_merge( $extra_rows, $extra_rows_new );

		}

		return $extra_rows;
	}
}

$wc_gsheetconnector_service = new wc_gsheetconnector_Service();
$wc_gsheetconnector_service->init();
// add_action( 'woocommerce_order_status_pending', array( $wc_gsheetconnector_service, 'gscwoo_pending' ) );
