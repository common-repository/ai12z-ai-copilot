<?php
/*
Author: ai12z Team
Author URI: https://ai12z.com/about/
License: GPL3
*/

if(!defined('ABSPATH')){ exit; }

function ai12z_settings_page($ai12z_options) {
  wp_register_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), AI12Z_BUILD);
  wp_enqueue_style('bootstrap-css');

	wp_nonce_field('ai12z-options'); 

  ?>
  <div class="wrap">
    <div class="container">
      <h2>ai12z Settings</h2>
      <form method="post" action="options.php">
          <?php settings_fields('ai12z-webhook-settings'); ?>
          <?php do_settings_sections('ai12z-webhook-settings'); ?>
          <div class="card">
              <div class="p-3">
                <p>Connector Configuration 
                  <a href="https://docs.ai12z.net/docs/connectors/wordpress" target="_blank" title="Documentation" alt="Click here for documentation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg>
                  </a>
                </p>
                <div class="mb-3">
                  <label for="ai12z_api_key" class="form-label">API Key</label>
                  <input class="regular-text" type="text" id="ai12z_api_key" name="ai12z_api_key" value="<?php echo esc_attr($ai12z_options['api_key']); ?>" />
                </div>
                <div class="mb-3">
                  <label for="ai12z_connector_id" class="form-label">Connector Id</label>
                  <input class="regular-text" type="text" id="ai12z_connector_id" name="ai12z_connector_id" value="<?php echo esc_attr($ai12z_options['connector_id']); ?>" />
                </div>
                <div class="mb-3">
                  <label for="ai12z_project_id" class="form-label">Project Id</label>
                  <input class="regular-text" type="text" id="ai12z_project_id" name="ai12z_project_id" value="<?php echo esc_attr($ai12z_options['project_id']); ?>" />
                </div>
              </div>
              <div class="p-3">
                <p>Web Control Version</p>
                <div class="mb-3">
                  <label for="ai12z_control_version" class="form-label">Version</label>
                  <input class="regular-text" type="text" id="ai12z_control_version" name="ai12z_control_version" defaultValue="latest" placeholder="latest" value="<?php echo esc_attr(isset($ai12z_options['control_version']) ? $ai12z_options['control_version'] : 'latest'); ?>" />
                  <p>Leave blank or set it as "latest" for the latest version of web controls.</p>
                </div>
                <p>
                  <a href="https://www.npmjs.com/package/ai12z?activeTab=versions" target="_blank" title="Versions">ai12z web controls versions
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
                      <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
                    </svg>
                  </a>
                </p>
              </div>
              <div class="px-3 container-fluid">
                <!-- <?php submit_button(); ?> -->
                <button type="submit" class="button button-primary">Save Changes</button>
                <button type="button" id="trigger-check" class="button button-primary">Test</button>
                <div id="check-response" style="max-height: 250px" class="overflow-auto"></div>

              </div>
          </div>
          <div class="card ms-3">
            <p>Click the button to trigger the initial sync of all pages.</p>
            <button type="button" id="trigger-ajax" class="button button-primary">Initial Sync</button>
            <div id="ajax-response" style="max-height: 250px" class="overflow-auto"></div>
          </div>
      </form>
  </div>
  <?php
}
