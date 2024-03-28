<?php
/**
 * Settings class for Gogglesheet Role settings
 * @since 1.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit;
}

/**
 * WPF_Role_Settings Class
 * @since 1.0
 */
class wc_gsheetconnector_role_settings_free {

   /**
    * @var string group name
    */
   protected $gs_group_name = 'gs-woo-settings';

   /**
    * @var string roles that can access Google Sheet page
     @since 1.0
    */
   protected $gs_woo_page_roles_setting_option_name = 'gs_woo_page_roles_setting';

   
   /**
    * Set things up.
    * @since 1.0
    */
   public function __construct() {
      add_action('admin_init', array($this, 'init_settings'));
   }

   // White list our options using the Settings API
   public function init_settings() {
      register_setting('gs_woo-settings', $this->gs_woo_page_roles_setting_option_name, array($this, 'validate_gs_woo_access_roles'));
   }

   /**
    * do validate and sanitize selected participants
    * @param array $selected_roles
    * @return array $roles
    * @since 1.0
    */
   public function validate_gs_woo_access_roles($selected_roles) {
      $roles = array();
      $system_roles = wc_gsheetconnector_utility::instance()->get_system_roles();


      if ( $selected_roles && count( $selected_roles ) > 0) {

         foreach ($system_roles as $role => $display_name) {
            if (is_array($selected_roles) && in_array(esc_attr($role), $selected_roles)) {
               // preselect specified role
               $roles[$role] = $display_name;
            }
         }
      }
      return $roles;
   }

   public function add_role_setting_page_free() {
      if ( ! current_user_can( 'administrator' ) ) {
      ?>
      <span class="per_not_allo">Permission Not Allowed </span>
      <?php
        return;
      }
      $gs_woo_page_roles = get_option($this->gs_woo_page_roles_setting_option_name);

      //$gs_woo_tab_roles = get_option($this->gs_woo_tab_roles_setting_option_name);
      ?>
      <form id="gs_woo_gs_settings_form" method="post" action="options.php">
      <?php
      // adds nonce and option_page fields for the settings page
      settings_fields('gs_woo-settings');
      settings_errors();
      ?>
         <div class="wrap gs-form">
            <div class="card" id="googlesheet">
               <div class="wrap gs-form">
                  <div class="gs_woo-gs-card">
                     <div><label><?php echo __('Roles that can access Google Sheet Page', 'wc-gsheetconnector'); ?></label></div>
				      <?php
				      wc_gsheetconnector_utility::instance()->gs_woocommerce_checkbox_roles_multi(
				              $this->gs_woo_page_roles_setting_option_name . '[]', $gs_woo_page_roles);   
                  
				      ?>
                     <br/>
                     <div class="select-info">
                        <input type="submit" class="button button-primary button-large" name="gs_woo_gs_settings" value="<?php echo __("Buy Pro", "wc-gsheetconnector"); ?>"/>
                     </div>
                  </div>
               </div>
           </div>
       </div>
    </form>
      <?php
   }

}

$gs_woo_role_settings = new wc_gsheetconnector_role_settings_free();