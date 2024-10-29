jQuery(document).ready(function($) {
  $('#ai12z_test_button').click(function(e) {
    console.log('Button clicked jquery');
    alert('Button clicked');
      e.preventDefault();
      $.ajax({
          url: ai12z_ajax_object.ajax_url,
          type: 'POST',
          data: {
              action: 'ai12z_test_action'
          },
          success: function(response) {
              alert('Webhook called successfully!');
          },
          error: function(response) {
              alert('Error calling webhook.');
          }
      });
  });
});

function test_connection(e) {
    console.log('Button clicked', e);
    e.preventDefault();

    fetch(ai12z_ajax_object.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'ai12z_custom_action'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Webhook called successfully!');
        } else {
            alert('Error calling webhook.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error calling webhook.');
    });
}
