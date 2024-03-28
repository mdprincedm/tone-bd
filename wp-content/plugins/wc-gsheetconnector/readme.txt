=== WooCommerce Google Sheet Connector ===
Contributors: westerndeal, abdullah17, gsheetconnector
Author URI: https://www.gsheetconnector.com/
Tags:  woocommerce, google sheets, woocommerce google sheets, woocommerce to google sheet, google sheet integration, woocommerce addon, woocommerce google spreadsheet, woocommerce spreadsheet, google spreadsheets, googlesheets, gsheet, gsheets, woocommerce orders to google sheets, export woocommerce orders to google sheets, manage woocommerce orders with google spreadsheet, woocommerce order spreadsheet, woocommerce wpsyncsheets, syncsheets, sync googlesheets, sync spreadsheets, woocommerce, woo commerce, woo, woocommerce addon, woocommerce, #1 woocommerce, Automattic, freemius, google integration, wordpress
Tested up to: 6.4.1
Requires at least: 5.3
Requires PHP: 7.4
Stable tag: 1.3.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Google Sheet Integration for WooCommerce Plugin, It is an Addon plugin of WooCommerce - Which helps to send the orders directly to Google Sheets in a real-time.

== Description ==

WooCommerce Google Spreadsheet Addon Plugin to connect with Google Sheets!

