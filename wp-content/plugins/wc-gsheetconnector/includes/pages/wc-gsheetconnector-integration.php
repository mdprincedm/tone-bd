<?php

$Code = "";
$header = admin_url('admin.php?page=wc-gsheetconnector-config');
if (isset($_GET['code'])) {
    update_option('is_new_client_secret_woogsc', 1);
    if (is_string($_GET['code'])) {
      $Code = sanitize_text_field($_GET["code"]);
    }
}

?>
<!-- save code, alert and css -->
<input type="hidden" name="redirect_auth" id="redirect_auth"
    value="<?php echo (isset($header)) ? esc_attr($header) : ''; ?>">
<div class="card-wp">
    <div class="gs-woo-in-fields">
        <h2>
            <span class="title1"><?php echo __('WooCommerce - '); ?></span>
            <span class="title"><?php echo __('Google Sheets Integration'); ?></span>
        </h2>
        <hr>
        <?php if (empty($Code)) { ?>
            <div class="wpform-gs-alert-kk" id="google-drive-msg">
                <p class="wpform-gs-alert-heading">
                    <?php echo esc_html__('Authenticate with your Google account, follow these steps:', 'wc-gsheetconnector'); ?>
                </p>
                <ol class="wpform-gs-alert-steps">
                    <li><?php echo esc_html__('Click on the "Sign In With Google" button.', 'wc-gsheetconnector'); ?></li>
                    <li><?php echo esc_html__('Grant permissions for the following:', 'wc-gsheetconnector'); ?>
                        <ul class="wpform-gs-alert-permissions">
                            <li><?php echo esc_html__('Google Drive', 'wc-gsheetconnector'); ?></li>
                            <li><?php echo esc_html__('Google Sheets', 'wc-gsheetconnector'); ?></li>
                        </ul>
                        <p class="wpform-gs-alert-note">
                            <?php echo esc_html__('Ensure that you enable the checkbox for each of these services.', 'wc-gsheetconnector'); ?>
                        </p>
                    </li>
                    <li><?php echo esc_html__('This will allow the integration to access your Google Drive and Google Sheets.', 'wc-gsheetconnector'); ?>
                    </li>
                </ol>
            </div>
            <?php } ?>
        
        <p>
            <label style="/* color: #1d9838; *//* font-size: 14px; */color: #242628;font-size: 14px;font-weight: 600;line-height: 2.3;">
                Google Access Code </label>

            <?php if (!empty(get_option('gs_woo_token')) && get_option('gs_woo_token') !== "") { ?>
            <input type="text" name="gs-woo-code" id="gs-woo-code" value=""
                placeholder="<?php echo __('Currently Active', 'wc-gsheetconnector'); ?>" disabled />
            <input type="button" name="gs-woo-deactivate-log" id="gs-woo-deactivate-log"
                value="<?php echo __('Deactivate', 'wc-gsheetconnector'); ?>" class="button button-primary" />
            <span class="tooltip">
                <img src="<?php echo WC_GSHEETCONNECTOR_URL; ?>assets/img/help.png" class="help-icon">
                <span class="tooltiptext tooltip-right">
                    <?php _e('On deactivation, all your data saved with authentication will be removed and you need to reauthenticate with your Google account and configure sheet name and tab.', 'wc-gsheetconnector'); ?>
                </span>
            </span>
            <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <?php } else {
                $redirct_uri = admin_url('admin.php?page=wc-gsheetconnector-config');
            ?>
            <input type="text" name="gs-woo-code" id="gs-woo-code" value="<?php echo esc_attr($Code); ?>" readonly placeholder="<?php echo esc_html__('Click on Sign In With Google', 'wc-gsheetconnector'); ?>" oncopy="return false;" onpaste="return false;" oncut="return false;" />
            <?php if (empty($Code)) { ?>
                <a href="https://oauth.gsheetconnector.com/index.php?client_admin_url=<?php echo $redirct_uri; ?>&plugin=woocommercegsheetconnector"
                    class="button_woogsc">
                    <img src="<?php echo WC_GSHEETCONNECTOR_URL ?>/assets/img/btn_google_signin_dark_pressed_web.png">
                </a>
             <?php } ?>
            <?php } ?>
            <br>
            <?php if (!empty($_GET['code'])) { ?>
            <button type="button" name="save-gs-woo-code" id="save-gs-woo-code">Save & Authenticate</button>
            <?php } ?>
            <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </p>

        
        <span id="deactivate-msg"></span>

        <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
            value="<?php echo wp_create_nonce('gs-ajax-nonce'); ?>" />

           <?php
            //resolved - google sheet permission issues - START
            $gs_woo_verify = get_option('gs_woo_verify');
            if (!empty($gs_woo_verify) && $gs_woo_verify == "invalid-auth") {
            ?>
            
            <p style="color:#c80d0d; font-size: 14px; border: 1px solid;padding: 8px;">
                <?php echo 'Something went wrong! It looks you have not given the permission of Google Drive and Google Sheets from your google account.Please Deactivate Auth and Re-Authenticate again with the permissions.'; ?></p>
            <p style="color:#c80d0d;border: 1px solid;padding: 8px;"><img width="350px"
                    src="<?php echo WC_GSHEETCONNECTOR_URL; ?>assets/img/permission_screen.png"></p>
            <p style="color:#c80d0d; font-size: 14px; border: 1px solid;padding: 8px;">
                <?php echo esc_html(__('Also,', 'wc-gsheetconnector')); ?><a href="https://myaccount.google.com/permissions"
                    target="_blank"> <?php echo esc_html(__('Click Here ', 'wc-gsheetconnector')); ?></a>
                <?php echo esc_html(__(' and if it displays "GSheetConnector for WooCommerce" under Third-party apps with account access then remove it.', 'wc-gsheetconnector')); ?>
            </p>
            <?php
            } // Close the if condition
            //resolved - google sheet permission issues - END
            else {
                if (!empty(get_option('gs_woo_token')) && get_option('gs_woo_token') !== "") {
                    $google_sheet = new GSCWOO_googlesheet();
                    $email_account = $google_sheet->gsheet_print_google_account_email();
                    if ($email_account) {
                    ?>
                    <p class="connected-account">
                        <?php printf(__('Connected Email Account:   <u>%s </u>', 'wc-gsheetconnector'), $email_account); ?>
                    </p>
                    <?php
                    } else {
                    ?>
                    <p style="color:red">
                        <?php echo esc_html(__('Something went wrong! Your Auth code may be wrong or expired. Please Deactivate and Re-Authenticate.', 'wc-gsheetconnector')); ?>
                    </p>
                    <?php
                    }
                }
            }
            ?> 
            <?php 
            //resolved - google sheet permission issues - START
            if(!empty(get_option('gs_woo_verify')) && (get_option('gs_woo_verify') =="valid")){ ?>
                <p class="gs-woo-sync-row">
                    <?php echo __('<a id="gs-woo-sync" data-init="yes">Click here </a> to fetch sheets detail for "WooCommerce Data Settings" tab.', 'wc-gsheetconnector'); ?>
                    <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </p>
            <?php } 
            //resolved - google sheet permission issues - END
            ?> 
            <p>
                <label><?php echo __('Debug Log ->', 'wc-gsheetconnector'); ?></label>
                <button class="wcgsc-logs">View</button>
                <!-- <label><a href="<?php echo plugins_url('/logs/log.txt', __FILE__); ?>" target="_blank"
                    class="gs-woo-debug-view"><?php echo __('View', 'wc-gsheetconnector'); ?></a></label> -->
                <label><a class="debug-clear"><?php echo __('Clear', 'wc-gsheetconnector'); ?></a></label><span
                        class="clear-loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <p id="gs-woo-validation-message"></p>    
            </p>
            <!-- display content error logs -->
          <div class="wc-system-Error-logs" >
           <div class="wcdisplayLogs">
                <?php
                    $wcexistDebugFile = get_option('wcfgs_debug_log_file');
                    // check if debug unique log file exist or not
                    if (!empty($wcexistDebugFile) && file_exists($wcexistDebugFile)) {
                      $displaywcfreeLogs =  nl2br(file_get_contents($wcexistDebugFile));
                    if(!empty($displaywcfreeLogs)){
                     echo $displaywcfreeLogs;
                    }
                    else{
                        echo "No errors found.";
                     }
                }
               else{
                    // check if debug unique log file not exist
                    echo "No log file exists as no errors are generated";
                }
                    
                     ?>
            </div>
          </div>
            <div id="wc-gsc-cta" class="wc-gsc-privacy-box">
                <div class="wc-gsc-table">
                    <div class="wc-gsc-less-free">
                        <p><i class="dashicons dashicons-lock"></i> We do not store any of the data from your Google account on our servers, everything is processed & stored on your server. We take your privacy extremely seriously and ensure it is never misused.</p> <a href="https://gsheetconnector.com/usage-tracking/" target="_blank" rel="noopener noreferrer">Learn more.</a>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var googleDriveMsg = document.getElementById('google-drive-msg');
    if (googleDriveMsg) {
        // Check if the 'gfgs_token' option is not empty
        if ('<?php echo get_option('gs_woo_token'); ?>' !== '') {
            googleDriveMsg.style.display = 'none';
        }
    }
});
</script>

