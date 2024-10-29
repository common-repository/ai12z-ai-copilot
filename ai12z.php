<?php
/*
Plugin Name: ai12z AI Copilot
Plugin URI: https://ai12z.com
Description: Go live with AI-powered search, chatbots, and copilot digital assistants.
Version: 1.0.3
Author: ai12z Team
Author URI: https://ai12z.com/about/
License: GPL3
*/

// We need the ABSPATH
if(!defined('ABSPATH')){ exit; }

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if(defined('AI12Z_VERSION')) {
    // the plugin is loaded already!
	return;
}

define('AI12Z_FILE', __FILE__);

include_once(dirname(__FILE__).'/init.php');