This plugin is a bridge between your [WooCommerce](https://wordpress.org/plugins/woocommerce/) orders and [Google Sheets](https://www.google.com/sheets/about/). Whenever any new order is placed in **WooCommerce** a new row with the order details will be added or moved to the appropriate **Google Sheet Tabs as per the order status.**

[Documentation](https://www.gsheetconnector.com/docs) | [Support](https://www.gsheetconnector.com/support) | [Demo](https://woogsheets.gsheetconnector.com/) | [Premium Version](https://www.gsheetconnector.com/woocommerce-google-sheet-connector-pro)

= Still haven't purchased ? <a href="https://www.gsheetconnector.com/wc-gsheetconnector-pro?wp-repo" target="_blank">Get it Now</a> =

= Check Live Demo =
[Demo Link](https://woogsheets.gsheetconnector.com/)

[Google Sheet URL to Check submitted Data](https://docs.google.com/spreadsheets/d/1BLkcJLk8bQvSSuIRPHXLDnSNBNmrEVGZ2o44x5uZVlI/edit#gid=1091708451)
<br>
= How to Use this Plugin =

* **Step: 1 - [In Google Sheets](https://sheets.google.com/)** 
➜ Log into your Google Sheets.  
➜ Create a new sheet and name it. ( You can also select existing sheet while setting the connection as per Step 3).

* **Step: 2 - In WordPress Admin**
➜ Navigate to WooCommerce > Google Sheet > Integration Tab
➜ Authenticate with Google using new "Google Access Code" while clicking on "Get Code"
➜ Make Sure to ALLOW Google Permissions for Google Drive and Google Sheets and then copy the code and paste in Google Access Code field, and Hit Save & Authenticate.
➜ Now fetch the sheet details by clicking "Click here to fetch Sheet details to be set at WooCommerce settings."

* **Step: 3 - Connect with Google Sheet**
➜ Navigate to WooCommerce > Google Sheet > WooCommerce Data Settings.
➜ Select the appropriate Sheet from the "Google Sheet Name" dropdown box.
➜ Select WooCommerce Order Status to create Sheet Tabs and add headers to your selected Google Sheet.
➜ Lastly test by ordering any product and putting it to a different order states ( Processing, Hold etc).

**Compatible with various WooCommerce Addons.**

* **Upgrade to [WooCommerce Google Sheet PRO Version](https://www.gsheetconnector.com/wc-gsheetconnector-pro?wp-repo)**

➜ Custom Google API Integration Settings
➜ Allowing to Create a New Sheet from Plugin Settings
➜ Custom Ordering Feature / Manage Fields to Display in Sheet using Enable-Disable / Edit the Fields/ Headers Name to display in Google Sheet.
➜ Enabled Various Fields in Headers and also Compatible with various WooCommerce Addons
➜ Manage Existing WooCommerce Products and Users
➜ Syncronize Existing Orders, Products and Users
➜ Freeze Header Settings
➜ Header Color and Row Odd/Even Colors.
Refer to the features and benefits page for more detailed information on the features of the  [WooCommerce Google Sheet PRO Plugin](https://www.gsheetconnector.com/wc-gsheetconnector-pro?wp-repo)

> <strong>Google Sheet Connector Contact Form Addons</strong>
[CF7 Google Sheet Connector](https://www.gsheetconnector.com/cf7-google-sheet-connector-pro?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WPGSC&utm_content=plugin+repos+description)
[WPForms Google Sheet Connector](https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WPGSC&utm_content=plugin+repos+description)
[Gravity Forms Google Sheet Connector](https://www.gsheetconnector.com/gravity-forms-google-sheet-connector?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WPGSC&utm_content=plugin+repos+description)
[Ninja Forms Google Sheet Connector](https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WPGSC&utm_content=plugin+repos+description)
[Avada Forms Google Sheet Connector](https://www.gsheetconnector.com/avada-forms-google-sheet-connector-pro?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WPGSC&utm_content=plugin+repos+description)
[DIVI Forms Google Sheet Connector](https://www.gsheetconnector.com/divi-forms-db-google-sheet-connector-pro?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WPGSC&utm_content=plugin+repos+description)
[Elementor Forms Google Sheet Connector](https://www.gsheetconnector.com/elementor-forms-google-sheet-connector-pro?utm_source=wordpress.org&utm_medium=referral&utm_campaign=WPGSC&utm_content=plugin+repos+description)
If you are using Easy Digital Downloads for Selling Digital Products then you can use in FREE
[Easy Digital Downloads Google Sheet Connector](https://wordpress.org/plugins/gsheetconnector-easy-digital-downloads/)


== Installation ==

1. Upload "wc-gsheetconnector" to the "/wp-content/plugins/" directory, OR "Site Admin > Plugins > New > Search > GSheetConnector WooCommerce > Install`.  
2. Activate the plugin through the 'Plugins' screen in WordPress.  
3. Use the `Admin Panel > WooCommerce > Google Sheet > Integration` screen to connect to `Google Sheets` by entering the Access Code. You can get the Access Code by clicking the "Get Code" button. 
Enjoy!

== Screenshots ==

1. Google Sheet Integration without authentication.
2. Permission page if user is already logged-in to there account. 
3. Permission popup-1 after logged-in to your account.
4. Permission popup-2 after logged-in to your account.
5. After successful integration - Displays "Currently Active".
6. WooCommerce Google Sheet settings page.
7. Google Sheet Tab Creation as per order status.
8. Google Sheet headers with form submitted data.

== Frequently Asked Questions ==

= New Submitted Orders are not showing in my Configured Sheet? =

If the new submitted orders never shows in your Sheet then one of these things might be the reason:
1. Wrong access code or did not allowed permission to Google Drive and Google Sheets(Check debug log under Integration Tab)
Please double-check those items and hopefully getting them right will fix the issue.

= View Debug log is empty under Integration Tabs  =

Make sure to <a href="https://www.gsheetconnector.com/how-to-enable-debugging-in-wordpress">enable debug log from wp-config.php</a> if submitted order is not showing in sheet, It will helps to show the conflict issue (if there is any) or shows what is making an issue.

== Changelog ==

= 1.3.13 (20-02-2024) =
- Secured code from the Vulnerabilities.

= 1.3.12 (16-02-2024) =
- Fixed: Uninstallation of Plugin.
- Added: Pro features(Custom Order Status,Other Sheet Tabs to Enable,Custom Static Headers,Custom Static Blank Headers, Coupons Header,Subscriptions Header,Sync Coupons,Sync Subscriptions ) display on woocommerce data settings page and also Pro features display(Beta Version, Debug Logs)Tabs.

= 1.3.11 (29-12-2023) =
- Fixed : Fixed validate parent plugin exists or not then show alert message display issue.

= 1.3.10 (02-12-2023) =
- Added : Orders Header - Values ​​are shown in extra header related to order and extra header related to order's product option.

= 1.3.9 (17-10-2023) =
- Fixed : Undefined image path issue resolved.

= 1.3.8 (13-10-2023) =
- Fixed : Solved Conflict CSS Issue in Google Sheet Tab Settings.

= Version 1.3.8 (09-10-2023) =
= Key Highlights: =
- Client library updated : Google API V-2.12.6 . 
- Authorization Form Settings: Alerts for Unauthorized Access.
- Streamlined Authentication: Replaced "Get Code" button with "Sign in with Google" for ease of use.
- Permissions Alert: Users lacking Google Drive and Sheets permissions now receive a clear alert during authentication.
- Enhanced System Status: Redesigned System Status and Error Log for improved functionality.
- Pro-Version Feature Expansion: Extended a Pro-Version feature tab for added capabilities.
= Bug Fixes and Updates: =
- Deprecation Fixes: Resolved deprecated function errors related to Freemius integration.
- Multi-Site Optimization: Freemius optimized for multi-site compatibility.
- UI Enhancements: Several user interface fixes and additions to the Integration Page.

= 1.3.6 (07-08-2023) =
- Fixed : Vulnerabilities issues.

= 1.3.5 (05-07-2023) =
- Updated Freemius SDK version to 2.5.10.

= 1.3.4 (08-06-2023) =
- Fixed : Undefined option issues is resolved.
- Change UI : Pro features display on setting page and Integration Page.

= 1.3.3 (28-04-2023) =
- Fixed : Vulnerabilities issue resolved.

= 1.3.2 (08-04-2023) =
- Removed unused code. 

= 1.3.1 (15-03-2023) =
- Undefined "get_sku()" function issue resolved.

= 1.3.0 (02-08-2022) =
- Freemius issues resolved: (1)Undefined property: stdClass::$plugins 

= 1.2.9 (01-08-2022) =
- Freemius issues -  (1)count(): Parameter must be an array or an object issues are resolved.

= 1.2.8 (30-07-2022) =
- Wrong option name issue is resolved.

= 1.2.7 (29-07-2022) =
- New Google Integration method implemented using web app.
- Code Optimization

= 1.2.6 (18-06-2022) =
- Compatible with WooCommerce GSheetConnector PRO.

= 1.2.5 (30-05-2022) =
- Is Plugin active error resolved in some cases.

= 1.2.4 (28-05-2022) =
- Free and Pro both plugins can be activated at the same time.

= 1.2.3 (21-03-2022) =
- Freemius SDK updated to 2.4.3
- Solved PHP Notice:  "Undefined property: stdClass::$plugin"
- Solved PHP Warning:  "count(): Parameter must be an array or an object that implements Countable"

= 1.2.2 (22-02-2022) =
- Changed UI of Google Sheet Setting Page.


= 1.2.1 (18-09-2021) =
- Resolved Notice Error for WooCommerce as a required plugin.

= 1.2 (12-09-2021) =
- Freemius Integration.

= 1.1 (18-11-2021) =
- Fixed : Errors throws while updating orders without changing order status.
- Added Validation For Google Settings Form. 

= 1.0 Released Initial version =
