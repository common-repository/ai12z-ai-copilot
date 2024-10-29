<?php
/*
Version: 1.0.3
Author: ai12z Team
Author URI: https://ai12z.com/about/
License: GPL3
*/

// We need the ABSPATH
if (!defined('ABSPATH')) exit;

define('AI12Z_BASE', plugin_basename(AI12Z_FILE));
define('AI12Z_VERSION', '1.0.3');
define('AI12Z_DIR', dirname(AI12Z_FILE));
define('AI12Z_SLUG', 'ai12z');
define('AI12Z_URL', plugins_url('', AI12Z_FILE));
define('AI12Z_WWW_URL', 'https://ai12z.com/');
define('AI12Z_PORTAL_URL', 'https://app.ai12z.com/');
define('AI12Z_DOCS', 'https://docs.ai12z.net/docs/');
define('AI12Z_API', 'https://integrate.ai12z.net/api/connector/');
define('AI12Z_BUILD', '15');

include_once(AI12Z_DIR . '/main/logging.php');
include_once(AI12Z_DIR . '/main/functions.php');
include_once(AI12Z_DIR . '/main/hooks.php');
require_once(AI12Z_DIR . '/includes/ajax-handler.php');
require_once(AI12Z_DIR . '/includes/enqueue-scripts.php');

spl_autoload_register('ai12z_autoload_register');
function ai12z_autoload_register($class){
	
	if(!preg_match('/AI12Z\\\\/', $class)){
		return;
	}
	
	$file = strtolower(str_replace( array('AI12Z', '\\'), array('', DIRECTORY_SEPARATOR), $class)); 
	$file = trim(strtolower($file), '/').'.php';

	if(file_exists(AI12Z_DIR.'/main/'.$file)){
		include_once(AI12Z_DIR.'/main/'.$file);
	}
	
}

register_activation_hook(AI12Z_FILE, 'ai12z_activation');
// Is called when the ADMIN enables the plugin
function ai12z_activation(){
	global $wpdb;

	$sql = array();

	add_option('ai12z_version', AI12Z_VERSION);

}

// Checks if we are to update ?
function ai12z_update_check(){
	global $wpdb;

	$sql = array();
	$current_version = get_option('ai12z_version');
	$version = (int) str_replace('.', '', $current_version);

	// No update required
	if($current_version == AI12Z_VERSION){
		return true;
	}

	// Is it first run ?
	if(empty($current_version)){

		// Reinstall
		ai12z_activation();

		// Trick the following if conditions to not run
		$version = (int) str_replace('.', '', AI12Z_VERSION);

	}

	// Save the new Version
	update_option('ai12z_version', AI12Z_VERSION);
	
}

// Add action to load GoSMTP
add_action('plugins_loaded', 'ai12z_load_plugin');
function ai12z_load_plugin(){
	global $ai12z;
	
	if(empty($ai12z)){
		$ai12z = new stdClass();
	}
	
	// Check if the installed version is outdated
	ai12z_update_check();
	
	// $options = get_option('ai12z_options', array());
  $options = array(
    'api_key' => get_option('ai12z_api_key'),
    'connector_id' => get_option('ai12z_connector_id'),
    'project_id' => get_option('ai12z_project_id'),
    'control_version' => get_option('ai12z_control_version'),
  );
  // print_r($options);
	$ai12z->options = empty($options) ? array() : $options;
}


function ai12z_webhook_menu() {
    add_options_page(
        'ai12z Settings',   // Page Title
        'ai12z Settings',   // Menu Title
        'manage_options',   // Capability (in this case Administrator)
        AI12Z_SLUG,         // Menu Slug
        'ai12z_settings_handler'// Callback Function
    );
}

add_action('admin_menu', 'ai12z_webhook_menu');
function ai12z_settings_handler() {
  global $ai12z;
	include_once AI12Z_DIR .'/main/settings.php';
  ai12z_settings_page($ai12z->options);
}

function ai12z_webhook_settings() {
  register_setting('ai12z-webhook-settings', 'ai12z_api_key');
  register_setting('ai12z-webhook-settings', 'ai12z_connector_id');
  register_setting('ai12z-webhook-settings', 'ai12z_project_id');
  register_setting('ai12z-webhook-settings', 'ai12z_control_version', array('default' => 'latest'));
}

add_action('admin_init', 'ai12z_webhook_settings');
add_action('save_post', 'ai12z_webhook_send');
add_action('delete_post', 'ai12z_webhook_send');

function ai12z_settings_link( $links ) {
    $settings_link = '<a href="' . admin_url( 'options-general.php?page='.AI12Z_SLUG ) . '">Settings</a>';
    array_unshift($links, $settings_link); // This will put it at the beginning of the links
    return $links;
}
add_filter('plugin_action_links_'.AI12Z_BASE, 'ai12z_settings_link');

