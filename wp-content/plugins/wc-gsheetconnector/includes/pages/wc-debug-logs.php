<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
   exit();
}

?>
<div class="system-debug-logs" >
   <div class="info-container">
      <h2 class="systemifo"><span  style="opacity: 0.8;
    pointer-events: none;">Debug Constants</span>
        <a href="https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro" target="_blank" class="protitle">
        <?php echo esc_html( __( 'Upgrade To Pro', 'wc-gsheetconnector' ) ); ?>
      </a>
</h2>
<form method="post" style="opacity: 0.8;
    pointer-events: none;">
<table>
  <tr>
    <th>Key</th>
    <th>Info</th>
    <th>Status</th>
  </tr>
  <tr>
    <th>WP_DEBUG</th>
    <td>Enable WP_DEBUG mode</td>
    <td>
    	<label class="switch">
  <input type="checkbox" name="wpgsc-debug" value="">
  <span class="slider round"></span>
</label>
    </td>
  </tr>
  <tr>
    <th>WP_DEBUG_LOG</th>
    <td>Enable Debug logging to the /wp-content/debug.log file</td>
    <td>
    	<label class="switch">
  <input type="checkbox" name="wpgsc-debug-log" value="">
  <span class="slider round"></span>
</label>
    </td>
  </tr>
  <tr>
    <th>SCRIPT_DEBUG</th>
    <td>Use the “dev” versions of core CSS and JavaScript files</td>
    <td>
    	<label class="switch">
  <input type="checkbox" name="wpgsc-script-debug" value="">
  <span class="slider round"></span>
</label>
    </td>
  </tr>
  <tr>
    <th>SAVEQUERIES</th>
    <td>Enable database query logging, turn it off when not debuging cause it will effect site performace. The array is stored in the global $wpdb->queries.</td>
    <td>
    	<label class="switch">
  <input type="checkbox" name="wpgsc-savequeries" value="">
  <span class="slider round"></span>
</label>
    </td>
  </tr>
</table>

<h2><input type="submit" class="button button-primary button-large debug-logs-save" name="gs_woo_debug_settings" value="<?php echo __("Save", "wc-gsheetconnector"); ?>"/>
               <span class="beta-loading-sign-woogsc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
           </h2>
           </form>

            </div>

  </div>