<!--  -->
<div class="two-col wc-free-box-help12">
    <div class="col wc-free-box12">
        <header>
            <h3>Next steps…</h3>
        </header>
        <div class="wc-free-box-content12">
            <ul class="wc-free-list-icon12">
                <li>
                    <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro"
                        target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-star-filled"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Upgrade to PRO</strong>
                            <p>Sync Orders, Order wise data and much more...</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro"
                        target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Compatibility</strong>
                            <p>Compatibility with WooCommerce Third-Party Plugins</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro"
                        target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-chart-bar"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Multi Languages</strong>
                            <p>This plugin supports multi-languages as well!</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro"
                        target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Support Wordpress multisites</strong>
                            <p>With the use of a Multisite, you’ll also have a new level of user-available: the Super
                                Admin.</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 2nd div -->
    <div class="col wc-free-box13">
        <header>
            <h3>Product Support</h3>
        </header>
        <div class="wc-free-box-content13">
            <ul class="wc-free-list-icon13">
                <li>
                    <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro"
                        target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        <div>
                            <strong>Online Documentation</strong>
                            <p>Understand all the capabilities of Woocommerce GsheetConnector</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro"
                        target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <div>
                            <strong>Ticket Support</strong>
                            <p>Direct help from our qualified support team</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/docs/woocommerce-google-sheet-connector-pro"
                        target="_blank">
                        <span class="dashicons dashicons-admin-links"></span>
                        <div>
                            <strong>Affiliate Program</strong>
                            <p>Earn flat 30% on every sale!</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!--  -->