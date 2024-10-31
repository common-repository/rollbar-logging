<?php
/**
 * Plugin Name:       Rollbar Logging
 * Plugin URI:        http://wordpress.org/plugins/rollbar-logging/
 * Description:       Activates Rollbar logging
 * Version:           1.1.2
 * Author:            EMA
 * Author URI:        http://www.mower.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rollbar-logging
 * Domain Path:       /languages
 */

if( !class_exists( 'RollbarJSLogging' ) ) {
  
  require_once( dirname(__FILE__).'/lib/RollbarJSLogging.php' );
  
  $rollbar_js_logging = new RollbarJSLogging();
}

if( !class_exists( 'RollbarPHPLogging' ) ) {
  
  require_once( dirname(__FILE__).'/lib/RollbarPHPLogging.php' );

  $rollbar_php_logging = new RollbarPHPLogging();
  
}

if( !class_exists( 'RollbarAdminConfig' ) ) {
  
  require_once( dirname(__FILE__).'/lib/RollbarAdminConfig.php' );
  
  if( is_admin() ) {
    $rollbar_admin_config = new RollbarAdminConfig();
  }
  
}
