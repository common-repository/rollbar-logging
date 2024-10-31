<?php

if( !class_exists( 'Rollbar' ) ) {
  require_once( dirname(__FILE__) . '/../vendor/rollbar.php' );
}

class RollbarPHPLogging {
  
  function __construct() {
    add_action( 'plugins_loaded', array( &$this, 'init' ), 10 );
  }
  
  public function init() {
    $option = get_option( 'rollbar_logging_config' );
    
    if( !isset( $option['pause_all'] ) ) {
      if( isset( $option ) && isset( $option['post_server_item'] ) && !empty( $option['post_server_item'] ) ) {
        // The config options are eventually used in a curl request. Escape accordingly.
        $config = array(
          // required
          'access_token' => trim( urlencode( $option['post_server_item'] ) ),
          // optional - environment name. any string will do.
          'environment' => isset( $option['post_client_item'] ) ? trim( urlencode( $option['environment'] ) ) : '',
          
        );
      
        Rollbar::init( $config );
        
      }
      
    }
  }
  
}
