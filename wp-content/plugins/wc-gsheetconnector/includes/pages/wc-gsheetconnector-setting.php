<?php
// Get selected Sheet
$selected_sheet_key = get_option( 'gs_woo_settings' );


// Get all sheet details of the connected account
$sheet_data = get_option( 'gs_woo_sheet_feeds' );



// Get order states/ Tab names
$selected_order_states = get_option( 'gscwc_order_states' );


$woo_service = new wc_gsheetconnector_Service();

$adding_extra_order_row = $woo_service->get_adding_extra_order_row();
$adding_extra_product_item_row = $woo_service->get_adding_extra_product_item_row();

?>
<?php
// Check if the user is authenticated
   $authenticated = get_option('gs_woo_token');
  
   $per = get_option('gs_woo_verify');
   $per_msg = __( 'invalid-auth', 'wc-gsheetconnector' );
   // check user is authenticated when save existing api method
  $show_setting = 0;
      
 if ((!empty($authenticated) && $per == "valid") ) {
    $show_setting = 1;
}
else{
 ?>
 <p class="wc-display-note">
        <?php 
        echo __( '<strong>Authentication Required:</strong>
              You must have to <a href="admin.php?page=wc-gsheetconnector-config&tab=integration" target="_blank">Authenticate using your Google Account</a> along with Google Drive and Google Sheets Permissions in order to enable the settings for configuration.', 'wc-gsheetconnector' );
        ?>
       
    </p>
 <?php 
}

if($show_setting == 1){
  ?>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <form method="post" id="gsSettingFormFree">

        <div class="gs-woo-fields">
            <h2>
                <span
                    class="title11"><?php echo esc_html( __( 'WooCommerce Google Sheet Settings', 'wc-gsheetconnector' ) ); ?></span>

            </h2>
            <hr>
            </br>

            <div class="gs-woo-in-fields">
                <div class="sheet-details <?php echo esc_html($class,'wc-gsheetconnector'); ?>">
                    <p>
                        <label><?php echo esc_html( __( 'Google Sheet Name', 'wc-gsheetconnector' ) ); ?></label>
                        <select name="gs-woo-sheet-id" id="gs-woo-sheet-id">
                            <option value=""><?php echo __( 'Select', 'wc-gsheetconnector' ); ?></option>

                            <?php
    							if ( ! empty( $sheet_data ) ) {
    								foreach ( $sheet_data as $key => $value ) {
    									$selected = "";
    									if ( $selected_sheet_key !== "" && $key == $selected_sheet_key ) {
    										$selected = "selected";
    									}
    									?>
                            <option value="<?php echo esc_html($key,'wc-gsheetconnector'); ?>"
                                <?php echo esc_html($selected,'wc-gsheetconnector'); ?>>
                                <?php echo esc_html($value['sheet_name'],'wc-gsheetconnector'); ?></option>
                            <?php
    								}
    							}
    						?>
                        </select>
                        <span class="error_msg" id="error_spread"></span>

                        <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
                            value="<?php echo wp_create_nonce( 'gs-ajax-nonce' ); ?>" />

                    </p>

                    <p class="sheet-url" id="sheet-url">
                        <?php $sheet_id	 = "";
    					
    					if ( ! empty( $selected_sheet_key ) ) {
    						$sheet_id	 = $selected_sheet_key; ?>
                        <label><?php echo __( 'Google Sheet URL', 'wc-gsheetconnector' ); ?></label>
                        <a href="https://docs.google.com/spreadsheets/d/<?php echo esc_html($sheet_id,'wc-gsheetconnector'); ?>"
                            target="_blank"><input type="button" id="viewsheet" name="viewsheet"
                                value="View Spreadsheet"></a>
                        <?php    
    					}
    					?>
                    </p>

                    <br />

                    <p class="gs-woo-sync-row">
                        <?php echo __( 'Not showing Sheet Name, and Sheet URL Link ? Then <a id="gs-woo-sync" data-init="no"> Click here </a> to fetch it. ', 'wc-gsheetconnector' ); ?><span
                            class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                </div>
            </div>
        </div>
        </br>

        <div class="gs-woo-tabs-set">
            <h2><span class="title1"><?php echo esc_html( __( 'Google Sheets/Tab Name ', 'wc-gsheetconnector' ) ); ?>
                </span>
            </h2>
            <hr>
           <!--  <span class="freeze_header_option">
                <input type="checkbox" name="checkAllWooSheet" id="checkAllWooSheet" class="check-toggle"
                    style="display: none;"> Check All
                <label for="checkAllWooSheet" class="button-woo-toggle"></label>
            </span> -->
            <span class="error_msg" id="error_gsTabName"></span>
            <br class="clear">

            <?php $order_state_list = $woo_service->status_and_sheets;
        
              foreach ( $order_state_list as $key => $state_name ) {
    			$order_state_checked = "";
    			if(!empty($selected_order_states)){
    				if ( in_array( $key, $selected_order_states ) ) {
    					$order_state_checked = "checked";
    				}
    			}
    			?>
            <div class="gs-woo-cards">
                <span class="woo-pointer">
                    <input type="checkbox" class="wc_order_state check-toggle" name="wc_order_state[]"
                        value="<?php echo esc_html($key,'wc-gsheetconnector'); ?>"
                        <?php echo esc_html($order_state_checked,'wc-gsheetconnector'); ?> id="<?php echo $key; ?>"
                        style="display: none;"><?php echo esc_html($state_name,'wc-gsheetconnector'); ?>
                    <label for="<?php echo $key; ?>" class="button-woo-toggle"></label>
                </span>
            </div>
            <?php } ?>

            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    All Orders <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
          
        </div>
        <br class="clear">
        <div class="woo-header1" hidden>
            <h2>
                <span class="title1"><?php echo esc_html( __( 'Headers ', 'wc-gsheetconnector' ) ); ?> </span>
            </h2>
            <hr>
            <br class="clear">
            <ul>
                <?php 
    		$header_list = $woo_service->sheet_headers;
    		foreach( $header_list as $header => $data ) { ?>
                <!-- <li class="li-woo-header"><label><?php echo esc_html($header,'wc-gsheetconnector'); ?></label></li> -->

                <li class="li-woo-header1">
                    <i class="fa fa-sort sort-icon1"></i>
                    <div class="switch-label1">
                        <label>
                            <span class='label1'>
                                <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                <div class="edit_col_name1"><span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                            class="fa fa-pencil"></i></div>
                        </label>
                    </div>


                    <div class="toggle-buttom-pos">
                        <span class="tooltip11"><span
                                class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                            <label for="<?php echo $header ?>-one"
                                class="button-woo-toggle1 button-tog-active product_headers-lbl"
                                id="button-woo-toggle1-click"></label>
                    </div>

                    </span>

                </li>
                <?php } ?>
            </ul>
        </div>
 <div class="gs-woo-google-set">
        <a class="gs-woo-list-set" data-id="12" href="#0">
            <p class="maxi_mize maxi_mize12"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
            <p class="mini_mize mini_mize12"><i class="fa fa-minus" aria-hidden="true"></i></p>
            <h2>
                <span
                    class="title1"><?php echo esc_html( __( ' Custom Order Status ', 'wc-gsheetconnector' ) ); ?>
                </span>
                  <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
                          <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector-pro' ) ); ?>
                </a>
            </h2>
        </a>
        <hr>
        </br>
        <!-- custom order status -->
        <div class="gs-woo-list-set12">
        

            <?php 

             $corder_statuses = wc_get_order_statuses();
            
             $wc_custom_order_status = array_diff($corder_statuses, ["Pending payment", "Processing","On hold","Completed","Cancelled","Refunded","Failed","Draft"]);
             if(!empty($wc_custom_order_status)){

                foreach ( $wc_custom_order_status as $key => $state_name ) {
                $width = 0;
                ?>
            <span class="gs-woo-cards1" <?php echo ($width == "1") ? "style='width:20%'" : "" ?>>
                <span class="woo-pointer">
                    <?php echo $state_name; ?>
                    <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                  
                </span>
            </span>
            <?php
             }
            
            }
            else{
             ?>
               <h4 style="margin-left: 40%;">
                <?php echo esc_html( __( "No Custom Orders found in your WooCommerce Store", 'wc-gsheetconnector' ) ); ?>
             </h4>
             <?php 
            }
            ?>
        </div>
    </div>

    <br class="clear">

    <div class="gs-woo-google-set">
        <a class="gs-woo-list-set" data-id="13" href="#0">
            <p class="maxi_mize maxi_mize13"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
            <p class="mini_mize mini_mize13"><i class="fa fa-minus" aria-hidden="true"></i></p>
            <h2>
                <span
                    class="title1"><?php echo esc_html( __( ' Other Sheet Tabs to Enable ', 'wc-gsheetconnector' ) ); ?>
                </span>
                 <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
                          <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?>
                        </a>
            </h2>
        </a>
        <hr>
        </br>
        <!-- Other Sheet Tabs to Enable -->
        <div class="gs-woo-list-set13">
         
  <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    All Products <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    All Products Variation<label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    All Customers <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    All Coupons <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
             <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
            <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    All Subscriptions <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>
                </span>
            </div>
             <?php } ?>
              
        </div>
 </div>

    <br class="clear">
        <!-- product category filter start-->
        <div class="gs-woo-google-set">
                <a class="gs-woo-list-set" data-id="7" href="#0">
                    <p class="maxi_mize maxi_mize7"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
                    <p class="mini_mize mini_mize7"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span
                            class="title1"><?php echo esc_html( __( 'Product Category Filter:', 'wc-gsheetconnector' ) ); ?>
                        </span>
                        <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
                          <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?>
                        </a>
                    </h2>
                </a>
                <hr>
                </br>
                <?php 
                // get all product categories
                $product_categories = get_terms('product_cat', array(
                      'orderby' => 'name',
                      'order'   => 'ASC',
                      'hide_empty' => false
                      ));


               if (!empty($product_categories)) {
                ?>
               <div class="gs-woo-list-set7">
                <div class="gs-woo-cards1">
                <span class="woo-pointer">
                    Select All Category
                      <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>


                  
                </span>
            </div>
                    <!-- <span class="error_msg" id="error_gsTabName"></span> -->
                    <br class="clear">

                    <?php 
                     foreach ( $product_categories as $key => $category ) {
                      ?>
                         <div class="gs-woo-cards1">
                <span class="woo-pointer">
                     <?php echo $category->name; ?>
                      <label for="pro" class="button-woo-toggle tooltip11">
                        <span class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                    </label>


                  
                </span>
            </div>
                  
                    <?php } 

                    ?>
                </div>
            <?php } ?>
        </div>
        <hr>

        <div id="gform_setting_gsheet_field_maps" class="gform-settings-field gform-settings-field__map_form_fields"
            titlea="Upgrade to Pro">
            <!-- upgrade to pro Ahmed 7-6-2023-->
            <!-- <hr style="color: #000; background-color: #CCCCCC; height: 2px; width: 100%; margin: 20px auto;"> -->

            <!-- <div class="gs-woo-header-set"> -->
            <div class="gs-woo-google-set">

                <a class="gs-woo-list-set" data-id="3" href="#0">
                    <p class="maxi_mize maxi_mize3"><i class="fa fa-plus" aria-hidden="true"></i></i></p>
                    <p class="mini_mize mini_mize3"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span
                            class="title1"><?php echo esc_html( __( 'Google Sheet Headers (Column Name) ', 'wc-gsheetconnector' ) ); ?>
                        </span>
                        <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
                          <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?>
                        </a>

                    </h2>
                </a>
                <!-- </div> -->
                <!-- <hr style="color: #000; background-color: #CCCCCC; height: 2px; width: 100%; margin: 20px auto;"> -->

                <hr>
                <div class="woo-header-wrapper gs-woo-list-set3">

                    <div class="tabs-gs-back">
                        <div class="tabs-gs">

                            <a class="gs-woo-list-set active-t-gs" data-id="31" href="#0">Orders Header</a>
                            <a class="gs-woo-list-set" data-id="32" href="#0">Products Header</a>
                            <a class="gs-woo-list-set" data-id="34" href="#0">Product Variation Header</a>
                            <a class="gs-woo-list-set" data-id="33" href="#0">Customers Header</a>
                            <a class="gs-woo-list-set" data-id="35" href="#0">Coupons Header</a>
                            <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
                            <a class="gs-woo-list-set" data-id="36" href="#0">Subscriptions Header</a>
                            <?php } ?>
                        </div>
                    </div>
                    <br class="clear">
                    <div class="woo-header-wrapper gs-woo-list-set31" id="extra-field">
                        <div class="checkallmaindiv">
                            <div class="extra-all-main">
                                <table class="table table-light adding_extra_table">
                                    <tbody>
                                        <tr>
                                            <td><label class="check-all-lbl">Extra Header Related To Order</label></td>
                                            <td>
                                                <select class="adding_extra_order_row adding_extra_css"
                                                    id="adding_extra_order_row">
                                                    <option value="">--Select--</option>
                                                    <?php if(!empty($adding_extra_order_row)){
                                                        foreach ($adding_extra_order_row as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled>
                                                        <?php echo $value ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled>Label</label></td>
                                            <td>
                                                <input type="text" name="ext_row_label_order" id="ext_row_label_order"
                                                    class="ext_row_label_order" disabled />
                                            </td>
                                            <td><button type="button" id="btn_extra_order_row"
                                                    class="btn_extra_order_row tooltip11">
                                                    Add New Extra Fields
                                                    <span class="tooltiptext11">Upgrade To Pro</span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-extra-order-row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label class="check-all-lbl">Extra Header Related To Order's Product</label>
                                            </td>
                                            <td>
                                                <select class="adding_extra_product_item_row adding_extra_css"
                                                    id="adding_extra_product_item_row">
                                                    <option value="">--Select--</option>
                                                    <?php if(!empty($adding_extra_product_item_row)){
                                                        foreach ($adding_extra_product_item_row as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled
                                                        >
                                                        <?php echo $value ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled>Label</label></td>
                                            <td><input type="text" name="ext_row_label_order_item_row"
                                                    id="ext_row_label_order_item_row" class="ext_row_label_order_item_row"
                                                    disabled />
                                            </td>
                                            <td><button type="button" id="btn_extra_order_item_row"
                                                    class="btn_extra_order_item_row tooltip11">
                                                    Add New Extra Fields
                                                    <span class="tooltiptext11">Upgrade To Pro</span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-extra-order-item-row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>


                                         <tr>
                                            <td><label class="check-all-lbl">Custom Static Headers</label>
                                            </td>
                                            <td>
                                                <?php 
                                         $adding_custom_static_headers = array('ip_address' => 'IP Address','site_name'=> 'Site Name','site_url'=> 'Site URL','site_admin_email'=>'Site Admin Email','site_description'=>'Site Description','user_agent'=> 'User Agent','user_name'=> 'User Name','user_login'=>'User Login','user_email'=>'User Email');

                                        ?>
                                                <select class="adding_custom_static_headers adding_extra_css"
                                                    id="adding_custom_static_headers">
                                                    <option value="">--Select--</option>
                                                    <?php if(!empty($adding_custom_static_headers)){
                                                        foreach ($adding_custom_static_headers as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled
                                                      >
                                                        <?php echo $value ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled>Label</label></td>
                                            <td><input type="text" name="ext_row_custom_static_headers"
                                                    id="ext_row_custom_static_headers" class="ext_row_custom_static_headers"
                                                    disabled />
                                            </td>
                                            <td><button type="button" id="btn_custom_static_headers"
                                                    class="btn_custom_static_headers tooltip11">
                                                    Add New Custom Static Headers
                                                    <span class="tooltiptext11">Upgrade To Pro</span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-custom-static-headers">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>

                                    <tr>
                                            <td><label class="check-all-lbl">Custom Static Blank Headers</label>
                                            </td>
                                            <td>
                                                <?php 
                                         $adding_custom_static_blank_headers = array('blank1' => 'Blank1','blank2'=> 'Blank2','blank3'=> 'Blank3','blank4'=>'Blank4','blank5'=>'Blank5','blank6'=> 'Blank6','blank7'=> 'Blank7','blank8'=>'Blank8','blank9'=>'Blank9','blank10'=>'Blank10');

                                        ?>
                                                <select class="adding_custom_static_blank_headers adding_extra_css"
                                                    id="adding_custom_static_blank_headers">
                                                    <option value="">--Select--</option>
                                                    <?php if(!empty($adding_custom_static_blank_headers)){
                                                        foreach ($adding_custom_static_blank_headers as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $value ?>" disabled
                                                      >
                                                        <?php echo $value ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><label class="check-all-lbl" disabled>Label</label></td>
                                            <td><input type="text" name="ext_row_custom_blank_headers"
                                                    id="ext_row_custom_blank_headers" class="ext_row_custom_blank_headers"
                                                    disabled />
                                            </td>
                                            <td><button type="button" id="btn_custom_blank_headers"
                                                    class="btn_custom_blank_headers tooltip11">
                                                    Add New Custom Static Blank Headers
                                                    <span class="tooltiptext11">Upgrade To Pro</span>
                                                </button>
                                            </td>
                                            <td>
                                                <span
                                                    class="loading-btn-custom-blank-headers">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="checked-all-div">
                                <label class="check-all-lbl">Check All</label>
                                <span class="tooltip11"><span
                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>


                                    <!-- Toggle button -->
                                    <label class="button-woo-toggle1 sheet_headers-order button-tog-inactive"
                                        id="button-woo-toggle1-click" data-id="sheet_headers-"
                                        style="float: left;margin-top: 5px;"></label>

                                    <!-- <label class="button-woo-toggle1 sheet_headers-order" id="button-woo-toggle1-click" data-id="sheet_headers-" style="float: left;margin-top: 5px;"></label> -->
                                    <!-- Toggle button -->
                            </div>
                        </div>
                        <ul class="woo-header">
                            <ul>
                                <?php 
    						$header_list = $woo_service->sheet_headers;
    						foreach( $header_list as $header => $data ) { ?>
                                <!-- <li class="li-woo-header"><label><?php echo esc_html($header,'wc-gsheetconnector'); ?></label></li> -->

                                <li class="li-woo-header1">
                                    <i class="fa fa-sort sort-icon1"></i>
                                    <div class="switch-label1">
                                        <label>
                                            <span class='label1'>
                                                <div class='label_text1'>
                                                    <?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                                <div class="edit_col_name1"><span class="tooltip11"><span
                                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                            class="fa fa-pencil"></i></div>
                                        </label>
                                    </div>


                                    <div class="toggle-buttom-pos">
                                        <span class="tooltip11"><span
                                                class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                            <label for="<?php echo $header ?>-one"
                                                class="button-woo-toggle1 button-tog-active product_headers-lbl"
                                                id="button-woo-toggle1-click"></label>
                                    </div>

                                    </span>

                                </li>
                                <?php } ?>

                                <?php 
    						$header_list_pro = $woo_service->sheet_headers_pro;
    						foreach( $header_list_pro as $header  ) { ?>
                                <!-- <li class="li-woo-header"><label><?php echo esc_html($header,'wc-gsheetconnector'); ?></label></li> -->

                                <li class="li-woo-header1">
                                    <i class="fa fa-sort sort-icon1"></i>
                                    <div class="switch-label1">
                                        <label>
                                            <span class='label1'>
                                                <div class='label_text1'>
                                                    <?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                                <div class="edit_col_name1"><span class="tooltip11"><span
                                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                            class="fa fa-pencil"></i></div>
                                        </label>
                                    </div>


                                    <div class="toggle-buttom-pos">
                                        <span class="tooltip11"><span
                                                class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                            <label for="<?php echo $header ?>-one"
                                                class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                                id="button-woo-toggle1-click"></label>
                                    </div>

                                    </span>

                                </li>
                                <?php } ?>
                                <!-- Toggle button -->
                                <div class="remove_col_name">
                                </div>
                                </li>
                                <!-- order header -->
                                <li class="li-woo-header li-woo-header-order remove-currency ui-sortable-handle">
                                    <i class="fa fa-sort sort-icon"></i>


                                </li>
                            </ul>
                    </div>
                    <!-- 32 product headers -->
                    <div class="woo-header-wrapper gs-woo-list-set32" style="opacity: 0.6;">
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl">Check All</label>
                                <input type="radio" id="product_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="product_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 product_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="product_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <?php 
    						$header_list_pro2 = $woo_service->product_headers_pro;
    						foreach( $header_list_pro2 as $header  ) { ?>
                        <!-- <li class="li-woo-header"><label><?php echo esc_html($header,'wc-gsheetconnector'); ?></label></li> -->

                        <li class="li-woo-header1">
                            <i class="fa fa-sort sort-icon1"></i>
                            <div class="switch-label1">
                                <label>
                                    <span class='label1'>
                                        <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?></div>
                                        <div class="edit_col_name1"><span class="tooltip11"><span
                                                    class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                    class="fa fa-pencil"></i></div>
                                </label>
                            </div>


                            <div class="toggle-buttom-pos">
                                <span class="tooltip11"><span
                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                    <label for="<?php echo $header ?>-one"
                                        class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                        id="button-woo-toggle1-click"></label>
                            </div>

                            </span>

                        </li>
                        <?php } ?>

                        <!-- Toggle button -->
                        <div class="toggle-buttom-pos">
                            <label class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                id="button-woo-toggle1-click" data-id="prod_external_link-"></label>
                        </div>
                        <!-- Toggle button -->


                        <input type="radio" id="prod_external_link-one" name="product_headers[prod_external_link]" value="1"
                            class="header_name_1 product_headers-one radio-btn-hide">

                        <input type="radio" id="prod_external_link-two" name="product_headers[prod_external_link]" value="0"
                            checked="" class="header_name_0 product_headers-two radio-btn-hide">

                        </li>
                        </ul>
                    </div>
                    <!-- 33 ahmed -->
                    <div class="woo-header-wrapper gs-woo-list-set33" style="opacity: 0.6;">
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl">Check All</label>
                                <input type="radio" id="customer_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="customer_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 customer_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="customer_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
    						$header_list_pro3 = $woo_service->customer_headers_pro;
    						foreach( $header_list_pro3 as $header  ) { ?>
                            <!-- <li class="li-woo-header"><label><?php echo esc_html($header,'wc-gsheetconnector'); ?></label></li> -->

                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- 34 ahmed -->
                    <div class="woo-header-wrapper gs-woo-list-set34" style="opacity: 0.6;">
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl">Check All</label>
                                <input type="radio" id="customer_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="customer_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 customer_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="customer_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
                            $header_list_pro4 = $woo_service->product_variations_headers_pro;
                            foreach( $header_list_pro4 as $header  ) { ?>
                            <!-- <li class="li-woo-header"><label><?php echo esc_html($header,'wc-gsheetconnector'); ?></label></li> -->

                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive product_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                   <!-- coupons header -->

                    <div class="woo-header-wrapper gs-woo-list-set35" style="opacity: 0.6;">
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl">Check All</label>
                                <input type="radio" id="coupon_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="coupon_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 coupon_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="coupon_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
                            $header_list_pro5 = $woo_service->coupons_headers_pro;
                            foreach( $header_list_pro5 as $header  ) { ?>
                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive coupon_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                   <!-- subscriptions header -->
            <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
                    <div class="woo-header-wrapper gs-woo-list-set36" style="opacity: 0.6;">
                        <div class="checkallmaindiv">
                            <div class="checked-all-div">
                                <label class="check-all-lbl">Check All</label>
                                <input type="radio" id="subscription_headers-one" name="switch-one" class="radio-btn-hide"
                                    value="yes" checked="">
                                <input type="radio" id="subscription_headers-two" name="switch-one" class="radio-btn-hide"
                                    value="no">

                                <!-- Toggle button -->
                                <label class="button-woo-toggle1 subscription_headers-order button-tog-inactive"
                                    id="button-woo-toggle1-click" data-id="subscription_headers-"
                                    style="float: left;margin-top: 5px;"></label>
                                <!-- Toggle button -->
                            </div>

                        </div>
                        <ul class="woo-header ui-sortable">
                            <?php 
                            $header_list_pro6 = $woo_service->subscriptions_headers_pro;
                            foreach( $header_list_pro6 as $header  ) { ?>
                            <li class="li-woo-header1">
                                <i class="fa fa-sort sort-icon1"></i>
                                <div class="switch-label1">
                                    <label>
                                        <span class='label1'>
                                            <div class='label_text1'><?php echo esc_html($header,'wc-gsheetconnector'); ?>
                                            </div>
                                            <div class="edit_col_name1"><span class="tooltip11"><span
                                                        class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span><i
                                                        class="fa fa-pencil"></i></div>
                                    </label>
                                </div>


                                <div class="toggle-buttom-pos">
                                    <span class="tooltip11"><span
                                            class="tooltiptext11"><?php _e('Upgrade To Pro', 'wc-gsheetconnector'); ?></span>
                                        <label for="<?php echo $header ?>-one"
                                            class="button-woo-toggle1 button-tog-inactive subscription_headers-lbl"
                                            id="button-woo-toggle1-click"></label>
                                </div>

                                </span>

                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <hr style="color: #000; background-color: ##f0eeef; height: 2px; width: 100%; margin: 20px auto;">

            <!-- sorting -->

            <br class="clear">

            <!-- <hr style="color: #000; background-color: #CCCCCC; height: 2px; width: 100%; margin: 20px auto;"> -->

            <div class="gs-woo-header-set" style="padding-top:5px">
                <a class="gs-woo-list-set" data-id="6" href="#0">
                    <p class="maxi_mize maxi_mize6"><i class="fa fa-plus" aria-hidden="true"></i></p>
                    <p class="mini_mize mini_mize6"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span class="title1">WooCommerce orders row's management</span>
                        <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
                          <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?>
                        </a>
                    </h2>
                </a>
                <hr>
                <br>
                <div class="gs-woo-list-set6">

                    <div class="gs-woo-op-wise">
                        <label style="font-weight: bold;"> Manage row's by :</label>

                        <span class="woo-pointer">
                            <input type="radio" name="order_wise_product_wise" value="productwise" id="product_wise">
                            <label>Product Wise :</label>

                            <input type="radio" name="order_wise_product_wise" value="orderwise" id="order_wise" checked="">

                            <label>Order Wise :</label>

                        </span>
                    </div>
                    <div class="note_orderwise">
                        <p class="notes">Notes :</p>
                        <div class="message">
                            <p>
                                <i>Order-Wise:</i>
                                Single Entry will be saved in Google Sheet!
                            </p>
                            <p>
                                <i>Product Wise:</i>
                                Each Entry will be shown product wise with same Order ID, if multiple products are there
                                in
                                order
                            </p>
                        </div>
                    </div>
                    <br>
                    <div class="gs-woo-op-wise">
                        <label style="font-weight: bold;"> Sorting :</label>

                        <span class="woo-pointer">
                            <input type="radio" name="asc_desc_sorting" value="ASC" id="asc_sorting" checked="">
                            <label>Ascending :</label>

                            <input type="radio" name="asc_desc_sorting" value="DESC" id="desc_sorting">

                            <label>Descending :</label>

                        </span>
                    </div>

                </div>
            </div>
            <hr>

            <br>
            <hr style="color: #000; background-color: ##f0eeef; height: 2px; width: 100%; margin: 20px auto;">
            <!-- color -->
            <div class="gs-woo-google-set">
                <a class="gs-woo-list-set" data-id="4" href="#0">
                    <p class="maxi_mize maxi_mize4"><i class="fa fa-plus" aria-hidden="true"></i></p>
                    <p class="mini_mize mini_mize4"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span class="title1">Google Sheet Settings.</span>
                        <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
                          <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?>
                        </a>
                    </h2>
                </a>
                <hr>
                <br class="clear">
                <div class="gs-woo-list-set4">
                    <div class="freez_order_sort">
                        <div class="">
                            <label style="font-weight: bold;">Freeze Header :</label>
                            <span class="woo-pointer">
                                <input type="checkbox" name="freeze_header" value="true" class="check-toggle"
                                    id="freeze_header" style="display: none;">

                                <label for="freeze_header" class="button-woo-toggle"></label>
                            </span>
                     <br class="clear">
                         <hr>
                         <label style="font-weight: bold;">Background Color : </label><br>
                    <div class="gs-woo-cards">
                        <label>Header Row : </label>
                        <span class="woo-pointer">
                            <input type="color" name="gs_woo_header_color" value="#ffffff">
                        </span>
                    </div>
                    <div class="gs-woo-cards">
                        <label>Odd Rows :</label>
                        <span class="woo-pointer">
                            <input type="color" name="gs_woo_odd_color" value="#ffffff">
                        </span>
                    </div>
                    <div class="gs-woo-cards">
                        <span class="woo-pointer">
                            <label>Even Rows :</label>
                            <input type="color" name="gs_woo_even_color" value="#ffffff">
                        </span>
                    </div>
                    </div>
                        </div>
                         
                    

                   
                    <br class="clear">
                </div>
            </div>

            <!--  -->
            <hr>
            <div class="gs-woo-google-syc-set1">
                <a class="gs-woo-list-set" data-id="5" href="#0">
                    <p class="maxi_mize maxi_mize5"><i class="fa fa-plus" aria-hidden="true"></i></p>
                    <p class="mini_mize mini_mize5"><i class="fa fa-minus" aria-hidden="true"></i></p>
                    <h2>
                        <span class="title1">Google Sheet Sync</span>
                        <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
                          <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?>
                        </a>
                    </h2>
                </a>
                <hr>
                <br class="clear">
                <div class="gs-woo-list-set5">
                    <div class=" sync-card">
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele">Sync Orders </label>
                                <select name="asc_desc_order" id="asc_desc_order" class="design-syn-ele">
                                    <option value="ASC">
                                        Ascending</option>
                                    <option value="DESC">
                                        Descending</option>
                                </select>

                                <label class="design-syn-ele">From Date :</label>
                                <input type="date" name="sync_all_fromdate" id="sync_all_fromdate" class="design-syn-ele">
                                <label class="design-syn-ele">To Date :</label>
                                <input type="date" name="sync_all_todate" id="sync_all_todate" class="design-syn-ele">
                                <label class="design-syn-ele">Sync Orders </label>
                                <select name="asc_desc_order" id="asc_desc_order" class="design-syn-ele">
                                    <option value="ASC">
                                        All</option>
                                </select>
                                <button type="button" class="button button_primary sync-orders sync-btn design-syn-ele"
                                    data-type="all">Sync Orders <img class="sync-loader-orders"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>

                            </span>
                            <span id="synctext"></span>
                            <span class="sync-message-orders sync-message" style="display:block"></span>
                        </div>

                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele">Sync Products</label>
                                <select name="asc_desc_pro" id="asc_desc_pro" class="design-syn-ele">
                                    <option value="ASC" selected="">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                                <label class="design-syn-ele">From Date :</label>
                                <input type="date" name="sync_all_fromdate_pro" id="sync_all_fromdate_pro"
                                    class="design-syn-ele">
                                <label class="design-syn-ele">To Date :</label>
                                <input type="date" name="sync_all_todate_pro" id="sync_all_todate_pro"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-products sync-btn design-syn-ele"
                                    data-type="wc-products">Sync Products <img class="sync-loader-products"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span id="synctext-product"></span>
                            <span class="sync-message-products sync-message" style="display:block"></span>
                        </div>
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele">Sync Products Variation</label>
                                <select name="asc_desc_cus" id="asc_desc_cus" class="design-syn-ele">
                                    <option value="ASC" selected="">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                                <label class="design-syn-ele">From Date :</label>
                                <input type="date" name="sync_all_fromdate_cus" id="sync_all_fromdate_cus"
                                    class="design-syn-ele">
                                <label class="design-syn-ele">To Date :</label>
                                <input type="date" name="sync_all_todate_cus" id="sync_all_todate_cus"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-customers sync-btn design-syn-ele"
                                    data-type="wc-customers">Sync Products Variation <img class="sync-loader-customers"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-customers sync-message" style="display:block"></span>
                        </div>

                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele">Sync Customers</label>
                                <select name="asc_desc_cus" id="asc_desc_cus" class="design-syn-ele">
                                    <option value="ASC" selected="">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                                <label class="design-syn-ele">From Date :</label>
                                <input type="date" name="sync_all_fromdate_cus" id="sync_all_fromdate_cus"
                                    class="design-syn-ele">
                                <label class="design-syn-ele">To Date :</label>
                                <input type="date" name="sync_all_todate_cus" id="sync_all_todate_cus"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-customers sync-btn design-syn-ele"
                                    data-type="wc-customers">Sync Customers <img class="sync-loader-customers"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-customers sync-message" style="display:block"></span>
                        </div>
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele">Sync Coupons</label>
                                <select name="asc_desc_coupons" id="asc_desc_coupons" class="design-syn-ele">
                                    <option value="ASC" selected="">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                                <label class="design-syn-ele">From Date :</label>
                                <input type="date" name="sync_all_fromdate_coupons" id="sync_all_fromdate_coupons"
                                    class="design-syn-ele">
                                <label class="design-syn-ele">To Date :</label>
                                <input type="date" name="sync_all_todate_coupons" id="sync_all_todate_coupons"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-coupons sync-btn design-syn-ele"
                                    data-type="wc-coupons">Sync Coupons <img class="sync-loader-coupons"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-coupons sync-message" style="display:block"></span>
                        </div>

                     <?php if (is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php')) { ?>
                        <div class="gs-woo-syn-btn">
                            <span class="woo-pointer">
                                <label class="design-syn-ele">Sync Subscriptions</label>
                                <select name="asc_desc_subscription" id="asc_desc_subscription" class="design-syn-ele">
                                    <option value="ASC" selected="">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                                <label class="design-syn-ele">From Date :</label>
                                <input type="date" name="sync_all_fromdate_subscription" id="sync_all_fromdate_subscription"
                                    class="design-syn-ele">
                                <label class="design-syn-ele">To Date :</label>
                                <input type="date" name="sync_all_todate_subscription" id="sync_all_todate_subscription"
                                    class="design-syn-ele">

                                <button type="button" class="button button_primary sync-subscription sync-btn design-syn-ele"
                                    data-type="wc-subscription">Sync Subscriptions <img class="sync-loader-subscriptions"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button>
                            </span>
                            <span class="sync-message-subscriptions sync-message" style="display:block"></span>
                        </div>
                    <?php } ?>

                    </div>
                    <div class="download-card">
                        <!-- dropdown select tab name -->
                        <div class="gs-woo-download-drop" style="display:none;">
                            <select name="gs-woo-download-tab" id="gs-woo-download-tab">

                                <option value="all_entire_sheet_tabs" style="font-weight: bold;" selected=""> </option>
                            </select>
                        </div>
                        <!-- dropdown select tab name -->
                        <div class="gs-woo-download-btn" style="padding: 5px;">
                            <span class="woo-pointer">
                                <button type="button" class="button button_primary download-orders download-btn"
                                    data-type="all" data-url="https://docs.google.com/spreadsheets/d/"
                                    data-sheet_id="">Download Spreadsheet
                                    <img class="download-loader"
                                        src="<?php echo WC_GSHEETCONNECTOR_URL . '/assets/img/ajax-loader.gif' ?>"
                                        style="display:none;"></button><span>(You can download connected Google Spreadsheet )</span>
                            </span>
                        </div>
                        <div class="gs-woo-download-msg">
                            <span class="download-message"></span>
                        </div>
                    </div>
                    <br class="clear">
                </div>
            </div>
        </div>
        </h2>

        <hr style="color: #000; background-color: #CCCCCC; height: 2px; width: 100%; margin: 20px auto;">
          <input type="hidden" name="gs-woo-nonce" id="gs-woo-nonce"
            value="<?php echo wp_create_nonce('gs-woo-nonce'); ?>" />
        <input type="submit" value="Submit Data" id="woo-save-btn" class="woo-save-btn" name="woo-save-btn">
    </form>
<?php
}
?>




