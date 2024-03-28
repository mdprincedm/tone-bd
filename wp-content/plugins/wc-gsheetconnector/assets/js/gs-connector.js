jQuery(document).ready(function () {
  
   /**
   * verify the api code
   * @since 1.0
   */
   jQuery(document).on('click', '#save-gs-woo-code', function (event) {
      event.preventDefault();
         jQuery( ".loading-sign" ).addClass( "loading" );
         var data = {
         action: 'verify_gs_woo_integation',
         code: jQuery('#gs-woo-code').val(),
         security: jQuery('#gs-ajax-nonce').val()
         };
         jQuery.post(ajaxurl, data, function (response ) {
            var clear_msg = response.data;
            if( ! response.success ) { 
               jQuery( ".loading-sign" ).removeClass( "loading" );
               jQuery( "#gs-woo-validation-message" ).empty();
               jQuery("<span class='error-message'>Access code Can't be blank.</span>").appendTo('#gs-woo-validation-message');
            } else {
               jQuery( ".loading-sign" ).removeClass( "loading" );
               jQuery( "#gs-woo-validation-message" ).empty();
               jQuery("<span class='woo-valid-message'>Your Google Access Code is Authorized and Saved.</span> ").appendTo('#gs-woo-validation-message');
            //setTimeout(function () { location.reload(); }, 1000);
            setTimeout(function () {
               window.location.href = jQuery("#redirect_auth").val();
           }, 1000);
           }
         });
         
   });  



     

   /**
    * deactivate the api code
    * @since 1.0
    */
   jQuery(document).on('click', '#gs-woo-deactivate-log', function () {
      jQuery(".loading-sign-deactive").addClass( "loading" );
    var txt;
    var r = confirm("Are You sure you want to deactivate Google Integration ?");
    if (r == true) {
       var data = {
          action: 'deactivate_gs_woo_integation',
          security: jQuery('#gs-ajax-nonce').val()
       };
       jQuery.post(ajaxurl, data, function (response ) {
          if ( response == -1 ) {
             return false; // Invalid nonce
          }
        
          if( ! response.success ) {
             alert('Error while deactivation');
             jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
             jQuery( "#deactivate-msg" ).empty();
             
          } else {
             jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
             jQuery( "#deactivate-msg" ).empty();
             jQuery("<span class='woo-valid-message'>Your account is removed. Reauthenticate again to integrate WooCommerce with Google Sheet.</span>").appendTo('#deactivate-msg');
             setTimeout(function () { location.reload(); }, 1000);
          }
       });
    } else {
       jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
    }
         
  }); 

  function html_decode(input) {
      var doc = new DOMParser().parseFromString(input, "text/html");
      return doc.documentElement.textContent;
   }

   jQuery(document).on('click', '#gs-woo-sync', function () {
      jQuery(this).parent().children(".loading-sign").addClass("loading");
      var integration = jQuery(this).data("init");
      var data = {
         action: 'sync_woo_google_account',
         isajax: 'yes',
         isinit: integration,
         security: jQuery('#gs-ajax-nonce').val()
      };

      jQuery.post(ajaxurl, data, function (response) {
         if (response == -1) {
            return false; // Invalid nonce
         }

         if (response.data.success == "yes") {
            jQuery(".loading-sign").removeClass("loading");
            jQuery("#gs-woo-validation-message").empty();
            jQuery("<span class='woo-valid-message'>Fetched latest sheet names.</span>").appendTo('#gs-woo-validation-message');
            setTimeout(function () { location.reload(); }, 1000);
         } else {
            jQuery(this).parent().children(".loading-sign").removeClass( "loading" );
          location.reload(); // simply reload the page
         }
      });
   });

   /**
    * Clear debug
    */
   jQuery(document).on('click', '.debug-clear', function () {
      jQuery(".clear-loading-sign").addClass("loading");
      var data = {
         action: 'gs_woo_clear_log',
         security: jQuery('#gs-ajax-nonce').val()
      };
      jQuery.post(ajaxurl, data, function (response) {
         var clear_msg = response.data;
         if (response.success) {
            jQuery(".clear-loading-sign").removeClass("loading");
            jQuery("#gs-woo-validation-message").empty();
            jQuery("<span class='woo-valid-message'>"+clear_msg+"</span>").appendTo('#gs-woo-validation-message');
            setTimeout(function () {
                     location.reload();
                 }, 1000);
         }
      });
   });

   /**
     * Display Error logs
     */
    jQuery(document).ready(function($) {
       // Hide .wc-system-Error-logs initially
       $('.wc-system-Error-logs').hide();

       // Add a variable to track the state
       var isOpen = false;

       // Function to toggle visibility and button text
       function toggleLogs() {
           $('.wc-system-Error-logs').toggle();
           // Change button text based on visibility
           $('.wcgsc-logs').text(isOpen ? 'View' : 'Close');
           isOpen = !isOpen; // Toggle the state
       }

       // Toggle visibility and button text when clicking .wcgsc-logs button
       $('.wcgsc-logs').on('click', function() {
           toggleLogs();
       });

       // Toggle visibility and button text when clicking .wc-system-Error-logs element
       $('.wc-system-Error-logs').on('click', function() {
           toggleLogs();
       });
   });


   jQuery(document).on('submit', '#gsSettingFormFree', function (event) {
      console.log('prevent the subitting the form');
      jQuery('#error_spread').html('');
      jQuery('#error_gsTabName').html('');
      
      var submit = true;
      var spreadsheetsName = jQuery('#gs-woo-sheet-id').val();
      var gsTabName = jQuery('input.wc_order_state:checked').length;
      

      if(spreadsheetsName == ""){
         jQuery('#error_spread').html('* Please Select Spreadsheet Name !');
         submit = false;
      }
      if(gsTabName <= 0){
         jQuery('#error_gsTabName').html('* Please select atleast one Tabs !');
         submit = false;
      }
      
      if(submit == false){
         event.preventDefault();
         window.scrollTo({ top: 0, behavior: 'smooth' });
         // jQuery([document.documentElement, document.body]).animate({
         //    scrollTop: jQuery(".gs-woo-tabs-set").offset().top
         // }, 2000);
      }
   });
   jQuery(".gs-woo-list-set32").hide();
   jQuery(".gs-woo-list-set33").hide();
   jQuery(".gs-woo-list-set34").hide();
   jQuery(".gs-woo-list-set35").hide();
   jQuery(".gs-woo-list-set36").hide();
   jQuery(document).on("click", ".gs-woo-list-set", function (event){
      var $this = jQuery(this);
      var $id = $this.attr( "data-id" );
      
      
      if($id == "31" || $id == "32" || $id == "33" || $id == "34" || $id == "35" || $id == "36"){
         if(jQuery(".gs-woo-list-set"+$id).css("display") == "none") { 
           jQuery(".gs-woo-list-set31").hide();
           jQuery(".gs-woo-list-set32").hide();
           jQuery(".gs-woo-list-set33").hide();
           jQuery(".gs-woo-list-set34").hide();
           jQuery(".gs-woo-list-set").removeClass("active-t-gs");
           jQuery(this).addClass("active-t-gs");
           jQuery(".gs-woo-list-set"+$id).show();
         }else{
            if ( !($this).hasClass("active-t-gs") ) {
               jQuery(".gs-woo-list-set"+$id).hide();
            }
         }
      }else{
         if(jQuery(".gs-woo-list-set"+$id).css("display") == "none") { 
           //jQuery(".gs-woo-list-set"+$id).css("display", "block");
           jQuery(".gs-woo-list-set"+$id).show('slow');
           jQuery(".mini_mize"+$id).show();
           jQuery(".maxi_mize"+$id).hide();
         }else{
           //jQuery(".gs-woo-list-set"+$id).css("display", "none");
           jQuery(".gs-woo-list-set"+$id).hide('slow');
           jQuery(".mini_mize"+$id).hide();
           jQuery(".maxi_mize"+$id).show();
         } 
      }
   });

   /**
    * Clear debug for system status tab
    */
   jQuery(document).on('click', '.clear-content-logs-wc', function () {

      jQuery(".clear-loading-sign-logs-wc").addClass("loading");
      var data = {
         action: 'wc_clear_debug_logs',
         security: jQuery('#gs-ajax-nonce').val()
      };
      jQuery.post(ajaxurl, data, function ( response ) {
         if (response == -1) {
            return false; // Invalid nonce
         }
         
         if (response.success) {
            jQuery(".clear-loading-sign-logs-wc").removeClass("loading");
            jQuery('.clear-content-logs-msg-wc').html('Logs are cleared.');
            setTimeout(function () {
                        location.reload();
                    }, 1000);
         }
      });
   });
});