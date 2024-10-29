jQuery(document).ready(function($) {
  $('#trigger-ajax').on('click', function(e) {
    e.preventDefault();

    const connector_id = encodeURIComponent($('#ai12z_connector_id').val());
    const project_id = $('#ai12z_project_id').val();

    // Show loading message
    $('#ajax-response').html('<p>Sending data, please wait...</p>');

    $.ajax({
      url: custom_ajax_object.ajax_url,
      method: 'POST',
      data: {
          action: 'ai12z_post_action',
          nonce: custom_ajax_object.nonce,
          connector_id: connector_id,
          project_id: project_id,
          type: 'intial_sync'
      },
      success: function(response) {
          var resultHtml = '<ul>';
          response.forEach(function(item) {
              resultHtml += '<li><strong>' + item.page + ':</strong> ' + item.status + ' - ' + item.message + '</li>';
          });
          resultHtml += '</ul>';

          $('#ajax-response').html(resultHtml);
      },
      error: function() {
          $('#ajax-response').html('<p>There was an error processing the request.</p>');
      }
    });
  });

  $('#trigger-check').on('click', function(e) {
    const connector_id = encodeURIComponent($('#ai12z_connector_id').val());
    const project_id = $('#ai12z_project_id').val();
    console.log(connector_id, project_id);
    $('#trigger-check').prop('disabled', true);
    $('#check-response').html(`<div class="p-3 container">
      <div class="row">
        <div class="col">
          <div class="alert alert-primary">Checking the configuration...</div>
        </div>
      </div>
      <div class="row">
        <div class="col">Connector ID</div>
        <div class="col">${connector_id}</div>
      </div>
      <div class="row">
        <div class="col">Project ID</div>
        <div class="col">${project_id}</div>
      </div>        
      </div>`);

    $.ajax({
      url: custom_ajax_object.ajax_url,
      method: 'POST',
      data: {
        action: 'ai12z_post_action',
        nonce: custom_ajax_object.nonce,
        connector_id: connector_id,
        project_id: project_id,
        type: 'check_configuration',
      },
      success: function(response) {
        $('#check-response').html(`
          <div class="p-3 container">
            <div class="row">
              <div class="col">
                <div class="alert alert-success">Configuration is Ok.</div>
              </div>
            </div>
          </div>`);
      },
      error: function() {
        $('#check-response').html(`
          <div class="p-3 container">
            <div class="row">
              <div class="col">
                <div class="alert alert-danger">There was an error processing the request. Check your configuration.</div>
              </div>
            </div>
          </div>`);
      }
    });

    $('#trigger-check').prop('disabled', false);

  });
});
