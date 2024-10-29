<?php
if(!defined('ABSPATH')){ exit; }

// Enqueue the JavaScript and CSS files
function custom_enqueue_scripts_and_styles() {
  $ver = get_option('ai12z_control_version');
  $version = !empty($ver) ? $ver : 'latest';

  wp_enqueue_style( 'ai12z-style', 'https://unpkg.com/ai12z@'.$version.'/dist/library/library.css', array(), AI12Z_BUILD );
  wp_enqueue_script( 'ai12z-script', 'https://unpkg.com/ai12z@'.$version.'/dist/esm/library.js', array(), AI12Z_BUILD, true );

  // Add 'type="module"' attribute to the script tag
  add_filter('script_loader_tag', 'add_module_to_custom_script', 10, 2);

}
add_action( 'wp_enqueue_scripts', 'custom_enqueue_scripts_and_styles' );

function add_module_to_custom_script($tag, $handle) {
  // Check if the script handle matches the one we enqueued
  if ('ai12z-script' !== $handle) {
      return $tag;
  }

  // Modify the script tag to include the 'type="module"' attribute
  return str_replace('<script ', '<script type="module" ', $tag);
}

// Enqueue the JavaScript file
function enqueue_custom_ajax_script() {
  wp_enqueue_script('custom-ajax-js', '/wp-content/plugins/ai12z/js/ajax.js', array('jquery'), AI12Z_BUILD, true);

  // Pass the AJAX URL to the script
  wp_localize_script('custom-ajax-js', 'custom_ajax_object', array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => wp_create_nonce('custom_ajax_nonce')
  ));
}
add_action('admin_enqueue_scripts', 'enqueue_custom_ajax_script');