<?php

function ai12z_webhook_send($post_id) {
  global $ai12z;
  
  ai12z_log('*** ai12z_webhook_send function called with data: ' . wp_json_encode($post_id));

  // Avoid sending webhook when the post is an auto-draft
  $post_type = get_post_type($post_id);
  $status = get_post_status($post_id);
  ai12z_log('*** ai12z_webhook_send function: ' . wp_json_encode($status));
  if ($post_type === 'revision') {
    ai12z_log('*** ai12z_webhook_send function: stopped execution');
    return;
  }

  if ($status !== "publish" && $status !== "trash") {
    ai12z_log('*** ai12z_webhook_send function: stopped execution');
    return;
  }
  ai12z_log('*** ai12z_webhook_send function: continue');


  $webhook_url = AI12Z_API.$ai12z->options["connector_id"].'?projectid='.$ai12z->options["project_id"];

  if (!$webhook_url) return; // No URL configured

  $post_data = [
      'post_id' => $post_id,
      'post_status' => get_post_status($post_id),
      'post_title' => get_the_title($post_id),
      "post_type" => $post_type,
      "post_content" => get_post_field('post_content', $post_id),
      "post_metadata" => get_post_meta($post_id),  
  ];

  $response = wp_remote_post($webhook_url, [
      'body' => wp_json_encode($post_data),
      'headers' => [
          'Content-Type' => 'application/json',
      ],
  ]);

  if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("Webhook send failed: $error_message");
  }
}
