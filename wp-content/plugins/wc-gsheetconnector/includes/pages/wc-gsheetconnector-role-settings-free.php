<?php
   
     $gs_woo_page_roles = get_option('gs_woo_tab_roles_setting');
      ?>
      <form id="gs_woo_role_settings_form" method="post" action="options.php">
      <?php
      // adds nonce and option_page fields for the settings page
      settings_fields('gs-woo-settings');
      settings_errors();
      ?>
      <div class="wrap gs-form">
         <div class="card" id="googlesheet">
            <div class="wrap gs-form">
               <div class="gs-woo-gs-card">
                     <label><?php echo __('Roles that can access Google Sheet Page', 'wc-gsheetconnector'); ?></label>
                        <?php
                        wc_gsheetconnector_utility::instance()->gs_woocommerce_checkbox_roles_multi(
                                $gs_woo_page_roles . '[]', $gs_woo_page_roles);
                        ?>
                     </div>
                  </div>
               </div>
            </div>
            <div class="select-info">
               <input type="submit" class="button button-primary button-large" name="gs_woo_gs_settings" value="<?php echo __("Save", "wc-gsheetconnector"); ?>"/>
            </div>
      </form>