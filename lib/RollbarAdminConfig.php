<?php

class RollbarAdminConfig {
  /**
   * Holds the values to be used in the fields callbacks
   */
  private $options;

  /**
   * Start up
   */
  public function __construct() {
    add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );
    add_action( 'admin_init', array( $this, 'add_options_sections' ) );
    add_action( 'admin_init', array( $this, 'add_global_options_fields' ) );
    add_action( 'admin_init', array( $this, 'add_js_options_fields' ) );
    add_action( 'admin_init', array( $this, 'add_php_options_fields' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
    
  }

  /**
   * Add options page
   */
  public function add_plugin_page() {
    // This page will be under "Settings"
    add_options_page(
      'Settings Admin',
      'Rollbar Logging',
      'manage_options',
      'rollbar-logging-config',
      array( $this, 'create_admin_page' )
    );
    
  }
  
  public function load_scripts($hook) {
    if( $hook !== 'settings_page_rollbar-logging-config' ) {
      return;
    }
    
    wp_register_script( 'rollbar-logging', plugin_dir_url( dirname( __FILE__ ) ) . 'js/admin.js', array('jquery'), '1.1.1' );
    wp_enqueue_script('rollbar-logging');
    
    wp_register_style( 'rollbar-logging', plugin_dir_url( dirname( __FILE__ ) ) . 'css/style.css', array(), '1.1.1' );
    wp_enqueue_style('rollbar-logging');
    
  }

  /**
   * Options page callback
   */
  public function create_admin_page() {
    // Set class property
    $this->options = get_option( 'rollbar_logging_config' );
    ?>
    <div class="wrap">
      <h1><?php _e( 'Rollbar Logging Configuration', 'rollbar-logging' ); ?></h1>
      <form method="post" action="options.php" id="rollbar-logging-form">
      <?php
        // This prints out all hidden setting fields
        settings_fields( 'rollbar_config_options' );
        do_settings_sections( 'rollbar-config-global-setting-admin' );
      ?>
      <ul class="rollbar-logging-tabs">
        <li><a class="rollbar-logging-js-handle" href="#rollbar-logging-js"><?php _e( 'JavaScript', 'rollbar-logging' ); ?></a></li>
        <li><a class="rollbar-logging-php-handle" href="#rollbar-logging-php"><?php _e( 'PHP', 'rollbar-logging' ); ?></a></li>
      </ul>
      <div class="rollbar-logging-tab-panel rollbar-logging-js-tab" id="rollbar-logging-js">
        <?php do_settings_sections( 'rollbar-config-javascript-setting-admin' ); ?>
      </div>
      <div class="rollbar-logging-tab-panel rollbar-logging-php-tab" id="rollbar-logging-php">
        <?php do_settings_sections( 'rollbar-config-php-setting-admin' ); ?>
      </div>
      <?php 
        submit_button();
      ?>
      </form>
    </div>
    <?php
  }

  /**
   * Register and add settings
   */
  public function page_init() {
    register_setting(
      'rollbar_config_options', // Option group
      'rollbar_logging_config', // Option name
      array( $this, 'sanitize' ) // Sanitize
    );
  }
  
  public function add_options_sections() {
    // Sections
    add_settings_section(
      'rollbar_config_global_section', // ID
      __( 'Global Settings', 'rollbar-logging' ), // Title
      array( $this, 'print_config_info' ), // Callback
      'rollbar-config-global-setting-admin' // Page
    );
    
    add_settings_section(
      'rollbar_config_javascript_section', // ID
      __( 'JavaScript', 'rollbar-logging' ), // Title
      array( $this, 'print_config_info' ), // Callback
      'rollbar-config-javascript-setting-admin' // Page
    );
    
    add_settings_section(
      'rollbar_config_php_section', // ID
      __( 'PHP', 'rollbar-logging' ), // Title
      array( $this, 'print_options_info' ), // Callback
      'rollbar-config-php-setting-admin' // Page
    );
  
  }
  
  public function add_global_options_fields() {
    add_settings_field(
      'environment',
      __( 'Environment Name', 'rollbar-logging' ),
      array( $this, 'environment_callback' ),
      'rollbar-config-global-setting-admin',
      'rollbar_config_global_section'
    );
    
    add_settings_field(
      'pause_all',
      __( 'Pause all logging', 'rollbar-logging' ),
      array( $this, 'pause_all_callback' ),
      'rollbar-config-global-setting-admin',
      'rollbar_config_global_section'
    );
  }
  
  public function add_php_options_fields() {
    add_settings_field(
      'post_server_item', // ID
      __( 'Server Access Token (post_server_item)', 'rollbar-logging' ), // Title
      array( $this, 'post_server_item_callback' ), // Callback
      'rollbar-config-php-setting-admin', // Page
      'rollbar_config_php_section' // Section
    );
  }
  
  public function add_js_options_fields() {
    add_settings_field(
      'post_client_item', // ID
      __( 'Client Access Token (post_client_item)', 'rollbar-logging' ), // Title
      array( $this, 'post_client_item_callback' ), // Callback
      'rollbar-config-javascript-setting-admin', // Page
      'rollbar_config_javascript_section' // Section
    );

    add_settings_field(
      'verbose',
      __( 'Verbose', 'rollbar-logging' ),
      array( $this, 'verbose_callback' ), // Callback
      'rollbar-config-javascript-setting-admin', 
      'rollbar_config_javascript_section'
    );
    
    add_settings_field(
      'jquery',
      __( 'Include jQuery plugin', 'rollbar-logging' ),
      array( $this, 'jquery_callback' ), // Callback
      'rollbar-config-javascript-setting-admin',
      'rollbar_config_javascript_section'
    );
    
    add_settings_field(
      'errors_to_ignore',
      __( 'Errors to ignore (1 per line)', 'rollbar-logging' ),
      array( $this, 'errors_to_ignore_callback' ), // Callback
      'rollbar-config-javascript-setting-admin',
      'rollbar_config_javascript_section'
    );
    
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function sanitize( $input ) {
    $new_input = array();
    $input_keys = array(
      'post_client_item',
      'post_server_item',
      'environment',
      'verbose',
      'jquery',
      'pause_all',
      'errors_to_ignore'
    );
    
    foreach( $input_keys as $key ) {
      if( isset( $input[$key] ) ) {
        if( $key !== 'errors_to_ignore' ) {
          $new_input[$key] = trim( sanitize_text_field( $input[$key] ) );
        }
        else {
          // do not trim textarea
          $new_input[$key] = wp_strip_all_tags( $input[$key] );
        }
      }
    }
    
    return $new_input;
  }

  /**
   * Print the Section text
   */
  public function print_config_info() {
    _e( 'Enter your settings below:', 'rollbar-logging' ) ;
  }
  
  /**
   * Print the Section text
   */
  public function print_options_info() {
    _e( 'Enter additional options below:', 'rollbar-logging' ) ;
  }

  /**
   * Get the settings option array and print one of its values
   */
  public function post_client_item_callback() {
    printf(
      '<input type="text" id="post_client_item" name="rollbar_logging_config[post_client_item]" value="%s" />',
      isset( $this->options['post_client_item'] ) ? esc_attr( $this->options['post_client_item'] ) : ''
    );
  }

  /**
   * Get the settings option array and print one of its values
   */
  public function post_server_item_callback() {
    printf(
      '<input type="text" id="post_server_item" name="rollbar_logging_config[post_server_item]" value="%s" />',
      isset( $this->options['post_server_item'] ) ? esc_attr( $this->options['post_server_item'] ) : ''
    );
  }
  
  /**
   * Get the settings option array and print one of its values
   */
  public function environment_callback() {
    printf(
      '<input type="text" id="environment" name="rollbar_logging_config[environment]" value="%s" />',
      isset( $this->options['environment'] ) ? esc_attr( $this->options['environment'] ) : ''
    );
  }
  
  /**
   * Get the settings option array and print one of its values
   */
  public function verbose_callback() {
    printf(
      '<input type="checkbox" id="verbose" name="rollbar_logging_config[verbose]" value="1" %s />',
      isset( $this->options['verbose'] ) ? 'checked="checked"' : ''
    );
  }
  
  public function jquery_callback() {
    printf(
      '<input type="checkbox" id="jquery" name="rollbar_logging_config[jquery]" value="1" %s />',
      isset( $this->options['jquery'] ) ? 'checked="checked"' : ''
    );
  }
  
  public function pause_all_callback() {
    printf(
      '<input type="checkbox" id="pause_all" name="rollbar_logging_config[pause_all]" value="1" %s />',
      isset( $this->options['pause_all'] ) ? 'checked="checked"' : ''
    );
  }
  
  public function errors_to_ignore_callback() {
    printf(
      '<textarea id="errors_to_ignore" rows="8" name="rollbar_logging_config[errors_to_ignore]">%s</textarea>',
      isset( $this->options['errors_to_ignore'] ) ? esc_textarea( $this->options['errors_to_ignore'] ) : ''
    );
  }
}
