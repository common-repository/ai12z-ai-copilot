<?php
// Handle the AJAX request
function custom_ajax_handler() {
    check_ajax_referer('custom_ajax_nonce', 'nonce');

    $connector_id = isset($_POST['connector_id']) ? sanitize_text_field(wp_unslash($_POST['connector_id'])) : '';
    $project_id = isset($_POST['project_id']) ? sanitize_text_field(wp_unslash($_POST['project_id'])) : '';

    if (empty($connector_id) || empty($project_id)) {
    ai12z_log('Connector ID and Project ID are required.');
    wp_send_json_error(array('message' => 'Connector ID and Project ID are required.'));
    }

    $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
    if ($type === 'check_configuration') {
        // Handle the "check_configuration" type
        $response = array();
        $data = array(
            'connectorId' => $connector_id,
            'projectId' => $project_id,
            'type' => $type
        );
        $api_response = wp_remote_post(AI12Z_API . $connector_id . '/validate?projectid=' . $project_id, array(
            'body' => wp_json_encode($data),
            'headers' => array(
                'Content-Type' => 'application/json'
            )
        ));
        $response_code = wp_remote_retrieve_response_code( $api_response );

        if ($response_code !== 200) {
            $response[] = array(
                'status' => 'Error',
                'message' => $api_response->get_error_message()
            );
        } else {
            $response[] = array(
                'status' => 'Success',
                'message' => 'Configuration is Ok.'
            );
        }
        wp_send_json($response);
    } else {
        $pages = get_posts(array(
            // 'post_type'   => 'page',
            'post_status' => 'publish',
            'numberposts' => -1
        ));

        $response = array();

        foreach ($pages as $page) {
            // Prepare data to send
            $data = array(
                'post_id' => $page->ID,
                'post_status' => $page->post_status,
                'post_title' => $page->post_title,
                "post_type" => $page->post_type,
                "post_content" => $page->post_content,
                "post_metadata" => $page->post_meta, 
                'link' => get_permalink($page->ID),
                'connectorId' => $connector_id,
                'projectId' => $project_id,
                'type' => $type
            );

            // Send data to external service
            $api_response = wp_remote_post(AI12Z_API . $connector_id . '?projectid=' . $project_id, array(
                'body' => wp_json_encode($data),
                'headers' => array(
                    'Content-Type' => 'application/json'
                )
            ));

            if (is_wp_error($api_response)) {
                $response[] = array(
                    'page' => $page->post_title,
                    'status' => 'Error',
                    'message' => $api_response->get_error_message()
                );
            } else {
                $response[] = array(
                    'page' => $page->post_title,
                    'status' => 'Success',
                    'message' => 'Data sent successfully.'.AI12Z_API . $connector_id . '?projectid=' . $project_id
                );
            }

            break; // Remove this line to send data for all pages
        }

        wp_send_json($response);
    }
}
add_action('wp_ajax_ai12z_post_action', 'custom_ajax_handler');
